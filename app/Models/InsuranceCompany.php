<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsToMany;
class InsuranceCompany extends Model
{
    use HasFactory;


    protected $fillable = ['name','company_code','email','phone','logo_url','logo_path','user_id','policies','status'];


    /**
     * Get all of the type for the InsuranceCompany
     *
     * @return \Illuminate\Database\Eloquent\Relations\BelongToMany
     */
    public function types   (): BelongsToMany
    {
        return $this->belongsToMany(InsuranceType::class);
    }
}
