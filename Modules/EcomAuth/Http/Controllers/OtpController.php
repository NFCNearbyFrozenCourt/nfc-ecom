<?php

namespace Modules\EcomAuth\Http\Controllers;

use App\Http\Controllers\Controller;
use Modules\EcomAuth\Services\WhatsAppService;
use Carbon\Carbon;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;
use Illuminate\Support\Str;
use Modules\EcomAuth\Otp;
use App\User;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Session;

class OtpController extends Controller
{
    protected $whatsAppService;
    
    public function __construct(WhatsAppService $whatsAppService)
    {
        $this->whatsAppService = $whatsAppService;
    }
    
    /**
     * Generate and send OTP to WhatsApp number
     */
    public function generateOtp(Request $request)
    {
        // Validate request
        $validator = Validator::make($request->all(), [
            'phone_number' => 'required|regex:/^\+[1-9]\d{1,14}$/' // E.164 format
        ]);
        
        if ($validator->fails()) {
            return response()->json([
                'success' => false,
                'message' => 'Validation error',
                'errors' => $validator->errors()
            ], 422);
        }
        
        $phoneNumber = $request->phone_number;
        
        // Generate a random 6-digit OTP
        $otpCode = str_pad(rand(0, 999999), 6, '0', STR_PAD_LEFT);
        
        // Set expiry time (5 minutes from now)
        $expiryTime = Carbon::now()->addMinutes(5);
        
        // Store OTP in database
        $otp = Otp::updateOrCreate(
            ['phone_number' => $phoneNumber],
            [
                'otp_code' => $otpCode,
                'expires_at' => $expiryTime,
                'verified' => false,
                'attempts' => 0,
                'reference_id' => Str::uuid()->toString()
            ]
        );
        
        // Send OTP via WhatsApp
        $message = "$otpCode";
        
        try {
            $whatsAppResponse = $this->whatsAppService->sendOtpTemplate($phoneNumber, $message);
            
            if (!$whatsAppResponse['success']) {
                \Log::error('WhatsApp API Error', [
                    'phone' => $phoneNumber,
                    'error' => $whatsAppResponse['error'] ?? 'Unknown error'
                ]);
                
                return response()->json([
                    'success' => false,
                    'message' => 'Failed to send OTP via WhatsApp',
                    'error_details' => $whatsAppResponse['error'] ?? 'Unknown error'
                ], 500);
            }
            
            return response()->json([
                'success' => true,
                'message' => 'OTP sent successfully',
                'data' => [
                    'reference_id' => $otp->reference_id,
                    'expires_at' => $expiryTime
                ]
            ]);
        } catch (\Exception $e) {
            \Log::error('WhatsApp Exception', [
                'phone' => $phoneNumber,
                'exception' => $e->getMessage(),
                'trace' => $e->getTraceAsString()
            ]);
            
            return response()->json([
                'success' => false,
                'message' => 'Failed to send OTP via WhatsApp',
                'error_details' => $e->getMessage()
            ], 500);
        }
    }
    
    /**
     * Verify the OTP submitted by user
     */
    public function verifyOtp(Request $request)
    {
        // Validate request
        $validator = Validator::make($request->all(), [
            'reference_id' => 'required|string',
            'otp_code' => 'required|string|size:6',
        ]);
        
        if ($validator->fails()) {
            return response()->json([
                'success' => false,
                'message' => 'Validation error',
                'errors' => $validator->errors()
            ], 422);
        }
        
        $referenceId = $request->reference_id;
        $otpCode = $request->otp_code;
        
        // Find OTP record
        $otp = Otp::where('reference_id', $referenceId)->first();
        
        if (!$otp) {
            return response()->json([
                'success' => false,
                'message' => 'Invalid reference ID'
            ], 404);
        }
        
        // Check if OTP is already verified
        if ($otp->verified) {
            return response()->json([
                'success' => false,
                'message' => 'OTP already verified'
            ], 400);
        }
        
        // Check if OTP is expired
        if (Carbon::now()->gt($otp->expires_at)) {
            return response()->json([
                'success' => false,
                'message' => 'OTP has expired'
            ], 400);
        }
        
        // Increment attempts
        $otp->increment('attempts');
        
        // Check max attempts (3)
        if ($otp->attempts > 3) {
            return response()->json([
                'success' => false,
                'message' => 'Max verification attempts exceeded'
            ], 400);
        }
        
        // Verify OTP code
        if ($otp->otp_code !== $otpCode) {
            return response()->json([
                'success' => false,
                'message' => 'Invalid OTP code',
                'attempts_left' => 3 - $otp->attempts
            ], 400);
        }
        
        // Mark OTP as verified
        $otp->update([
            'verified' => true,
            'verified_at' => Carbon::now()
        ]);
    
        // Extract mobile from OTP record and remove +91 if present
        $mobile = $otp->phone_number ?? '';
    
        if (Str::startsWith($mobile, '+91')) {
            $mobile = substr($mobile, 3);
        }
    
        $mobile = ltrim($mobile); // remove any extra spaces
    
        if (!$mobile) {
            return response()->json([
                'success' => false,
                'message' => 'Phone number not found in OTP record.'
            ], 400);
        }
    
        // Check user DB with formatted mobile
        $user = User::where('contact_no', $mobile)->first();
        Session::forget('mobile_number');
       
    
        if (!$user) {
            Session::put('mobile_number', $mobile);
            return response()->json([
                'success' => true,
                'message' => 'No account found with this mobile number',
                'redirect' => route('register.User')
            ]);
        }
    
        // If user exists, log them in
        Auth::login($user, $request->has('remember'));

    
        return response()->json([
            'success' => true,
            'message' => 'OTP verified and login successful',
            'redirect' => route('home')
        ]);
    }
    
    /**
     * Resend OTP to WhatsApp number
     */
    public function resendOtp(Request $request)
    {
        // Validate request
        $validator = Validator::make($request->all(), [
            'reference_id' => 'required|string'
        ]);
        
        if ($validator->fails()) {
            return response()->json([
                'success' => false,
                'message' => 'Validation error',
                'errors' => $validator->errors()
            ], 422);
        }
        
        $referenceId = $request->reference_id;
        
        // Find OTP record
        $otp = Otp::where('reference_id', $referenceId)->first();
        
        if (!$otp) {
            return response()->json([
                'success' => false,
                'message' => 'Invalid reference ID'
            ], 404);
        }
        
        // Check if OTP is already verified
        if ($otp->verified) {
            return response()->json([
                'success' => false,
                'message' => 'Phone number already verified'
            ], 400);
        }
        
        // Generate a new OTP code
        $newOtpCode = str_pad(rand(0, 999999), 6, '0', STR_PAD_LEFT);
        
        // Set new expiry time (5 minutes from now)
        $expiryTime = Carbon::now()->addMinutes(5);
        
        // Update OTP record
        $otp->update([
            'otp_code' => $newOtpCode,
            'expires_at' => $expiryTime,
            'attempts' => 0
        ]);
        
        // Send new OTP via WhatsApp
        $message = "Your new verification code is: $newOtpCode. Valid for 5 minutes.";
        $sent = $this->whatsAppService->sendMessage($otp->phone_number, $message);
        
        if (!$sent) {
            return response()->json([
                'success' => false,
                'message' => 'Failed to resend OTP via WhatsApp'
            ], 500);
        }
        
        return response()->json([
            'success' => true,
            'message' => 'OTP resent successfully',
            'data' => [
                'reference_id' => $otp->reference_id,
                'expires_at' => $expiryTime
            ]
        ]);
    }
}