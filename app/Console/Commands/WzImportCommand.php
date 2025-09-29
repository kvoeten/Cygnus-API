<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\File;
use Symfony\Component\Process\Process;

class WzImportCommand extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'wz:import {--force : Force re-importing even if the table is not empty} {--batch-file= : The path to a file containing a batch of JSON files to process (for internal use)}';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Import WZ string data from JSON files into the database.';

    /**
     * Execute the console command.
     *
     * @return int
     */
    public function handle()
    {
        // If a batch file is specified, this is a worker process.
        if ($this->option('batch-file')) {
            return $this->handleBatchImport($this->option('batch-file'));
        }

        // This is the main orchestrator process.
        if (DB::table('wz')->count() > 0 && !$this->option('force')) {
            $this->info('WZ table is not empty. Use --force to re-import.');
            return 0;
        }

        if ($this->option('force')) {
            $this->warn('Forcing re-import. Truncating wz table...');
            DB::table('wz')->truncate();
        }

        $this->info('Gathering all WZ files for processing...');

        $wzPath = base_path('resources/wz/String.wz');
        $types = ['map', 'mob', 'item', 'npc', 'skill'];
        $allFiles = [];

        foreach ($types as $type) {
            $typePath = $wzPath . '/' . $type;
            if (File::isDirectory($typePath)) {
                $allFiles = array_merge($allFiles, File::glob($typePath . '/*.json'));
            }
        }

        if (empty($allFiles)) {
            $this->warn('No JSON files found to import.');
            return 1;
        }

        $this->info('Found ' . count($allFiles) . ' total files. Creating batches...');

        // Define the number of parallel processes. Adjust this based on your server's core count.
        $parallelProcesses = 12;
        $totalFiles = count($allFiles);
        $chunkSize = (int) ceil($totalFiles / $parallelProcesses);
        $fileBatches = $chunkSize > 0 ? array_chunk($allFiles, $chunkSize) : [];

        if (empty($fileBatches)) {
            $this->warn('No batches to process.');
            return 1;
        }


        $tmpDir = storage_path('app/tmp/wz_import');
        File::ensureDirectoryExists($tmpDir);
        File::cleanDirectory($tmpDir); // Clean up from previous runs

        $processes = [];
        $batchFiles = [];

        // Create batch files and prepare processes
        foreach ($fileBatches as $i => $batch) {
            $batchFilePath = $tmpDir . "/batch_{$i}.txt";
            File::put($batchFilePath, implode(PHP_EOL, $batch));
            $batchFiles[] = $batchFilePath;

            $process = new Process([
                PHP_BINARY,
                'artisan',
                'wz:import',
                '--batch-file=' . $batchFilePath,
            ]);
            $process->setTimeout(null); // No timeout for the import process
            
            $batchDetails[$i] = [
                'process' => $process,
                'total' => count($batch),
                'processed' => 0,
                'error_output' => '',
            ];
        }

        $this->info('Starting ' . count($batchDetails) . ' worker processes...');
        $this->line(''); // For spacing

        // Start all processes and set up callbacks to receive progress updates
        foreach ($batchDetails as $i => &$details) {
            $details['process']->start(function ($type, $buffer) use (&$details) {
                if (Process::ERR === $type) {
                    $details['error_output'] .= $buffer;
                } else {
                    $lines = explode("\n", trim($buffer));
                    foreach ($lines as $line) {
                        if (str_starts_with($line, 'PROGRESS:')) {
                            $details['processed'] = (int) substr($line, 9);
                        }
                    }
                }
            });
        }
        unset($details); // Unset reference to avoid side-effects

        // Continuously redraw the progress bars until all workers are finished
        $firstRun = true;
        while (count(array_filter($batchDetails, fn ($d) => $d['process']->isRunning())) > 0) {
            if (!$firstRun) {
                // Move cursor up to overwrite the previous progress display
                $this->output->write(sprintf("\r\033[%dA", count($batchDetails)));
            }
            $firstRun = false;

            foreach ($batchDetails as $i => $details) {
                $this->drawProgressBar($i, $details['processed'], $details['total']);
            }
            usleep(100000); // 100ms sleep to prevent high CPU usage
        }

        // Final redraw to ensure all bars show 100%
        if (!$firstRun) {
            $this->output->write(sprintf("\r\033[%dA", count($batchDetails)));
        }
        foreach ($batchDetails as $i => $details) {
            $this->drawProgressBar($i, $details['total'], $details['total']);
        }
        $this->line(''); // Final newline after progress bars

        // Check for failures and report errors
        $failedWorkers = array_filter($batchDetails, fn ($d) => !$d['process']->isSuccessful());
        if (count($failedWorkers) > 0) {
            $this->error(count($failedWorkers) . ' worker(s) failed. See output below:');
            foreach ($failedWorkers as $i => $details) {
                $this->warn("--- Error from Batch {$i} ---");
                $this->error($details['error_output'] ?: 'No error output captured.');
            }
        } else {
            $this->info('All worker processes have finished successfully.');
        }

        // Cleanup temporary batch files
        File::delete($batchFiles);
        $totalCount = DB::table('wz')->count();
        $this->info("WZ data import completed successfully! Inserted {$totalCount} records.");
        return 0;
    }

    /**
     * Handles the import for a single batch of files specified in a file.
     *
     * @param string $batchFilePath
     * @return int
     */
    private function handleBatchImport(string $batchFilePath)
    {
        if (!File::exists($batchFilePath)) {
            $this->error("Batch file not found: {$batchFilePath}");
            return 1;
        }

        $files = array_filter(explode(PHP_EOL, File::get($batchFilePath)));

        $dataToInsert = [];
        $processedCount = 0;
        $totalFiles = count($files);

        foreach ($files as $file) {
            // Extract type from path. e.g., ".../String.wz/item/123.json" -> "item"
            $pathParts = explode(DIRECTORY_SEPARATOR, $file);
            $type = $pathParts[count($pathParts) - 2];

            $data = json_decode(File::get($file));

            if (isset($data->id) && isset($data->name)) {
                $dataToInsert[] = [
                    'wz_id' => $data->id,
                    'type' => $type,
                    'name' => $data->name
                ];
            }

            if (count($dataToInsert) >= 500) {
                DB::table('wz')->insert($dataToInsert);
                $dataToInsert = [];
            }

            $processedCount++;
            // Report progress every 50 files or on the very last file
            if ($processedCount % 50 === 0 || $processedCount === $totalFiles) {
                // This output is captured by the orchestrator process
                echo "PROGRESS:{$processedCount}\n";
            }
        }

        if (!empty($dataToInsert)) {
            DB::table('wz')->insert($dataToInsert);
        }

        return 0;
    }

    /**
     * Draws a single, formatted progress bar to the console.
     *
     * @param int $batchIndex
     * @param int $processed
     * @param int $total
     */
    private function drawProgressBar(int $batchIndex, int $processed, int $total)
    {
        $percentage = $total > 0 ? ($processed / $total) : 1;
        $width = 40; // A safe width for most terminal sizes

        $completeWidth = (int) floor($percentage * $width);
        // Use block characters for a nicer visual style, similar to the default Symfony progress bar.
        $bar = str_repeat('▓', $completeWidth) . str_repeat('░', $width - $completeWidth);
        $percentStr = str_pad(number_format($percentage * 100, 0), 3, ' ', STR_PAD_LEFT);

        // Kills the current line and writes the new one
        $this->output->writeln(sprintf(
            "  Batch %-2d: [%s] %s%% (%d/%d)",
            $batchIndex, $bar, $percentStr, $processed, $total
        ));
    }
}
