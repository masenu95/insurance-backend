<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Library extends Model
{
    use HasFactory;

    protected $fillable = ['title','size','name','url','user_id'];

    public function user(){
        return $this->belongsTo(User::class);
    }
}
