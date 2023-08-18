<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Run the migrations.
     */
    public function up(): void
    {
        Schema::create('members', function (Blueprint $table) {
            $table->id();
            $table->string("name");
            $table->string("dob")->nullable();
            $table->string("gender")->nullable();
            $table->string("region")->nullable();
            $table->string("district")->nullable();
            $table->string("street")->nullable();
            $table->string("marital_status")->nullable();
            $table->unsignedBigInteger('user_id');



            $table->timestamps();


            $table->foreign('user_id')->references('id')->on('users');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('members');
    }
};
