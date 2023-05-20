<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateBankPaymentsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('bank_payments', function (Blueprint $table) {
            $table->id();

            $table->string('order_id',80);
            $table->double('amount')->nullable();
            $table->double('paid_amount')->nullable();
            $table->double('remain_amount')->nullable();
            $table->string('bank_name',120)->nullable();
            $table->string('description',920)->nullable();
            $table->string('reference_number',220)->nullable();
            $table->string('status',120)->nullable();
            $table->integer('user_id')->nullable();
            $table->integer('is_deleted')->default('0');
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
        Schema::dropIfExists('bank_payments');
    }
}
