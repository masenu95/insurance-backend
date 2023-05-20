<?php


namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class HbStaff extends Model
{
    use HasFactory;
    protected $table ="hb_staffs";
    
    protected $fillable =['user_id','role'];

    public function user()
    {
        return $this->belongsTo(User::class);
    }

}