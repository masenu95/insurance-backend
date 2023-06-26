<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateClaimNotificationsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('claim_notifications', function (Blueprint $table) {
            $table->id();
            $table->string('request_id')->nullable();
            $table->foreignId('transaction_id')->constrained();
            $table->string('notification_number');
            $table->dateTime('report_date');
            $table->string('form_dully_filled');
            $table->dateTime('loss_date');
            $table->string('loss_nature');
            $table->string('loss_type');
            $table->string('loss_location');
            $table->string('officer_name');
            $table->string('officer_title');
            $table->string('acknowledgement_status_code')->nullable();
            $table->string('acknowledgement_status_desc')->nullable();
            $table->string('reference_number')->nullable();
            $table->string('response_status_code')->nullable();
            $table->string('response_status_desc')->nullable();
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
        Schema::dropIfExists('claim_notifications');
    }
}
