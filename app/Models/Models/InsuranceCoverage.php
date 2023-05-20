<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class InsuranceCoverage extends Model
{
    use HasFactory;

    protected $guarded = [];

    protected $hidden = ["created_at", "updated_at"];

    protected $casts = ['parameters' => 'array'];

    public function insuranceProduct(): BelongsTo
    {
        return $this->belongsTo(InsuranceProduct::class);
    }

}


