<?php

namespace App\Models;

use App\Models\BusinessDetails;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class BusinessDocuments extends Model
{
    protected $table = 'business_documents';
    use HasFactory;

    public function businessDetails()
    {
        return $this->belongsTo(BusinessDetails::class, 'business_id', 'id');
    }
}
