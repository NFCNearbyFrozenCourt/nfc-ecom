<!DOCTYPE html>
<!-- Html Start -->
<html lang="en">
  <!-- Head Start -->
  <head>
    <meta http-equiv="Content-Type" content="text/html; charset=UTF-8" />
    <meta http-equiv="X-UA-Compatible" content="IE=edge" />
    <meta name="viewport" content="width=device-width, initial-scale=1.0" />
    <meta name="description" content="Fastkart" />
    <meta name="keywords" content="Fastkart" />
    <meta name="author" content="Fastkart" />
    <link rel="manifest" href="./manifest.json" />
    <title>{{ env('APP_TITLE', 'NFC NearbyFrozenCourt') }}</title>
    <link rel="icon" href="{{ asset('/Ecom/assets/images/favicon.png')}}" type="image/x-icon" />
    <link rel="apple-touch-icon" href="{{ asset('/Ecom/assets/images/favicon.png')}}" />
    <meta name="theme-color" content="#0baf9a" />
    <meta name="apple-mobile-web-app-capable" content="yes" />
    <meta name="apple-mobile-web-app-status-bar-style" content="black" />
    <meta name="apple-mobile-web-app-title" content="Fastkart" />
    <meta name="msapplication-TileImage" content="{{ asset('/Ecom/assets/images/favicon.png')}}" />
    <meta name="msapplication-TileColor" content="#FFFFFF" />
    <meta http-equiv="X-UA-Compatible" content="IE=edge" />

    <!-- Bootstrap 5 -->
    <link
      rel="stylesheet"
      id="rtl-link"
      type="text/css"
      href="{{ asset('/Ecom/assets/css/vendors/bootstrap.css')}}"
    />

    <!-- Iconly Icon css -->
    <link rel="stylesheet" type="text/css" href="{{ asset('/Ecom/assets/css/iconly.css')}}" />

    <!-- Style css -->
    <link
      rel="stylesheet"
      id="change-link"
      type="text/css"
      href="{{ asset('/Ecom/assets/css/style.css')}}"
    />
  </head>
  <!-- Head End -->

  <!-- Body Start -->
  <body>
    <div class="bg-pattern-wrap ratio2_1">
      <!-- Background Image -->
      <div class="bg-patter">
        <img
          src="{{ asset('/Ecom/assets/images/banner/bg-pattern2.png')}}"
          class="bg-img"
          alt="pattern"
        />
      </div>
    </div>

    <!-- Main Start -->
    <main class="main-wrap login-page mb-xxl">
      <img class="logo" src="{{ asset('/Ecom/assets/images/logo/logo.png') }}" alt="logo" />
      <img class="logo logo-w" src="{{ asset('/Ecom/assets/images/logo/logo-w.png') }}" alt="logo" />
      <p class="font-sm content-color">
        Online Supermarket for all your daily needs. You are just One Click away
        from your all needs at your door step.
      </p>

      <!-- Mobile Login Section - NOW PRIMARY -->
      <section class="login-section p-0" id="mobile-login-section">
        <!-- Mobile Login Form Start -->
        <form id="mobile-login-form" class="custom-form">
          {{ csrf_field() }}
          <h1 class="font-md title-color fw-600">Login with Mobile</h1>
          <input type="hidden" id="reference_id" name="reference_id" value="">
          <!-- Mobile Input start -->
          <div class="input-box">
            <input
              type="number"
              id="mobile"
              name="mobile"
              placeholder="Mobile Number"
              required
              class="form-control"
              maxlength="10"
              oninput="this.value=this.value.replace(/\D/g,'').slice(0,10)"
            />
            <i data-feather="smartphone"></i>
          </div>
          <!-- Mobile Input End -->

          <!-- OTP Input start -->
          <div class="input-box otp-field" style="display: none;">
            <input
              type="text"
              id="otp"
              name="otp"
              placeholder="Enter OTP"
              class="form-control"
              maxlength="6"
            />
            <i data-feather="lock"></i>
          </div>
          <!-- OTP Input End -->

          <div class="d-flex justify-content-between align-items-center mb-3">
            <label class="checkbox-box">
              <input type="checkbox" name="remember" />
              <span class="font-sm">Remember Me</span>
            </label>
            <a href="javascript:void(0)" id="email-login-toggle" class="content-color font-sm">
              Login with Email
            </a>
          </div>

          <!-- Conditional Login Buttons -->
          @if($firebase_login == 1)
            <button type="button" id="send-otp-btn" class="btn-solid">Send OTP</button>
          @endif
          
          @if($whatsapp_login == 1)
            <button type="button" id="send-whatsapp-otp-btn" class="btn-solid">Send WhatsApp Verification Code</button>
          @endif
          
          <button type="button" id="verify-otp-btn" style="display: none;" class="btn-solid">Verify & Login</button>
          <div id="resend-container" class="text-center mt-2"></div>
        </form>
        <!-- Mobile Login Form End -->
      </section>
      <!-- Mobile Login Section End -->

      <!-- Email Login Section - NOW SECONDARY (hidden by default) -->
      <section class="login-section p-0" id="email-login-section" style="display:none;">
        <!-- Login Form Start -->
        <form action="{{ route('login') }}" method="POST" class="custom-form">
          {{ csrf_field() }}
          <h1 class="font-md title-color fw-600">Login Account</h1>

          <!-- Email Input start -->
          <div class="input-box">
            <input
              type="text"
              name="username"
              placeholder="Email Address"
              required
              class="form-control"
              value="{{ $username ?? '' }}"
            />
            <i data-feather="at-sign"></i>
          </div>
          <!-- Email Input End -->

          <!-- Password Input start -->
          <div class="input-box">
            <input
              type="password"
              name="password"
              placeholder="Password"
              required
              class="form-control"
              id="password"
            />
            <i class="iconly-Hide icli showHidePassword"></i>
          </div>
          <!-- Password Input End -->
          
          <div class="d-flex justify-content-between align-items-center mb-3">
            <label class="checkbox-box">
              <input type="checkbox" name="remember" />
              <span class="font-sm">Remember Me</span>
            </label>
            <a href="{{ route('password.request') }}" class="content-color font-sm">
              Forgot Password?
            </a>
          </div>
          
          <button type="submit" class="btn-solid">Sign in</button>
          
          <div class="text-center mt-3">
            <a href="javascript:void(0)" id="mobile-login-toggle" class="content-color font-sm">
              Login with Mobile
            </a>
          </div>
          
          <span class="content-color font-sm d-block text-center fw-600 mt-3">
            If you are new,
            <a href="{{ route('business.getRegister') }}" class="underline">Create Now</a>
          </span>
        </form>
        <!-- Login Form End -->
      </section>
      <!-- Email Login Section End -->
    </main>
    <!-- Main End -->

    <!-- jquery 3.6.0 -->
    <script src="{{ asset('/Ecom/assets/js/jquery-3.6.0.min.js')}}"></script>

    <!-- Bootstrap Js -->
    <script src="{{ asset('/Ecom/assets/js/bootstrap.bundle.min.js')}}"></script>

    <!-- Feather Icon -->
    <script src="{{ asset('/Ecom/assets/js/feather.min.js')}}"></script>

    <!-- Theme Setting js -->
    <script src="{{ asset('/Ecom/assets/js/theme-setting.js')}}"></script>

    <!-- Script js -->
    <script src="{{ asset('/Ecom/assets/js/script.js')}}"></script>

    <!-- Firebase App (the core Firebase SDK) -->
    <script src="https://www.gstatic.com/firebasejs/9.6.1/firebase-app-compat.js"></script>
    <!-- Firebase Auth -->
    <script src="https://www.gstatic.com/firebasejs/9.6.1/firebase-auth-compat.js"></script>

    <script type="text/javascript">
      $(document).ready(function() {
        // Initialize Feather icons
        feather.replace();

        // Show/hide password
        $('.showHidePassword').on('click', function() {
          const passwordInput = $('#password');
          const passwordType = passwordInput.attr('type');
          
          if (passwordType === 'password') {
            passwordInput.attr('type', 'text');
            $(this).removeClass('iconly-Hide').addClass('iconly-Show');
          } else {
            passwordInput.attr('type', 'password');
            $(this).removeClass('iconly-Show').addClass('iconly-Hide');
          }
        });

        // Toggle between login methods
        $('#email-login-toggle').on('click', function() {
          $('#email-login-section').show();
          $('#mobile-login-section').hide();
        });
        
        $('#mobile-login-toggle').on('click', function() {
          $('#mobile-login-section').show();
          $('#email-login-section').hide();
        });
        
        // Firebase configuration
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
        
        // Send OTP button click handler
        $('#send-otp-btn').on('click', function() {
          const mobile = $('#mobile').val();
          if (!mobile || mobile.length !== 10) {
            displayError('Please enter a valid 10-digit mobile number');
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
          const formattedPhoneNumber = '+91' + phoneNumber;
          
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
              
              // Hide both send buttons
              @if($firebase_login == 1)
                $('#send-otp-btn').hide();
              @endif
              @if($whatsapp_login == 1)
                $('#send-whatsapp-otp-btn').hide();
              @endif
              
              $('#verify-otp-btn').show();
              $('.alert').remove(); 
              
              // Display success message
              $('<div class="alert alert-success">OTP sent successfully. Please enter the verification code.</div>')
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
        
        // Verify OTP and login
        $('#verify-otp-btn').on('click', function(e) {
          e.preventDefault();
          
          const otp = $('#otp').val();
          const referenceId = $('#reference_id').val();
          
          if (!otp) {
            displayError('Please enter the verification code');
            return;
          }
          
          // Disable button and show loading state
          const originalBtnText = $(this).text();
          $(this).prop('disabled', true).text('Verifying...');
          
          // Clear previous messages
          $('.alert').remove();
          
          // If we have a reference ID, it's a WhatsApp verification
          if (referenceId) {
            verifyWhatsAppOTP(otp, referenceId, originalBtnText);
          } 
          // Otherwise it's a Firebase verification
          else if (window.confirmationResult) {
            verifyFirebaseOTP(otp, originalBtnText);
          } 
          else {
            displayError('Verification process not initialized properly. Please try again.');
            $('#verify-otp-btn').prop('disabled', false).text(originalBtnText);
          }
        });
        
        // Verify Firebase OTP
        function verifyFirebaseOTP(otp, originalBtnText) {
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
        }
        
        // Verify WhatsApp OTP
        function verifyWhatsAppOTP(otp, referenceId, originalBtnText) {
          $.ajax({
            url: "{{ route('verify.whatsapp.otp') }}",
            method: "POST",
            data: {
              _token: '{{ csrf_token() }}',
              otp_code: otp,
              reference_id: referenceId
            },
            success: function(response) {
              $('<div class="alert alert-success">Verification successful! Redirecting...</div>')
                .insertAfter('.otp-field');
              
              if (response.redirect) {
                window.location.href = response.redirect;
              } else {
                window.location.href = '/home';
              }
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
        }
        
        // Send WhatsApp OTP button click handler
        $('#send-whatsapp-otp-btn').on('click', function() {
          let mobile = $('#mobile').val();
          if (!mobile || mobile.length !== 10) {
            displayError('Please enter a valid 10-digit mobile number');
            return;
          }
          
          if (!mobile.startsWith('+91')) {
            mobile = '+91' + mobile;
          }
          
          // Disable button and show loading state
          const originalBtnText = $(this).text();
          $(this).prop('disabled', true).text('Sending...');
          
          // Clear previous messages
          $('.alert').remove();
          
          // AJAX to backend to trigger WhatsApp OTP
          $.ajax({
            url: "{{ route('send.whatsapp.otp') }}",
            method: "POST",
            data: {
              _token: '{{ csrf_token() }}',
              phone_number: mobile
            },
            success: function(response) {
              // Show OTP input field
              $('.otp-field').show();
              
              // Hide both send buttons
              @if($firebase_login == 1)
                $('#send-otp-btn').hide();
              @endif
              @if($whatsapp_login == 1)
                $('#send-whatsapp-otp-btn').hide();
              @endif
              
              $('#verify-otp-btn').show();
              
              // Save reference_id in hidden field
              $('#reference_id').val(response.data.reference_id);
              
              // Display success message
              $('<div class="alert alert-success">WhatsApp verification code sent! Please check your WhatsApp messages.</div>')
                .insertAfter('.otp-field');
              
              // Start countdown timer
              startResendTimer('whatsapp');
            },
            error: function(xhr) {
              let errorMsg = 'Failed to send WhatsApp verification code. Please try again.';
              
              if (xhr.responseJSON && xhr.responseJSON.message) {
                errorMsg = xhr.responseJSON.message;
              }
              
              displayError(errorMsg);
              $('#send-whatsapp-otp-btn').prop('disabled', false).text(originalBtnText);
            }
          });
        });
        
        // Display error message function
        function displayError(message) {
          $('.alert').remove();
          $('<div class="alert alert-danger">' + message + '</div>')
            .insertBefore('#mobile-login-form .otp-field');
        }
        
        // Resend OTP timer function
        function startResendTimer(type = 'firebase') {
          let timerSeconds = 60;
          
          // Clear resend container first
          $('#resend-container').html('');
          
          const $resendBtn = $('<button type="button" id="resend-otp-btn" class="btn-link" disabled>Resend OTP in 60s</button>');
          $('#resend-container').append($resendBtn);
          
          const timerInterval = setInterval(function() {
            timerSeconds--;
            
            if (timerSeconds <= 0) {
              clearInterval(timerInterval);
              $resendBtn.text('Resend OTP').prop('disabled', false);
              
              // Add click handler for resend
              $resendBtn.off('click').on('click', function() {
                // Show the appropriate button based on the type
                if (type === 'whatsapp') {
                  $('#send-whatsapp-otp-btn').prop('disabled', false).text('Send WhatsApp Verification Code').show();
                } else {
                  $('#send-otp-btn').prop('disabled', false).text('Send OTP').show();
                }
                
                $('#verify-otp-btn').hide();
                $('.otp-field').hide();
                $('#reference_id').val(''); // Clear reference ID
                $('#resend-container').html('');
              });
            } else {
              $resendBtn.text('Resend OTP in ' + timerSeconds + 's');
            }
          }, 1000);
        }
      });
    </script>
  </body>
  <!-- Html End -->
</html>