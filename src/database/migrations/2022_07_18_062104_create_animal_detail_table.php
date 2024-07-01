<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateAnimalDetailTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('animal_detail', function (Blueprint $table) {
            $table->bigIncrements('id');
            $table->integer('id_animal');
            $table->text('image');
            $table->string('breed', 50);
            $table->string('age', 50);
            $table->string('sex');
            $table->text('about');
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('animal_detail');
    }
}
