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
        <a href="/"><i class="iconly-Arrow-Left-Square icli"></i></a>
        <h1 class="title-color font-md">Accounts</h1>
      </div>
      <div class="avatar-wrap">
        <a href="/">
          <i class="iconly-Home icli"></i>
        </a>
      </div>
    </header>
    <!-- Header End -->

    <!-- Main Start -->
    <main class="main-wrap account-page mb-xxl">
      <div class="account-wrap section-b-t">
        <div class="user-panel">
          <div class="media">
            <a href="/account"> <img src="assets/images/avatar/avatar.jpg" alt="avatar" /></a>
            <div class="media-body">
              <a href="/account" class="title-color"
                >{{ $user->first_name . ' ' . $user->last_name }}
                <span class="content-color font-sm">{{$user->email}}</span>
              </a>
            </div>
          </div>
        </div>

        <!-- Navigation Start -->
        <ul class="navigation">
          <li>
            <a href="/" class="nav-link title-color font-sm">
              <i class="iconly-Home icli"></i>
              <span>Home</span>
            </a>
            <a href="/" class="arrow"><i data-feather="chevron-right"></i></a>
          </li>

          {{-- <li>
            <a href="category-wide.html" class="nav-link title-color font-sm">
              <i class="iconly-Category icli"></i>
              <span>Shop by Category</span>
            </a>
            <a href="category-wide.html" class="arrow"><i data-feather="chevron-right"></i></a>
          </li> --}}

          <li>
            <a href="/order-history" class="nav-link title-color font-sm">
              <i class="iconly-Document icli"></i>
              <span>Orders</span>
            </a>
            <a href="/order-history" class="arrow"><i data-feather="chevron-right"></i></a>
          </li>

          {{-- <li>
            <a href="wishlist.html" class="nav-link title-color font-sm">
              <i class="iconly-Heart icli"></i>
              <span>Your Wishlist</span>
            </a>
            <a href="wishlist.html" class="arrow"><i data-feather="chevron-right"></i></a>
          </li> --}}

          {{-- <li>
            <a href="javascript:void(0)" data-bs-toggle="offcanvas" data-bs-target="#language" aria-controls="language" class="nav-link title-color font-sm">
              <img src="assets/icons/png/flags.png" alt="flag" />
              <span>Langauge</span>
            </a>
            <a href="javascript:void(0)" class="arrow"><i data-feather="chevron-right"></i></a>
          </li> --}}

          {{-- <li>
            <a href="notification.html" class="nav-link title-color font-sm">
              <i class="iconly-Notification icli"></i>
              <span>Notification</span>
            </a>
            <a href="notification.html" class="arrow"><i data-feather="chevron-right"></i></a>
          </li> --}}

          <li>
            <a href="/billing-profile" class="nav-link title-color font-sm">
              <i class="iconly-Setting icli"></i>
              <span>Billing Profile</span>
            </a>
            <a href="/billing-profile" class="arrow"><i data-feather="chevron-right"></i></a>
          </li>

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

          {{-- <li>
            <a href="javascript:void(0)" class="nav-link title-color font-sm">
              <i class="iconly-Filter icli"></i>
              <span>RTL</span>
            </a>

            <div class="dark-switch">
              <input id="rtlButton" type="checkbox" />
              <span class="before-none"></span>
            </div>
          </li> --}}
        </ul>
        <!-- Navigation End -->
        <a href="/logout" class="log-out"><i class="iconly-Logout icli"></i>Sign Out</a>
        {{-- <button class="log-out" data-bs-toggle="offcanvas" data-bs-target="#confirmation" aria-controls="confirmation"><i class="iconly-Logout icli"></i>Sign Out</button> --}}
      </div>
    </main>
    <!-- Main End -->


@include('EcomLayouts.partials.footer')

    <!-- Action confirmation Start -->
    <div class="action action-confirmation offcanvas offcanvas-bottom" tabindex="-1" id="confirmation" aria-labelledby="confirmation">
      <div class="offcanvas-body small">
        <div class="confirmation-box">
          <h2>Are You Sure?</h2>
          <p class="font-sm content-color">The permission for the use/group, preview is inherited from the object, Modifiying it for this object will create a new permission for this object</p>
          <div class="btn-box">
            <button class="btn-outline" data-bs-dismiss="offcanvas" aria-label="Close">Cancel</button>
            <a href="onboarding2.html" class="btn-solid d-block" data-bs-dismiss="offcanvas" aria-label="Close">Sign Out</a>
          </div>
        </div>
      </div>
    </div>
    <!-- Action Confirmation End -->

   @include('EcomLayouts.partials.scripts')
   
  </body>
  <!-- Body End -->
</html>
<!-- Html End -->
