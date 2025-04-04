<?php

return [
    'name' => 'EcomAuth',
    
    // Firebase configuration
    'firebase' => [
        'use_firebase' => env('ECOM_AUTH_USE_FIREBASE', true),
    ],
    
    // Authentication settings
    'auth' => [
        'login_redirect' => '/ecom/dashboard',
        'logout_redirect' => '/ecom/login',
        'register_redirect' => '/ecom/dashboard',
        'verify_email' => true,
        'password_reset_expiry' => 60, // minutes
    ],
    
    // Social login enabled platforms
    'social_login' => [
        'google' => env('ECOM_AUTH_ENABLE_GOOGLE', true),
        'facebook' => env('ECOM_AUTH_ENABLE_FACEBOOK', true),
    ],
];
