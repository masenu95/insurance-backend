<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class smsHeader extends Model
{
    
    use HasFactory;
    protected $hidden = ["created_at", "updated_at"];
    protected $table = 'sms_headers';
    
}
