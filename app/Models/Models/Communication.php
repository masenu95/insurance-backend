<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use \Illuminate\Database\Eloquent\Relations\HasMany;

class Communication extends Model
{
    use HasFactory;

    protected $fillable = [
        'user_id','branch_id','no_sms'
    ];
    public function branch(): BelongTo{
       return $this->belongsTo(Branch::class);
    }


     public function user(): BelongsTo
     {
         return $this->belongsTo(User::class, 'user_id', 'id');
     }

     public function messages(): HasMany{
        return $this->hasMany(Message::class,'communication_id', 'id');
     }

}

			