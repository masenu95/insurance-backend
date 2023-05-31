<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateCustomersTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('customers', function (Blueprint $table) {
            $table->id();

            $table->unsignedBigInteger('user_id');
            $table->unsignedBigInteger('region_id');
            $table->unsignedBigInteger('district_id');

            $table->string('full_name', 100);
            $table->date('birth_date');
            $table->integer('customer_type');
            $table->string('id_number', 50);
            $table->integer('id_type');
            $table->string('gender', 1);
            $table->string('country_code', 3);
            $table->string('street', 50);
            $table->string('phone_number', 50);
            $table->string('fax', 50)->nullable();
            $table->string('postal_address', 50);
            $table->string('email', 100);
            $table->integer('is_deleted')->default('0');
            $table->timestamps();

            $table->foreign('user_id')->references('id')->on('users');
            $table->foreign('region_id')->references('id')->on('regions');
            $table->foreign('district_id')->references('id')->on('districts');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('customers');
    }
}
