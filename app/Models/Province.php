<?php

namespace App\Models;

use App\Models\Region;
use App\Models\City;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Province extends Model
{
    protected $table = 'table_province';
    protected $primaryKey = 'province_id';
    use HasFactory;

    public function region()
    {
        return $this->belongsTo(Region::class,'region_id','region_id');
    }

    public function municipalities()
    {
        return $this->hasMany(City::class,'province_id','province_id');
    }
}
