<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class ClaimNotification extends Model
{
    use HasFactory;

    protected $guarded = [];

    protected $fillable = ['request_id','transaction_id','notification_number','report_date','form_dully_filled','loss_date','loss_nature','loss_type','loss_location','officer_name','officer_title','acknowledgement_status_code','acknowledgement_status_desc','reference_number','response_status_code','response_status_desc',];

    public function transaction(): BelongsTo
    {
        return $this->belongsTo(Transaction::class);
    }
}
