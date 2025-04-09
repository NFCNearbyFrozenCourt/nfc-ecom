
<!DOCTYPE html>
<!-- Html Start -->
<html lang="en">
  <!-- Head Start -->
  <meta http-equiv="Content-Type" content="text/html; charset=UTF-8" />
  <meta http-equiv="X-UA-Compatible" content="IE=edge" />
  <meta name="viewport" content="width=device-width, initial-scale=1.0" />
  <meta name="description" content="Fastkart" />
  <meta name="keywords" content="Fastkart" />
  <meta name="author" content="Fastkart" />
  <link rel="manifest" href="./manifest.json" />
  <title>{{ config('app.name') }}</title>
  <link rel="icon" href="assets/images/favicon.png" type="image/x-icon" />
  <link rel="apple-touch-icon" href="assets/images/favicon.png" />
  <meta name="theme-color" content="#0baf9a" />
  <meta name="apple-mobile-web-app-capable" content="yes" />
  <meta name="apple-mobile-web-app-status-bar-style" content="black" />
  <meta name="apple-mobile-web-app-title" content="Fastkart" />
  <meta name="msapplication-TileImage" content="assets/images/favicon.png" />
  <meta name="msapplication-TileColor" content="#FFFFFF" />
  <meta http-equiv="X-UA-Compatible" content="IE=edge" />

  <link rel="stylesheet" id="rtl-link" type="text/css" href="{{ asset('/Ecom/assets/css/vendors/bootstrap.css')}}" />

  <!-- Iconly Icon css -->
  <link rel="stylesheet" type="text/css" href="{{ asset('/Ecom/assets/css/iconly.css')}}" />

  <!-- Style css -->
  <link rel="stylesheet" id="change-link" type="text/css" href="{{ asset('/Ecom/assets/css/style.css')}}" />
  <!-- Head End -->
  <script>
    var baseUrl = "{{ asset('') }}";
</script>
  <!-- Body Start -->
  <body>
    <!-- Header Start -->
    <header class="header">
      <div class="logo-wrap">
        <a href="/><i class="iconly-Arrow-Left-Square icli"></i></a>
        <h1 class="title-color font-md">Select Business Location</h1>
      </div>
    </header>
    <!-- Header End -->

    <!-- Main Start -->
    <main class="main-wrap address2-page mb-xxl">
      <!-- Address Section Start -->
      <section class="pt-0">
        <div class="address-wrap">
            @foreach($address as $addr)
            <div class="address-box" onclick="selectAddress('{{ $addr['id'] }}')">
                <div class="conten-box">
                    <div class="heading">
                        <i class="iconly-Work icli"></i>
                        <h2 class="title-color font-md">{{ $addr['name'] }}</h2>
                        @if($loop->first)
                            {{-- <span class="badges-round font-white bg-theme-theme font-xs">Default</span> --}}
                        @endif
                    </div>
                    <h3 class="title-color font-sm">{{ $addr['landmark'] }}</h3>
                    <p class="content-color font-sm">
                        {{ $addr['location_id'] }}, {{ $addr['city'] }}, {{ $addr['state'] }}, {{ $addr['country'] }}, {{ $addr['zip_code'] }}
                    </p>
                </div>
                {{-- <img src="assets/images/map/map.jpg" alt="map" /> --}}
            </div>
        @endforeach
        </div>
      </section>
      <!-- Address Section End -->
    </main>
    <!-- Main End -->

    <!-- Footer Start -->
    <footer class="footer-wrap footer-button">
        <a id="proceedToPayment" class="font-md" href="#">Proceed to Payment</a>
    
        <script>
            document.getElementById("proceedToPayment").addEventListener("click", function(event) {
                let selectedAddress = localStorage.getItem("selected_address");
    
                if (!selectedAddress) {
                    alert("Please select a location before proceeding.");
                    event.preventDefault(); // Prevent navigation
                } else {
                    window.location.href = "/cart/payments?selected_address=" + encodeURIComponent(selectedAddress);
                }
            });
        </script>
    </footer>
    <!-- Footer End -->

    @include('EcomLayouts.partials.scripts')

    <script>
        document.addEventListener("DOMContentLoaded", function () {
            // Reset selected address on page load
            localStorage.removeItem("selected_address");
    
            // Update UI after resetting
            updateSelectedAddressUI();
        });
    
        function selectAddress(addressId) {
            // Store the new selected address
            localStorage.setItem("selected_address", addressId);
    
            // Update UI to highlight the selected address
            updateSelectedAddressUI();
        }
    
        function updateSelectedAddressUI() {
            let selectedAddress = localStorage.getItem("selected_address");
            let addressElements = document.querySelectorAll(".address-box");
    
            addressElements.forEach(element => {
                element.style.border = "1px solid #ddd"; // Reset border for all
                if (element.getAttribute("onclick").includes(selectedAddress)) {
                    element.style.border = "2px solid #0baf9a"; // Highlight selected
                }
            });
        }
    </script>
  </body>
  <!-- Body End -->
</html>
<!-- Html End -->
