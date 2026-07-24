<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Barangay extends Model
{
    protected $table = 'table_barangay';
    protected $primaryKey = 'barangay_id';
    use HasFactory;

}
