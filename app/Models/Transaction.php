<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Database\Eloquent\Relations\HasOne;
use Illuminate\Notifications\Notifiable;

class Transaction extends Model
{
    use HasFactory, Notifiable;


    protected $fillable = [
    'user_id',
    'customer_id',
    'insurance_type_id',
    'insurance_product_id',
    'insurance_coverage_id',
    'insurance_company_id',
    'agent_id',
    'request_id',
    'company_code',
    'system_code',
    'callback_url',
    'insurer_company_code',
    'tran_company_code',
    'covernote_type',
    'covernote_number',
    'prev_covernote_reference_number',
    'sales_point_code',
    'covernote_start_date',
    'covernote_end_date',
    'covernote_desc',
    'operative_clause',
    'payment_mode',
    'currency_code',
    'exchange_rate',
    'total_premium_excluding_tax',
    'total_premium_including_tax',
    'commission_paid',
    'commission_rate',
    'officer_name',
    'officer_title',
    'endorsement_type',
    'endorsement_reason',
    'sum_insured',
    'sum_insured_equivalent',
    'premium_rate',
    'premium_before_discount',
    'premium_discount',
    'discount_type',
    'premium_after_discount',
    'premium_excluding_tax_equivalent',
    'premium_including_tax',
    'tax_code','tax_rate',
    'tax_amount',
    'subject_matter_reference',
    'subject_matter_desc',
    'addon_reference',
    'addon_desc','
    addon_amount',
    'addon_premium_rate',
    'premium_excluding_tax',
    'is_feet',
    'fleet_id',
    'fleet_size',
    'comprehensive_insured',
    'fleet_entry',
    'motor_category',
    'registration_number',
    'Chassis_number','make','model',
    'model_number',
    'body_type',
    'color',
    'engine_number',
    'engine_capacity',
    'fuel_used',
    'number_Of_axles',
    'axle_distance',
    'Sitting_capacity',
    'year_Of_manufacture',
    'tare_weight',
    'gross_weight',
    'motor_usage',
    'owner_name',
    'owner_category',
    'owner_address',
    'covernote_reference_number',
    'sticker_number',
    'acknowledgement_status_code',
    'acknowledgement_status_desc',
    'response_status_code',
    'response_status_desc',
    'status',
    'cheque_number',
    'bank',
    'payment_number',
    'payment_network',
    'company_id',
    'is_deleted'
];

    public function routeNotificationForSlack($notification): string
    {
        return env('SLACK_WEBHOOK_URL');
    }

    protected $guarded = [];

    protected $with = ['customer.region', 'customer.district', 'user', 'insuranceCoverage', 'insuranceProduct', 'insuranceType'];

    public function scopeExpiredThreeDaysAgo($query)
    {
        return $query->whereDate('covernote_end_date', '=', now()->subDays(3));
    }

    public function scopeExpiresToday($query)
    {
        return $query->whereDate('covernote_end_date', '=', now());
    }

    public function scopeExpiresInThreeDays($query)
    {
        return $query->whereDate('covernote_end_date', '=', now()->addDays(3));
    }


    public function scopeExpiresInSevenDays($query)
    {
        return $query->whereDate('covernote_end_date', '=', now()->addDays(7));
    }

    public function scopeExpiresInAMonth($query)
    {
        return $query->whereDate('covernote_end_date', '=', now()->addMonth());
    }

    public function user(): BelongsTo
    {
        return $this->belongsTo(User::class);
    }

    public function customer(): BelongsTo
    {
        return $this->belongsTo(Customer::class);
    }

   /**
    * Get the company that owns the Transaction
    *
    * @return \Illuminate\Database\Eloquent\Relations\BelongsTo
    */
   public function company(): BelongsTo
   {
       return $this->belongsTo(Company::class, 'company_id', 'id');
   }

    public function insuranceType(): BelongsTo
    {
        return $this->belongsTo(InsuranceType::class);
    }

    public function insuranceProduct(): BelongsTo
    {
        return $this->belongsTo(InsuranceProduct::class);
    }

    public function insuranceCoverage(): BelongsTo
    {
        return $this->belongsTo(InsuranceCoverage::class);
    }

    public function claimNotification(): HasOne
    {
        return $this->hasOne(ClaimNotification::class);
    }

    /**
     * Get all of the images for the Transaction
     *
     * @return \Illuminate\Database\Eloquent\Relations\HasMany
     */
    public function images(): HasMany
    {
        return $this->hasMany(MotorGallery::class, 'transaction_id', 'id');
    }
}
