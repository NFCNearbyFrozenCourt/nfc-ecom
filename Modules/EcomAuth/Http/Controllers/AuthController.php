<?php

namespace Modules\EcomAuth\Http\Controllers;

use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Password;
use Illuminate\Support\Str;
use Illuminate\Routing\Controller;
use Illuminate\Validation\Rules;
use Modules\EcomAuth\Services\FirebaseService;
use Modules\EcomAuth\Http\Requests\LoginRequest;
use Modules\EcomAuth\Http\Requests\RegisterRequest;
use Modules\EcomAuth\Http\Requests\PasswordResetRequest;
use Modules\EcomAuth\Http\Requests\NewPasswordRequest;

class AuthController extends Controller
{
    protected $firebaseService;
    
    /**
     * Create a new controller instance.
     *
     * @param FirebaseService $firebaseService
     * @return void
     */
    public function __construct(FirebaseService $firebaseService)
    {
        $this->firebaseService = $firebaseService;
    }
    
    /**
     * Show the login form.
     *
     * @return \Illuminate\View\View
     */
    public function showLoginForm()
    {
        try {
            return view('ecomauth::auth.login');
        } catch (\Exception $e) {
            Log::error('Failed to load login form: ' . $e->getMessage(), [
                'exception' => $e,
                'trace' => $e->getTraceAsString()
            ]);
            return redirect('/')->with('error', 'Unable to load login page. Please try again later.');
        }
    }
    
    /**
     * Handle a login request.
     *
     * @param LoginRequest $request
     * @return \Illuminate\Http\RedirectResponse
     */
    public function login(LoginRequest $request)
    {
        try {
            $request->authenticate();
            $request->session()->regenerate();
            
            // If Firebase integration is enabled, also sign in with Firebase
            if (config('ecomauth.firebase.use_firebase', true)) {
                try {
                    $firebaseResult = $this->firebaseService->signInWithEmailAndPassword(
                        $request->email,
                        $request->password
                    );
                    
                    if ($firebaseResult['success']) {
                        session(['firebase_token' => $firebaseResult['idToken']]);
                    } else {
                        Log::warning('Firebase authentication failed during login', [
                            'email' => $request->email,
                            'error' => $firebaseResult['error'] ?? 'Unknown error'
                        ]);
                    }
                } catch (\Exception $e) {
                    Log::error('Firebase authentication exception during login', [
                        'email' => $request->email,
                        'exception' => $e->getMessage(),
                        'trace' => $e->getTraceAsString()
                    ]);
                    // Continue with local login even if Firebase fails
                }
            }
            
            Log::info('User logged in successfully', ['user_id' => Auth::id(), 'email' => $request->email]);
            return redirect()->intended(config('ecomauth.auth.login_redirect', '/ecom/dashboard'));
            
        } catch (\Exception $e) {
            Log::error('Login failed with exception', [
                'email' => $request->email,
                'exception' => $e->getMessage(),
                'trace' => $e->getTraceAsString()
            ]);
            return back()->withErrors(['email' => 'Authentication failed. Please try again.']);
        }
    }
    
    /**
     * Show the registration form.
     *
     * @return \Illuminate\View\View
     */
    public function showRegistrationForm()
    {
        try {
            return view('ecomauth::auth.register');
        } catch (\Exception $e) {
            Log::error('Failed to load registration form: ' . $e->getMessage(), [
                'exception' => $e,
                'trace' => $e->getTraceAsString()
            ]);
            return redirect('/')->with('error', 'Unable to load registration page. Please try again later.');
        }
    }
    
