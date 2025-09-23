<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\File;

class WzImportCommand extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'wz:import {--force : Force re-importing even if the table is not empty}';

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
        if (DB::table('wz')->count() > 0 && !$this->option('force')) {
            $this->info('WZ table is not empty. Use --force to re-import.');
            return 0;
        }

        if ($this->option('force')) {
            $this->warn('Forcing re-import. Truncating wz table...');
            DB::table('wz')->truncate();
        }

        $this->info('Starting WZ string data import...');

        $wzPath = base_path('resources/wz/String.wz');
        $types = ['map', 'mob', 'item', 'npc', 'skill'];
        $allData = [];

        foreach ($types as $type) {
            $typePath = $wzPath . '/' . $type;
            if (!File::isDirectory($typePath)) {
                $this->warn("Directory for type '{$type}' not found. Skipping.");
                continue;
            }

            $files = File::glob($typePath . '/*.json');
            $this->info("Found " . count($files) . " files for type '{$type}'.");

            $bar = $this->output->createProgressBar(count($files));
            $bar->start();

            foreach ($files as $file) {
                $content = File::get($file);
                $data = json_decode($content);

                if (isset($data->id) && isset($data->name)) {
                    $allData[] = [
                        'wz_id' => $data->id,
                        'type' => $type,
                        'name' => $data->name
                    ];
                }
                $bar->advance();
            }
            $bar->finish();
            $this->line(''); // Newline after progress bar
        }

        if (empty($allData)) {
            $this->warn('No data found to import.');
            return 1;
        }

        $this->info('Inserting ' . count($allData) . ' records into the database...');

        // Insert in chunks to avoid memory issues
        foreach (array_chunk($allData, 500) as $chunk) {
            DB::table('wz')->insert($chunk);
        }

        $this->info('WZ data import completed successfully!');
        return 0;
    }
}

