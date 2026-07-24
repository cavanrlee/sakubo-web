<?php

namespace App\Services;

use Illuminate\Support\Facades\Http;
use Vonage\Client\Credentials\Basic;
use Vonage\Client;
use Vonage\SMS\Message\SMS;

class VonageSmsService
{
	protected object $client;

	public function __construct()
	{
		$basic  = new Basic(
			config('services.vonage.key'),
			config('services.vonage.secret')
		);

		$this->client = new Client($basic);
	}

	public function sendOTPSMS(?string $recepient_mobile_number, ?string $message)
	{
		try {
			$response = $this->client->sms()->send(
				new SMS($recepient_mobile_number,config('services.vonage.sms_from'),$message)
			);

			return [
				'success' 	=> true,
				'response' 	=> $response
			];
		} catch (\Exception $e) {
			return [
				'success' => false,
				'error' 	=> $e->getMessage()
			];
		}
	}

	public function sendOTPWhatsapp(?string $recepient_mobile_number, ?string $message)
	{
		try {
			$response 
			= Http::withBasicAuth(
				config('services.vonage.key'),
				config('services.vonage.secret')
			)->post('https://messages-sandbox.nexmo.com/v1/messages', [
				"from" 		=> "14157386102",
				"to" 		=> $recepient_mobile_number,
				"message_type" => "text",
				"text" 		=> $message,
				"channel" 	=> "whatsapp"
			]);

			return [
				'success' 	=> true,
				'response' 	=> $response
			];
		} catch (\Exception $e) {
			return [
				'success' => false,
				'error' 	=> $e->getMessage()
			];
		}
	}

	// public function OTPValidation($user_mobile_number)
	// {
	// 	$latest = DB::connection('pgsql')->table('sample_otp as so')
	// 		->where('so.mobile_number', $user_mobile_number)
	// 		->orderByDesc('so.entry_date_time')
	// 		->first();

	// 	if ($latest) {
	// 		if ($latest->otp_send_count >= 3) {
	// 			return response()->json([
	// 				'error' => 'Too many OTP requests. Please wait.'
	// 			], 429);
	// 		}

	// 		return response()->json([
	// 			'error' => 'OTP still active. Please wait before requesting again.'
	// 		], 429);
	// 	}
	// }
}
