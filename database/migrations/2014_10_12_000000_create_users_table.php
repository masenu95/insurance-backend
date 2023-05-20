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
        Schema::create('users', function (Blueprint $table) {
            $table->id();

            $table->string('username');
            $table->string('first_name', 160);
            $table->string('last_name', 160);
            $table->string('email')->unique();
            $table->string('phone', 20)->unique();
            $table->string('user_image',900)->default('user-img.png');
            $table->string('status', 60)->default("Active");
            $table->string('role',60);
            $table->integer('user_id');
            $table->string('password');
            $table->integer('is_deleted')->default('0');
            $table->timestamp('email_verified_at')->nullable();
            $table->rememberToken();
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('users');
    }
};
