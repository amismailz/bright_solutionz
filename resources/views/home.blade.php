@extends('layouts.app')
{{-- @section('title', __('Home'))
@section('meta_data')
    <meta name="title"
        content="{{ __('Ayady - (Recruitment - Transfer of Services - Rent) of Domestic Workers with the Best Offices and Recruitment Installment Services') }}" />
    <meta name="description"
        content="{{ __('Ayadi platform facilitates the recruitment of domestic workers through the best recruitment offices and licensed companies in Saudi Arabia. The platform provides flexible recruitment services that include recruitment installments, rental of domestic workers, and transfer of services, in line with Vision 2030. You can view domestic workers, recruitment companies, and recruitment offices and access them easily through the platform') }}" />
        <meta name="keywords"
        content="{{ __('Labor rental, recruitment of labor from abroad, recruitment of domestic labor, rental of company labor, recruitment services, recruitment of labor from the Philippines, recruitment of labor from India, recruitment of maids, domestic labor services, rental of maids, recruitment of professional labor, employment services, Saudi labor, rental of temporary labor, recruitment of company labor, recruitment of construction labor, labor recruitment companies') }}" />
@endsection --}}
@section('content')
    <!-- Start of Banner section
                                                                                 ============================================= -->
    <section id="nio-con-slider" class="nio-con-slider-section position-relative">
        <span class="slider-shape slider-sh1  position-absolute" data-parallax='{"y" : 100, "rotateY":500}'><img
                src="{{ asset('style/assets/img/shape/abs.png') }}" alt=""></span>
        <span class="slider-shape slider-sh2 position-absolute" data-parallax='{"y" : 150}'><img
                src="{{ asset('style/assets/img/shape/abs1.png') }}" alt=""></span>
        <span class="slider-shape slider-sh3 position-absolute" data-parallax='{"y" : -150}'><img
                src="{{ asset('style/assets/img/shape/abs2.png') }} " alt=""></span>
        <span class="slider-shape slider-sh4 position-absolute"><img src="{{ asset('style/assets/img/shape/s-sh1.png') }}"
                alt=""></span>
        <span class="slider-shape slider-sh5 position-absolute" data-parallax='{"x" : 150}'><img
                src="{{ asset('style/assets/img/shape/s-sh2.png') }}" alt=""></span>
        <div id="nio-con-slider-id" class="nio-con-main-slider">
            <div class="nio-con-slider-item position-relative">
                <div class="background_overlay"></div>
                <div class="slider-main-img img-zooming" data-background="{{ asset('style/assets/img/slider/s1.jpg') }}">
                </div>
                <div class="slider-text-wrap">
                    <div class="container">
                        <div class="slider-text-area headline text-center pera-content">
                            <span>{!! __('Empowering Your Growth') !!}</span>
                            <h1> {!! __('Elevating Your Efficiency') !!}</h1>
                            <div class="slider-btn">
                                <a href="#nio-con-about">{!! __('ABOUT US') !!}</a>
                                <a href="#nio-con-service">{!! __('OUR SERVICES') !!}</a>
                            </div>
                        </div>
                    </div>
                </div>
            </div>

            <div class="nio-con-slider-item position-relative">
                <div class="background_overlay"></div>
                <div class="slider-main-img img-zooming" data-background="{{ asset('style/assets/img/slider/s2.jpg') }} ">
                </div>
                <div class="slider-text-wrap">
                    <div class="container">
                        <div class="slider-text-area headline text-center pera-content">
                            <span> {{ __('We connect ambition with opportunity, driven by compliance.') }}</span>
                            <h1> {{ __('And guided by a clear vision') }}</h1>
                            <div class="slider-btn">
                                <a href="#nio-con-about">{!! __('ABOUT US') !!}</a>
                                <a href="#nio-con-service">{!! __('OUR SERVICES') !!}</a>
                            </div>
                        </div>
                    </div>
                </div>
            </div>


        </div>
    </section>
    <!-- End of Banner section
    ============================================= -->

    <!-- Start of about section
     ============================================= -->
    <section id="nio-con-about" class="nio-con-about-section">
        <div class="container">
            <div class="nio-con-about-content position-relative">
                <span class="nio-con-about-circle position-absolute"><img src="assets/img/shape/ab-c.png"
                        alt=""></span>
                <div class="row">
                    <div class="col-lg-6">
                        <div class="nio-con-about-img position-relative wow fadeInLeft" data-wow-delay="0ms"
                            data-wow-duration="1500ms">
                            <img src="{{ asset('style/assets/img/about/ab1.jpg') }}" alt="">
                            <div class="nio-con-about-counter headline">
                                <h3>{!! __('Empowering Your Growth') !!}</h3>
                                <h4>{!! __('Elevating Your Efficiency') !!}</h4>
                            </div>
                        </div>
                    </div>
                    <div class="col-lg-6">
                        <div class="nio-con-about-text wow fadeInRight" data-wow-delay="0ms" data-wow-duration="1500ms">
                            <div class="nio-con-section-title headline">
                                <h2>{!! __('About Us') !!}</h2>
                                <span>{!! __($aboutUs->title) ?? '' !!}</span>

                            </div>
                            <div class="nio-con-about-details">
                                {!! __($aboutUs->description) ?? '' !!}
                            </div>
                            <div class="nio-con-about-list clearfix ul-li">
                                <ul>
                                    <li>
                                        <span>{{ __('Our Vision') }}</span>
                                        {!! __($aboutUs->vision) ?? '' !!}
                                    </li>
                                    <li>
                                        <span> {{ __('Our Mission') }} </span>
                                        {!! __($aboutUs->mission) ?? '' !!}
                                    </li>
                                </ul>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </section>
    <!-- End of About section
                                                                                 ============================================= -->

    <!-- Start of special feature section
                                                                                 ============================================= -->
    <section id="nio-con-spccial-feature" class="nio-con-spccial-feature-section position-relative">
        <span class="sf-deco-shape deco-shape-1 position-absolute" data-parallax='{"y" : 40}'><img
                src="assets/img/shape/abs3.png" alt=""></span>
        <span class="sf-deco-shape deco-shape-2 position-absolute" data-parallax='{"y" : 140}'><img
                src="assets/img/shape/abs4.png" alt=""></span>
        <span class="sf-deco-shape deco-shape-3 position-absolute"><img src="{{ asset('assets/img/shape/abs5.png') }}"
                alt=""></span>
        <div class="container">
            <div class="nio-con-spccial-feature-content">
                <div class="row justify-content-center">
                    @if ($ourValues->isNotEmpty())
                        @foreach ($ourValues as $index => $value)
                            <div class="col-lg-3 col-md-6">
                                <div class="nio-con-spccial-feature-innerbox position-relative wow fadeInUp"
                                    data-wow-delay="{{ $index * 300 }}ms" data-wow-duration="1500ms">
                                    <div class="nio-con-spccial-feature-icon">
                                        <img src="{{ asset('storage/' . $value->image) }}"
                                            alt="{{ __('Our Values') . __($value->title) ?? '' }}">
                                    </div>
                                    <div class="nio-con-spccial-feature-text headline pera-content">
                                        <h3> {!! __($value->title) ?? '' !!}</h3>
                                        <p> {!! __($value->description) ?? '' !!}</p>
                                    </div>
                                </div>
                            </div>
                        @endforeach
                    @else
                        <div class="col-12 text-center">
                            <p>{{ __('No values found.') }}</p>
                        </div>
                    @endif



                </div>
            </div>
        </div>
    </section>
    <!-- End of specia feature section
                                                                                 ============================================= -->

    <!-- Start of Service section
                                                                                 ============================================= -->
    <section id="nio-con-service" class="nio-con-service-section position-relative">
        <span class="ser-shape position-absolute" data-parallax='{"y" : -50}'><img
                src="{{ asset('style/assets/img/shape/s-sh3.png') }}" alt=""></span>
        <div class="container">
            <div class="nio-con-section-title text-center headline">
                <span>{{ __('OUR SERVICES') }}</span>
                <h2>{{ __('Services we provide') }}</h2>
            </div>
            <div class="nio-con-service-content">
                <div class="row">
                    @if ($ourServices->isNotEmpty())
                        @foreach ($ourServices as $index => $service)
                            <div class="col-lg-6">
                                <div class="nio-con-service-img-text wow fadeInUp" data-wow-delay="{{ $index * 300 }}ms"
                                    data-wow-duration="1500ms">
                                    <div class="nio-con-service-img position-relative">
                                        <div class="nio-con-service-img-wrap">
                                            <img src="{{ asset('storage/' . $service->image) }}"
                                                alt="{{ __('Our Services') . __($value->title) ?? '' }}">
                                        </div>
                                        <div class="nio-con-service-icon-txt headline">
                                            <div class="nio-con-service-icon">
                                                <img src="{{ asset('style/assets/img/icon/ser-icon1.png') }}"
                                                    alt="">
                                            </div>
                                            <h3>{!! __($service->title) ?? '' !!}</h3>
                                        </div>
                                        <div class="nio-con-service-middle position-absolute">
                                            <h3>{!! __($service->title) ?? '' !!}</h3>
                                        </div>
                                    </div>
                                    <div class="nio-con-service-text pera-content position-relative">
                                        {!! __($service->description) ?? '' !!}
                                    </div>
                                </div>
                            </div>
                        @endforeach
                    @else
                        <div class="col-12 text-center">
                            <p>{{ __('No values found.') }}</p>
                        </div>
                    @endif
                </div>
            </div>
        </div>
    </section>
    <!-- End of service section
                                                                                 ============================================= -->

    <!-- Start of Portfolio section
                                                                                 ============================================= -->
    <section id="nio-con-portfolio" class="nio-con-portfolio-section position-relative"
        data-background="{{ asset('style/assets/img/bg/port-bg.jpg') }} ">
        <span class="pr-shape port-shape1 position-absolute"><img src="{{ asset('style/assets/img/shape/p-sh1.png') }}"
                alt=""></span>
        <span class="pr-shape port-shape2 position-absolute"><img src="{{ asset('style/assets/img/shape/p-sh2.png') }}"
                alt=""></span>
        <div class="background_overlay"></div>
        <div class="container">
            <div class="nio-con-portfolio-top">
                <div class="row">
                    <div class="col-md-12">
                        <div class="nio-con-section-title headline">
                            <h2>{{ __('Why Bright Solutionz?') }}</h2>
                            <span>"{{ __('Simplified process, hassle-free experience') }}"</span>
                        </div>
                    </div>
                </div>
            </div>
        </div>
        <div class="nio-con-portfolio-content">
            <div id="nio-con-portfolio-slider" class="nio-con-portfolio-silder-area">
                @if ($ourServices->isNotEmpty())
                    @foreach ($whyUs as $item)
                        <div class="nio-con-portfolio-innerbox position-relative">
                            <div class="nio-con-portfolio-img">
                                <img src="{{ asset('storage/' . $item->image) }}"
                                    alt="{{ __('Why Us') . __($item->title) }}">
                            </div>
                            <div class="nio-con-portfolio-text headline">
                                <span> {!! __($item->description) ?? '' !!}</span>
                                <h3> {!! __($item->description) ?? '' !!}</h3>
                            </div>
                        </div>
                    @endforeach
                @else
                    <div class="col-12 text-center">
                        <p>{{ __('No values found.') }}</p>
                    </div>
                @endif
            </div>
        </div>
    </section>
    <!-- End of Portfolio section
                                                                                 ============================================= -->

    <!-- Start of why choose section
                                                                                 ============================================= -->
    <section id="nio-con-why-choose" class="nio-con-why-choose-section position-relative">
        <span class="wh-circle-shape position-absolute"><img src="{{ asset('style/assets/img/shape/wh-circle.png') }}"
                alt=""></span>
        <div class="container">
            <div class="nio-con-why-choose-content">
                <div class="row">
                    <div class="col-lg-6">
                        <div class="nio-con-why-choose-main-img position-relative wow fadeInLeft" data-wow-delay="0ms"
                            data-wow-duration="1500ms">
                            <span class="nio-con-wh-shape position-absolute nio-con-wh-sh1"><img
                                    src="{{ asset('style/assets/img/shape/wh-shape.png') }}" alt=""></span>
                            <span class="nio-con-wh-shape position-absolute nio-con-wh-sh2"><img
                                    src="{{ asset('style/assets/img/shape/wh-shape1.png') }}" alt=""></span>
                            <span class="nio-con-wh-shape position-absolute nio-con-wh-sh3"><img
                                    src="{{ asset('style/assets/img/shape/wh-shape2.png') }}" alt=""></span>
                            <div class="nio-con-why-choose-img">
                                <img src="{{ asset('style/assets/img/about/wh1.jpg') }}" alt="">
                            </div>
                        </div>
                    </div>
                    <div class="col-lg-6">
                        <div class="nio-con-why-choose-text">
                            <div class="nio-con-section-title headline">
                                <span>"{{ __("We don't just support your operations, we enhance your strategy.") }}"</span>
                                <h2>{{ __('Strategic Advantages') }}</h2>
                            </div>
                            <div class="nio-con-why-choose-list">
                                <div class="row">
                                    @if ($strategyAdvantages->isNotEmpty())
                                        @foreach ($strategyAdvantages as $index => $advantage)
                                            <div class="col-md-6">
                                                <div class="nio-con-wh-icon-text wow fadeInUp"
                                                    data-wow-delay="{{ $index * 200 }}ms" data-wow-duration="1500ms">
                                                    <div class="nio-con-wh-icon">
                                                        <img src="{{ asset('storage/' . $advantage->image) }}"
                                                            alt="{{ __('Strategic Advantages') . __($advantage->title) ?? '' }}">
                                                    </div>
                                                    <div class="nio-con-wh-text headline pera-content">
                                                        <h3>{!! __($advantage->title) ?? '' !!}
                                                        </h3>
                                                        <p>{!! __($advantage->description) ?? '' !!}
                                                        </p>
                                                    </div>
                                                </div>
                                            </div>
                                        @endforeach
                                    @else
                                        <div class="col-12 text-center">
                                            <p>{{ __('No values found.') }}</p>
                                        </div>
                                    @endif
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </section>
    <!-- End of why choose section
                                                                                 ============================================= -->

    <section id="nio-con-spccial-clients" class="nio-con-spccial-feature-section2 position-relative">
        <div class="container">
            <div class="nio-con-portfolio-top">
                <div class="row">
                    <div class="col-md-12">
                        <div class="nio-con-section-title headline">
                            <h2>{{ __('OUR CLIENTS') }} </h2>
                        </div>
                    </div>
                </div>
            </div>
            <div class="nio-con-spccial-feature-content">
                <div class="row justify-content-center">
                    @if ($clients->isNotEmpty())
                        @foreach ($clients as $index => $client)
                            <div class="col-lg-4 col-md-6">
                                <div class="nio-con-spccial-feature-innerbox position-relative wow fadeInUp"
                                    data-wow-delay="{{ $index * 300 }}ms" data-wow-duration="1500ms">
                                    <div class="nio-con-spccial-feature-icon">
                                        <img src="{{ asset('storage/' . $client->image) }}"
                                            alt="{{ __('OUR CLIENTS') . __($client->title) }}">
                                    </div>
                                    <div class="nio-con-spccial-feature-text headline pera-content">
                                        <h3>{{ __($client->title) ?? '' }}</h3>
                                    </div>
                                </div>
                            </div>
                        @endforeach
                    @else
                        <div class="col-12 text-center">
                            <p>{{ __('No clients found.') }}</p>
                        </div>
                    @endif

                </div>
            </div>
        </div>
    </section>
@endsection
