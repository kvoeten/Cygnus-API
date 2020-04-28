<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateAvatarDataTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('avatar_data', function (Blueprint $table) {
            $table->bigIncrements('dwCharacterID');
            $table->integer('nAccountID');
            $table->integer('nWorld');
            $table->integer('nCharListPos');
            $table->integer('nRank');
            $table->integer('nRankMove');
            $table->integer('nOverallRank');
            $table->integer('nOverallRankMove');
            $table->timestamp('created_at')->default(DB::raw('CURRENT_TIMESTAMP'));
            $table->timestamp('updated_at')->default(DB::raw('CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP'));
            $table->softDeletes('deleted_at', 0);
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('avatar_data');
    }
}
