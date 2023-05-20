<?php

namespace App\Models;

use Illuminate\Contracts\Auth\MustVerifyEmail;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable;
use Laravel\Sanctum\HasApiTokens;

class User extends Authenticatable
{
    use HasFactory, Notifiable, HasApiTokens;

    protected $guarded = [];

    protected $hidden = ['password'];

    public function transactions(): HasMany
    {
        return $this->hasMany(Transaction::class);
    }

    public function libraries(){
        return $this->hasMany(Library::class);
    }

    public function branch(){
        return $this->belongsTo(Branch::class,'branch_id','id');
    }




}
