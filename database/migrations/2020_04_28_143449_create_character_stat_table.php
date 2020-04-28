<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateCharacterStatTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('character_stat', function (Blueprint $table) {
            $table->integer('dwCharacterID');
            $table->integer('dwCharacterIDForLog');
            $table->tinyInteger('dwWorldIDForLog');
            $table->string('sCharacterName');
            $table->tinyInteger('nGender');
            $table->integer('nSkin');
            $table->integer('nFace');
            $table->integer('nHair');
            $table->tinyInteger('nMixBaseHairColor');
            $table->tinyInteger('nMixAddHairColor');
            $table->tinyInteger('nMixHairBaseProb');
            $table->smallInteger('nLevel');
            $table->smallInteger('nJob');
            $table->smallInteger('nSTR');
            $table->smallInteger('nDEX');
            $table->smallInteger('nINT');
            $table->smallInteger('nLUK');
            $table->integer('nHP');
            $table->integer('nMHP');
            $table->integer('nMP');
            $table->integer('nMMP');
            $table->smallInteger('nAP');
            $table->string('sSP');
            $table->unsignedBigInteger('nExp64');
            $table->integer('nPop');
            $table->integer('nWP');
            $table->integer('dwPosMap');
            $table->tinyInteger('nPortal');
            $table->integer('nSubJob');
            $table->integer('nDefFaceAcc');
            $table->smallInteger('nFatigue');
            $table->integer('nLastFatigueUpdateTime');
            $table->integer('nCharismaEXP');
            $table->integer('nInsightExp');
            $table->integer('nWillExp');
            $table->integer('nCraftExp');
            $table->integer('nSenseExp');
            $table->integer('nCharmExp');
            $table->string('sDayLimit');
            $table->integer('nPvPExp');
            $table->tinyInteger('nPVPGrade');
            $table->integer('nPvpPoint');
            $table->tinyInteger('nPvpModeLevel');
            $table->tinyInteger('nPvpModeType');
            $table->integer('nEventPoint');
            $table->integer('ftLastLogoutTime');
            $table->boolean('bBurning');
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
        Schema::dropIfExists('character_stat');
    }
}
