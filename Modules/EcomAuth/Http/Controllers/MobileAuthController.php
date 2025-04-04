<?php

namespace Modules\EcomAuth\Http\Controllers;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Validator;
use Illuminate\Support\Facades\Session;
use App\User;
use Kreait\Firebase\Factory;
use Kreait\Firebase\Exception\FirebaseException;
use App\Contact;
use Illuminate\Support\Facades\Hash;
use App\Utils\Util;
use App\Utils\TransactionUtil;

class MobileAuthController extends Controller
{
    protected $auth;
    protected $database;

    protected $util;

    /**
     * Initialize Firebase services
     */
    public function __construct(Util $util)
    {
        $this->util = $util;
        
        $factory = (new Factory)
            ->withServiceAccount(storage_path('nfc-ecom-firebase-adminsdk-fbsvc-f7eb47f070.json'));
    
        $this->auth = $factory->createAuth();
    }
    /**
     * Display the mobile login form
     *
     * @return \Illuminate\Contracts\View\View
     */
    public function showMobileLoginForm()
    {
        return view('auth.mobile-login');
    }

    /**
     * Prepare for OTP verification
     *
     * @param Request $request
     * @return \Illuminate\Http\JsonResponse
     */
    public function sendOTP(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'mobile' => 'required|regex:/^[0-9]{10,15}$/',
        ]);

        if ($validator->fails()) {
            return response()->json([
                'status' => false,
                'message' => 'Invalid mobile number',
                'errors' => $validator->errors()
            ], 422);
        }

        $mobile = $request->mobile;
        
        // Check if user exists with this mobile number
        // $user = User::where('contact_no', $mobile)->first();
        // if (!$user) {
        //     return response()->json([
        //         'status' => false,
        //         'message' => 'No account found with this mobile number'
        //     ], 404);
        // }

        try {
            // Store the mobile number in session for later verification
            Session::put('mobile_number', $mobile);
            
            // Return a response to the client to initiate client-side Firebase authentication
            return response()->json([
                'status' => true,
                'message' => 'Ready for OTP verification',
                'mobile' => $mobile,
                // You may want to include a token or timestamp for additional security
                'request_id' => uniqid('otp_')
            ]);
            
        } catch (\Exception $e) {
            Log::error('OTP preparation error: ' . $e->getMessage());
            
            return response()->json([
                'status' => false,
                'message' => 'Failed to prepare OTP verification. Please try again.',
                'error' => $e->getMessage()
            ], 500);
        }
    }

    /**
     * Verify Firebase auth token and login user
     *
     * @param Request $request
     * @return \Illuminate\Http\JsonResponse
     */
    public function verifyOTP(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'firebase_token' => 'required|string',
        ]);
    
        if ($validator->fails()) {
            return response()->json([
                'status' => false,
                'message' => 'Invalid token',
                'errors' => $validator->errors()
            ], 422);
        }
    
        $mobile = Session::get('mobile_number');
        
        if (!$mobile) {
            return response()->json([
                'status' => false,
                'message' => 'Verification session expired. Please request a new OTP.'
            ], 400);
        }
    
        try {
            // Verify the Firebase ID token
            $verifiedToken = $this->auth->verifyIdToken($request->firebase_token);
            $uid = $verifiedToken->claims()->get('sub');
            
            // Get the Firebase user
            $firebaseUser = $this->auth->getUser($uid);
            
            // Verify that the phone number matches what we expect
            $userPhoneNumber = $firebaseUser->phoneNumber;
            
            // Format may vary, so ensure proper comparison
            // Use a default country code if needed
            $countryCode = Session::get('country_code', '1'); // Default to US
            $formattedMobile = '+' . $countryCode . $mobile;
            
            if (!$userPhoneNumber || !$this->phoneNumbersMatch($userPhoneNumber, $formattedMobile)) {
                return response()->json([
                    'status' => false,
                    'message' => 'Phone number mismatch'
                ], 401);
            }
            
            // Find the user
            $user = User::where('contact_no', $mobile)->first();
            
            // Clear the session variables
            Session::forget('mobile_number');
            Session::forget('country_code');
    
            if (!$user) {
                Session::put('mobile_number', $mobile);
                return response()->json([
                    'status' => true,
                    'message' => 'No account found with this mobile number',
                    'redirect' => route('register.User')
                ]);
            }
            
            // Login the user if they exist
            Auth::login($user, $request->has('remember'));
            
            return response()->json([
                'status' => true,
                'message' => 'Login successful',
                'redirect' => route('home')
            ]);
            
        } catch (FirebaseException $e) {
            Log::error('Firebase verification error: ' . $e->getMessage());
            
            return response()->json([
                'status' => false,
                'message' => 'Invalid token or verification failed',
                'error' => $e->getMessage()
            ], 401);
        }
    }

    /**
     * Handle a mobile login request to the application (non-AJAX version).
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\RedirectResponse
     */
    public function login(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'mobile' => 'required|regex:/^[0-9]{10,15}$/',
            'firebase_token' => 'required|string',
        ]);

        if ($validator->fails()) {
            return redirect()->back()
                ->withErrors($validator)
                ->withInput($request->only('mobile', 'remember'));
        }

        $mobile = $request->mobile;

        try {
            // Verify the Firebase ID token
            $verifiedToken = $this->auth->verifyIdToken($request->firebase_token);
            $uid = $verifiedToken->claims()->get('sub');
            
            // Get the Firebase user
            $firebaseUser = $this->auth->getUser($uid);
            
            // Find the user
            $user = User::where('contact_no', $mobile)->first();
            
            if (!$user) {
                return redirect()->back()
                    ->withErrors(['mobile' => 'No account found with this mobile number'])
                    ->withInput($request->only('mobile', 'remember'));
            }
            
            // Login the user
            Auth::login($user, $request->has('remember'));
            
            return redirect()->intended(route('home'));
            
        } catch (FirebaseException $e) {
            Log::error('Firebase verification error: ' . $e->getMessage());
            
            return redirect()->back()
                ->withErrors(['firebase_token' => 'Invalid token or verification failed'])
                ->withInput($request->only('mobile', 'remember'));
        }
    }

    /**
     * Helper function to compare phone numbers regardless of formatting
     * 
     * @param string $phone1
     * @param string $phone2
     * @return bool
     */
    private function phoneNumbersMatch($phone1, $phone2)
    {
        // Strip all non-numeric characters
        $clean1 = preg_replace('/[^0-9]/', '', $phone1);
        $clean2 = preg_replace('/[^0-9]/', '', $phone2);
        
        // Compare the last 10 digits (or fewer if shorter)
        $length = min(strlen($clean1), strlen($clean2), 10);
        return substr($clean1, -$length) === substr($clean2, -$length);
    }
    
    public function registerUser(Request $request)
    {
        Log::info('Incoming Request:', $request->all()); // Log request data
    
        // Fix confirm password issue
        $request->merge(['password_confirmation' => $request->confirm_password]);
    
        // Validate the request
        $validator = Validator::make($request->all(), [
            'first_name' => 'required|string|max:255',
            'last_name' => 'required|string|max:255',
            'mobile' => 'required|numeric|digits:10|unique:users,contact_no', // Ensure unique mobile
            'email' => 'required|email|unique:users,email',
            'password' => 'required|min:6|confirmed',
        ]);
    
        if ($validator->fails()) {
            Log::error('Validation Errors:', $validator->errors()->toArray());
    
            if ($request->ajax()) {
                return response()->json(['errors' => $validator->errors()], 422);
            }
    
            return redirect()->back()->withErrors($validator)->withInput();
        }
    
        try {
            // Create the User
            $user = User::create([
                'first_name' => $request->first_name,
                'business_id' => config('app.business_id', env('BUSINESS_ID')),
                'allow_login' => 1,
                'user_type'=>"user_customer",
                'status' => "active",
                'last_name' => $request->last_name,
                'contact_no' => $request->mobile, // Fix contact_no field
                'username' => $request->mobile, // Use mobile as username
                'email' => $request->email,
                'password' => Hash::make($request->password),
            ]);
    
            Log::info('User Created:', $user->toArray());
    
            $ref_count = $this->util->setAndGetReferenceCount('contacts', env('BUSINESS_ID'));
            $contact_id = $this->util->generateReferenceNumber('contacts', $ref_count, env('BUSINESS_ID'));
    
            // Create the Contact linked to this user
            $contact = Contact::create([
                'type' => "customer",
                'contact_id' => $contact_id,
                'business_id' => config('app.business_id', env('BUSINESS_ID')),
                'name' => $request->first_name . " " . $request->last_name,
                'first_name' => $request->first_name,
                'last_name' => $request->last_name,
                'mobile' => $request->mobile,
                'credit_limit' => 0,
                'created_by' => $user->id,
            ]);
    
            Log::info('Contact Created:', $contact->toArray());
    
            if ($request->ajax()) {
                return response()->json(['success' => true, 'message' => 'Registration successful! Please log in.']);
            }
    
            return redirect()->route('login')->with('success', 'Registration successful! Please log in.');
    
        } catch (\Exception $e) {
            Log::error('Error in registration: ' . $e->getMessage());
    
            if ($request->ajax()) {
                return response()->json(['error' => 'Something went wrong! Please try again.'], 500);
            }
    
            return redirect()->back()->with('error', 'Something went wrong! Please try again.')->withInput();
        }
    }

}