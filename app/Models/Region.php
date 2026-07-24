<?php

namespace App\Models;

use App\Models\Province;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Region extends Model
{
    protected $table = 'table_region';
    protected $primaryKey = 'region_id';
    use HasFactory;

    public function provinces()
    {
        return $this->hasMany(Province::class,'region_id','region_id');
    }
}
