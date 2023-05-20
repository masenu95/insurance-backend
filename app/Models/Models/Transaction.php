<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasOne;
use Illuminate\Notifications\Notifiable;

class Transaction extends Model
{
    use HasFactory, Notifiable;

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

    public function branch(): BelongsTo
    {
        return $this->belongsTo(Branch::class);
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
}
