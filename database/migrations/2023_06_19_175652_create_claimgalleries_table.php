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
        Schema::create('claimgalleries', function (Blueprint $table) {
            $table->unsignedBigInteger('claim_notification_id');
            $table->string('name');
            $table->string('url');
            $table->string('path');
            $table->string('lat')->nullable();
            $table->string('long')->nullable();
            $table->string('location')->nullable();
            $table->string('devices')->nullable();
            $table->string('time')->nullable();
            $table->string('image_type');
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('claimgalleries');
    }
};