    /**
     * Handle a registration request.
     *
     * @param RegisterRequest $request
     * @return \Illuminate\Http\RedirectResponse
     */
    public function register(RegisterRequest $request)
    {
        try {
            $user = User::create([
                'name' => $request->name,
                'email' => $request->email,
                'password' => Hash::make($request->password),
                'phone' => $request->phone,
            ]);
            
            Log::info('User created successfully', ['user_id' => $user->id, 'email' => $user->email]);
            
            // If Firebase integration is enabled, also create a Firebase user
            if (config('ecomauth.firebase.use_firebase', true)) {
                try {
                    $firebaseResult = $this->firebaseService->createUser(
                        $request->email,
                        $request->password,
                        [
                            'name' => $request->name,
                            'phone' => $request->phone,
                            'local_id' => $user->id,
                        ]
                    );
                    
                    if ($firebaseResult['success']) {
                        $user->firebase_uid = $firebaseResult['uid'];
                        $user->save();
                        Log::info('Firebase user created', ['user_id' => $user->id, 'firebase_uid' => $firebaseResult['uid']]);
                    } else {
                        Log::warning('Firebase user creation failed', [
                            'user_id' => $user->id,
                            'email' => $request->email,
                            'error' => $firebaseResult['error'] ?? 'Unknown error'
                        ]);
                    }
                } catch (\Exception $e) {
                    Log::error('Firebase user creation exception', [
                        'user_id' => $user->id,
                        'email' => $request->email,
                        'exception' => $e->getMessage(),
                        'trace' => $e->getTraceAsString()
                    ]);
                    // Continue with local registration even if Firebase fails
                }
            }
            
            Auth::login($user);
            
            return redirect(config('ecomauth.auth.register_redirect', '/ecom/dashboard'));
            
        } catch (\Exception $e) {
            Log::error('Registration failed with exception', [
                'email' => $request->email,
                'exception' => $e->getMessage(),
                'trace' => $e->getTraceAsString()
            ]);
            return back()->withErrors(['email' => 'Registration failed. Please try again.']);
        }
    }
    
    /**
     * Log the user out.
     *
     * @param Request $request
     * @return \Illuminate\Http\RedirectResponse
     */
    public function logout(Request $request)
    {
        try {
            $userId = Auth::id();
            $userEmail = Auth::user() ? Auth::user()->email : null;
            
            Auth::logout();
            
            $request->session()->invalidate();
            $request->session()->regenerateToken();
            
            // Clear any Firebase tokens
            if (session()->has('firebase_token')) {
                session()->forget('firebase_token');
            }
            
            Log::info('User logged out successfully', ['user_id' => $userId, 'email' => $userEmail]);
            return redirect(config('ecomauth.auth.logout_redirect', '/ecom/login'));
            
        } catch (\Exception $e) {
            Log::error('Logout failed with exception', [
                'user_id' => Auth::id(),
                'exception' => $e->getMessage(),
                'trace' => $e->getTraceAsString()
            ]);
            return redirect('/');
        }
    }
    
    /**
     * Show the forgot password form.
     *
     * @return \Illuminate\View\View
     */
    public function showForgotPasswordForm()
    {
        try {
            return view('ecomauth::auth.forgot-password');
        } catch (\Exception $e) {
            Log::error('Failed to load forgot password form: ' . $e->getMessage(), [
                'exception' => $e,
                'trace' => $e->getTraceAsString()
            ]);
            return redirect('/')->with('error', 'Unable to load password reset page. Please try again later.');
        }
    }
    
    /**
     * Send a password reset link.
     *
     * @param PasswordResetRequest $request
     * @return \Illuminate\Http\RedirectResponse
     */
    public function sendResetLink(PasswordResetRequest $request)
    {
        try {
            // Send the password reset link
            $status = Password::sendResetLink(
                $request->only('email')
            );
            
            // If Firebase integration is enabled, also send a Firebase password reset email
            if (config('ecomauth.firebase.use_firebase', true)) {
                try {
                    $firebaseResult = $this->firebaseService->sendPasswordResetEmail($request->email);
                    
                    if (!$firebaseResult['success']) {
                        Log::warning('Firebase password reset email failed', [
                            'email' => $request->email,
                            'error' => $firebaseResult['error'] ?? 'Unknown error'
                        ]);
                    } else {
                        Log::info('Firebase password reset email sent', ['email' => $request->email]);
                    }
                } catch (\Exception $e) {
                    Log::error('Firebase password reset email exception', [
                        'email' => $request->email,
                        'exception' => $e->getMessage(),
                        'trace' => $e->getTraceAsString()
                    ]);
                    // Continue with local password reset even if Firebase fails
                }
            }
            
            if ($status === Password::RESET_LINK_SENT) {
                Log::info('Password reset link sent successfully', ['email' => $request->email]);
                return back()->with(['status' => __($status)]);
            } else {
                Log::warning('Password reset link failed to send', ['email' => $request->email, 'status' => $status]);
                return back()->withErrors(['email' => __($status)]);
            }
            
        } catch (\Exception $e) {
            Log::error('Password reset request failed with exception', [
                'email' => $request->email,
                'exception' => $e->getMessage(),
                'trace' => $e->getTraceAsString()
            ]);
            return back()->withErrors(['email' => 'Failed to send password reset link. Please try again.']);
        }
    }
    
