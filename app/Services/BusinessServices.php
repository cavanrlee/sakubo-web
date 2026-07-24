<?php

namespace App\Services;

use App\Models\BusinessDetails;
use Illuminate\Support\Facades\Auth;


class BusinessServices
{


	public function __construct()
	{

	}

	public static function Business()
	{
		return 
		BusinessDetails::from('business_details as bd')
		->join('table_region as tr', 'bd.region_id', '=', 'tr.region_id')
		->join('table_province as tp', 'bd.province_id', '=', 'tp.province_id')
		->join('table_municipality as tm', 'bd.municipality_id', '=', 'tm.municipality_id')
		->join('table_barangay as tb', 'bd.barangay_id', '=', 'tb.barangay_id')
		->where('bd.user_cd', Auth::id())
		->get();
	}

}
