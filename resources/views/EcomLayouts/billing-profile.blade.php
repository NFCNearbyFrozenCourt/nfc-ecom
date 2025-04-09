<!DOCTYPE html>
<!-- Html Start -->
<html lang="en">
  <!-- Head Start -->
  @include('EcomLayouts.partials.head')
  <!-- Head End -->

  <!-- Body Start -->
  <body>
    <!-- Header Start -->
    <header class="header">
      <div class="logo-wrap">
        <a href="{{ route('home') }}"><i class="iconly-Arrow-Left-Square icli"></i></a>
        <h1 class="title-color font-md">Billing Profile</h1>
      </div>
      <div class="avatar-wrap">
        <a href="{{ route('home') }}">
          <i class="iconly-Home icli"></i>
        </a>
      </div>
    </header>
    <!-- Header End -->

    <!-- Main Start -->
    <main class="main-wrap setting-page mb-xxl">
      <div class="user-panel">
        <div class="media">
          <div class="media-body">
            <h2 class="title-color">{{ $contact->name }}</h2>
            <span class="content-color font-md">{{ $contact->email }}</span>
          </div>
        </div>
      </div>

      <!-- Form Section Start -->
      <form class="custom-form" method="POST" action="{{ route('billingProfileUpdate', $contact->id) }}">
        @csrf
        @method('PUT')
        
        @if(session('success'))
        <div class="alert alert-success">
            {{ session('success') }}
        </div>
    @endif
        
        @if($errors->any())
            <div class="alert alert-danger">
                <ul>
                    @foreach($errors->all() as $error)
                        <li>{{ $error }}</li>
                    @endforeach
                </ul>
            </div>
        @endif
        
        <div class="input-box">
          <i class="iconly-Profile icli"></i>
          <input type="text" name="name" value="{{ old('name', $contact->name) }}" placeholder="Full Name" class="form-control" required />
        </div>

        <div class="input-box">
          <i data-feather="at-sign"></i>
          <input type="email" name="email" value="{{ old('email', $contact->email) }}" placeholder="Email Address" class="form-control" required />
        </div>

        <div class="input-box">
          <i class="iconly-Call icli"></i>
          <input type="text" name="mobile" value="{{ old('mobile', $contact->mobile) }}" placeholder="Mobile Number" class="form-control" required />
        </div>
        
        <div class="input-box">
          <i class="iconly-Work icli"></i>
          <input type="text" name="supplier_business_name" value="{{ old('supplier_business_name', $contact->supplier_business_name) }}" placeholder="Business Name" class="form-control" />
        </div>
        
        <div class="input-box">
          <i class="iconly-Document icli"></i>
          <input type="text" name="tax_number" value="{{ old('tax_number', $contact->tax_number) }}" placeholder="Tax Number" class="form-control" />
        </div>
        
        <div class="input-box">
          <i class="iconly-Location icli"></i>
          <input type="text" name="address_line_1" value="{{ old('address_line_1', $contact->address_line_1) }}" placeholder="Address Line 1" class="form-control" />
        </div>
        
        <div class="input-box">
          <i class="iconly-Location icli"></i>
          <input type="text" name="address_line_2" value="{{ old('address_line_2', $contact->address_line_2) }}" placeholder="Address Line 2" class="form-control" />
        </div>
        
        <div class="input-box">
          <i class="iconly-Location icli"></i>
          <input type="text" name="city" value="{{ old('city', $contact->city) }}" placeholder="City" class="form-control" />
        </div>
        
        <div class="input-box">
          <i class="iconly-Location icli"></i>
          <input type="text" name="state" value="{{ old('state', $contact->state) }}" placeholder="State" class="form-control" />
        </div>
        
        <div class="input-box">
          <i class="iconly-Location icli"></i>
          <input type="text" name="country" value="{{ old('country', $contact->country) }}" placeholder="Country" class="form-control" />
        </div>
        
        <div class="input-box">
          <i class="iconly-Location icli"></i>
          <input type="text" name="zip_code" value="{{ old('zip_code', $contact->zip_code) }}" placeholder="ZIP Code" class="form-control" />
        </div>
        
        <div class="input-box">
          <i class="iconly-Call icli"></i>
          <input type="text" name="landline" value="{{ old('landline', $contact->landline) }}" placeholder="Landline" class="form-control" />
        </div>
        
        <div class="input-box">
          <i class="iconly-Location icli"></i>
          <input type="text" id="shipping_address" name="shipping_address" 
                 class="form-control" placeholder="Shipping Address" 
                 value="{{ old('shipping_address', $contact->shipping_address) }}" 
                 readonly onclick="openFullScreenMap()" />
      </div>
      
      <!-- Hidden fields for latitude and longitude -->
      <input type="hidden" id="latitude" name="latitude" value="">
      <input type="hidden" id="longitude" name="longitude" value="">
      
      <!-- Full-Screen Google Maps Modal -->
      <div id="fullScreenMapModal" style="display: none; position: fixed; top: 0; left: 0; width: 100%; height: 100%; background: rgba(0,0,0,0.9); z-index: 9999;">
          <div style="width: 100%; height: 100%; display: flex; flex-direction: column;">
              
              <!-- Map Container -->
              <div id="map" style="flex-grow: 1; width: 100%;"></div>
      
              <!-- Pick Location Button -->
              <div style="padding: 15px; background: white; text-align: center;">
                  <button type="button" style="background: green; color: white; padding: 15px 30px; border: none; border-radius: 5px; font-size: 16px;" 
                          onclick="selectLocation()">Pick This Location</button>
              </div>
          </div>
      </div>
      
      <!-- Google Maps Script -->
      <script src="https://maps.googleapis.com/maps/api/js?key={{ config('services.google_maps_api_key') }}&libraries=places"></script>
      
      <script>
          let map, marker, selectedLat, selectedLng;
      
          function openFullScreenMap() {
    document.getElementById('fullScreenMapModal').style.display = 'block';

    if (!map) {
        // Initialize map centered at default location
        map = new google.maps.Map(document.getElementById('map'), {
            center: { lat: 12.9716, lng: 77.5946 }, // Default: Bangalore
            zoom: 20,
            mapTypeControl: false,  // 🚀 Hide Map/Satellite buttons
            fullscreenControl: false, // Optional: Hide Fullscreen button
            streetViewControl: false  // Optional: Hide Street View
        });

        marker = new google.maps.Marker({
            position: { lat: 12.9716, lng: 77.5946 },
            map: map,
            draggable: true
        });

        // Try to get the user's location
        if (navigator.geolocation) {
            navigator.geolocation.getCurrentPosition(
                function (position) {
                    let userLocation = {
                        lat: position.coords.latitude,
                        lng: position.coords.longitude
                    };

                    // Move the map to user's location
                    map.setCenter(userLocation);
                    marker.setPosition(userLocation);

                    // Store the user's initial location
                    selectedLat = userLocation.lat;
                    selectedLng = userLocation.lng;
                },
                function () {
                    console.log("Geolocation failed.");
                }
            );
        }

        // Allow marker dragging
        google.maps.event.addListener(marker, 'dragend', function(event) {
            selectedLat = event.latLng.lat();
            selectedLng = event.latLng.lng();
        });

        // Allow clicking on the map to move marker
        google.maps.event.addListener(map, 'click', function(event) {
            marker.setPosition(event.latLng);
            selectedLat = event.latLng.lat();
            selectedLng = event.latLng.lng();
        });
    }
}
      
          function selectLocation() {
              if (selectedLat && selectedLng) {
                  let geocoder = new google.maps.Geocoder();
                  let latlng = new google.maps.LatLng(selectedLat, selectedLng);
      
                  geocoder.geocode({ 'location': latlng }, function(results, status) {
                      if (status === google.maps.GeocoderStatus.OK) {
                          if (results[0]) {
                              document.getElementById('shipping_address').value = results[0].formatted_address;
                              document.getElementById('latitude').value = selectedLat;
                              document.getElementById('longitude').value = selectedLng;
                          }
                      }
                  });
              }
      
              // Hide the map after selection
              document.getElementById('fullScreenMapModal').style.display = 'none';
          }
      </script>
        <button type="submit" class="btn-solid">Update Settings</button>
      </form>
      <!-- Form Section End -->
    </main>
    <!-- Main End -->

    <!-- Footer Start -->
    @include('EcomLayouts.partials.footer')
    <!-- Footer End -->

    <!-- jquery 3.6.0 -->
    @include('EcomLayouts.partials.scripts')
  </body>
  <!-- Body End -->
</html>
<!-- Html End -->