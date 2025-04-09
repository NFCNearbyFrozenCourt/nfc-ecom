<!DOCTYPE html>

@include('EcomLayouts.partials.header')
@php
    if (Auth::check() && Auth::user()->user_type == 'user') {
        header("Location: " . route('home'));
        exit();
    }
@endphp

<script>
  var isAuthenticated = <?php echo json_encode(Auth::check()); ?>;
  var shippingAddressFound = <?php echo json_encode(isset($shippingAddress) && $shippingAddress); ?>;
</script>
    <!-- Main Start -->
    <main class="main-wrap index-page mb-xxl">
      <!-- Search Box Start -->
      <div class="search-box mb-4">
        <i class="iconly-Search icli search"></i>
        <input class="form-control" type="search" placeholder="Search here..." id="search_query" />
        <i class="iconly-Voice icli mic"></i>
      </div>
      <!-- Say hello to Offers! Start -->
      <section class="offer-section pt-0">
        <div class="offer">
            <div class="top-content">
                <div>
                    <p class="content-color">Best price ever of all the time</p>
                </div>
                {{-- <a href="offer.html" class="font-theme">See all</a> --}}
            </div>
    
            <div class="offer-wrap" id="products">
              @foreach($products as $product)
                  <div class="product-list media">
                      <a href="#">
                          <img src="{{ $product->image ? asset('uploads/img/' . $product->image) : asset('img/default.png') }}" 
                               class="img-fluid" 
                               alt="{{ $product->name }}" />
                      </a>
                      <div class="media-body">
                          <a href="#" class="font-sm"> 
                              {{ $product->name }} 
                          </a>
                          <span class="content-color font-xs">{{ $product->weight }}</span>
                          @php
                           $sellPrice = $product->price_inc_tax ?? $product->regular_price ?? $product->variations->first()->sell_price_inc_tax ;
                            $mrp = $product->mrp ?? null; // Assuming 'mrp' is available in variations
                            $discount = $mrp ? round((($mrp - $sellPrice) / $mrp) * 100) : null;
                        @endphp
                       <span class="title-color font-sm">
                            ₹{{ number_format($sellPrice, 2) }}
                        </span>

                        @if($mrp && $mrp > $sellPrice)
                            <div>
                                <span><del>₹{{ number_format($mrp, 2) }}</del></span>
                                <span class="badges-round bg-theme-theme font-xs">{{ $discount }}% off</span>
                            </div>
                        @endif
                         
                          <div class="product-action">
                            <button class="btn btn-primary add-to-cart" 
                                data-product-id="{{ $product->id }}" 
                                data-productname="{{ $product->name }}" 
                                data-price="{{ $product->price_inc_tax ?? $product->regular_price ?? 0 }}"
                                style="position: absolute; bottom: 15px; right: 30px;">
                                Add
                            </button>
                            <div class="plus-minus d-xs-none" style="display: none;">
                                <i class="sub" data-feather="minus" data-product-id="{{ $product->id }}" data-price="{{ $product->price_inc_tax ?? $product->regular_price ?? $product->variations->first()->sell_price_inc_tax  }}"></i>
                                <input type="number" value="0" />
                                <i class="add" data-feather="plus" data-product-id="{{ $product->id }}" data-price="{{ $product->price_inc_tax ?? $product->regular_price ?? $product->variations->first()->sell_price_inc_tax  }}"></i>
                            </div>
                        </div>
                      </div>
                  </div>
              @endforeach
          </div>
        </div>
    </section>
    </main>
    <!-- Main End -->
    <footer class="footer-wrap shop" style="position: fixed; bottom: 80px; width: 100%; padding: 10px;">
      <ul class="footer">
        <li class="footer-item">
          <span class="font-xs" id="cart-items">0 Items</span>
          <span class="font-sm" id="cart-total">₹0.00</span>
        </li>
        <li class="footer-item">
          <a href="{{ url('/cart') }}" class="font-md">View Cart <i data-feather="chevron-right"></i></a>
        </li>
      </ul>
    </footer>

  @include('EcomLayouts.partials.footer')

    <!-- Shipping Address Offcanvas Start -->
    <div class="offcanvas offcanvas-bottom" tabindex="-1" id="shippingAddressOffcanvas" style="height:50vh">
      <div class="offcanvas-header">
        <h5 class="offcanvas-title">Add Shipping Address</h5>
        <button type="button" class="btn-close text-reset" data-bs-dismiss="offcanvas" aria-label="Close"></button>
      </div>
      <div class="offcanvas-body">
        <div class="location-content text-center">
          <lord-icon src="https://cdn.lordicon.com/oaflahpk.json" trigger="loop" colors="primary:#121331" style="width:80px;height:80px"></lord-icon>
          <h4 class="mt-3">Your Location</h4>
          <p class="font-sm content-color">Choose how you want to set your delivery location</p>
          
          <div class="mt-4">
            <div class="location-options">
              
              <!-- Pick on Map Option -->
              <div class="card mb-3">
                <div class="card-body">
                  <div class="form-floating mb-3">
                    <input type="text" class="form-control" id="address" placeholder="Your Address" readonly>
                    <label for="address">Current Location</label>
                  </div>
                </div>
              </div>
            </div>
            
            <div class="mt-4">
              <button type="button" class="btn btn-primary w-100" id="updateAddressBtn">Update Shipping Address</button>
            </div>
          </div>
        </div>
      </div>
    </div>
    <!-- Shipping Address Offcanvas End -->

    <!-- Map Modal -->
    <div class="modal fade" id="mapModal" tabindex="-1" aria-labelledby="mapModalLabel" aria-hidden="true">
      <div class="modal-dialog modal-fullscreen">
        <div class="modal-content">
          <div class="modal-header">
            <h5 class="modal-title" id="mapModalLabel">Pick Your Location</h5>
            <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
          </div>
          <div class="modal-body p-0">
            <div id="map" style="width: 100%; height: calc(100vh - 130px);"></div>
          </div>
          <div class="modal-footer">
            <button type="button" class="btn btn-secondary" id="getCurrentLocationBtn">Get Current Location</button>
            <button type="button" class="btn btn-outline-primary" data-bs-dismiss="modal">Cancel</button>
            <button type="button" class="btn btn-primary" id="confirmLocationBtn">Confirm Location</button>
          </div>
        </div>
      </div>
    </div>

    <!-- Search -->
    <script src="{{ asset('/Ecom/assets/js/search.js')}}"></script>

    <!--Cart -->
    <script src="{{ asset('/Ecom/assets/js/cart.js')}}"></script>

    <!-- jquery 3.6.0 -->
    <script src="{{ asset('/Ecom/assets/js/jquery-3.6.0.min.js')}}"></script>

    <!-- Bootstrap Js -->
    <script src="{{ asset('/Ecom/assets/js/bootstrap.bundle.min.js')}}"></script>

    <!-- Lord Icon -->
    <script src="{{ asset('/Ecom/assets/js/lord-icon-2.1.0.js')}}"></script>

    <!-- Feather Icon -->
    <script src="{{ asset('/Ecom/assets/js/feather.min.js')}}"></script>

    <!-- Slick Slider js -->
    <script src="{{ asset('/Ecom/assets/js/slick.js')}}"></script>
    <script src="{{ asset('/Ecom/assets/js/slick.min.js')}}"></script>
    <script src="{{ asset('/Ecom/assets/js/slick-custom.js')}}"></script>

    <!-- Theme Setting js -->
    <script src="{{ asset('/Ecom/assets/js/theme-setting.js')}}"></script>

    <!-- Script js -->
    <script src="{{ asset('/Ecom/assets/js/script.js')}}"></script>


<script src="{{ asset('/Ecom/assets/js/location.js')}}"></script>
<script async src="https://maps.googleapis.com/maps/api/js?key={{ config('services.google_maps_api_key') }}&libraries=places&callback=initMap"></script>
      </body>
      </html>
