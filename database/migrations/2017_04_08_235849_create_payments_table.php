<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreatePaymentsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('payments', function (Blueprint $table) {
            $table->increments('id');
            $table->integer('lease_id')->unsigned()->index();
            $table->integer('tenant_id')->unsigned()->index();
            $table->string('payment_type')->nullable();
            $table->integer('bank_deposit_id')->nullable()->unsigned()->index('fk_payments_bank_deposits1_idx');
            $table->string('method')->nullable();
            $table->string('memo')->nullable();
            $table->dateTime('paid_date')->nullable();
            $table->integer('amount')->default(0);
            $table->string('check_no', 45)->nullable();
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
        Schema::dropIfExists('payments');
    }
}
