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
          <h1 class="title-color font-md">Order History</h1>
        </div>
        <div class="avatar-wrap">
          <a href="/">
            <i class="iconly-Home icli"></i>
          </a>
        </div>
      </header>
      <!-- Header End -->

      <!-- Main Start -->
      <div class="main-wrap order-history mb-xxl">
        <!-- Catagories Tabs  Start -->
        <ul class="nav nav-tab nav-pills custom-scroll-hidden">
          <li class="nav-item" role="presentation">
            <button class="nav-link active">Processing</button>
          </li>
          <li class="nav-item" role="presentation">
            <button class="nav-link">Past 30 days</button>
          </li>
          <li class="nav-item" role="presentation">
            <button class="nav-link">November</button>
          </li>
          <li class="nav-item" role="presentation">
            <button class="nav-link">October</button>
          </li>
        </ul>
        <!-- Catagories Tabs  End -->

        <!-- Search Box Start -->
        <div class="search-box">
          <div>
            <input class="form-control" type="search" disabled />
          </div>
          <button class="filter font-md" type="button" data-bs-toggle="offcanvas" data-bs-target="#filter" aria-controls="filter">Filter</button>
        </div>
        <!-- Search Box End -->

        <!-- Tab Content Start -->
        <div class="tab-content ratio2_1 section-p-tb" id="pills-tabContent-sk">
          <!-- Catagories Content Start -->
          <div class="tab-pane fade show active" id="catagories1-sk" role="tabpanel">
            <!-- Order Box Start -->
            <div class="order-box">
              <div class="media">
                <a href="javascript:void(0)" class="content-box">
                  <h2 class="font-sm title-color">ID: #5151515 , Dt: 20 Dec, 2020</h2>
                  <p class="font-xs content-color">8857 Morris Rd. ,Charlottesville</p>
                  <span class="content-color font-xs">Paid: <span class="font-theme">$250.00</span>, Items: <span class="font-theme">15</span></span>
                </a>
                <div class="media-body">
                  <div class="img"></div>
                </div>
              </div>
              <div class="bottom-content">
                <a href="javascript:void(0)" class="title-color font-sm"> Order Again </a>
                <a href="javascript:void(0)" class="give-rating content-color font-sm"> Rate & Review Product</a>
                <div class="rating">
                  <span class="star"></span>
                  <span class="star"></span>
                  <span class="star"></span>
                  <span class="star"></span>
                  <span class="star"></span>
                </div>
              </div>
            </div>
            <!-- Order Box End -->

            <!-- Order Box Start -->
            <div class="order-box">
              <div class="media">
                <a href="javascript:void(0)" class="content-box">
                  <h2 class="font-sm title-color">ID: #5151515 , Dt: 20 Dec, 2020</h2>
                  <p class="font-xs content-color">8857 Morris Rd. ,Charlottesville</p>
                  <span class="content-color font-xs">Paid: <span class="font-theme">$250.00</span>, Items: <span class="font-theme">15</span></span>
                </a>
                <div class="media-body">
                  <div class="img"></div>
                </div>
              </div>
              <div class="bottom-content active">
                <a href="javascript:void(0)" class="title-color font-sm"> Order Again </a>
                <a href="javascript:void(0)" class="give-rating content-color font-sm"> Rate & Review Product</a>
                <div class="rating">
                  <span class="star"></span>
                  <span class="star"></span>
                  <span class="star"></span>
                  <span class="star"></span>
                  <span class="star"></span>
                </div>
              </div>
            </div>
            <!-- Order Box End -->

            <!-- Order Box Start -->
            <div class="order-box">
              <div class="media">
                <a href="javascript:void(0)" class="content-box">
                  <h2 class="font-sm title-color">ID: #5151515 , Dt: 20 Dec, 2020</h2>
                  <p class="font-xs content-color">8857 Morris Rd. ,Charlottesville</p>
                  <span class="content-color font-xs">Paid: <span class="font-theme">$250.00</span>, Items: <span class="font-theme">15</span></span>
                </a>
                <div class="media-body">
                  <div class="img"></div>
                </div>
              </div>
              <div class="bottom-content active">
                <a href="javascript:void(0)" class="title-color font-sm"> Order Again </a>
                <a href="javascript:void(0)" class="give-rating content-color font-sm"> Rate & Review Product</a>
                <div class="rating">
                  <span class="star"></span>
                  <span class="star"></span>
                  <span class="star"></span>
                  <span class="star"></span>
                  <span class="star"></span>
                </div>
              </div>
            </div>
            <!-- Order Box End -->

            <!-- Order Box Start -->
            <div class="order-box">
              <div class="media">
                <a href="javascript:void(0)" class="content-box">
                  <h2 class="font-sm title-color">ID: #5151515 , Dt: 20 Dec, 2020</h2>
                  <p class="font-xs content-color">8857 Morris Rd. ,Charlottesville</p>
                  <span class="content-color font-xs">Paid: <span class="font-theme">$250.00</span>, Items: <span class="font-theme">15</span></span>
                </a>
                <div class="media-body">
                  <div class="img"></div>
                </div>
              </div>
              <div class="bottom-content active">
                <a href="javascript:void(0)" class="title-color font-sm"> Order Again </a>
                <a href="javascript:void(0)" class="give-rating content-color font-sm"> Rate & Review Product</a>
                <div class="rating">
                  <span class="star"></span>
                  <span class="star"></span>
                  <span class="star"></span>
                  <span class="star"></span>
                  <span class="star"></span>
                </div>
              </div>
            </div>
            <!-- Order Box End -->
          </div>
          <!-- Catagories Content End -->
        </div>
        <!-- Tab Content End -->
      </div>
      <!-- Main End -->
    </div>
    <!-- Skeleton loader End -->

    <!-- Header Start -->
    <header class="header">
      <div class="logo-wrap">
        <a href="/account"><i class="iconly-Arrow-Left-Square icli"></i></a>
        <h1 class="title-color font-md">Order History</h1>
      </div>
      <div class="avatar-wrap">
        <a href="/">
          <i class="iconly-Home icli"></i>
        </a>
      </div>
    </header>
    <!-- Header End -->

    <!-- Main Start -->
    <main class="main-wrap order-history mb-xxl">
      <!-- Catagories Tabs  Start -->
      <ul class="nav nav-tab nav-pills custom-scroll-hidden" id="pills-tab" role="tablist">
        <li class="nav-item" role="presentation">
          <button class="nav-link active" id="catagories1-tab" data-bs-toggle="pill" data-bs-target="#catagories1" type="button" role="tab" aria-controls="catagories1" aria-selected="true">
            Pending
          </button>
        </li>

        <li class="nav-item" role="presentation">
          <button class="nav-link" id="catagories2-tab" data-bs-toggle="pill" data-bs-target="#catagories2" type="button" role="tab" aria-controls="catagories2" aria-selected="false">
           Invoiced
          </button>
        </li>

        <li class="nav-item" role="presentation">
          <button class="nav-link" id="catagories3-tab" data-bs-toggle="pill" data-bs-target="#catagories3" type="button" role="tab" aria-controls="catagories3" aria-selected="false">Cancelled</button>
        </li>
      </ul>
      <!-- Catagories Tabs  End -->

      <!-- Search Box Start -->
      {{-- <div class="search-box">
        <div>
          <i class="iconly-Search icli search"></i>
          <input class="form-control" type="search" placeholder="Search here..." />
          <i class="iconly-Voice icli mic"></i>
        </div>
        <button class="filter font-md" type="button" data-bs-toggle="offcanvas" data-bs-target="#filter" aria-controls="filter">Filter</button>
      </div> --}}
      <!-- Search Box End -->

      <!-- Tab Content Start -->
      <section class="tab-content ratio2_1" id="pills-tabContent">
        <!-- Catagories Content Start -->
        <div class="tab-pane fade show active" id="catagories1" role="tabpanel" aria-labelledby="catagories1-tab">
          <!-- Order Box Start -->
          @foreach ($pendingTransactions as $transaction)
    <div class="order-box">
        <div class="media">
            <a href="/order-details/{{ $transaction->id }}" class="content-box">
                <h2 class="font-sm title-color">ID: #{{ $transaction->invoice_no }}, Dt: {{ date('d M, Y', strtotime($transaction->transaction_date)) }}</h2>
                {{-- <p class="font-xs content-color">{{ $transaction->address }}</p> --}}
                <span class="content-color font-xs">Paid: 
                    <span class="font-theme">₹{{ number_format($transaction->final_total, 2) }}</span>, 
                    Items: <span class="font-theme">{{ $transaction->item_count }}</span>
                </span>
            </a>
            <div class="media-body">
                <div class="img"></div>
            </div>
        </div>
    </div>
