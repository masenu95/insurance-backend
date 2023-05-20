<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class District extends Model
{
    use HasFactory;

    protected $guarded = [];

    protected $hidden = ["created_at", "updated_at"];

    public function region(): BelongsTo
    {
        return $this->belongsTo(Region::class);
    }

}
