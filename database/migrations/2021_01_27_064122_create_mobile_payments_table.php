<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateMobilePaymentsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('mobile_payments', function (Blueprint $table) {
            $table->id();

            $table->string('order_id',80);
            $table->string('currency',10)->nullable();
            $table->double('amount')->nullable();
            $table->double('paid_amount')->nullable();
            $table->double('remain_amount')->nullable();
            $table->string('payment_status',80)->nullable();
            $table->string('payment_token',60)->nullable();
            $table->string('qr',900)->nullable();
            $table->string('channel',120)->nullable();
            $table->string('phone',30)->nullable();
            $table->string('transid',120)->nullable();
            $table->string('reference',220)->nullable();
            $table->string('status',120)->nullable();
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
        Schema::dropIfExists('mobile_payments');
    }
}
