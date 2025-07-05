 <header id="header-id" class="main-header header-style-five">
     <span class="nio-con-header-shape position-absolute nio-h-shape1"><img
             src="{{ asset('style/assets/img/shape/h-shape1.png') }}" alt=""></span>
     <span class="nio-con-header-shape position-absolute nio-h-shape2"><img
             src="{{ asset('style/assets/img/shape/h-shape2.png') }}" alt=""></span>
     <div class="nio-con-header-content">
         <div class="brand-logo float-left">
             <a href="index-2.1.html"><img src="{{ asset('style/assets/img/logo/logo1.png') }}" alt=""></a>
         </div>
         <div class="nio-con-header-social ul-li float-right">
             <ul>

                 <li>
                     @if (app()->getLocale() === 'ar')
                         <a href="{{ route('lang.switch', 'en') }}"
                             class="{{ app()->getLocale() === 'en' ? 'active' : '' }}">EN</a>
                     @else
                         <a href="{{ route('lang.switch', 'ar') }}"
                             class="{{ app()->getLocale() === 'ar' ? 'active' : '' }}">العربية</a>
                     @endif
                 </li>
                 <li><a href="#"><i class="fab fa-facebook-f"></i></a></li>
                 <li><a href="#"><i class="fab fa-twitter"></i></a></li>
                 <li><a href="#"><i class="fab fa-youtube"></i></a></li>

             </ul>
         </div>

         <div class="nio-con-main-menu-item float-right">

             <nav class="nio-con-main-navigation one-page-scroll-2 float-right ul-li">
                 <ul id="main-nav" class="navbar-nav text-capitalize clearfix">
                     <li>
                         <a href="#body">{{ __('Home') }}</a>
                     </li>
                     <li>
                         <a href="#nio-con-about">{{ __('About Us') }}</a>
                     </li>

                     <li>
                         <a href="#nio-con-service"> {{ __('Our Services') }}</a>
                     </li>

                     <li>
                         <a href="#nio-con-portfolio">{{ __('Why Us') }}</a>
                     </li>

                     <li>
                         <a href="#nio-con-why-choose"> {{ __('Strategic Advantages') }}</a>
                     </li>

                     <li>
                         <a href="#nio-con-spccial-clients"> {{ __('Our Clients') }}</a>
                     </li>
                 </ul>
             </nav>
         </div>
     </div>
     <div class="nio-con-mobile_menu position-relative">
         <div class="nio-con-mobile_menu_button nio-con-open_mobile_menu">
             <i class="fas fa-bars"></i>
         </div>
         <div class="nio-con-mobile_menu_wrap">
             <div class="mobile_menu_overlay nio-con-open_mobile_menu"></div>
             <div class="nio-con-mobile_menu_content">
                 <div class="nio-con-mobile_menu_close nio-con-open_mobile_menu">
                     <i class="fas fa-times"></i>
                 </div>
                 <div class="m-brand-logo text-center">
                     <a href="#"><img src="{{ asset('style/assets/img/logo/logo1.png') }}" alt=""></a>
                 </div>
                 <nav class="nio-con-mobile-main-navigation one-page-scroll-2  clearfix ul-li">
                     <ul id="m-main-nav" class="navbar-nav text-capitalize clearfix">
                         <li>
                             <a href="#body">{{ __('Home') }}</a>
                         </li>
                         <li>
                             <a href="#nio-con-about">{{ __('About Us') }}</a>
                         </li>

                         <li>
                             <a href="#nio-con-service"> {{ __('Our Services') }}</a>
                         </li>

                         <li>
                             <a href="#nio-con-portfolio">{{ __('Why Us') }}</a>
                         </li>

                         <li>
                             <a href="#nio-con-why-choose"> {{ __('Strategic Advantages') }}</a>
                         </li>

                         <li>
                             <a href="#nio-con-spccial-clients"> {{ __('Our Clients') }}</a>
                         </li>


                     </ul>
                 </nav>
             </div>
         </div>
     </div>
     <!-- /Mobile-Menu -->
 </header>
