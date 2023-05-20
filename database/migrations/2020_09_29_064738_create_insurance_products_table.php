<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateInsuranceProductsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('insurance_products', function (Blueprint $table) {
            $table->id();

            $table->unsignedBigInteger('insurance_type_id');

            $table->string('code')->unique();
            $table->string('name');
            $table->string('product_type')->nullable();
            $table->integer('is_deleted')->default('0');
            $table->timestamps();

            $table->foreign('insurance_type_id')->references('id')->on('insurance_types');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('insurance_products');
    }
}
