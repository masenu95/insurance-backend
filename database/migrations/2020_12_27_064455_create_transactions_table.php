<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateTransactionsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('transactions', function (Blueprint $table) {
            $table->id();

            $table->unsignedBigInteger('user_id'); //user id fk from user table
            $table->unsignedBigInteger("customer_id"); //customer id
            $table->unsignedBigInteger("insurance_type_id"); //insurance type eg Motor, Fire...
            $table->unsignedBigInteger("insurance_product_id"); //product code
            $table->unsignedBigInteger("insurance_coverage_id"); //risk code

            $table->string("request_id"); //transaction id
            $table->string("company_code", 6)->comment("assigned by TIRA"); //assigned by TIRA
            $table->string("system_code", 12)->comment("assigned by TIRA"); //assigned by TIRA
            $table->string("callback_url", 250)->nullable(); //unique
            $table->string("insurer_company_code", 6)->comment("assigned by TIRA");
            $table->string("tran_company_code", 6)->comment("assigned by TIRA");
            $table->integer("covernote_type")->default("1"); //1-new, 2-Renew, 3-Endorsement
            $table->string("covernote_number", 50)->nullable(); //assigned by insurer
            $table->string("prev_covernote_reference_number", 50)->nullable(); //from tira Opt for covernote data, m for any endorsement
            $table->string("sales_point_code", 12)->nullable(); //assigned by TIRA
            $table->dateTime("covernote_start_date")->nullable(); //YYYY-MM-DD 'T'HH:MM:SS eg 2021-07-04T
            $table->dateTime("covernote_end_date")->nullable(); //YYYY-MM-DD 'T'HH:MM:SS eg 2021-07-04T
            $table->string("covernote_desc", 1000)->nullable();
            $table->string("operative_clause")->nullable();
            $table->string("payment_mode")->nullable(); //[2-cheque, 3-EFT/]
            $table->string("currency_code", 4)->default("TZS"); //
            $table->double("exchange_rate")->default("1");
            $table->double("total_premium_excluding_tax")->nullable();
            $table->double("total_premium_including_tax")->nullable();
            $table->double("commission_paid")->nullable(); //in case of sales done through intermediaries
            $table->double("commission_rate")->nullable(); //on paid commission

            $table->string("officer_name")->nullable(); //auth office
            $table->string("officer_title", 100)->nullable();
            $table->string("endorsement_type", 100)->nullable();
            $table->string("endorsement_reason", 100)->nullable();
            $table->double("sum_insured")->nullable();
            $table->double("sum_insured_equivalent")->nullable();
            $table->double("premium_rate")->nullable(); //Risk premium rate charged
            $table->double("premium_before_discount")->nullable();
            $table->double("premium_discount")->nullable();
            $table->double("discount_type")->default("1"); //1-fleet
            $table->double("premium_after_discount")->nullable(); //         -- nu-36,2    --m
            $table->double("premium_excluding_tax_equivalent")->nullable(); //  -- nu-36,2  --m
            $table->double("premium_including_tax")->nullable(); //            -- nu-36,2  --m
            $table->string("tax_code")->nullable(); //assigned by tira
            $table->double("tax_rate")->nullable(); //18 to 15
            $table->double("tax_amount")->nullable();
            $table->string("subject_matter_reference")->nullable(); //assigned by insurer
            $table->string("subject_matter_desc", 250)->nullable();
            $table->string("addon_reference")->nullable(); //assigned by insurer
            $table->double("addon_desc", 250)->nullable();
            $table->double("addon_amount")->nullable();
            $table->double("addon_premium_rate")->nullable();
            $table->double("premium_excluding_tax")->nullable();

            $table->String("is_feet", 1)->nullable();
            $table->String("fleet_id", 50)->nullable();
            $table->integer("fleet_size")->nullable();
            $table->integer("comprehensive_insured")->nullable();
            $table->integer("fleet_entry")->nullable();
            $table->integer("motor_category")->nullable();
            $table->String("registration_number", 50)->nullable();
            $table->String("Chassis_number", 50)->nullable();
            $table->String("make", 100)->nullable();
            $table->String("model", 100)->nullable();
            $table->String("model_number", 100)->nullable();
            $table->String("body_type", 100)->nullable();
            $table->String("color", 100)->nullable();
            $table->String("engine_number", 100)->nullable();
            $table->String("engine_capacity", 100)->nullable();
            $table->String("fuel_used", 100)->nullable();
            $table->integer("number_Of_axles",)->nullable();
            $table->integer("axle_distance",)->nullable();
            $table->integer("Sitting_capacity")->nullable();
            $table->integer("year_Of_manufacture")->nullable();
            $table->integer("tare_weight")->nullable();
            $table->integer("gross_weight")->nullable();
            $table->String("motor_usage")->nullable();
            $table->String("owner_name")->nullable();
            $table->String("owner_category")->nullable();
            $table->String("owner_address")->nullable();

            $table->string("covernote_reference_number")->nullable();
            $table->string("sticker_number")->nullable();
            $table->string("acknowledgement_status_code")->nullable();
            $table->string("acknowledgement_status_desc")->nullable();
            $table->string("response_status_code")->nullable();
            $table->string("response_status_desc")->nullable();

            $table->string("status")->nullable();
            $table->string("cheque_number")->nullable();
            $table->string("bank")->nullable();
            $table->string("payment_number")->nullable();
            $table->string("payment_network")->nullable();
            $table->integer('is_deleted')->default('0');

            $table->foreign('user_id')->references('id')->on('users'); //user id
            $table->foreign('branch_id')->references('id')->on('branches'); //user id
            $table->foreign('customer_id')->references('id')->on('customers'); //user id
            $table->foreign('insurance_type_id')->references('id')->on('insurance_types'); //user id
            $table->foreign('insurance_product_id')->references('id')->on('insurance_products'); //product
            $table->foreign('insurance_coverage_id')->references('id')->on('insurance_coverages'); //coverage

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
        Schema::dropIfExists('transactions');
    }
}
