<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\HasMany;

class InsuranceCompany extends Model
{
    use HasFactory;


    protected $fillable = ['name','company_code','email','phone','logo_url','logo_path','user_id'];


    /**
     * Get all of the type for the InsuranceCompany
     *
     * @return \Illuminate\Database\Eloquent\Relations\HasMany
     */
    public function types   (): HasMany
    {
        return $this->hasMany(InsuranceType::class, 'insurance_type_id', 'id');
    }
}
