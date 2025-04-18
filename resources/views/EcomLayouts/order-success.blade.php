
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
    <link rel="stylesheet" id="rtl-link" type="text/css" href="{{ asset('/Ecom/assets/css/vendors/bootstrap.css')}}" />

    <!-- Iconly Icon css -->
    <link rel="stylesheet" type="text/css" href="{{ asset('/Ecom/assets/css/iconly.css')}}" />

    <!-- Style css -->
    <link rel="stylesheet" id="change-link" type="text/css" href="{{ asset('/Ecom/assets/css/style.css')}}" />
  </head>
  <!-- Head End -->

  <!-- Body Start -->
  <body>
    <!-- Skeleton loader Start -->
    <div class="skeleton-loader">
      <!-- Header Start -->
      <header class="header">
        <div class="logo-wrap">
          <i class="iconly-Category icli"></i>
          <a href="/"> <img class="logo logo-w" src="{{ asset('/Ecom/assets/images/logo/logo-w.png')}}" alt="logo" /></a
          ><a href="/"> <img class="logo" src="{{ asset('/Ecom/assets/images/logo/logo.png')}}" alt="logo" /></a>
        </div>
        <div class="avatar-wrap">
          <a href="/">
            <i class="iconly-Home icli"></i>
          </a>
        </div>
      </header>
      <!-- Header End -->

      <!-- Main Start -->
      <div class="main-wrap order-success-page mb-xxl">
        <!-- Banner Section Start -->
        <div class="banner-section section-p-tb">
          <div class="banner-wrap">
            <img src="{{ asset('/Ecom/assets/svg/order-success.svg')}}" alt="order-success" />
          </div>
          <div class="content-wrap">
            <div class="heading"></div>
            <p class="font-sm content-color sk-1"></p>
            <p class="font-sm content-color sk-2"></p>
          </div>
        </div>
        <!-- Banner Section End -->

        <!-- Order Id Section Start -->
        <div class="order-id-section section-p-tb">
          <div class="media">
            <span><i class="iconly-Calendar icli"></i></span>
            <div class="media-body">
              <h2 class="font-sm color-title">Order Date</h2>
              <span class="content-color">Sun, 14 Apr, 19:12</span>
            </div>
          </div>
          <div class="media">
            <span><i class="iconly-Document icli"></i></span>
            <div class="media-body">
              <h2 class="font-sm color-title">Order ID</h2>
              <span class="content-color">#548475151</span>
            </div>
          </div>
        </div>
        <!-- Order Id Section End -->

        <!-- Order Detail Start -->
        <div class="order-detail">
          <h3 class="title-2">Order Summary:</h3>
          <!-- Product Detail  Start -->
          <ul>
            <li>
              <span>Bag total</span>
              <span>$220.00</span>
            </li>

            <li>
              <span>Bag savings</span>
              <span class="font-theme">-$20.00</span>
            </li>

            <li>
              <span>Coupon Discount</span>
              <span> <a href="offer.html" class="font-danger">Apply Coupon</a></span>
            </li>

            <li>
              <span>Delivery</span>
              <span>$50.00</span>
            </li>
            <li>
              <span>Total Amount</span>
              <span>$270.00</span>
            </li>
          </ul>
          <!-- Product Detail  End -->
        </div>
        <!-- Order Detail End -->
      </div>
      <!-- Main End -->
    </div>
    <!-- Skeleton loader End -->

    <!-- Header Start -->
    <header class="header">
      <div class="logo-wrap">
        <i class="iconly-Category icli nav-bar"></i>
        <a href="/"> <img class="logo logo-w" src="{{ asset('/Ecom/assets/images/logo/logo-w.png')}}" alt="logo" /></a><a href="/"> <img class="logo" src="{{ asset('/Ecom/assets/images/logo/logo.png')}}" alt="logo" /></a>
      </div>
      <div class="avatar-wrap">
        <a href="/">
          <i class="iconly-Home icli"></i>
        </a>
      </div>
    </header>
    <!-- Header End -->

    <!-- Sidebar Start -->
    <a href="javascript:void(0)" class="overlay-sidebar"></a>
    <aside class="header-sidebar">
      <div class="wrap">
        <div class="user-panel">
          <div class="media">
            <a href="/account"> <img src="{{ asset('/Ecom/assets/images/avatar/avatar.jpg')}}" alt="avatar" /></a>
            <div class="media-body">
              @if(Auth::check())
                  <a href="/account" class="title-color font-sm">
                      {{ Auth::user()->first_name . ' ' . Auth::user()->last_name }}
                      <span class="content-color font-xs">{{ Auth::user()->email }}</span>
                  </a>
              @else
                  <a href="/login" class="title-color font-sm">Guest User</a>
              @endif
          </div>
          </div>
        </div>

        <!-- Navigation Start -->
        <nav class="navigation">
          <ul>
            <li>
              <a href="/" class="nav-link title-color font-sm">
                <i class="iconly-Home icli"></i>
                <span>Home</span>
              </a>
              <a class="arrow" href="/"><i data-feather="chevron-right"></i></a>
            </li>

            {{-- <li>
              <a href="pages-list.html" class="nav-link title-color font-sm">
                <i class="iconly-Paper icli"></i>
                <span>Fastkart Pages list</span>
              </a>
              <a class="arrow" href="pages-list.html"><i data-feather="chevron-right"></i></a>
            </li> --}}

            {{-- <li>
              <a href="category-wide.html" class="nav-link title-color font-sm">
                <i class="iconly-Category icli"></i>
                <span>Shop by Category</span>
              </a>
              <a class="arrow" href="category-wide.html"><i data-feather="chevron-right"></i></a>
            </li> --}}

            <li>
              <a href="/order-history" class="nav-link title-color font-sm">
                <i class="iconly-Document icli"></i>
                <span>Orders</span>
              </a>
              <a class="arrow" href="order-history"><i data-feather="chevron-right"></i></a>
            </li>

            {{-- <li>
              <a href="wishlist.html" class="nav-link title-color font-sm">
                <i class="iconly-Heart icli"></i>
                <span>Your Wishlist</span>
              </a>
              <a class="arrow" href="wishlist.html"><i data-feather="chevron-right"></i></a>
            </li> --}}

            {{-- <li>
              <a href="javascript:void(0)" data-bs-toggle="offcanvas" data-bs-target="#language" aria-controls="language" class="nav-link title-color font-sm">
                <img src="{{ asset('/Ecom/assets/icons/png/flags.png')}}" alt="flag" />
                <span>Langauge</span>
              </a>
              <a class="arrow" href="javascript:void(0)"><i data-feather="chevron-right"></i></a>
            </li> --}}

            <li>
              <a href="/account" class="nav-link title-color font-sm">
                <i class="iconly-Add-User icli"></i>
                <span>Your Account</span>
              </a>
              <a class="arrow" href="/account"><i data-feather="chevron-right"></i></a>
            </li>

            {{-- <li>
              <a href="notification.html" class="nav-link title-color font-sm">
                <i class="iconly-Notification icli"></i>
                <span>Notification</span>
              </a>
              <a class="arrow" href="notification.html"><i data-feather="chevron-right"></i></a>
            </li> --}}

            {{-- <li>
              <a href="setting.html" class="nav-link title-color font-sm">
                <i class="iconly-Setting icli"></i>
                <span>Settings</span>
              </a>
              <a class="arrow" href="setting.html"><i data-feather="chevron-right"></i></a>
            </li> --}}

            <li>
              <a href="javascript:void(0)" class="nav-link title-color font-sm">
                <i class="iconly-Graph icli"></i>
                <span>Dark</span>
              </a>

              <div class="dark-switch">
                <input id="darkButton" type="checkbox" />
                <span></span>
              </div>
            </li>


          </ul>
        </nav>
        <!-- Navigation End -->
      </div>

      <div class="contact-us">
        <span class="title-color">Contact Support</span>
        <p class="content-color font-xs">If you have any problem,queries or questions feel free to reach out</p>
        <a href="javascript:void(0)" class="btn-solid"> Contact Us </a>
      </div>
    </aside>
    <!-- Sidebar End -->

    <!-- Main Start -->
    <main class="main-wrap order-success-page mb-xxl">
      <!-- Banner Section Start -->
      <section class="banner-section">
        <div class="banner-wrap">
          <img src="{{ asset('/Ecom/assets/svg/order-success.svg')}}" alt="order-success" />
        </div>

        <div class="content-wrap">
          <h1 class="font-lg title-color">Thank you for your order!</h1>
          <p class="font-sm content-color">your order has been placed successfully. your order ID is {{ $orderDetails->invoice_no }}</p>
        </div>
      </section>
      <!-- Banner Section End -->

      <!-- Order Id Section Start -->
      <section class="order-id-section">
        <div class="media">
          <span><i class="iconly-Calendar icli"></i></span>
          <div class="media-body">
            <h2 class="font-sm color-title">Order Date</h2>
            <span class="content-color">{{ $orderDetails->transaction_date }}</span>
          </div>
        </div>

        <div class="media">
          <span><i class="iconly-Document icli"></i></span>
          <div class="media-body">
            <h2 class="font-sm color-title">Order ID</h2>
            <span class="content-color">{{ $orderDetails->invoice_no }}</span>
          </div>
        </div>
      </section>
      <!-- Order Id Section End -->

      <!-- Order Detail Start -->
      <section class="order-detail">
        <h3 class="title-2">Order Details</h3>
        <!-- Product Detail  Start -->
        <ul>
          <li>
            <span>Bag total</span>
            <span>₹{{ number_format($orderDetails->total_amount, 2) }}</span>
          </li>

          {{-- <li>
            <span>Bag savings</span>
            <span class="font-theme">-$20.00</span>
          </li>

          <li>
            <span>Coupon Discount</span>
            <a href="offer.html" class="font-danger">Apply Coupon</a>
          </li>

          <li>
            <span>Delivery</span>
            <span>$50.00</span>
          </li> --}}

          <li>
            <span>Total Amount</span>
            <span>₹{{ number_format($orderDetails->total_amount, 2) }}</span>
          </li>
        </ul>
        <!-- Product Detail  End -->
      </section>
      <!-- Order Detail End -->
    </main>
    <!-- Main End -->

    <!-- Footer Start -->
    <footer class="footer-wrap footer-button">
      <a href="/" class="font-md">Home</a>
    </footer>
    <!-- Footer End -->

    <!-- Action Language Start -->
    <div class="action action-language offcanvas offcanvas-bottom" tabindex="-1" id="language" aria-labelledby="language">
      <div class="offcanvas-body small">
        <h2 class="m-b-title1 font-md">Select Language</h2>

        <ul class="list">
          <li>
            <a href="javascript:void(0)" data-bs-dismiss="offcanvas" aria-label="Close"> <img src="{{ asset('/Ecom/assets/icons/flag/us.svg')}}" alt="us" /> English </a>
          </li>

          <li>
            <a href="javascript:void(0)" data-bs-dismiss="offcanvas" aria-label="Close"> <img src="{{ asset('/Ecom/assets/icons/flag/in.svg')}}" alt="us" />Indian </a>
          </li>

          <li>
            <a href="javascript:void(0)" data-bs-dismiss="offcanvas" aria-label="Close"> <img src="{{ asset('/Ecom/assets/icons/flag/it.svg')}}" alt="us" />Italian</a>
          </li>

          <li>
            <a href="javascript:void(0)" data-bs-dismiss="offcanvas" aria-label="Close"> <img src="{{ asset('/Ecom/assets/icons/flag/tf.svg')}}" alt="us" /> French</a>
          </li>

          <li>
            <a href="javascript:void(0)" data-bs-dismiss="offcanvas" aria-label="Close"> <img src="{{ asset('/Ecom/assets/icons/flag/cn.svg')}}" alt="us" /> Chines</a>
          </li>
        </ul>
      </div>
    </div>
    <!-- Action Language End -->

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
  </body>
  <!-- Body End -->
</html>
<!-- Html End -->
