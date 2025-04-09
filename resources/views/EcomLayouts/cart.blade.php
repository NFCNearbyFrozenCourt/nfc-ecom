@php

    if (!Auth::check()) {
        echo "<script>window.location.href = '" . route('login') . "';</script>";
        exit();
    }
@endphp
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
    <link rel="manifest" href="{{ asset('Ecom/assets/manifest.json') }}" />
   <title>{{ env('APP_TITLE', 'NFC NearbyFrozenCourt') }}</title>
    <link rel="icon" href="{{ asset('/Ecom/assets/images/favicon.png') }}" type="image/x-icon" />
    <link rel="apple-touch-icon" href="{{ asset('/Ecom/assets/images/favicon.png') }}" />
    <meta name="theme-color" content="#0baf9a" />
    <meta name="apple-mobile-web-app-capable" content="yes" />
    <meta name="apple-mobile-web-app-status-bar-style" content="black" />
    <meta name="apple-mobile-web-app-title" content="Fastkart" />
    <meta name="msapplication-TileImage" content="assets/images/favicon.png" />
    <meta name="msapplication-TileColor" content="#FFFFFF" />
    <meta http-equiv="X-UA-Compatible" content="IE=edge" />
    <meta name="csrf-token" content="{{ csrf_token() }}">

    <!-- Bootstrap 5 -->
    <link
      rel="stylesheet"
      id="rtl-link"
      type="text/css"
      href="{{ asset('/Ecom/assets/css/vendors/bootstrap.css ') }}"
    />

    <!-- Iconly Icon css -->
    <link rel="stylesheet" type="text/css" href="{{ asset('/Ecom/assets/css/iconly.css') }}" />

    <!-- Style css -->
    <link
      rel="stylesheet"
      id="change-link"
      type="text/css"
      href="{{ asset('/Ecom/assets/css/style.css') }}"
    />
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
          <h1 class="title-color font-md">
            My Cart <span class="font-sm content-color"></span>
          </h1>
        </div>
        <div class="avatar-wrap">
          <a href="/">
            <i class="iconly-Home icli"></i>
          </a>
        </div>
      </header>
      <!-- Header End -->

      <!--Main Start -->
      <div class="main-wrap cart-page mb-xxl">
        <!-- Cart Item Section Start  -->
        <div class="cart-item-wrap pt-0">
          <div class="swipe-to-show">
            <div class="product-list media">
              <div class="link">
                <div class="img"></div>
              </div>
              <div class="media-body">
                <a href="javascript:void(0)" class="font-sm">
                  Assorted Capsicum Combo
                </a>
                <span class="content-color font-xs">500g</span>
                <span class="title-color font-sm"
                  ><span> $25.00</span>
                  <span class="badges-round font-xs">50% off</span></span
                >
                <div class="plus-minus"></div>
              </div>
            </div>
          </div>

          <div class="swipe-to-show">
            <div class="product-list media">
              <div class="link">
                <div class="img"></div>
              </div>
              <div class="media-body">
                <a href="javascript:void(0)" class="font-sm">
                  Assorted Capsicum Combo
                </a>
                <span class="content-color font-xs">500g</span>
                <span class="title-color font-sm"
                  ><span> $25.00</span>
                  <span class="badges-round font-xs">50% off</span></span
                >
                <div class="plus-minus"></div>
              </div>
            </div>
          </div>

          <div class="cart-item-wrap pt-0">
            <div class="swipe-to-show">
              <div class="product-list media">
                <div class="link">
                  <div class="img"></div>
                </div>
                <div class="media-body">
                  <a href="javascript:void(0)" class="font-sm">
                    Assorted Capsicum Combo
                  </a>
                  <span class="content-color font-xs">500g</span>
                  <span class="title-color font-sm"><span> $25.00</span></span>
                  <div class="plus-minus"></div>
                </div>
              </div>
            </div>

            <div class="swipe-to-show">
              <div class="product-list media">
                <div class="link">
                  <div class="img"></div>
                </div>
                <div class="media-body">
                  <a href="javascript:void(0)" class="font-sm">
                    Assorted Capsicum Combo
                  </a>
                  <span class="content-color font-xs">500g</span>
                  <span class="title-color font-sm"
                    ><span> $25.00</span>
                    <span class="badges-round font-xs">50% off</span></span
                  >
                  <div class="plus-minus"></div>
                </div>
              </div>
            </div>
          </div>
          <!-- Cart Item Section End  -->

          <!-- Coupons Section Start -->
          <div class="pt-0 coupon-ticket-wrap">
            <div class="coupon-ticket">
              <div class="media">
                <div class="off">
                  <span>50</span>
                  <span><span>%</span><span>OFF</span> </span>
                </div>
                <div class="media-body">
                  <h2 class="title-color"></h2>
                  <span class="content-color">on order above $250.00</span>
                </div>
                <div class="big-circle">
                  <span></span>
                </div>
                <div class="code">
                  <span class="content-color">Use Code: </span>
                  <a href="javascript:void(0)">SCD450</a>
                </div>
              </div>
              <div class="circle-5 left">
                <span class="circle-shape"></span>
                <span class="circle-shape"></span>
              </div>
              <div class="circle-5 right">
                <span class="circle-shape"></span>
                <span class="circle-shape"></span>
              </div>
            </div>
          </div>
          <!-- Coupons Section End  -->

          <!-- Order Detail Start -->
          <div class="order-detail section-p-t">
            <h3 class="title-2"></h3>

            <!-- Detail list Start -->
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
                <span>
                  <a href="offer.html" class="font-danger">Apply Coupon</a>
                </span>
              </li>

              <li>
                <span>Delivery</span>
                <span>$50.00</span>
              </li>
              <li>
                <span>Total Amount</span>
                <span >0</span>
              </li>
            </ul>
            <!-- Detail list End -->
          </div>
          <!-- Order Detail End -->
        </div>
        <!--Main End -->
      </div>
    </div>
    <!-- Skeleton loader End -->

    <!-- Header Start -->
    <header class="header">
      <div class="logo-wrap">
        <a href="/"><i class="iconly-Arrow-Left-Square icli"></i></a>
        <h1 class="title-color font-md">
          My Cart <span class="font-sm content-color"></span>
        </h1>
      </div>
      <div class="avatar-wrap">
        <a href="/">
          <i class="iconly-Home icli"></i>
        </a>
      </div>
    </header>
    <!-- Header End -->

    <!-- Main Start -->
    <main class="main-wrap cart-page mb-xxl">
      <!-- Cart Item Section Start  -->
      <div class="cart-item-wrap pt-0">
