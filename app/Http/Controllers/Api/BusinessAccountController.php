<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Http\Requests\BusinessRegisterRequest;
use App\Models\BusinessDetails;
use App\Models\BusinessDocuments;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Str;

class BusinessAccountController extends Controller
{

    // store
    public function store(BusinessRegisterRequest $request)
    {
        $businessId 
        = BusinessDetails::insertGetId([
            'user_id'                   => Auth::id(),
            'business_cd'               => Str::uuid(),

            'mobileno'                  => $request->business_contact_number,
            'business_type'             => $request->business_type,
            'business_name'             => $request->business_name,
            'business_category'         => $request->business_category,
            'business_industry'         => $request->additional_product,
            'business_contact_no'       => $request->business_contact_number,
            'business_email'            => $request->business_email,

            'business_services'         => json_encode($request->business_services),
            'payments_accepted'         => json_encode($request->payments_accepted),
            'days_open'                 => json_encode($request->days_of_operation),

            'open_time'                 => $request->open_time,
            'close_time'                => $request->close_time,

            'barangay_id'               => $request->barangay_id,
            'municipality_id'           => $request->municipality_id,
            'province_id'               => $request->province_id,
            'region_id'                 => $request->region_id,

            'business_loc_region'       => $request->region_id,
            'business_loc_barangay'     => $request->barangay_id,
            'business_loc_municipality' => $request->municipality_id,
            'business_loc_province'     => $request->province_id,
            'business_loc_zip_code'     => null,

            'facebook_account_link'     => $request->facebook_link,
            'tiktok_account_link'       => $request->tiktok_link,
            'instagram_account_link'    => $request->instagram_link,
            'website_link'              => $request->website_link,

            'date_registered'           => now(),

            'latitude'                  => $request->latitude,
            'longitude'                 => $request->longitude,
        ]);

        // Insert uploaded document names/paths
        DB::connection('pgsql')->table('business_documents')
        ->insert([
            'business_id'               => $businessId,
            'business_permit'           => $request->business_permit,
            'bir_certificate'           => $request->bir_certificate_of_registration,
            'dti_registration'          => $request->dti_registration,
            'sec_registration'          => $request->sec_registration,
            'sanitary_permit'           => $request->sanitary_registration,
        ]);

        return response()->json([
            'success'                   => true,
            'message'                   => 'Business registered successfully.'
        ]);
    }

    // update
    public function update(BusinessRegisterRequest $request)
    {

        BusinessDetails
        ::where('id', $request->route('id'))
        ->where('user_id', Auth::id())
        ->update([
            'mobileno'                  => $request->business_contact_number,
            'business_type'             => $request->business_type,
            'business_name'             => $request->business_name,
            'business_category'         => $request->business_category,
            'business_industry'         => $request->additional_product,
            'business_contact_no'       => $request->business_contact_number,
            'business_email'            => $request->business_email,

            'business_services'         => json_encode($request->business_services),
            'payments_accepted'         => json_encode($request->payments_accepted),
            'days_open'                 => json_encode($request->days_of_operation),

            'open_time'                 => $request->open_time,
            'close_time'                => $request->close_time,

            'barangay_id'               => $request->barangay_id,
            'municipality_id'           => $request->municipality_id,
            'province_id'               => $request->province_id,
            'region_id'                 => $request->region_id,

            'business_loc_region'       => $request->region_id,
            'business_loc_barangay'     => $request->barangay_id,
            'business_loc_municipality' => $request->municipality_id,
            'business_loc_province'     => $request->province_id,
            'business_loc_zip_code'     => null,

            'facebook_account_link'     => $request->facebook_link,
            'tiktok_account_link'       => $request->tiktok_link,
            'instagram_account_link'    => $request->instagram_link,
            'website_link'              => $request->website_link,

            // 'date_registered'        => now(), // Karaniwang hindi na pinapalitan kapag nag-a-update para hindi mabura ang original registration date

            'latitude'                  => $request->latitude,
            'longitude'                 => $request->longitude,
        ]);

        // Insert uploaded document names/paths
        BusinessDocuments
        ::where('business_id', $request->route('id'))
        ->update([
            'business_permit'           => $request->business_permit,
            'bir_certificate'           => $request->bir_certificate_of_registration,
            'dti_registration'          => $request->dti_registration,
            'sec_registration'          => $request->sec_registration,
            'sanitary_permit'           => $request->sanitary_registration,
        ]);

        return response()->json([
            'success'                   => true,
            'message'                   => 'Business successfully updated.'
        ]);
    }


    // delete
    public function destroy(Request $request)
    {

        BusinessDetails
        ::where('id', $request->route('id'))
        ->where('user_id', Auth::id())
        ->delete();

        BusinessDocuments
        ::where('business_id', $request->route('id'))
        ->delete();


        return response()->json([
            'success'                   => true,
            'message'                   => 'Business successfully deleted.'
        ]);
    }

}
