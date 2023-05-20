<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use App\Models\Communication;

class Hb_Staff extends Model
{
    use HasFactory;

 public function communications(){
     return $this->morphMany(Communication::class,'sender');
 }
}
