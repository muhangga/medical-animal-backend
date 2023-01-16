<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateFacilityTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        // Schema::create('facility', function (Blueprint $table) {
        //     $table->id();
        //     $table->string('clinic_id')->nullable();
        //     $table->string('konsultasi')->nullable();
        //     $table->string('layanan_medis')->nullable();
        //     $table->string('penginapan')->nullable();
        //     $table->string('grooming')->nullable();
        // });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('facility');
    }
}
