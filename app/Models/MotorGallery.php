<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class MotorGallery extends Model
{
    use HasFactory;

    protected $fillable = ['transaction_id','name','url','path','lat','long','location','devices','time','image_type'];

    /**
     * Get the transaction that owns the MotorGallery
     *
     * @return \Illuminate\Database\Eloquent\Relations\BelongsTo
     */
    public function transaction(): BelongsTo
    {
        return $this->belongsTo(Transaction::class, 'transaction_id', 'id');
    }
}
