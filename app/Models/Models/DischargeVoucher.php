<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class DischargeVoucher extends Model
{
    use HasFactory;

    protected $guarded = [];

    public function claimAssessment(): BelongsTo
    {
        return $this->belongsTo(ClaimAssessment::class);
    }
}
