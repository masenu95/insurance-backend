<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class Customer extends Model
{
    use HasFactory;

    protected $fillable = ['user_id','region_id','district_id','full_name','birth_date','customer_type','id_number','id_type','gender','country_code','street','phone_number','fax','postal_address','email'];

    public function user(): BelongsTo
    {
        return $this->belongsTo(User::class);
    }


    public function region(): BelongsTo
    {
        return $this->belongsTo(Region::class);
    }

    public function district(): BelongsTo
    {
        return $this->belongsTo(District::class);
    }
}
