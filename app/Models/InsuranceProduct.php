<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;

class InsuranceProduct extends Model
{
    use HasFactory;

    protected $guarded = [];

    protected $hidden = ["created_at", "updated_at"];

    public function insuranceType(): BelongsTo
    {
        return $this->belongsTo(InsuranceType::class);
    }

    public function coveragies(): HasMany
    {
        return $this->hasMany(InsuranceCoverage::class);
    }
}