    /**
     * Show the password reset form.
     *
     * @param Request $request
     * @return \Illuminate\View\View
     */
    public function showResetPasswordForm(Request $request)
    {
        try {
            return view('ecomauth::auth.reset-password', ['request' => $request]);
        } catch (\Exception $e) {
            Log::error('Failed to load reset password form: ' . $e->getMessage(), [
                'exception' => $e,
                'token' => $request->token,
                'email' => $request->email,
                'trace' => $e->getTraceAsString()
            ]);
            return redirect('/')->with('error', 'Unable to load password reset page. Please try again later.');
        }
    }
    
    /**
     * Reset the password.
     *
     * @param NewPasswordRequest $request
     * @return \Illuminate\Http\RedirectResponse
     */
    public function resetPassword(NewPasswordRequest $request)
    {
        try {
            // Reset the user's password
            $status = Password::reset(
                $request->only('email', 'password', 'password_confirmation', 'token'),
                function ($user, $password) {
                    try {
                        $user->forceFill([
                            'password' => Hash::make($password)
                        ])->setRememberToken(Str::random(60));
                        
                        $user->save();
                        Log::info('User password reset successfully', ['user_id' => $user->id, 'email' => $user->email]);
                    } catch (\Exception $e) {
                        Log::error('Failed to save user after password reset', [
                            'user_id' => $user->id,
                            'email' => $user->email,
                            'exception' => $e->getMessage(),
                            'trace' => $e->getTraceAsString()
                        ]);
                        throw $e;
                    }
                }
            );
            
            if ($status === Password::PASSWORD_RESET) {
                return redirect()->route('ecom.login')->with('status', __($status));
            } else {
                Log::warning('Password reset failed', ['email' => $request->email, 'status' => $status]);
                return back()->withErrors(['email' => [__($status)]]);
            }
            
        } catch (\Exception $e) {
            Log::error('Password reset failed with exception', [
                'email' => $request->email,
                'exception' => $e->getMessage(),
                'trace' => $e->getTraceAsString()
            ]);
            return back()->withErrors(['email' => ['An error occurred while resetting your password. Please try again.']]);
        }
    }
    
    /**
     * Show the confirm password form.
     *
     * @return \Illuminate\View\View
     */
    public function showConfirmPasswordForm()
    {
        try {
            return view('ecomauth::auth.confirm-password');
        } catch (\Exception $e) {
            Log::error('Failed to load confirm password form: ' . $e->getMessage(), [
                'user_id' => Auth::id(),
                'exception' => $e,
                'trace' => $e->getTraceAsString()
            ]);
            return redirect('/')->with('error', 'Unable to load confirm password page. Please try again later.');
        }
    }
    
    /**
     * Confirm the user's password.
     *
     * @param Request $request
     * @return \Illuminate\Http\RedirectResponse
     */
    public function confirmPassword(Request $request)
    {
        try {
            if (!Hash::check($request->password, $request->user()->password)) {
                Log::warning('Password confirmation failed - incorrect password', ['user_id' => $request->user()->id]);
                return back()->withErrors([
                    'password' => ['The provided password does not match our records.'],
                ]);
            }
            
            $request->session()->put('auth.password_confirmed_at', time());
            Log::info('User confirmed password successfully', ['user_id' => $request->user()->id]);
            
            return redirect()->intended(config('ecomauth.auth.login_redirect', '/ecom/dashboard'));
            
        } catch (\Exception $e) {
            Log::error('Password confirmation failed with exception', [
                'user_id' => $request->user() ? $request->user()->id : null,
                'exception' => $e->getMessage(),
                'trace' => $e->getTraceAsString()
            ]);
            return back()->withErrors(['password' => ['An error occurred while confirming your password. Please try again.']]);
        }
    }
}