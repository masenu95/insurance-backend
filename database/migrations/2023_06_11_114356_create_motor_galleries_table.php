<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateMotorGalleriesTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('motor_galleries', function (Blueprint $table) {
            $table->id();
            $table->string('transaction_id');
            $table->string('name');
            $table->string('lat')->nullable();
            $table->string('long')->nullable();
            $table->string('devices')->nullable();
            $table->string('time')->nullable();
            $table->string('image_type');
            $table->timestamps();

            $table->foreign('transaction_id')->reference('id')->on('transactions');
        });


    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('motor_galleries');
    }
}
