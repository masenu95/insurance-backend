<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Message extends Model
{
    use HasFactory;

    protected $table ="messages";

    protected $fillable =['communication_id','sender_by','message','user_id','status'];

    public function communication()
    {
        return $this->belongsTo(Communication::class);
    } 

    public function user()
    {
        return $this->belongsTo(User::class);
    }
}