<div id="cart-item"></div>
      </div>

      <!-- Order Detail Start -->
      <section class="order-detail pt-0">
        <h3 class="title-2">Order Details</h3>

        <!-- Detail list Start -->
        <ul>
          <li>
            <span>Bag total</span>
            <span id='bag_total'>0</span>
          </li>

          <li>
            <span>Total Amount</span>
            <span id='total_amount'>0</span>
          </li>
        </ul>
        <!-- Detail list End -->
      </section>
      <!-- Order Detail End -->
    </main>
    <!-- Main End -->

    <!-- Footer Start -->
    <footer class="footer-wrap footer-button">
      @if(is_null($location))
          
          <a href="{{ url('/business/address') }}" class="font-md">Proceed to Checkout</a>
      @else
          <a href="{{ url('/cart/payments') }}" class="font-md">Proceed to Checkout</a>
      @endif
  </footer>
    <!-- Footer End -->

    <!-- Action confirmation Start -->
    <div
      class="action action-confirmation offcanvas offcanvas-bottom"
      tabindex="-1"
      id="confirmation"
      aria-labelledby="confirmation"
    >
      <div class="offcanvas-body small">
        <div class="confirmation-box">
          <h2>Are You Sure?</h2>
          <p class="font-sm content-color">
            The permission for the use/group, preview is inherited from the
            object, Modifiying it for this object will create a new permission
            for this object
          </p>
          <div class="btn-box">
            <button
              class="btn-outline"
              data-bs-dismiss="offcanvas"
              aria-label="Close"
            >
              Cancel
            </button>
            <button
              class="btn-solid d-block"
              data-bs-dismiss="offcanvas"
              aria-label="Close"
            >
              Remove
            </button>
          </div>
        </div>
      </div>
    </div>
    <!-- Action Confirmation End -->
    <script>

        document.addEventListener("DOMContentLoaded", function () {
            
            fetchCartItems(); // Fetch cart on page load
        });
        
            let csrfToken = document.querySelector('meta[name="csrf-token"]').getAttribute('content');
        
        
        async function fetchCartItems() {
            try {
                const response = await fetch("/cart/latest"); // Replace with your API endpoint
                const data = await response.json();
        
                renderCart(data);
                updateTotals(data);
            } catch (error) {
                console.error("Error fetching cart:", error);
            }
        }
        
        function renderCart(cartItems) {
        let cartContainer = document.getElementById("cart-item");
        cartContainer.innerHTML = "";


        cartItems.cart.forEach(item => {
    console.log(item);
    let itemHTML = `
        <div class="swipe-to-show">
            <div class="product-list media">
                <a href="product.html">
                    <img src="${item.image}"
                         class="img-fluid" 
                         alt="${item.product_name}" />
                </a>
                <div class="media-body">
                    <a href="product.html" class="font-sm">${item.product_name}</a>
                    <span class="content-color font-xs">${item.weight || "N/A"}</span>
                    <span class="title-color font-sm">₹${parseFloat(item.price).toFixed(2)}
                        ${item.discount ? `<span class="badges-round bg-theme-theme font-xs">${item.discount}% off</span>` : ""}
                    </span>
                    <div class="plus-minus">
                        <i class="sub" onclick="updateQuantity(${item.product_id}, '${item.product_name}', -1)" data-feather="minus"></i>
                        <input type="number" value="${item.quantity}" readonly />
                        <i class="add" onclick="updateQuantity(${item.product_id}, '${item.product_name}', +1)" data-feather="plus"></i>
                    </div>
                </div>
            </div>
            <div class="delete-button" data-bs-toggle="offcanvas" data-bs-target="#confirmation" aria-controls="confirmation" onclick="removeItem(${item.id})">
                <i data-feather="trash"></i>
            </div>
        </div>
    `;
    cartContainer.innerHTML += itemHTML;
});
    // Reinitialize Feather icons
    feather.replace();
}
async function updateQuantity(productId, productName, newQuantity) {
    try {

        console.log("Updating Quantity:", productId, productName, newQuantity);

        const updateResponse = await fetch(`/cart/update`, {
            method: "POST",
            headers: {
                "Content-Type": "application/json",
                "X-CSRF-TOKEN": csrfToken
            },
            body: JSON.stringify({
                cart: [{ id: productId, name: productName, quantity: newQuantity }]
            })
        });

        const updatedCart = await updateResponse.json();
        if (!updateResponse.ok) {
            console.error("Error updating cart:", updatedCart);
            return;
        }

        console.log("Cart Updated Successfully:", updatedCart);
        fetchCartItems(); // Refresh the cart

    } catch (error) {
        console.error("Error updating quantity:", error);
    }
}

