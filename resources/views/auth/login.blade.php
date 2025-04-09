@extends('layouts.auth2')
@section('title', __('lang_v1.login'))
@inject('request', 'Illuminate\Http\Request')
@section('content')
    @php
        $username = old('username');
        $password = null;
        $mobile = old('mobile');
        if (config('app.env') == 'demo') {
            $username = 'admin';
            $password = '123456';
            $mobile = '9876543210';

            $demo_types = [
                'all_in_one' => 'admin',
                'super_market' => 'admin',
                'pharmacy' => 'admin-pharmacy',
                'electronics' => 'admin-electronics',
                'services' => 'admin-services',
                'restaurant' => 'admin-restaurant',
                'superadmin' => 'superadmin',
                'woocommerce' => 'woocommerce_user',
                'essentials' => 'admin-essentials',
                'manufacturing' => 'manufacturer-demo',
            ];

            if (!empty($_GET['demo_type']) && array_key_exists($_GET['demo_type'], $demo_types)) {
                $username = $demo_types[$_GET['demo_type']];
            }
        }
    @endphp
    <div class="row">
        <div class="col-md-4">
        @if (config('app.env') == 'demo')
            <!-- Demo section remains unchanged -->
            @component('components.widget', [
                'class' => 'box-primary',
                'header' =>
                    '<h4 class="text-center">Demo Shops <small><i> <br/>Demos are for example purpose only, this application <u>can be used in many other similar businesses.</u></i> <br/><b>Click button to login that business</b></small></h4>',
            ])
                <!-- Demo login options content remains unchanged -->
            @endcomponent
        @endif
        </div>
        <div class="col-md-4">
            <div
                class="tw-p-5 md:tw-p-6 tw-mb-4 tw-rounded-2xl tw-transition-all tw-duration-200 tw-bg-white tw-shadow-sm tw-ring-1 tw-ring-gray-200">
                <div class="tw-flex tw-flex-col tw-gap-4 tw-dw-rounded-box tw-dw-p-6 tw-dw-max-w-md">
                    <div class="tw-flex tw-items-center tw-flex-col">
                        <h1 class="tw-text-lg md:tw-text-xl tw-font-semibold tw-text-[#1e1e1e]">
                            @lang('lang_v1.welcome_back')
                        </h1>
                        <h2 class="tw-text-sm tw-font-medium tw-text-gray-500">
                            @lang('lang_v1.login_to_your') {{ config('app.name', 'ultimatePOS') }}
                        </h2>
                    </div>

                    <!-- Mobile Number Login Form - Shown by default -->
                    <form method="POST" action="" id="mobile-login-form" class="login-form">
                        {{ csrf_field() }}
                        <div class="form-group has-feedback {{ $errors->has('mobile') ? ' has-error' : '' }}">
                            <label class="tw-dw-form-control">
                                <div class="tw-dw-label">
                                    <span class="tw-text-xs md:tw-text-sm tw-font-medium tw-text-black">Mobile Number</span>
                                </div>

                                <input
    class="tw-border tw-border-[#D1D5DA] tw-outline-none tw-h-12 tw-bg-transparent tw-rounded-lg tw-px-3 tw-font-medium tw-text-black placeholder:tw-text-gray-500 placeholder:tw-font-medium"
    name="mobile"
    required
    autofocus
    placeholder="Enter your mobile number"
    data-last-active-input=""
    id="mobile"
    type="number"
    value="{{ $mobile }}"
    maxlength="10"
    oninput="validateMobileNumber(this)"
/>

