<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateInsuranceCoveragesTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('insurance_coverages', function (Blueprint $table) {
            $table->id();

            $table->unsignedBigInteger('insurance_product_id');

            $table->string('code')->unique();
            $table->string('name');
            $table->string("coverage_type");
            $table->double('rate');
            $table->double('minimum_amount');
            $table->json('parameters');
            $table->integer('is_deleted')->default('0');
            $table->timestamps();

            $table->foreign('insurance_product_id')->references('id')->on('insurance_products');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('insurance_coverages');
    }
}
