<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\HasMany;

class InsuranceType extends Model
{
    use HasFactory;

    protected $guarded = [];

    protected $hidden = ["created_at", "updated_at"];


    /**
     * Get all of the companies for the InsuranceType
     *
     * @return \Illuminate\Database\Eloquent\Relations\HasMany
     */
    public function companies(): HasMany
    {
        return $this->hasMany(InsuranceCompany::class, 'insurance_company_id', 'id');
    }
}
