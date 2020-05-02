<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateAccountTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('account', function (Blueprint $table) {
            $table->integer('nAccountID');
            $table->integer('nSessionID')->default(-1);
            $table->integer('nNexonCash')->default(0);
            $table->integer('nMaplePoint')->default(0);
            $table->integer('nMileage')->default(0);
            $table->tinyInteger('nLastWorldID')->default(-1);
            $table->tinyInteger('nCharSlots');
            $table->string('sAccountName');
            $table->string('sIP')->default('');
            $table->string('sSecondPW')->nullable()->default(null);
            $table->tinyInteger('nState')->default(0);
            $table->tinyInteger('nGender');
            $table->tinyInteger('nGradeCode')->default(0);
            $table->date('dBirthDay');
            $table->date('dLastLoggedIn')->default(DB::raw('CURRENT_DATE'));
            $table->date('dCreateDate')->default(DB::raw('CURRENT_DATE'));
            $table->timestamp('created_at')->default(DB::raw('CURRENT_TIMESTAMP'));
            $table->timestamp('updated_at')->default(DB::raw('CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP'));
            $table->primary('nAccountID');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('account');
    }
}