<script>
function validateMobileNumber(input) {
    input.value = input.value.replace(/\D/g, '').slice(0, 10);
}
</script>
                                @if ($errors->has('mobile'))
                                    <span class="help-block">
                                        <strong>{{ $errors->first('mobile') }}</strong>
                                    </span>
                                @endif
                            </label>
                        </div>

                        <div class="form-group has-feedback otp-field" style="display: none;">
                            <label class="tw-dw-form-control">
                                <div class="tw-dw-label">
                                    <span class="tw-text-xs md:tw-text-sm tw-font-medium tw-text-black">OTP</span>
                                </div>

                                <input
                                    class="tw-border tw-border-[#D1D5DA] tw-outline-none tw-h-12 tw-bg-transparent tw-rounded-lg tw-px-3 tw-font-medium tw-text-black placeholder:tw-text-gray-500 placeholder:tw-font-medium"
                                    name="otp" placeholder="Enter OTP"
                                    id="otp" type="text" maxlength="6" />
                            </label>
                        </div>

                        <div class="tw-flex tw-justify-between tw-items-center">
                            <label class="tw-dw-cursor-pointer tw-flex tw-items-center tw-gap-2">
                                <input type="checkbox" name="remember" {{ old('remember') ? 'checked' : '' }}
                                    class="tw-dw-checkbox">
                                <span
                                    class="tw-text-xs md:tw-text-sm tw-font-medium tw-text-black">@lang('lang_v1.remember_me')</span>
                            </label>
                            <button type="button" id="username-login-toggle"
                                class="tw-text-xs md:tw-text-sm tw-font-medium tw-bg-gradient-to-r tw-from-indigo-500 tw-to-blue-500 tw-inline-block tw-text-transparent tw-bg-clip-text hover:tw-text-[#467BF5]">
                                Login with Username
                            </button>
                        </div>
                        
                        <button type="button" id="send-otp-btn"
                            class="tw-bg-gradient-to-r tw-from-indigo-500 tw-to-blue-500 tw-h-12 tw-rounded-xl tw-text-sm md:tw-text-base tw-text-white tw-font-semibold tw-w-full tw-max-w-full mt-4 hover:tw-from-indigo-600 hover:tw-to-blue-600 focus:tw-outline-none focus:tw-ring-2 focus:tw-ring-blue-500 focus:tw-ring-offset-2 active:tw-from-indigo-700 active:tw-to-blue-700">
                            Send OTP
                        </button>
                        
                        <button type="submit" id="verify-otp-btn" style="display: none;"
                            class="tw-bg-gradient-to-r tw-from-indigo-500 tw-to-blue-500 tw-h-12 tw-rounded-xl tw-text-sm md:tw-text-base tw-text-white tw-font-semibold tw-w-full tw-max-w-full mt-4 hover:tw-from-indigo-600 hover:tw-to-blue-600 focus:tw-outline-none focus:tw-ring-2 focus:tw-ring-blue-500 focus:tw-ring-offset-2 active:tw-from-indigo-700 active:tw-to-blue-700">
                            Verify & Login
                        </button>
                    </form>

                    <!-- Username/Password Login Form - Hidden by default -->
                    <form method="POST" action="{{ route('login') }}" id="login-form" class="login-form" style="display: none;">
                        {{ csrf_field() }}
                        <div class="form-group has-feedback {{ $errors->has('username') ? ' has-error' : '' }}">
                            <label class="tw-dw-form-control">
                                <div class="tw-dw-label">
                                    <span
                                        class="tw-text-xs md:tw-text-sm tw-font-medium tw-text-black">@lang('lang_v1.username')</span>
                                </div>

                                <input
                                    class="tw-border tw-border-[#D1D5DA] tw-outline-none tw-h-12 tw-bg-transparent tw-rounded-lg tw-px-3 tw-font-medium tw-text-black placeholder:tw-text-gray-500 placeholder:tw-font-medium"
                                    name="username" required autofocus placeholder="@lang('lang_v1.username')"
                                    data-last-active-input="" id="username" type="text" name="username"
                                    value="{{ $username }}" />
                                @if ($errors->has('username'))
                                    <span class="help-block">
                                        <strong>{{ $errors->first('username') }}</strong>
                                    </span>
                                @endif
                            </label>
                        </div>

                        <div class="form-group has-feedback {{ $errors->has('password') ? ' has-error' : '' }}">
                            <label class="tw-dw-form-control">
                                <div class="tw-dw-label">
                                    <span
                                        class="tw-text-xs md:tw-text-sm tw-font-medium tw-text-black">@lang('lang_v1.password')</span>
                                    @if (config('app.env') != 'demo')
                                        <a href="{{ route('password.request') }}"
                                            class="tw-text-xs md:tw-text-sm tw-font-medium tw-bg-gradient-to-r tw-from-indigo-500 tw-to-blue-500 tw-inline-block tw-text-transparent tw-bg-clip-text hover:tw-text-[#467BF5]"
                                            tabindex="-1">@lang('lang_v1.forgot_your_password')</a>
                                    @endif
                                </div>

                                <input
                                    class="tw-border tw-border-[#D1D5DA] tw-outline-none tw-h-12 tw-bg-transparent tw-rounded-lg tw-px-3 tw-font-medium tw-text-black placeholder:tw-text-gray-500 placeholder:tw-font-medium"
                                    id="password" type="password" name="password" value="{{ $password }}" required
                                    placeholder="@lang('lang_v1.password')" />
                                <button type="button" id="show_hide_icon" class="show_hide_icon"
                                    style="position: absolute; top:48px;right:5px;">
                                    <svg xmlns="http://www.w3.org/2000/svg" class="icon icon-tabler icon-tabler-eye tw-w-6" viewBox="0 0 24 24" stroke-width="1.5" stroke="#000000" fill="none" stroke-linecap="round" stroke-linejoin="round">
                                        <path stroke="none" d="M0 0h24v24H0z" fill="none"/>
                                        <path d="M10 12a2 2 0 1 0 4 0a2 2 0 0 0 -4 0" />
                                        <path d="M21 12c-2.4 4 -5.4 6 -9 6c-3.6 0 -6.6 -2 -9 -6c2.4 -4 5.4 -6 9 -6c3.6 0 6.6 2 9 6" />
                                    </svg>
                                </button>
                            </label>
                            @if ($errors->has('password'))
                                <span class="help-block">
                                    <strong>{{ $errors->first('password') }}</strong>
                                </span>
                            @endif
                        </div>

                        <div class="tw-flex tw-justify-between tw-items-center">
                            <label class="tw-dw-cursor-pointer tw-flex tw-items-center tw-gap-2">
                                <input type="checkbox" name="remember" {{ old('remember') ? 'checked' : '' }}
                                    class="tw-dw-checkbox">
                                <span
                                    class="tw-text-xs md:tw-text-sm tw-font-medium tw-text-black">@lang('lang_v1.remember_me')</span>
                            </label>
                            <button type="button" id="mobile-login-toggle"
                                class="tw-text-xs md:tw-text-sm tw-font-medium tw-bg-gradient-to-r tw-from-indigo-500 tw-to-blue-500 tw-inline-block tw-text-transparent tw-bg-clip-text hover:tw-text-[#467BF5]">
                                Login with Mobile
                            </button>
                        </div>
                        
                        <button type="submit"
                            class="tw-bg-gradient-to-r tw-from-indigo-500 tw-to-blue-500 tw-h-12 tw-rounded-xl tw-text-sm md:tw-text-base tw-text-white tw-font-semibold tw-w-full tw-max-w-full mt-4 hover:tw-from-indigo-600 hover:tw-to-blue-600 focus:tw-outline-none focus:tw-ring-2 focus:tw-ring-blue-500 focus:tw-ring-offset-2 active:tw-from-indigo-700 active:tw-to-blue-700">
                            @lang('lang_v1.login')
                        </button>
                    </form>

                    @if(config('constants.enable_recaptcha'))
                    <div class="row recaptcha-section">
                        <div class="col-md-12">
                            <div class="form-group">
                                <div class="g-recaptcha" data-sitekey="{{ config('constants.google_recaptcha_key') }}"></div>
                                    @if ($errors->has('g-recaptcha-response'))
                                        <span class="text-danger">{{ $errors->first('g-recaptcha-response') }}</span>
                                    @endif
                            </div>  
                        </div>
                    </div>
                    @endif

                    <div class="tw-flex tw-items-center tw-flex-col">
                        <!-- Register Url -->
                        @if (!($request->segment(1) == 'business' && $request->segment(2) == 'register'))
                            <!-- Register Url -->
                            @if (config('constants.allow_registration'))
                                <a href="{{ route('business.getRegister') }}@if (!empty(request()->lang)) {{ '?lang=' . request()->lang }} @endif"
                                    class="tw-text-sm tw-font-medium tw-text-gray-500 hover:tw-text-gray-500 tw-mt-2">{{ __('business.not_yet_registered') }}
                                    <span
                                        class="tw-text-sm tw-font-medium tw-bg-gradient-to-r tw-from-indigo-500 tw-to-blue-500 tw-inline-block tw-text-transparent tw-bg-clip-text hover:tw-text-[#467BF5] hover:tw-underline">{{ __('business.register_now') }}</span></a>
                            @endif
                        @endif
                    </div>
                </div>
            </div>
        </div>
        <div class="col-md-4"></div>
    </div>

