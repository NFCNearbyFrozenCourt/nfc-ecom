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
    <meta name="csrf-token" content="{{ csrf_token() }}">

    <!-- Bootstrap 5 -->
    <link rel="stylesheet" id="rtl-link" type="text/css" href="{{ asset('/Ecom/assets/css/vendors/bootstrap.css')}}" />

    <!-- Iconly Icon css -->
    <link rel="stylesheet" type="text/css" href="{{ asset('Ecom/assets/css/iconly.css') }}" />

    <!-- Date Picker css -->
    <link rel="stylesheet" type="text/css" href="{{ asset('Ecom/assets/css/date-picker.css') }}" />
    
    <!-- Style css -->
    <link rel="stylesheet" id="change-link" type="text/css" href="{{ asset('Ecom/assets/css/style.css') }}" />
    <style>
      .footer-wrap {
    display: flex;
    justify-content: center;
    align-items: center;
    padding: 10px;
}

#confirmPaymentBtn {
    width: 100%;
  
    color: white;
    padding: 15px;
    font-size: 16px;
    text-align: center;
    border: none;
    cursor: pointer;
    border-radius: 5px; /* Optional: for rounded corners */
}
.check {
    padding: 2px 8px 4px;
    border-radius: 0px 5px 0px 5px;
    background-color: #0baf9a;
    -webkit-transform: scale(0);
    transform: scale(0);
    position: absolute;
    right: -4px;
    top: -3px;
}

    </style>
  </head>
  <!-- Head End -->

  <!-- Body Start -->
  <body>
    <!-- Header Start -->
    <header class="header">
      <div class="logo-wrap">
        <a href="/"><i class="iconly-Arrow-Left-Square icli"></i></a>
        <h1 class="title-color font-md">Add Payment Method</h1>
      </div>
    </header>
    <!-- Header End -->

    <!-- Main Start -->
    <main class="main-wrap payment-page mb-xxl">


      <!-- Payment Section Start -->
      <section class="payment-section">
        <div id="accordionExample" >
          <div class="accordion-body cash" style=" border-radius: 5px;border:1px solid #f1f1f1; border-color: #0baf9a;">
            <ul class="filter-row">
              <li class="filter-col active" >
                Cash on Delivery<span class="check" style="
                width: 60px;
                height: 30px;
                position: absolute;
                top: 82px;
                right: 16px;
            "><img src="{{ asset('/Ecom/assets/icons/svg/active.svg')}}" alt="active"></span>
              </li>
            </ul>
          </div>
        </div>
        
      </section>
      <!-- Payment Section End -->

      <!-- Order Detail Start -->
      <section class="order-detail">
        <h3 class="title-2">Order Details</h3>
        <!-- Product Detail  Start -->
        <ul>
          <li>
            <span>Bag total</span>
            <span>₹{{ number_format($totalAmount, 2) }}</span>
          </li>
{{-- 
          <li>
            <span>Bag savings</span>
            <span class="font-theme">-$20.00</span>
          </li>

          <li>
            <span>Coupon Discount</span>
            <a href="offer.html" class="font-danger">Apply Coupon</a>
          </li> --}}

          <li>
            <span>Delivery</span>
            <span>₹0.00</span>
          </li>

          <li>
            <span>Total Amount</span>
            <span>₹{{ number_format($totalAmount, 2) }}</span>
          </li>
        </ul>
        <!-- Product Detail  End -->
      </section>
      <!-- Order Detail End -->
    </main>
    <!-- Main End -->

    <!-- Footer Start -->
    <footer class="footer-wrap footer-button">
      <button id="confirmPaymentBtn" class="btn font-md">Confirm Payment</button>
  </footer>
    <!-- Footer End -->

    <script>
        document.getElementById("confirmPaymentBtn").addEventListener("click", function () {
            // Assuming location_id is stored somewhere on the page
            let locationId = localStorage.getItem("selected_address"); 
        
            // Get selected payment method
            let paymentMethod = document.querySelector('input[name="radio1"]:checked')?.id || "default";
        
            // Prepare request data
            let requestData = {
                location_id: locationId,
                payment_method: paymentMethod
            };
        
            // API call to placeOrder
            fetch("/cart/place-order", {
    method: "POST",
    headers: {
        "Content-Type": "application/json",
        "X-CSRF-TOKEN": document.querySelector('meta[name="csrf-token"]').content
    },
    body: JSON.stringify(requestData)
})
.then(response => {
    if (response.redirected) {
        // If Laravel responds with a redirect, navigate to that URL
        window.location.href = response.url;
    } else {
        return response.json();
    }
})
.then(data => {
    if (data && data.message === "Order placed successfully") {
        alert("Order placed successfully!");
        window.location.href = "/cart/order-success";
    } else if (data) {
        alert("Error placing order: " + data.message);
    }
})
.catch(error => {
    console.error("Error:", error);
    alert("Failed to place order. Please try again.");
});
        });
        </script>

    <!-- Add New Card OffCanvas Start -->
    <div class="offcanvas add-card offcanvas-bottom" tabindex="-1" id="add-card" aria-labelledby="add-card">
      <div class="offcanvas-header">
        <h5 class="title-color font-md fw-600">Add Card</h5>
      </div>

      <div class="offcanvas-body small">
        <form class="custom-form">
          <div class="input-box">
            <input type="text" placeholder="Card Holder Name" class="form-control" />
            <input type="number" placeholder="Card Number " class="form-control" />
          </div>

          <div class="row">
            <div class="col-6">
              <div class="input-box mb-0">
                <i class="iconly-Calendar icli"></i>
                <input class="datepicker-here form-control digits expriydate" type="text" data-language="en" data-multiple-dates-separator=", " placeholder="Expiry Date" />
              </div>
            </div>
            <div class="col-6">
              <div class="input-box mb-0">
                <input type="number" placeholder="CV" class="form-control" />
              </div>
            </div>
          </div>
        </form>
      </div>

      <div class="offcanvas-footer">
        <div class="btn-box">
          <button class="btn-outline font-md" data-bs-dismiss="offcanvas" aria-label="Close">Close</button>
          <button class="btn-solid font-md" data-bs-dismiss="offcanvas" aria-label="Close">Add</button>
        </div>
      </div>
    </div>
    <!-- Add New Card OffCanvas Start -->

    <!-- jquery 3.6.0 -->
    <script src="{{ asset('/Ecom/assets/js/jquery-3.6.0.min.js')}}"></script>

    <!-- Bootstrap Js -->
    <script src="{{ asset('/Ecom/assets/js/bootstrap.bundle.min.js')}}"></script>

    <!-- Date Picker  js -->
    <script src="{{ asset('/Ecom/assets/js/date-picker/datepicker.js')}}"></script>
    <script src="{{ asset('/Ecom/assets/js/date-picker/datepicker.en.js')}}"></script>
    <script src="{{ asset('/Ecom/assets/js/date-picker/datepicker.custom.js')}}"></script>

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
