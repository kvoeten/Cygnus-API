<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateCharacterDressTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('character_dress', function (Blueprint $table) {
            $table->integer('dwCharacterID');
            $table->integer('nFace');
            $table->integer('nHair');
            $table->integer('nClothes');
            $table->integer('nSkin');
            $table->integer('nMixBaseHairColor');
            $table->integer('nMixAddHairColor');
            $table->integer('nMixHairBaseProb');
            $table->timestamp('created_at')->default(DB::raw('CURRENT_TIMESTAMP'));
            $table->timestamp('updated_at')->default(DB::raw('CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP'));
           $table->primary('dwCharacterID');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('character_dress');
    }
}