@stop
@section('javascript')
    <script type="text/javascript">
  $(document).ready(function() {
    // Toggle between login methods
    $('#username-login-toggle').off('click').on('click', function() {
        $('#login-form').show();
        $('#mobile-login-form').hide();
        $('.recaptcha-section').appendTo('#login-form');
    });
    
    $('#mobile-login-toggle').off('click').on('click', function() {
        $('#mobile-login-form').show();
        $('#login-form').hide();
        $('.recaptcha-section').appendTo('#mobile-login-form');
    });
    
    // Firebase configuration - Add your Firebase config here
    const firebaseConfig = {
        apiKey: "{{ env('MIX_FIREBASE_API_KEY') }}",
        authDomain: "{{ env('MIX_FIREBASE_AUTH_DOMAIN') }}",
        projectId: "{{ env('MIX_FIREBASE_PROJECT_ID') }}",
        storageBucket: "{{ env('MIX_FIREBASE_STORAGE_BUCKET') }}",
        messagingSenderId: "{{ env('MIX_FIREBASE_MESSAGING_SENDER_ID') }}",
        appId: "{{ env('MIX_FIREBASE_APP_ID') }}"
    };

    // Initialize Firebase only once
    if (typeof firebase !== 'undefined' && !firebase.apps.length) {
        firebase.initializeApp(firebaseConfig);
    } else if (typeof firebase === 'undefined') {
        console.error("Firebase SDK not loaded");
    }
    
    // Remove any existing click handlers before attaching new ones
    $('#send-otp-btn').off('click').on('click', function() {
        const mobile = $('#mobile').val();
        if (!mobile) {
            displayError('Please enter your mobile number');
            return;
        }
        
        // Disable button and show loading state
        const originalBtnText = $(this).text();
        $(this).prop('disabled', true).text('Sending...');
        
        // Clear previous messages
        $('.alert').remove();
        
        // First, check if the mobile number exists in the system
        $.ajax({
            url: '/login/mobile/send-otp',
            type: 'POST',
            data: {
                mobile: mobile,
                _token: $('input[name="_token"]').val()
            },
            success: function(response) {
                if (response.status) {
                    // Initialize Firebase Phone Auth
                    initializePhoneAuth(mobile);
                } else {
                    displayError(response.message || 'Failed to send OTP. Please try again.');
                    $('#send-otp-btn').prop('disabled', false).text(originalBtnText);
                }
            },
            error: function(xhr) {
                let errorMsg = 'Failed to send OTP. Please try again.';
                
                if (xhr.responseJSON && xhr.responseJSON.message) {
                    errorMsg = xhr.responseJSON.message;
                }
                
                displayError(errorMsg);
                $('#send-otp-btn').prop('disabled', false).text(originalBtnText);
            }
        });
    });
    
    // Initialize Firebase Phone Authentication
    function initializePhoneAuth(phoneNumber) {
        // Format the phone number with country code
        const formattedPhoneNumber = '+' + (getCountryCode() || '1') + phoneNumber;
        
        // Clear existing recaptcha verifier if it exists
        if (window.recaptchaVerifier) {
            try {
                window.recaptchaVerifier.clear();
            } catch (e) {
                console.error("Error clearing recaptcha:", e);
            }
            window.recaptchaVerifier = null;
        }
        
        // Set up reCAPTCHA verifier
        window.recaptchaVerifier = new firebase.auth.RecaptchaVerifier('send-otp-btn', {
            'size': 'invisible',
            'callback': function(response) {
                // reCAPTCHA solved, allow signInWithPhoneNumber.
                console.log('reCAPTCHA verified');
            }
        });
        
        // Send OTP via Firebase
        firebase.auth().signInWithPhoneNumber(formattedPhoneNumber, window.recaptchaVerifier)
            .then(function(confirmationResult) {
                // SMS sent. Prompt user to type the code.
                window.confirmationResult = confirmationResult;
                
                // Show OTP input field
                $('.otp-field').show();
                $('#send-otp-btn').hide();
                $('#verify-otp-btn').show();
                $('.alert').remove(); 
                
                // Display success message
                $('<div class="alert alert-success">OTP sent successfully to your mobile number. Please enter the verification code.</div>')
                    .insertAfter('.otp-field');
                
                // Start countdown timer
                startResendTimer();
            })
            .catch(function(error) {
                console.error("Firebase Phone Auth Error:", error);
                displayError('Error sending verification code: ' + error.message);
                $('#send-otp-btn').prop('disabled', false).text('Send OTP');
            });
    }
    
    // Helper function to get country code
    function getCountryCode() {
        return '91'; // Default to India (+91)
    }
    
    // Verify OTP and login via Firebase and then backend
    $('#verify-otp-btn').off('click').on('click', function(e) {
        e.preventDefault();
        
        const otp = $('#otp').val();
        if (!otp || otp.length !== 6) {
            displayError('Please enter a valid 6-digit OTP');
            return;
        }
        
        // Disable button and show loading state
        const originalBtnText = $(this).text();
        $(this).prop('disabled', true).text('Verifying...');
        
        // Clear previous messages
        $('.alert').remove();
        
        // Verify OTP with Firebase
        if (window.confirmationResult) {
            window.confirmationResult.confirm(otp)
                .then(function(result) {
                    // User signed in successfully with phone number
                    const user = result.user;
                    
                    // Get the Firebase ID token
                    return user.getIdToken(true);
                })
                .then(function(firebaseToken) {
                    // Send the token to your backend for verification
                    const mobile = $('#mobile').val();
                    $.ajax({
                        url: '/login/mobile/verify-otp',
                        type: 'POST',
                        data: {
                            mobile: mobile,
                            firebase_token: firebaseToken,
                            _token: $('input[name="_token"]').val()
                        },
                        success: function(response) {
                            $('<div class="alert alert-success">Verification successful! Redirecting...</div>')
                                .insertAfter('.otp-field');
                            
                            // Redirect to home or dashboard page
                            window.location.href = response.redirect || '/home';
                        },
                        error: function(xhr) {
                            let errorMsg = 'Invalid OTP or verification failed. Please try again.';
                            
                            if (xhr.responseJSON && xhr.responseJSON.message) {
                                errorMsg = xhr.responseJSON.message;
                            }
                            
                            displayError(errorMsg);
                            $('#verify-otp-btn').prop('disabled', false).text(originalBtnText);
                        }
                    });
                })
                .catch(function(error) {
                    console.error("OTP Verification Error:", error);
                    displayError('Invalid verification code. Please try again.');
                    $('#verify-otp-btn').prop('disabled', false).text(originalBtnText);
                });
        } else {
            displayError('Verification process not initialized properly. Please try again.');
            $('#verify-otp-btn').prop('disabled', false).text(originalBtnText);
        }
    });
    
    // Display error message function
    function displayError(message) {
        $('.alert').remove();
        $('<div class="alert alert-danger">' + message + '</div>')
            .insertBefore('#mobile-login-form .otp-field');
    }
    
    // Resend OTP   timer function
    function startResendTimer() {
        let timerSeconds = 60;
        
        // Remove any existing resend button first
        $('#resend-otp-btn').remove();
        
        const $resendBtn = $('<button type="button" id="resend-otp-btn" class="btn btn-link" disabled>Resend OTP in 60s</button>');
        $resendBtn.insertAfter('#verify-otp-btn');
        
        const timerInterval = setInterval(function() {
            timerSeconds--;
            
            if (timerSeconds <= 0) {
                clearInterval(timerInterval);
                $resendBtn.text('Resend OTP').prop('disabled', false);
                
                // Add click handler for resend
                $resendBtn.off('click').on('click', function() {
                    $('#send-otp-btn').click();
                    $resendBtn.remove();
                });
            } else {
                $resendBtn.text('Resend OTP in ' + timerSeconds + 's');
            }
        }, 1000);
    }
    
    // Show/hide password
    $('#show_hide_icon').off('click').on('click', function(e) {
        e.preventDefault();
        const passwordInput = $('#password');

        if (passwordInput.attr('type') === 'password') {
            passwordInput.attr('type', 'text');
            $('#show_hide_icon').html('<svg xmlns="http://www.w3.org/2000/svg" class="icon icon-tabler icon-tabler-eye-off tw-w-6" viewBox="0 0 24 24" stroke-width="1.5" stroke="#000000" fill="none" stroke-linecap="round" stroke-linejoin="round"><path stroke="none" d="M0 0h24v24H0z" fill="none"/><path d="M10.585 10.587a2 2 0 0 0 2.829 2.828"/><path d="M16.681 16.673a8.717 8.717 0 0 1 -4.681 1.327c-3.6 0 -6.6 -2 -9 -6c1.272 -2.12 2.712 -3.678 4.32 -4.674m2.86 -1.146a9.055 9.055 0 0 1 1.82 -.18c3.6 0 6.6 2 9 6c-.666 1.11 -1.379 2.067 -2.138 2.87"/><path d="M3 3l18 18"/></svg>');
        }
        else if (passwordInput.attr('type') === 'text') {
            passwordInput.attr('type', 'password');
            $('#show_hide_icon').html('<svg xmlns="http://www.w3.org/2000/svg" class="icon icon-tabler icon-tabler-eye tw-w-6" viewBox="0 0 24 24" stroke-width="1.5" stroke="#000000" fill="none" stroke-linecap="round" stroke-linejoin="round"><path stroke="none" d="M0 0h24v24H0z" fill="none"/><path d="M10 12a2 2 0 1 0 4 0a2 2 0 0 0 -4 0"/><path d="M21 12c-2.4 4 -5.4 6 -9 6c-3.6 0 -6.6 -2 -9 -6c2.4 -4 5.4 -6 9 -6c3.6 0 6.6 2 9 6"/></svg>');
        }
    });
});
  </script>
     <!-- Firebase App (the core Firebase SDK) -->
<script src="https://www.gstatic.com/firebasejs/9.6.1/firebase-app-compat.js"></script>
<!-- Firebase Auth -->
<script src="https://www.gstatic.com/firebasejs/9.6.1/firebase-auth-compat.js"></script>
@endsection