@endforeach
          <!-- Order Box End -->
        </div>
        <!-- Catagories Content End -->

        <!-- Catagories Content Start -->
        <div class="tab-pane fade" id="catagories2" role="tabpanel" aria-labelledby="catagories2-tab">
          <!-- Order Box Start -->
          @foreach ($completedTransactions as $transaction)
          <div class="order-box">
              <div class="media">
                  <a href="/order-details/{{ $transaction->id }}" class="content-box">
                      <h2 class="font-sm title-color">ID: #{{ $transaction->invoice_no }}, Dt: {{ date('d M, Y', strtotime($transaction->transaction_date)) }}</h2>
                      {{-- <p class="font-xs content-color">{{ $transaction->address }}</p> --}}
                      <span class="content-color font-xs">Paid: 
                          <span class="font-theme">₹{{ number_format($transaction->final_total, 2) }}</span>, 
                          Items: <span class="font-theme">{{ $transaction->item_count }}</span>
                      </span>
                  </a>
                  <div class="media-body">
                      <div class="img"></div>
                  </div>
              </div>
          </div>
      @endforeach
          <!-- Order Box End -->

        </div>
        <!-- Catagories Content End -->

        <!-- Catagories Content Start -->
        <div class="tab-pane fade" id="catagories3" role="tabpanel" aria-labelledby="catagories3-tab">
          <!-- Order Box Start -->
          @foreach ($cancelledTransactions as $transaction)
    <div class="order-box">
        <div class="media">
            <a href="/order-details/{{ $transaction->id }}" class="content-box">
                <h2 class="font-sm title-color">ID: #{{ $transaction->invoice_no }}, Dt: {{ date('d M, Y', strtotime($transaction->transaction_date)) }}</h2>
                {{-- <p class="font-xs content-color">{{ $transaction->address }}</p> --}}
                <span class="content-color font-xs">Paid: 
                    <span class="font-theme">₹{{ number_format($transaction->final_total, 2) }}</span>, 
                    Items: <span class="font-theme">{{ $transaction->item_count }}</span>
                </span>
            </a>
            <div class="media-body">
                <div class="img"></div>
            </div>
        </div>
    </div>
