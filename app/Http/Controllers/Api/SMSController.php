<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Services\VonageSmsService;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class SMSController extends Controller
{
    public function sendOTP(Request $request, VonageSmsService $sms_otp)
    {
        // API KEY CHECK
        if ($request->header('X-API-KEY') !== config('services.api.key')) {
            return response()->json([
                'success' => false,
                'error' => [
                    'code'      => 'INVALID_API_KEY',
                    'message'   => 'The provided API key is invalid.',
                ],
            ], 401);
        }

        // $send_to = $request->mobile_number;
        // $message = "Your OTP is 123456";

        // $result = $sms_otp->sendOTPSMS($request->mobile_number, $message);

        // if ($result['success']) {
        //     return response()->json(['message' => 'SMS Sent']);
        // }

        // return response()->json(['error' => $result['error']], 500);

        $latest = DB::connection('pgsql')->table('sample_otp as so')
            ->where('so.mobile_number', $request->get('mobile_number'))
            ->orderByDesc('so.entry_date_time')
            ->first();

        $user_details = DB::connection('pgsql')->table('users as u')
            ->where('u.number', $request->mobile_number)
            ->first();

        if ($latest) {
            if (now()->lt($latest->otp_expiry)) {
                if ($latest->otp_send_count >= 3) {
                    return response()->json([
                        'error' => 'Too many OTP requests. Please wait.'
                    ], 429);
                }

                return response()->json([
                    'error' => 'OTP still active. Please wait before requesting again.'
                ], 429);
            }
        }

        $expiry = now()->addMinutes(5);
        $OTP = str_pad(random_int(0, 999999), 6, '0', STR_PAD_LEFT);
        $otp_message_string = "Your One-Time Password (OTP) is: {$OTP}. This code is valid for 5 minutes. For your security, do not share this code with anyone.";

        $result = $sms_otp->sendOTPSMS($request->mobile_number, $otp_message_string);

        DB::connection('pgsql')->table('sample_otp')->insert([
            'otp_no' => $OTP,
            'user_id' => $user_details->user_id,
            'mobile_number' => $request->mobile_number,
            'otp' => $OTP,
            'otp_send_count' => ($latest ? $latest->otp_send_count + 1 : 1),
            'otp_attempt_count' => 0,
            'otp_date_sent' => now(),
            'otp_expiry' => $expiry,
            'blocked' => 0,
            'entry_date_time' => now()
        ]);

        $response = [
            'mobile_number' => $request->mobile_number
        ];

        if ($result['success']) {
            return response()->json($response);
        }

        return response()->json(['error' => $result['error']], 500);
    }

    public function sendOTPWhatsApp(Request $request, VonageSmsService $sms_otp)
    {
        $latest = DB::connection('pgsql')->table('sample_otp as so')
            ->where('so.mobile_number', $request->get('mobile_number'))
            ->orderByDesc('so.entry_date_time')
            ->first();

        $user_details = DB::connection('pgsql')->table('users as u')
            ->where('u.number', $request->mobile_number)
            ->first();

        if ($latest) {
            if (now()->lt($latest->otp_expiry)) {
                if ($latest->otp_send_count >= 3) {
                    return response()->json([
                        'error' => 'Too many OTP requests. Please wait.'
                    ], 429);
                }

                return response()->json([
                    'error' => 'OTP still active. Please wait before requesting again.'
                ], 429);
            }
        }

        $expiry = now()->addMinutes(5);
        $OTP = str_pad(random_int(0, 999999), 6, '0', STR_PAD_LEFT);
        $otp_message_string = "Your One-Time Password (OTP) is: {$OTP}. This code is valid for 5 minutes. For your security, do not share this code with anyone.";

        $result = $sms_otp->sendOTPWhatsapp($request->mobile_number, $otp_message_string);

        DB::connection('pgsql')->table('sample_otp')->insert([
            'otp_no' => $OTP,
            'user_id' => $user_details->user_id,
            'mobile_number' => $request->mobile_number,
            'otp' => $OTP,
            'otp_send_count' => ($latest ? $latest->otp_send_count + 1 : 1),
            'otp_attempt_count' => 0,
            'otp_date_sent' => now(),
            'otp_expiry' => $expiry,
            'blocked' => 0,
            'entry_date_time' => now()
        ]);

        $response = [
            'mobile_number' => $request->mobile_number
        ];

        if ($result['success']) {
            return response()->json($response);
        }

        return response()->json(['error' => $result['error']], 500);
    }

    public function OTPValidation(Request $request)
    {
        $latest = DB::connection('pgsql')->table('sample_otp as so')
            ->where('so.mobile_number', $request->mobile_number)
            ->orderByDesc('so.entry_date_time')
            ->first();

        if (!$latest) {
            return response()->json([
                'error' => 'Invalid OTP.'
            ], 404);
        }

        if (now()->gt($latest->otp_expiry)) {
            DB::connection('pgsql')->table('sample_otp as so')
                ->where('so.entry_id', $latest->entry_id)
                ->update([
                    'blocked' => true
                ]);

            return response()->json([
                'error' => 'OTP expired.'
            ], 410);
        }

        if ($latest->otp_send_count >= 3) {
            DB::connection('pgsql')->table('sample_otp as so')
                ->where('so.entry_id', $latest->entry_id)
                ->update([
                    'blocked' => true
                ]);

            return response()->json([
                'error' => 'Too many attempts. Please try again later.'
            ], 429);
        }

        if ($latest->otp != $request->OTP) {
            DB::connection('pgsql')->table('sample_otp as so')
                ->where('so.entry_id', $latest->entry_id)
                ->update([
                    'otp_attempt_count' => $latest->otp_attempt_count + 1
                ]);

            return response()->json([
                'error' => 'Invalid OTP.'
            ], 401);
        }

        DB::connection('pgsql')->table('sample_otp as so')
            ->where('so.entry_id', $latest->entry_id)
            ->update([
                'otp_attempt_count' => $latest->otp_attempt_count + 1,
                'verified' => true,
                'verification_date_time' => now(),
            ]);

        $response = [
            'user_id' => $latest->user_id,
            'message' => 'OTP verified successfully.'
        ];

        return response()->json($response);
    }
}
