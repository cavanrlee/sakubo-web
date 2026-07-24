<?php

namespace App\Models;

use App\Models\Barangay;
use App\Models\Province;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class City extends Model
{
    protected $table = 'table_municipality';
    protected $primaryKey = 'municipality_id';
    use HasFactory;

    public function province()
    {
        return $this->belongsTo(Province::class,'province_id','province_id');
    }

    public function barangays()
    {
        return $this->hasMany(Barangay::class,'municipality_id','municipality_id');
    }
}
