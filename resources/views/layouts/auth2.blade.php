<!DOCTYPE html>
<html lang="{{ app()->getLocale() }}">

<head>
    <meta charset="utf-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <!-- Tell the browser to be responsive to screen width -->
    <meta content="width=device-width, initial-scale=1, maximum-scale=1, user-scalable=no" name="viewport">

    <!-- CSRF Token -->
    <meta name="csrf-token" content="{{ csrf_token() }}">

    <title>@yield('title') - {{ config('app.name', 'POS') }}</title>

    @include('layouts.partials.css')

    @include('layouts.partials.extracss_auth')

    <!--[if lt IE 9]>
    <script src="https://oss.maxcdn.com/html5shiv/3.7.3/html5shiv.min.js"></script>
    <script src="https://oss.maxcdn.com/respond/1.4.2/respond.min.js"></script>
    <![endif]-->
    <script src='https://www.google.com/recaptcha/api.js'></script>

</head>

<body class="pace-done" data-new-gr-c-s-check-loaded="14.1172.0" data-gr-ext-installed="" cz-shortcut-listen="true">
    @inject('request', 'Illuminate\Http\Request')
    @if (session('status') && session('status.success'))
        <input type="hidden" id="status_span" data-status="{{ session('status.success') }}"
            data-msg="{{ session('status.msg') }}">
    @endif
    <div class="container-fluid">
        <div class="row eq-height-row">
            <div class="col-md-12 col-sm-12 col-xs-12 right-col tw-pt-20 tw-pb-10 tw-px-5">
                <div class="row">
                    <div class="tw-absolute tw-top-2 md:tw-top-5 tw-left-4 md:tw-left-8 tw-flex tw-items-center tw-gap-4"
                        style="text-align: left">
                        <a href="{{ url('/') }}">
                            <div
                                class="lg:tw-w-16 md:tw-h-16 tw-w-12 tw-h-12 tw-flex tw-items-center tw-justify-center tw-mx-auto tw-overflow-hidden tw-p-0.5 tw-mb-4">
                                <img src="{{ asset('img/logo-small.png')}}" alt="lock" class="tw-object-fill" />
                            </div>
                        </a>
                        @if(config('constants.SHOW_REPAIR_STATUS_LOGIN_SCREEN') && Route::has('repair-status'))
                            <a class="tw-text-white tw-font-medium tw-text-sm md:tw-text-base hover:tw-text-white"
                                href="{{ action([\Modules\Repair\Http\Controllers\CustomerRepairStatusController::class, 'index']) }}">
                                @lang('repair::lang.repair_status')
                            </a>
                        @endif
                        
                        @if(Route::has('member_scanner'))
                            <a class="tw-text-white tw-font-medium tw-text-sm md:tw-text-base hover:tw-text-white"
                                href="{{ action([\Modules\Gym\Http\Controllers\MemberController::class, 'member_scanner']) }}">
                                @lang('gym::lang.gym_member_profile')
                            </a>
                        @endif
                    </div>

                    <!-- User Authentication Section -->
                    <div class="tw-absolute tw-top-5 md:tw-top-8 tw-right-5 md:tw-right-10 tw-flex tw-items-center tw-gap-4">
                        @if(Auth::check() || session('user'))
                            <!-- User is logged in -->
                            <a href="#" class="tw-relative tw-inline-block">
                                <i class="fa fa-shopping-cart tw-text-white tw-text-xl"></i>
                            
                                @if(session('cart_count', 0) > 0)
                                    <span class="tw-absolute tw-top-0 tw-right-0 tw-bg-red-500 tw-text-white tw-rounded-full tw-text-xs tw-w-5 tw-h-5 tw-flex tw-items-center tw-justify-center tw-font-bold tw-border-2 tw-border-white">
                                        {{ session('cart_count', 0) }}
                                    </span>
                                @endif
                            </a>

                            <div class="tw-relative" id="user-profile-dropdown">
                                <div class="tw-flex tw-items-center tw-gap-2 tw-cursor-pointer" id="profile-toggle">
                                    <span class="tw-text-white tw-font-medium tw-text-sm md:tw-text-base">
                                    </span>
                                    <i class="fa fa-user-circle tw-text-white tw-text-xl"></i>
                                    <i class="fa fa-angle-down tw-text-white"></i>
                                </div>
                                
                               

                                <!-- Dropdown menu -->
                                <div id="profile-dropdown-menu" class="tw-hidden tw-absolute tw-right-0 tw-mt-2 tw-w-48 tw-bg-white tw-rounded-md tw-shadow-lg tw-z-10">
                                    <div class="tw-p-2">
                                        <a href="#" class="tw-block tw-px-4 tw-py-2 tw-text-gray-800 hover:tw-bg-gray-100 tw-rounded-md">
                                            <i class="fa fa-user tw-mr-2"></i> Profile
                                        </a>
                                        <form method="POST" action="{{ route('logout') }}">
                                            @csrf
                                            <button type="submit" class="tw-block tw-w-full tw-text-left tw-px-4 tw-py-2 tw-text-gray-800 hover:tw-bg-gray-100 tw-rounded-md">
                                                <i class="fa fa-sign-out tw-mr-2"></i> Logout
                                            </button>
                                        </form>
                                    </div>
                                </div>
                            </div>
                            
                            
                        @else
                            <!-- User is not logged in - Display login/register options -->
                            @if (!($request->segment(1) == 'business' && $request->segment(2) == 'register'))
                                @if (config('constants.allow_registration'))
                                    <div class="tw-border-2 tw-border-white tw-rounded-full tw-h-10 md:tw-h-12 tw-w-24 tw-flex tw-items-center tw-justify-center">
                                        <a href="{{ route('business.getRegister')}}@if(!empty(request()->lang)){{'?lang='.request()->lang}}@endif"
                                            class="tw-text-white tw-font-medium tw-text-sm md:tw-text-base hover:tw-text-white">
                                            {{ __('business.register') }}
                                        </a>
                                    </div>
                                    @if (Route::has('pricing') && config('app.env') != 'demo' && $request->segment(1) != 'pricing')
                                        <a class="tw-text-white tw-font-medium tw-text-sm md:tw-text-base hover:tw-text-white"
                                            href="{{ action([\Modules\Superadmin\Http\Controllers\PricingController::class, 'index']) }}">
                                            @lang('superadmin::lang.pricing')
                                        </a>
                                    @endif
                                @endif
                            @endif
                            @if ($request->segment(1) != 'login')
                                <a class="tw-text-white tw-font-medium tw-text-sm md:tw-text-base hover:tw-text-white"
                                    href="{{ action([\App\Http\Controllers\Auth\LoginController::class, 'login'])}}@if(!empty(request()->lang)){{'?lang='.request()->lang}}@endif">
                                    {{ __('business.sign_in') }}
                                </a>
                            @endif
                        @endif
                    </div>

                    <div class="col-md-10 col-xs-8" style="text-align: right;">
                    </div>
                </div>
                @yield('content')
            </div>
        </div>
    </div>

    @include('layouts.partials.javascripts')

    <!-- Scripts -->
    <script src="{{ asset('js/login.js?v=' . $asset_v) }}"></script>

    @yield('javascript')

    <script type="text/javascript">
        $(document).ready(function() {
            $('.select2_register').select2();

            // Profile dropdown toggle functionality
            $('#profile-toggle').click(function(e) {
                e.preventDefault();
                $('#profile-dropdown-menu').toggleClass('tw-hidden');
            });

            // Close dropdown when clicking outside
            $(document).click(function(e) {
                if (!$(e.target).closest('#user-profile-dropdown').length) {
                    $('#profile-dropdown-menu').addClass('tw-hidden');
                }
            });
        });
    </script>
    <style>
        .wizard>.content {
            background-color: white !important;
        }
    </style>
</body>

</html>