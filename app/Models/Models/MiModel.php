<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class MiModel extends Model
{
    use HasFactory;
    protected $fillable = ['name', 'brand_id', 'cor_price', 'price'];
    protected $hidden = ["created_at", "updated_at"];
}
