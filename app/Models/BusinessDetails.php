<?php

namespace App\Models;

use App\Models\Barangay;
use App\Models\City;
use App\Models\Province;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class BusinessDetails extends Model
{
    protected $table = 'business_details';
    use HasFactory;

    public function user()
    {
        return $this->belongsTo(User::class, 'user_id', 'user_id');
    }

    public function barangay()
    {
        return $this->belongsTo(Barangay::class, 'barangay_id', 'barangay_id');
    }

    public function municipality()
    {
        return $this->belongsTo(City::class, 'municipality_id', 'municipality_id');
    }

    public function province()
    {
        return $this->belongsTo(Province::class, 'province_id', 'province_id');
    }

}
