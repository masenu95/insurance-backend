<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Train extends Model
{
    use HasFactory;

    
    protected $fillable = [
        'user_id','title','desc','url','training_type',
    ];

	

    public function user()
    {
        return $this->belongsTo(User::class);
    }
}