async function removeItem(productId) {
            try {
                const response = await fetch(`/api/cart/remove/${productId}`, {
                    method: "DELETE",
                });
        
                const updatedCart = await response.json();
                fetchCartItems();
                updateTotals(updatedCart);
            } catch (error) {
                console.error("Error removing item:", error);
            }
        }
        
        function updateTotals(cartItems) {
            let totalAmount = cartItems.cart.reduce((sum, item) => sum + item.price * item.quantity, 0);
            document.getElementById("bag_total").innerText = `₹${totalAmount.toFixed(2)}`;
            document.getElementById("total_amount").innerText = `₹${totalAmount.toFixed(2)}`;
        }

          </script>

    <!-- jquery 3.6.0 -->
    <script src="{{ asset('/Ecom/assets/js/jquery-3.6.0.min.js') }}"></script>

    <!-- Bootstrap Js -->
    <script src="{{ asset('/Ecom/assets/js/bootstrap.bundle.min.js') }}"></script>

    <!-- Feather Icon -->
    <script src="{{ asset('/Ecom/assets/js/feather.min.js') }}"></script>

    <!-- Theme Setting js -->
    <script src="{{ asset('/Ecom/assets/js/theme-setting.js') }}"></script>

    <!-- Swiper Js -->
    <script src="{{ asset('/Ecom/assets/js/jquery-swipe-1.11.3.min.js') }}"></script>
    <script src="{{ asset('/Ecom/assets/js/jquery.mobile-1.4.5.min.js') }}"></script>
    <script src="{{ asset('/Ecom/assets/js/script.js') }}"></script>







  
  </body>
  <!-- Body End -->
</html>
<!-- Html End -->