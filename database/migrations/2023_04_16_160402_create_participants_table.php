<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateParticipantsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('participants', function (Blueprint $table) {
            $table->bigIncrements('id');
            $table->unsignedBigInteger('user_id');
            $table->string('fname');
            $table->string('mname')->nullable();
            $table->string('lname');
            $table->string('address');

            $table->date('birthdate')->nullable();
            $table->integer('age')->nullable();
            $table->string('gender')->nullable();

            $table->text('path')->nullable();
            $table->text('directory')->nullable();
            $table->string('filename', 100)->nullable();
            
            $table->timestamps();
            $table->softDeletes();

            $table->foreign('user_id')->references('id')->on('users');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('participants');
    }
}
