<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class MobilePayment extends Model
{
    use HasFactory;

    protected $fillable = ['order_id','currency','amount','paid_amount','remain_amount','payment_status','payment_token','qr','channel','phone','transid','reference','status','is_deleted'];

    protected $hidden = ["created_at", "updated_at"];

}
