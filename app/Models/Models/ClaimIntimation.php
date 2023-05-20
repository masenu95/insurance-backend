<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Database\Eloquent\Relations\HasOne;

class ClaimIntimation extends Model
{
    use HasFactory;

    protected $guarded = [];

    public function claimNotification(): BelongsTo
    {
        return $this->belongsTo(ClaimNotification::class);
    }

    public function claimant(): BelongsTo
    {
        return $this->belongsTo(Claimant::class);
    }

}