@endforeach
          <!-- Order Box End -->

          <!-- Order Box End -->
        </div>
        <!-- Catagories Content End -->

        <!-- Catagories Content Start -->
        <div class="tab-pane fade" id="catagories4" role="tabpanel" aria-labelledby="catagories4-tab">
          <!-- Order Box Start -->
          <div class="order-box">
            <div class="media">
              <a href="order-detail.html" class="content-box">
                <h2 class="font-sm title-color">ID: #5151515 , Dt: 20 Dec, 2020</h2>
                <p class="font-xs content-color">8857 Morris Rd. ,Charlottesville</p>
                <span class="content-color font-xs">Paid: <span class="font-theme">$250.00</span>, Items: <span class="font-theme">15</span></span>
              </a>
              <div class="media-body">
                <img src="{{ asset('/Ecom/assets/images/map/map.jpg')}}" alt="map" />
              </div>
            </div>
            <div class="bottom-content">
              <a href="address2.html" class="title-color font-sm fw-600"> Order Again </a>
              <a href="javascript:void(0)" class="give-rating content-color font-sm"> Rate & Review Product</a>
              <div class="rating">
                <i data-feather="star"></i>
                <i data-feather="star"></i>
                <i data-feather="star"></i>
                <i data-feather="star"></i>
                <i data-feather="star"></i>
              </div>
            </div>
          </div>
          <!-- Order Box End -->
          <!-- Order Box End -->
        </div>
        <!-- Catagories Content End -->
      </section>
      <!-- Tab Content End -->
    </main>
    <!-- Main End -->

    <!-- Filter Offcanvas Start -->
    <div class="shop-fillter order-history-filter offcanvas offcanvas-bottom" tabindex="-1" id="filter" aria-labelledby="filter">
      <div class="offcanvas-header">
        <h5 class="title-color font-md">Filter</h5>
      </div>
      <div class="offcanvas-body small">
        <div class="pack-size mt-0">
          <div class="row g-3">
            <div class="col-6">
              <div class="size">
                <span class="font-sm">All order</span>
              </div>
            </div>

            <div class="col-6">
              <div class="size">
                <span class="font-sm">Open Order</span>
              </div>
            </div>

            <div class="col-6">
              <div class="size">
                <span class="font-sm">Return Orders</span>
              </div>
            </div>

            <div class="col-6">
              <div class="size">
                <span class="font-sm">Cancelled Order</span>
              </div>
            </div>
          </div>
        </div>

        <div class="pack-size">
          <h5 class="title-color font-md">Time Filter</h5>
          <div class="row g-3">
            <div class="col-6">
              <div class="size">
                <span class="font-sm">Last 30days</span>
              </div>
            </div>

            <div class="col-6">
              <div class="size">
                <span class="font-sm">Last 6 Month</span>
              </div>
            </div>

            <div class="col-6">
              <div class="size">
                <span class="font-sm">2021</span>
              </div>
            </div>

            <div class="col-6">
              <div class="size">
                <span class="font-sm">2020</span>
              </div>
            </div>
          </div>
        </div>
      </div>
      <div class="offcanvas-footer">
        <div class="btn-box">
          <button class="btn-outline font-md" data-bs-dismiss="offcanvas" aria-label="Close">Close</button>
          <button class="btn-solid font-md" data-bs-dismiss="offcanvas" aria-label="Close">Apply</button>
        </div>
      </div>
    </div>
    <!-- Filter Offcanvas End -->

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
