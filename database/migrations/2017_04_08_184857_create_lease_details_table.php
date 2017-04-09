<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateLeaseDetailsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('lease_details', function (Blueprint $table) {
            $table->increments('id');
            $table->integer('lease_id')->unsigned();
            $table->dateTime('start');
            $table->dateTime('end');
            $table->integer('month')->nullable();
            $table->integer('year')->nullable();            
            $table->integer('monthly_rent')->default(0);
            $table->integer('monthly_pet_rent')->default(0);
            $table->decimal('multiplier');
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
        Schema::dropIfExists('lease_details');
    }
}
