<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class ClaimRejection extends Model
{
    use HasFactory;

    protected $guarded = [];

    public function claimIntimation(): BelongsTo
    {
        return $this->belongsTo(ClaimIntimation::class);
    }
}