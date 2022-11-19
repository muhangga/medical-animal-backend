<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateWorkingDaysTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('working_days', function (Blueprint $table) {
            $table->id();
            $table->integer('clinic_id');
            $table->string('monday')->nullable();
            $table->string('tuesday')->nullable();;
            $table->string('wednesday')->nullable();;
            $table->string('thursday')->nullable();;
            $table->string('friday')->nullable();;
            $table->string('saturday')->nullable();;
            $table->string('sunday')->nullable();;
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('working_days');
    }
}
