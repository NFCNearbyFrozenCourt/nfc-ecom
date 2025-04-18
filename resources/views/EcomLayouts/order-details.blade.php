
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
          <a href="/"><i class="iconly-Arrow-Left-Square icli"></i></a>
          <h1 class="title-color font-md">Order Summary</h1>
        </div>
        <div class="avatar-wrap">
          <a href="/">
            <i class="iconly-Home icli"></i>
          </a>
        </div>
      </header>
      <!-- Header End -->

      <!-- Main Start -->
      <div class="main-wrap order-detail mb-xxl">
        <!-- Banner Start -->
        <div class="section-p-b">
          <div class="banner-box">
            <div class="media">
              <div class="img"></div>
              <div class="media-body">
                <span class="font-sm">Order ID: #5151515</span>
                <h2>Order Delivered</h2>
              </div>
            </div>
          </div>
        </div>
        <!-- Banner End -->

        <!-- Item Section Start -->
        <div class="item-section p-0">
          <h3 class="font-theme font-md">Items:</h3>

          <div class="item-wrap">
            <!-- Item Start -->
            <div class="media">
              <div class="count">
                <span class="font-sm">2</span>
                <div class="icon"></div>
              </div>
              <div class="media-body">
                <h4 class="title-color font-sm">Assorted Capsicum Combo</h4>
                <span class="content-color font-sm">500g</span>
              </div>
              <span class="title-color font-md">$25.00</span>
            </div>
            <!-- Item End -->

            <!-- Item Start -->
            <div class="media">
              <div class="count">
                <span class="font-sm">2</span>
                <div class="icon"></div>
              </div>
              <div class="media-body">
                <h4 class="title-color font-sm">Assorted Capsicum Combo</h4>
                <span class="content-color font-sm">500g</span>
              </div>
              <span class="title-color font-md">$25.00</span>
            </div>
            <!-- Item End -->

            <!-- Item Start -->
            <div class="media">
              <div class="count">
                <span class="font-sm">2</span>
                <div class="icon"></div>
              </div>
              <div class="media-body">
                <h4 class="title-color font-sm">Assorted Capsicum Combo</h4>
                <span class="content-color font-sm">500g</span>
              </div>
              <span class="title-color font-md">$25.00</span>
            </div>
            <!-- Item End -->
          </div>
        </div>
        <!-- Item Section End -->

        <!-- Order Summary Section Start -->
        <div class="order-summary p-0">
          <h3 class="font-theme font-md">Payment Details</h3>
          <!-- Product Summary Start -->
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
              <span> <a href="offer.html" class="font-danger">Apply Coupon</a> </span>
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
          <!-- Product Summary End -->
        </div>
        <!-- Order Summary Section End -->

        <!-- Address Section Start -->
        <div class="address-section p-0">
          <h3 class="font-theme font-md">Address</h3>

          <div class="address">
            <h4 class="font-sm title-color">Noah Hamilton</h4>
            <p class="font-sm content-color">8857 Morris Rd. ,Charlottesville, VA 22901</p>
          </div>
        </div>
        <!-- Address Section End -->

        <!-- Payment Method Section Start -->
        <section class="payment-method p-0">
          <h3 class="font-theme font-md">Payment Method</h3>

          <div class="payment-box">
            <div class="img"></div>
            <span class="font-sm title-color"> **** **** **** 6502</span>
          </div>
        </section>
        <!-- Payment Method Section End -->
      </div>
      <!-- Main End -->
    </div>
    <!-- Skeleton loader End -->

    <!-- Header Start -->
    <header class="header">
      <div class="logo-wrap">
        <a href="/order-history"><i class="iconly-Arrow-Left-Square icli"></i></a>
        <h1 class="title-color font-md">Order Summary</h1>
      </div>
      <div class="avatar-wrap">
        <a href="/">
          <i class="iconly-Home icli"></i>
        </a>
      </div>
    </header>
    <!-- Header End -->

    <!-- Main Start -->
    <main class="main-wrap order-detail mb-xxl">
      <!-- Banner Start -->
      <section class="pt-0">
        <div class="banner-box">
          <div class="media">
            <img src="{{ asset('/Ecom/assets/icons/svg/box.svg')}}" alt="box" />
            <div class="media-body">
              <span class="font-sm">Order ID: {{$transaction->invoice_no}}</span>
              <h2>
                @if($transaction->status == 'final')
                    Completed
                @elseif($transaction->status == 'ordered')
                    Ordered
                @else
                    {{ ucfirst($transaction->status) }}  {{-- Show the original status with the first letter capitalized --}}
                @endif
            </h2>
            </div>
          </div>
        </div>
      </section>
      <!-- Banner End -->


      <section class="item-section p-0 ">
        <h3 class="font-theme font-md">Invoiced Items:</h3>

        @foreach ($delivered_products as $item)
        <div class="item-wrap">
            <!-- Item Start -->
            <a href="{{ url('/product/' . $item->product_id) }}" class="media">
                <div class="count">
                    <span class="font-sm">{{ number_format($item->quantity, 0) }}</span>
                    <i data-feather="x"></i>
                </div>
                <div class="media-body">
                    <h4 class="title-color font-sm">{{ $item->product_name }}</h4>
                    <span class="content-color font-sm">{{ $item->weight ?? 'N/A' }}</span>
                </div>
                <span class="title-color font-md">₹{{ number_format($item->unit_price_inc_tax, 2) }}</span>
            </a>
        </div>
        @endforeach
      </section>

      <!-- Item Section Start -->
      <section class="item-section p-0 mt-4">
        <h3 class="font-theme font-md">Ordered Items:</h3>

        @foreach ($order_products as $item)
        <div class="item-wrap">
            <!-- Item Start -->
            <a href="{{ url('/product/' . $item->product_id) }}" class="media">
                <div class="count">
                    <span class="font-sm">{{ number_format($item->quantity, 0) }}</span>
                    <i data-feather="x"></i>
                </div>
                <div class="media-body">
                    <h4 class="title-color font-sm">{{ $item->product_name }}</h4>
                    <span class="content-color font-sm">{{ $item->weight ?? 'N/A' }}</span>
                </div>
                <span class="title-color font-md">₹{{ number_format($item->unit_price_inc_tax, 2) }}</span>
            </a>
        </div>
        @endforeach
      </section>

     
      <!-- Item Section End -->

      <!-- Order Summary Section Start -->
      <section class="order-summary p-0">
        <h3 class="font-theme font-md">Payment Details</h3>
        <!-- Product Summary Start -->
        <ul>
            <li>
                <span>Payment Status</span>
                <span>{{$transaction->payment_status}}</span>
            </li>
            <li>
                <span>Paid Amount</span>
                <span>₹{{number_format($paid_amount)}}</span>
            </li>
          <li>
            <span>Bag total</span>
            <span>₹{{ number_format($transaction->final_total, 2) }}</span>
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
            <span>₹{{ number_format($transaction->final_total, 2) }}</span>
          </li>
        </ul>
        <!-- Product Summary End -->
      </section>
      <!-- Order Summary Section End -->

      <!-- Address Section Start -->
      {{-- <section class="address-section p-0">
        <h3 class="font-theme font-md">Address</h3>

        <div class="address">
          <h4 class="font-sm title-color">Noah Hamilton</h4>
          <p class="font-sm content-color">8857 Morris Rd. ,Charlottesville, VA 22901</p>
        </div>
      </section> --}}
      <!-- Address Section End -->

      <!-- Payment Method Section Start -->
      {{-- <section class="payment-method p-0">
        <h3 class="font-theme font-md">Payment Method</h3>

        <div class="payment-box">
          <img src="{{ asset('/Ecom/assets/icons/png/mastercard1.png')}}" alt="card" />
          <span class="font-sm title-color"> **** **** **** 6502</span>
        </div>
      </section> --}}
      <!-- Payment Method Section End -->
    </main>
    <!-- Main End -->

    <!-- Footer Start -->
    <footer class="footer-wrap footer-button">
      <a href="/" class="font-md">Reorder</a>
    </footer>
    <!-- Footer End -->

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
