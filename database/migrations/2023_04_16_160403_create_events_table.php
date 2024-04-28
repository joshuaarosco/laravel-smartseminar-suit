<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateEventsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('events', function (Blueprint $table) {
            $table->bigIncrements('id');
            $table->unsignedBigInteger('category_id');
            $table->string('status')->default('Pending')->nullable();
            $table->string('name')->nullable();
            $table->datetime('start')->nullable();
            $table->datetime('end')->nullable();
            $table->text('details')->nullable();

            $table->timestamps();
            $table->softDeletes();

            $table->foreign('category_id')->references('id')->on('certificate_categories');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('events');
    }
}
