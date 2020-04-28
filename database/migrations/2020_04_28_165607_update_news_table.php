<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class UpdateNewsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        DB::table('news')->insert(
            array(
                'author' => '[Admin] Kaz',
                'title' => 'New API Updates',
                'category' => 'Update',
                'description' => 'The Cygnus API has gained a lot of new functionalities. Read this article to get an overview.',
                'content' => '',
                'views' => '0'
            )
        );

        DB::table('news')->insert(
            array(
                'author' => '[Admin] Kaz',
                'title' => 'No content yet',
                'category' => 'Error',
                'description' => 'The creator of this site has not yet added any other news articles!',
                'content' => '',
                'views' => '0'
            )
        );
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        //

    }
}
