<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateLeasesTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('leases', function (Blueprint $table) {
            $table->increments('id');
            $table->integer('apartment_id')->unsigned()->index();
            $table->datetime('start')->nullable();
            $table->datetime('end')->nullable();
            $table->integer('monthly_rent')->default(0);
            $table->integer('pet_rent')->default(0);
            $table->integer('deposit')->default(0);
            $table->integer('pet_deposit')->default(0);
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
        Schema::dropIfExists('leases');
    }
}
