       <!DOCTYPE html>
       <html lang="{{ app()->getLocale() }}" dir="{{ app()->getLocale() == 'ar' ? 'rtl' : 'ltr' }}">

       <head>
           <meta charset="UTF-8">
           <title>Bright Solution</title>
           <meta name="description" content="{{ __('Bright Solution') }}">
           <meta name="keywords" content="">
           <meta name="author" content="">
           <!-- Mobile Specific Meta -->
           <meta name="viewport" content="width=device-width, initial-scale=1">

           <!-- Favicon -->
           <link rel="shortcut icon" type="image/x-icon" href="{{ asset('style/assets/img/logo/fav.png') }}">

           <link rel="stylesheet" href="{{ asset('style/assets/css/owl.carousel.css') }}">

           <link rel="stylesheet" href="{{ asset('style/assets/css/fontawesome-all.css') }}">
           <link rel="stylesheet" href="{{ asset('style/assets/css/animate.css') }}">
           <link rel="stylesheet" href="{{ asset('style/assets/css/flaticon.css') }}">
           <link rel="stylesheet" href="{{ asset('style/assets/css/bootstrap.min.css') }}">
           <link rel="stylesheet" href="{{ asset('style/assets/css/video.min.css') }}">
           @if (app()->getLocale() === 'ar')
               <link rel="stylesheet" href="{{ asset('style/assets/css/style-ar.css') }}">
           @else
               <link rel="stylesheet" href="{{ asset('style/assets/css/style-en.css') }}">
           @endif
       </head>

       <body class="nio-con" id="body">
           <div id="preloader"></div>
           <div class="up">
               <a href="#" class="scrollup text-center"><i class="fas fa-chevron-up"></i></a>
           </div>

           <!-- Start of header section
 ============================================= -->
           @include('layouts.header')

           <!-- End of header section
 ============================================= -->
           <!-- Start of content section
 ============================================= -->

           @yield('content')

           <!-- End of content section
 ============================================= -->

           <!-- Start of footer section
============================================= -->
           @include('layouts.footer')

           <!-- End of footer section
 ============================================= -->


           <!-- For Js Library -->
           <script src="{{ asset('style/assets/js/jquery.min.js') }}"></script>
           <script src="{{ asset('style/assets/js/bootstrap.min.js') }}"></script>
           <script src="{{ asset('style/assets/js/popper.min.js') }}"></script>
           <script src="{{ asset('style/assets/js/owl.carousel.min.js') }}"></script>
           <script src="{{ asset('style/assets/js/jarallax.js') }}"></script>
           <script src="{{ asset('style/assets/js/jquery.magnific-popup.min.js') }}"></script>
           <script src="{{ asset('style/assets/js/appear.js') }}"></script>
           <script src="{{ asset('style/assets/js/Chart.min.js') }}"></script>
           <script src="{{ asset('style/assets/js/utils.js') }}"></script>
           <script src="{{ asset('style/assets/js/wow.min.js') }}  "></script>
           <script src="{{ asset('style/assets/js/jquery.filterizr.js') }}"></script>
           <script src="{{ asset('style/assets/js/circle-progress.js') }}"></script>
           <script src="{{ asset('style/assets/js/jquery.counterup.min.js') }}"></script>
           <script src="{{ asset('style/assets/js/waypoints.min.js') }}"></script>
           <script src="{{ asset('style/assets/js/parallax-scroll.js') }}"></script>
           <script src="{{ asset('style/assets/js/tilt.jquery.min.js') }}"></script>
           <script src="{{ asset('style/assets/js/script.js') }}"></script>
       </body>

       </html>
