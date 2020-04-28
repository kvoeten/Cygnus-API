<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateAvatarLookTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('avatar_look', function (Blueprint $table) {
            $table->integer('dwCharacterID');
            $table->tinyInteger('nGender');
            $table->tinyInteger('nSkin');
            $table->integer('nFace');
            $table->integer('nHair');
            $table->integer('nJob');
            $table->integer('nWeaponStickerID');
            $table->integer('nWeaponID');
            $table->integer('nSubWeaponID');
            $table->boolean('bDrawElfEar');
            $table->integer('nXenonDefFaceAcc');
            $table->integer('nDemonSlayerDefFaceAcc');
            $table->integer('nBeastDefFaceAcc');
            $table->integer('nBeastEars');
            $table->integer('nBeastTail');
            $table->tinyInteger('nMixedHairColor');
            $table->tinyInteger('nMixedHairPercent');
            $table->integer('nPetPrimary');
            $table->integer('nPetSecondary');
            $table->integer('nPetTertiary');
            $table->json('anEquip');
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
        Schema::dropIfExists('avatar_look');
    }
}
