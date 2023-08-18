<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class HTransaction extends Model
{
    use HasFactory;

    protected $fillable = ['member_id','order_id','currency','amount','paid_amount','remain_amount','payment_status','payment_token','qr','channel','phone','transid','reference','status'];

    /**
     * Get the member that owns the HTransaction
     *
     * @return \Illuminate\Database\Eloquent\Relations\BelongsTo
     */
    public function member(): BelongsTo
    {
        return $this->belongsTo(User::class, 'member_id', 'member');
    }
}
