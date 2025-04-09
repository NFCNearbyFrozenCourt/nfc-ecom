<?php
use Illuminate\Support\Facades\Route;
use Modules\EcomAuth\Http\Controllers\MobileAuthController;
use Modules\EcomAuth\Http\Controllers\FirebaseSettingsController;
use Modules\EcomAuth\Http\Controllers\CartController;
use Modules\EcomAuth\Http\Controllers\ContactController;
use Illuminate\Support\Facades\Session;
use Illuminate\Support\Facades\Auth;
use Modules\EcomAuth\Http\Controllers\DeliveryLocationController;
use Modules\EcomAuth\Http\Controllers\EcomSettings;
use Modules\EcomAuth\Http\Controllers\WhatsAppController;
use Modules\EcomAuth\Http\Controllers\OtpController;


Route::group(['middleware' => 'web'], function () {
    Route::get('login/mobile', [MobileAuthController::class, 'showMobileLoginForm'])->name('login.mobile.form');
    Route::post('login/mobile/send-otp', [MobileAuthController::class, 'sendOTP'])->name('login.mobile.send-otp');
    Route::post('login/mobile/verify-otp', [MobileAuthController::class, 'verifyOTP'])->name('login.mobile.verify-otp');
    Route::post('login/mobile', [MobileAuthController::class, 'login'])->name('login.mobile');
});

Route::get('/User/register', function () {
    $mobile = Session::get('mobile_number', ''); // Retrieve from session
    return view('ecomauth::auth.registerUser', compact('mobile')); 
})->name('register.User'); // Set the route name here

Route::post('/register-user', [MobileAuthController::class, 'registerUser'])->name('registerUser');


Route::middleware(['setData', 'auth', 'SetSessionData', 'language', 'timezone', 'AdminSidebarMenu', 'CheckUserLogin'])->group(function () {
Route::get('/firebase/settings', [FirebaseSettingsController::class, 'index'])->name('firebase.settings');

Route::get('/', function () { 
    return view('ecomauth::website.index'); 
})->name('EcomAuth.Website.index');


Route::get('/blank-screen', function () {
    return view('EcomLayouts.blank_screen');
});


Route::get('/ecommerce-settings',[EcomSettings::class, 'index'])->name('ecommerce-settings');
Route::post('/update-ecom-settings', [EcomSettings::class, 'updateSettings'])->name('update.ecom.settings');
Route::get('/delivery-locations',[DeliveryLocationController::class, 'index'])->name('delivery-locations.index');
Route::post('/delivery-locations', [DeliveryLocationController::class, 'store'])->name('delivery-locations.store');
// Delivery Locations routes
Route::put('/delivery-locations/{id}', [DeliveryLocationController::class, 'update'])->name('delivery-locations.update');
Route::delete('/delivery-locations/{id}', [DeliveryLocationController::class, 'destroy'])->name('delivery-locations.destroy');

Route::get('/whatsapp', [WhatsAppController::class, 'show'])->name('whatsapp.show');
Route::post('/whatsapp/update', [WhatsAppController::class, 'update'])->name('whatsapp.update');


});

Route::post('/firebase/update', [FirebaseSettingsController::class, 'update'])->name('firebase.settings.update');

Route::get('/account', function () { 
    $user = Auth::user(); // Get logged-in user details

    // If user_type is 'user_customer', redirect to /home
    if ($user->user_type == 'user') {
        return redirect()->route('home'); // Redirect to home route
    }

    return view('EcomLayouts.account', compact('user')); 
})->name('EcomAuth.Website.account')->middleware('auth');

Route::get('/cart',[CartController::class,'index'])->name('cart.view');
Route::post('/cart/update',[CartController::class,'updateCart'])->name('cart.update');
Route::get('/cart/latest',[CartController::class,'getLatestCart'])->name('cart.latest');

Route::get('/business/address',[CartController::class,'getBusinessAddress'])->name('business.address');

Route::get('/cart/payments',[CartController::class,'getpaymentdetails'])->name('payments');

Route::post('/cart/place-order',[CartController::class,'placeOrder'])->name('placeOrder');

Route::get('/cart/order-success',[CartController::class,'orderSuccess'])->name('orderSuccess');

Route::get('/order-history',[CartController::class,'orderHistory'])->name('orderHistory');

Route::get('/order-details/{id}',[CartController::class,'orderDetails'])->name('orderDetails');

Route::post('/search-products', [CartController::class,'search'])->name('search.products');


Route::get('/get-pincode',[CartController::class,'getPincode'])->name('getPincode');
Route::get('/billing-profile', [ContactController::class, 'show'])->name('billingProfile');
Route::put('/billingProfile/update/{id}', [ContactController::class, 'update'])->name('billingProfileUpdate');

Route::post('/update-shipping-address', [ContactController::class, 'updateShippingAddress']);

Route::prefix('v1')->group(function () {
    Route::post('/otp/generate', [OtpController::class, 'generateOtp'])->name('send.whatsapp.otp');
    Route::post('/otp/verify', [OtpController::class, 'verifyOtp'])->name('verify.whatsapp.otp');
    Route::post('/otp/resend', [OtpController::class, 'resendOtp']);
});
