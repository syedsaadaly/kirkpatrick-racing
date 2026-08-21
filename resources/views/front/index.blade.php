@extends('front.include.app')
@section('title','Kirkpatrick Racing')
@section('content')

    <section class="main-banner">
        <div class="container">
            <div class="row">
                <div class="col-md-6">
                    <div class="main-content">
                        <h1 data-swiper-parallax="-200">RIDE ELECTRIC.<span>RIDE KIRKPATRICK.</span></h1>
                        <p data-swiper-parallax="-200">Louisiana's authorized dealer for Electro and Company and Thorne
                            Cycles. Family-owned. Rider-driven.</p>
                        <div class="btn-group">
                            <a href="{{ route('front.shop') }}" class="themeBtn">Shop Bikes</a>
                            <a href="{{ route('front.services') }}" class="themeBtn">Contact Us</a>
                        </div>
                        <ul class="authorized">
                            <li>Authorized Dealer</li>
                            <li>Family Owned</li>
                            <li>Louisiana Based</li>
                            <li>Expert Support</li>
                        </ul>
                    </div>
                </div>
            </div>
        </div>
    </section>

    <section class="ride-sec">
        <div class="container">
            <div class="row">
                <div class="col-md-12">
                    <ul class="ride-list" data-aos="fade-up" data-duration="3000">
                        <li><img src="{{ asset('front/images/chcekimg.webp') }}" class="img-fluid" alt="">Family Owned</li>
                        <li><img src="{{ asset('front/images/chcekimg.webp') }}" class="img-fluid" alt="">Certified Service</li>
                        <li><img src="{{ asset('front/images/chcekimg.webp') }}" class="img-fluid" alt="">Motocross Focused</li>
                        <li><img src="{{ asset('front/images/chcekimg.webp') }}" class="img-fluid" alt="">Dealer Network</li>
                    </ul>
                    <div class="ride-heading" data-aos="fade-up" data-duration="3000">
                        <h2 class="mainHead">Ride the Future</h2>
                        <a href="{{ route('front.electric-bike') }}">Featured Models<i class="far fa-long-arrow-right"></i></a>
                    </div>
                </div>
            </div>
            <div class="row">
                <div class="col-md-4" data-aos="flip-right" data-duration="3000">
                    <div class="ride-wrapper">
                        <figure class="ride-img">
                            <img src="{{ asset('front/images/ride1.webp') }}" class="img-fluid" alt="">
                        </figure>
                        <div class="ride-content">
                            <h3>MX-5000 Electric</h3>
                            <p>Competition-grade dirt bike for serious racers</p>
                            <div class="ride-flex">
                                <figure class="ride-sub">
                                    <img src="{{ asset('front/images/ridesub1.webp') }}" class="img-fluid" alt="">
                                    <span>60 hp</span>
                                </figure>
                                <figure class="ride-sub">
                                    <img src="{{ asset('front/images/ridesub2.webp') }}" class="img-fluid" alt="">
                                    <span>80 mi</span>
                                </figure>
                                <figure class="ride-sub">
                                    <img src="{{ asset('front/images/ridesub3.webp') }}" class="img-fluid" alt="">
                                    <span>240 lb</span>
                                </figure>
                                <figure class="ride-sub">
                                    <img src="{{ asset('front/images/ridesub4.webp') }}" class="img-fluid" alt="">
                                    <span>3.2s</span>
                                </figure>
                            </div>
                            <ul class="ride-items">
                                <li>Adjustable suspension linkage</li>
                                <li>Regenerative braking system</li>
                                <li>Competition-spec frame geometry</li>
                            </ul>
                        </div>
                    </div>
                </div>
                <div class="col-md-4" data-aos="flip-right" data-duration="3000">
                    <div class="ride-wrapper">
                        <figure class="ride-img">
                            <img src="{{ asset('front/images/ride2.webp') }}" class="img-fluid" alt="">
                        </figure>
                        <div class="ride-content">
                            <h3>Trail-X Electric</h3>
                            <p>Built for long-range trail adventure riding</p>
                            <div class="ride-flex">
                                <figure class="ride-sub">
                                    <img src="{{ asset('front/images/ridesub1.webp') }}" class="img-fluid" alt="">
                                    <span>60 hp</span>
                                </figure>
                                <figure class="ride-sub">
                                    <img src=" {{ asset('front/images/ridesub2.webp') }}" class="img-fluid" alt="">
                                    <span>80 mi</span>
                                </figure>
                                <figure class="ride-sub">
                                    <img src="{{ asset('front/images/ridesub3.webp') }}" class="img-fluid" alt="">
                                    <span>240 lb</span>
                                </figure>
                                <figure class="ride-sub">
                                    <img src="{{ asset('front/images/ridesub4.webp') }}" class="img-fluid" alt="">
                                    <span>3.2s</span>
                                </figure>
                            </div>
                            <ul class="ride-items">
                                <li>Long-travel suspension</li>
                                <li>Extended battery capacity</li>
                                <li>All-terrain tyre package</li>
                            </ul>
                        </div>
                    </div>
                </div>
                <div class="col-md-4" data-aos="flip-right" data-duration="3000">
                    <div class="ride-wrapper">
                        <figure class="ride-img">
                            <img src="{{ asset('front/images/ride1.webp') }}" class="img-fluid" alt="">
                        </figure>
                        <div class="ride-content">
                            <h3>Junior EV-20</h3>
                            <p>Safe, fun electric riding for young riders</p>
                            <div class="ride-flex">
                                <figure class="ride-sub">
                                    <img src="{{ asset('front/images/ridesub1.webp') }}" class="img-fluid" alt="">
                                    <span>60 hp</span>
                                </figure>
                                <figure class="ride-sub">
                                    <img src="{{ asset('front/images/ridesub2.webp') }}" class="img-fluid" alt="">
                                    <span>80 mi</span>
                                </figure>
                                <figure class="ride-sub">
                                    <img src="{{ asset('front/images/ridesub3.webp') }}" class="img-fluid" alt="">
                                    <span>240 lb</span>
                                </figure>
                                <figure class="ride-sub">
                                    <img src="{{ asset('front/images/ridesub4.webp') }}" class="img-fluid" alt="">
                                    <span>3.2s</span>
                                </figure>
                            </div>
                            <ul class="ride-items">
                                <li>Parent-controlled speed limiter</li>
                                <li>Low center of gravity frame</li>
                                <li>Zero maintenance drivetrain</li>
                            </ul>
                        </div>
                    </div>
                </div>
            </div>
        </div>
        <div class="container-fluid p-0">
            <div class="row">
                <div class="col-md-12" data-aos="fade-up" data-duration="3000">
                    <div class="marquee">
                        <span>Professional Repair <img src="{{ asset('front/images/marqueeimg.webp') }}" class="img-fluid" alt="">&
                            Maintenance Services</span>
                        <span>Professional Repair <img src="{{ asset('front/images/marqueeimg.webp') }}" class="img-fluid" alt="">&
                            Maintenance Services</span>
                        <span>Professional Repair <img src="{{ asset('front/images/marqueeimg.webp') }}" class="img-fluid" alt="">&
                            Maintenance Services</span>
                        <span>Professional Repair <img src="{{ asset('front/images/marqueeimg.webp') }}" class="img-fluid" alt="">&
                            Maintenance Services</span>
                        <span>Professional Repair <img src="{{ asset('front/images/marqueeimg.webp') }}" class="img-fluid" alt="">&
                            Maintenance Services</span>
                        <span>Professional Repair <img src="{{ asset('front/images/marqueeimg.webp') }}" class="img-fluid" alt="">&
                            Maintenance Services</span>
                        <span>Professional Repair <img src="{{ asset('front/images/marqueeimg.webp') }}" class="img-fluid" alt="">&
                            Maintenance Services</span>
                        <span>Professional Repair <img src="{{ asset('front/images/marqueeimg.webp') }}" class="img-fluid" alt="">&
                            Maintenance Services</span>
                    </div>
                </div>
            </div>
        </div>
    </section>

    <!-- <section class="services-sec">
        <div class="container-fluid p-0">
            <div class="row no-gutters">
                <div class="col-md-3" data-aos="zoom-in" data-duration="3000">
                    <div class="services-wrapper">
                        <figure class="services-img">
                            <img src="{{ asset('front/images/services1.webp') }}" class="img-fluid" alt="">
                        </figure>
                        <a href="{{ route('front.services') }}">Dirt Bike Service<i class="far fa-long-arrow-right"></i></a>
                    </div>
                </div>
                <div class="col-md-3" data-aos="zoom-in" data-duration="3000">
                    <div class="services-wrapper">
                        <figure class="services-img">
                            <img src="{{ asset('front/images/services2.webp') }}" class="img-fluid" alt="">
                        </figure>
                        <a href="{{ route('front.services') }}">ATV & Four Wheeler<i class="far fa-long-arrow-right"></i></a>
                    </div>
                </div>
                <div class="col-md-3" data-aos="zoom-in" data-duration="3000">
                    <div class="services-wrapper">
                        <figure class="services-img">
                            <img src="{{ asset('front/images/services3.webp') }}" class="img-fluid" alt="">
                        </figure>
                        <a href="{{ route('front.services') }}">Side-by-Side Repair<i class="far fa-long-arrow-right"></i></a>
                    </div>
                </div>
                <div class="col-md-3" data-aos="zoom-in" data-duration="3000">
                    <div class="services-wrapper">
                        <figure class="services-img">
                            <img src="{{ asset('front/images/services4.webp') }}" class="img-fluid" alt="">
                        </figure>
                        <a href="{{ route('front.services') }}">Full Service Workshop<i class="far fa-long-arrow-right"></i></a>
                    </div>
                </div>
            </div>
        </div>
    </section> -->

    <section class="icon-sec">
        <div class="container">
            <div class="row">
                <div class="col-md-12" data-aos="zoom-in" data-duration="3000">
                    <div class="icon-flex">
                        <div class="icon-wrapper">
                            <figure class="icon-img">
                                <img src="{{ asset('front/images/icon1.webp') }}" class="img-fluid" alt="">
                            </figure>
                            <div class="icon-content">
                                <h3>Motocross Expertise</h3>
                                <p>Years of hands-on racing and repair experience from real riders.</p>
                            </div>
                        </div>
                        <div class="icon-wrapper">
                            <figure class="icon-img">
                                <img src="{{ asset('front/images/icon2.webp') }}" class="img-fluid" alt="">
                            </figure>
                            <div class="icon-content">
                                <h3>Family Owned</h3>
                                <p>Personal service with honest recommendations every time.</p>
                            </div>
                        </div>
                        <div class="icon-wrapper">
                            <figure class="icon-img">
                                <img src="{{ asset('front/images/icon3.webp') }}" class="img-fluid" alt="">
                            </figure>
                            <div class="icon-content">
                                <h3>Dealer Support</h3>
                                <p>Authorized electric bike sales with full warranty support.</p>
                            </div>
                        </div>
                        <div class="icon-wrapper">
                            <figure class="icon-img">
                                <img src="{{ asset('front/images/icon4.webp') }}" class="img-fluid" alt="">
                            </figure>
                            <div class="icon-content">
                                <h3>Fast Turnaround</h3>
                                <p>Quick diagnostics and efficient repairs to get you back riding.</p>
                            </div>
                        </div>
                        <div class="icon-wrapper">
                            <figure class="icon-img">
                                <img src="{{ asset('front/images/icon5.webp') }}" class="img-fluid" alt="">
                            </figure>
                            <div class="icon-content">
                                <h3>Performance Focused</h3>
                                <p>Built for serious riders and weekend adventurers alike.</p>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </section>

    <section class="services-sec">
        <div class="container-fluid p-0">
            <div class="row align-items-center">
                <div class="col-md-5" data-aos="fade-right" data-duration="3000">
                    <figure class="services-img">
                        <img src="{{ asset('front/images/servicesimg.webp') }}" alt="">
                    </figure>
                </div>
                <div class="col-md-7" data-aos="fade-left" data-duration="3000">
                    <div class="services-content">
                        <h2 class="mainHead">Side-by-Side Service</h2>
                        <ul class="services-list">
                            <li>Full vehicle inspections</li>
                            <li>Suspension repairs & lift kits</li>
                            <li>Drivetrain & differential service</li>
                            <li>Preventive maintenance plans</li>
                            <li>Accessory installation</li>
                        </ul>
                        <a href="{{ route('front.services') }}" class="themeBtn">Get Service Quote</a>
                    </div>
                </div>
            </div>
        </div>
        <img src="{{asset('front/images/servicessub.webp') }}" class="img-fluid service-sub" alt="">
    </section>

    <section class="dealer-sec">
        <div class="container">
            <div class="row">
                <div class="col-md-6" data-aos="fade-right" data-duration="3000">
                    <div class="dealer-content">
                        <h2 class="mainHead">Authorized Dealer</h2>
                        <p>Kirkpatrick Racing is expanding into electric bike sales through a trusted dealer
                            partnership, bringing riders cutting-edge technology, exceptional performance and reliable
                            support you can count on.</p>
                        <ul class="dealer-list">
                            <li><img src="{{ asset('front/images/checkimg.webp') }}" class="img-fluid" alt="">New Electric Models</li>
                            <li><img src="{{asset('front/images/checkimg.webp') }}" class="img-fluid" alt="">Warranty Support</li>
                            <li><img src="{{asset('front/images/checkimg.webp') }}" class="img-fluid" alt="">Dealer Backing</li>
                            <li><img src="{{asset('front/images/checkimg.webp') }}" class="img-fluid" alt="">Genuine Parts</li>   
                            <li><img src="{{asset('front/images/checkimg.webp') }}" class="img-fluid" alt="">Financing Ready</li>
                            <li><img src="{{ asset('front/images/checkimg.webp') }}" class="img-fluid" alt="">Expert Setup</li>
                        </ul>
                    </div>
                </div>
            </div>
        </div>
    </section>

    <section class="shop-sec">
        <div class="container-fluid p-0">
            <div class="row">
                <div class="col-md-12" data-aos="fade-up" data-duration="3000">
                    <div class="shop-flex">
                        <h5>SHOP BY<span>CATEGORY</span></h5>
                        <figure class="shop-img">
                            <img src="{{ asset('front/images/shopimg.webp') }}" class="img-fluid" alt="">
                        </figure>
                    </div>
                </div>
            </div>
        </div>
    </section>

    <section class="bike-sec">
        <div class="container">
            <div class="row align-items-center">
                <div class="col-md-5" data-aos="fade-right" data-duration="3000">
                    <div class="bike-main">
                        <div class="bike-counter">
                            <h5>01</h5>
                            <span>Electric Bikes</span>
                        </div>
                        <div class="bike-counter">
                            <h5>02</h5>
                            <span>Accessories</span>
                        </div>
                        <div class="bike-counter">
                            <h5>03</h5>
                            <span>Parts & Components</span>
                        </div>
                        <div class="bike-counter">
                            <h5>05</h5>
                            <span>Merchandise</span>
                        </div>
                    </div>
                </div>
                <div class="col-md-7" data-aos="fade-left" data-duration="3000">
                    <div class="bike-flex">
                        <figure class="bike-imag">
                            <img src="{{ asset('front/images/bikrimg.webp') }}" class="img-fluid csm-bike-img" alt="">
                        </figure>
                        <a href="{{ route('front.shop') }}" class="themeBtn">Shop now<i class="far fa-long-arrow-right"></i></a>
                    </div>
                </div>
            </div>
        </div>
    </section>

    <section class="process-section">
        <div class="container">
            <h2 class="mainHead" data-aos="fade-up" data-duration="3000">How It Works</h2>
            <div class="row justify-content-center">
                <div class="col-12 col-lg-10" data-aos="fade-up" data-duration="3000">
                    <div class="process-grid position-relative">
                        <div class="center-dot"></div>
                        <div class="row m-0 process-row">
                            <div class="col-6 p-0 process-cell q1">
                                <div class='content-box'>
                                    <div class="step-num">01</div>
                                    <p class="step-desc">Choose the perfect bike for your<br>riding style.</p>
                                </div>
                            </div>
                            <div class="col-6 p-0 process-cell q2">
                                <div class="content-box">
                                    <div class="step-num">02</div>
                                    <p class="step-desc">Get expert recommendations<br>from our team.</p>
                                </div>
                            </div>
                            <div class="col-6 p-0 process-cell q3">
                                <div class="content-box">
                                    <div class="step-num">03</div>
                                    <p class="step-desc">Secure checkout and<br>ordering.</p>
                                </div>
                            </div>
                            <div class="col-6 p-0 process-cell q4">
                                <div class="content-box">
                                    <div class="step-num">04</div>
                                    <p class="step-desc">Ongoing service and support<br>after delivery.</p>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </section>

    <section class="testimonials-section">
        <div class="container">
            <div class="testimonial-block position-relative" data-aos="zoom-in" data-duration="3000">
                <div class="container-fluid position-relative z-index-1">
                    <h2 class="mainHead text-center">What Riders are saying</h2>

                    <div class="swiper testimonial-swiper">
                        <div class="swiper-wrapper">
                            <!-- Card 1 (Hidden/Edge) -->
                            <div class="swiper-slide">
                                <div class="review-card">
                                    <div class="review-content">
                                        <div class="stars mb-3">
                                            <svg viewBox="0 0 20 20" fill="currentColor" width="18" height="18"
                                                class="star-icon">
                                                <path
                                                    d="M9.049 2.927c.3-.921 1.603-.921 1.902 0l1.07 3.292a1 1 0 00.95.69h3.462c.969 0 1.371 1.24.588 1.81l-2.8 2.034a1 1 0 00-.364 1.118l1.07 3.292c.3.921-.755 1.688-1.54 1.118l-2.8-2.034a1 1 0 00-1.175 0l-2.8 2.034c-.784.57-1.838-.197-1.539-1.118l1.07-3.292a1 1 0 00-.364-1.118L2.98 8.72c-.783-.57-.38-1.81.588-1.81h3.461a1 1 0 00.951-.69l1.07-3.292z">
                                                </path>
                                            </svg>
                                            <svg viewBox="0 0 20 20" fill="currentColor" width="18" height="18"
                                                class="star-icon">
                                                <path
                                                    d="M9.049 2.927c.3-.921 1.603-.921 1.902 0l1.07 3.292a1 1 0 00.95.69h3.462c.969 0 1.371 1.24.588 1.81l-2.8 2.034a1 1 0 00-.364 1.118l1.07 3.292c.3.921-.755 1.688-1.54 1.118l-2.8-2.034a1 1 0 00-1.175 0l-2.8 2.034c-.784.57-1.838-.197-1.539-1.118l1.07-3.292a1 1 0 00-.364-1.118L2.98 8.72c-.783-.57-.38-1.81.588-1.81h3.461a1 1 0 00.951-.69l1.07-3.292z">
                                                </path>
                                            </svg>
                                            <svg viewBox="0 0 20 20" fill="currentColor" width="18" height="18"
                                                class="star-icon">
                                                <path
                                                    d="M9.049 2.927c.3-.921 1.603-.921 1.902 0l1.07 3.292a1 1 0 00.95.69h3.462c.969 0 1.371 1.24.588 1.81l-2.8 2.034a1 1 0 00-.364 1.118l1.07 3.292c.3.921-.755 1.688-1.54 1.118l-2.8-2.034a1 1 0 00-1.175 0l-2.8 2.034c-.784.57-1.838-.197-1.539-1.118l1.07-3.292a1 1 0 00-.364-1.118L2.98 8.72c-.783-.57-.38-1.81.588-1.81h3.461a1 1 0 00.951-.69l1.07-3.292z">
                                                </path>
                                            </svg>
                                            <svg viewBox="0 0 20 20" fill="currentColor" width="18" height="18"
                                                class="star-icon">
                                                <path
                                                    d="M9.049 2.927c.3-.921 1.603-.921 1.902 0l1.07 3.292a1 1 0 00.95.69h3.462c.969 0 1.371 1.24.588 1.81l-2.8 2.034a1 1 0 00-.364 1.118l1.07 3.292c.3.921-.755 1.688-1.54 1.118l-2.8-2.034a1 1 0 00-1.175 0l-2.8 2.034c-.784.57-1.838-.197-1.539-1.118l1.07-3.292a1 1 0 00-.364-1.118L2.98 8.72c-.783-.57-.38-1.81.588-1.81h3.461a1 1 0 00.951-.69l1.07-3.292z">
                                                </path>
                                            </svg>
                                            <svg viewBox="0 0 20 20" fill="currentColor" width="18" height="18"
                                                class="star-icon">
                                                <path
                                                    d="M9.049 2.927c.3-.921 1.603-.921 1.902 0l1.07 3.292a1 1 0 00.95.69h3.462c.969 0 1.371 1.24.588 1.81l-2.8 2.034a1 1 0 00-.364 1.118l1.07 3.292c.3.921-.755 1.688-1.54 1.118l-2.8-2.034a1 1 0 00-1.175 0l-2.8 2.034c-.784.57-1.838-.197-1.539-1.118l1.07-3.292a1 1 0 00-.364-1.118L2.98 8.72c-.783-.57-.38-1.81.588-1.81h3.461a1 1 0 00.951-.69l1.07-3.292z">
                                                </path>
                                            </svg>
                                        </div>
                                        <p class="review-text">"Brilliant service. The team was incredibly helpful and
                                            walked me through everything. Can't wait to hit the trails!"</p>
                                    </div>
                                    <div class="review-footer">
                                        <div class="author-name">Mark Johnson</div>
                                        <div class="author-role">Weekend Rider</div>
                                    </div>
                                </div>
                            </div>

                            <!-- Card 2 (Left Partial) -->
                            <div class="swiper-slide">
                                <div class="review-card">
                                    <div class="review-content">
                                        <div class="stars mb-3">
                                            <svg viewBox="0 0 20 20" fill="currentColor" width="18" height="18"
                                                class="star-icon">
                                                <path
                                                    d="M9.049 2.927c.3-.921 1.603-.921 1.902 0l1.07 3.292a1 1 0 00.95.69h3.462c.969 0 1.371 1.24.588 1.81l-2.8 2.034a1 1 0 00-.364 1.118l1.07 3.292c.3.921-.755 1.688-1.54 1.118l-2.8-2.034a1 1 0 00-1.175 0l-2.8 2.034c-.784.57-1.838-.197-1.539-1.118l1.07-3.292a1 1 0 00-.364-1.118L2.98 8.72c-.783-.57-.38-1.81.588-1.81h3.461a1 1 0 00.951-.69l1.07-3.292z">
                                                </path>
                                            </svg>
                                            <svg viewBox="0 0 20 20" fill="currentColor" width="18" height="18"
                                                class="star-icon">
                                                <path
                                                    d="M9.049 2.927c.3-.921 1.603-.921 1.902 0l1.07 3.292a1 1 0 00.95.69h3.462c.969 0 1.371 1.24.588 1.81l-2.8 2.034a1 1 0 00-.364 1.118l1.07 3.292c.3.921-.755 1.688-1.54 1.118l-2.8-2.034a1 1 0 00-1.175 0l-2.8 2.034c-.784.57-1.838-.197-1.539-1.118l1.07-3.292a1 1 0 00-.364-1.118L2.98 8.72c-.783-.57-.38-1.81.588-1.81h3.461a1 1 0 00.951-.69l1.07-3.292z">
                                                </path>
                                            </svg>
                                            <svg viewBox="0 0 20 20" fill="currentColor" width="18" height="18"
                                                class="star-icon">
                                                <path
                                                    d="M9.049 2.927c.3-.921 1.603-.921 1.902 0l1.07 3.292a1 1 0 00.95.69h3.462c.969 0 1.371 1.24.588 1.81l-2.8 2.034a1 1 0 00-.364 1.118l1.07 3.292c.3.921-.755 1.688-1.54 1.118l-2.8-2.034a1 1 0 00-1.175 0l-2.8 2.034c-.784.57-1.838-.197-1.539-1.118l1.07-3.292a1 1 0 00-.364-1.118L2.98 8.72c-.783-.57-.38-1.81.588-1.81h3.461a1 1 0 00.951-.69l1.07-3.292z">
                                                </path>
                                            </svg>
                                            <svg viewBox="0 0 20 20" fill="currentColor" width="18" height="18"
                                                class="star-icon">
                                                <path
                                                    d="M9.049 2.927c.3-.921 1.603-.921 1.902 0l1.07 3.292a1 1 0 00.95.69h3.462c.969 0 1.371 1.24.588 1.81l-2.8 2.034a1 1 0 00-.364 1.118l1.07 3.292c.3.921-.755 1.688-1.54 1.118l-2.8-2.034a1 1 0 00-1.175 0l-2.8 2.034c-.784.57-1.838-.197-1.539-1.118l1.07-3.292a1 1 0 00-.364-1.118L2.98 8.72c-.783-.57-.38-1.81.588-1.81h3.461a1 1 0 00.951-.69l1.07-3.292z">
                                                </path>
                                            </svg>
                                            <svg viewBox="0 0 20 20" fill="currentColor" width="18" height="18"
                                                class="star-icon">
                                                <path
                                                    d="M9.049 2.927c.3-.921 1.603-.921 1.902 0l1.07 3.292a1 1 0 00.95.69h3.462c.969 0 1.371 1.24.588 1.81l-2.8 2.034a1 1 0 00-.364 1.118l1.07 3.292c.3.921-.755 1.688-1.54 1.118l-2.8-2.034a1 1 0 00-1.175 0l-2.8 2.034c-.784.57-1.838-.197-1.539-1.118l1.07-3.292a1 1 0 00-.364-1.118L2.98 8.72c-.783-.57-.38-1.81.588-1.81h3.461a1 1 0 00.951-.69l1.07-3.292z">
                                                </path>
                                            </svg>
                                        </div>
                                        <p class="review-text">"Picked up my dirt bike before the season and the
                                            suspension walked over everything. Can't wait to hit the track again. Top
                                            notch!"</p>
                                    </div>
                                    <div class="review-footer">
                                        <div class="author-name">Sarah M.</div>
                                        <div class="author-role">First-Time Customer</div>
                                    </div>
                                </div>
                            </div>

                            <!-- Card 3 (Center Active) -->
                            <div class="swiper-slide">
                                <div class="review-card">
                                    <div class="review-content">
                                        <div class="stars mb-3">
                                            <svg viewBox="0 0 20 20" fill="currentColor" width="18" height="18"
                                                class="star-icon">
                                                <path
                                                    d="M9.049 2.927c.3-.921 1.603-.921 1.902 0l1.07 3.292a1 1 0 00.95.69h3.462c.969 0 1.371 1.24.588 1.81l-2.8 2.034a1 1 0 00-.364 1.118l1.07 3.292c.3.921-.755 1.688-1.54 1.118l-2.8-2.034a1 1 0 00-1.175 0l-2.8 2.034c-.784.57-1.838-.197-1.539-1.118l1.07-3.292a1 1 0 00-.364-1.118L2.98 8.72c-.783-.57-.38-1.81.588-1.81h3.461a1 1 0 00.951-.69l1.07-3.292z">
                                                </path>
                                            </svg>
                                            <svg viewBox="0 0 20 20" fill="currentColor" width="18" height="18"
                                                class="star-icon">
                                                <path
                                                    d="M9.049 2.927c.3-.921 1.603-.921 1.902 0l1.07 3.292a1 1 0 00.95.69h3.462c.969 0 1.371 1.24.588 1.81l-2.8 2.034a1 1 0 00-.364 1.118l1.07 3.292c.3.921-.755 1.688-1.54 1.118l-2.8-2.034a1 1 0 00-1.175 0l-2.8 2.034c-.784.57-1.838-.197-1.539-1.118l1.07-3.292a1 1 0 00-.364-1.118L2.98 8.72c-.783-.57-.38-1.81.588-1.81h3.461a1 1 0 00.951-.69l1.07-3.292z">
                                                </path>
                                            </svg>
                                            <svg viewBox="0 0 20 20" fill="currentColor" width="18" height="18"
                                                class="star-icon">
                                                <path
                                                    d="M9.049 2.927c.3-.921 1.603-.921 1.902 0l1.07 3.292a1 1 0 00.95.69h3.462c.969 0 1.371 1.24.588 1.81l-2.8 2.034a1 1 0 00-.364 1.118l1.07 3.292c.3.921-.755 1.688-1.54 1.118l-2.8-2.034a1 1 0 00-1.175 0l-2.8 2.034c-.784.57-1.838-.197-1.539-1.118l1.07-3.292a1 1 0 00-.364-1.118L2.98 8.72c-.783-.57-.38-1.81.588-1.81h3.461a1 1 0 00.951-.69l1.07-3.292z">
                                                </path>
                                            </svg>
                                            <svg viewBox="0 0 20 20" fill="currentColor" width="18" height="18"
                                                class="star-icon">
                                                <path
                                                    d="M9.049 2.927c.3-.921 1.603-.921 1.902 0l1.07 3.292a1 1 0 00.95.69h3.462c.969 0 1.371 1.24.588 1.81l-2.8 2.034a1 1 0 00-.364 1.118l1.07 3.292c.3.921-.755 1.688-1.54 1.118l-2.8-2.034a1 1 0 00-1.175 0l-2.8 2.034c-.784.57-1.838-.197-1.539-1.118l1.07-3.292a1 1 0 00-.364-1.118L2.98 8.72c-.783-.57-.38-1.81.588-1.81h3.461a1 1 0 00.951-.69l1.07-3.292z">
                                                </path>
                                            </svg>
                                            <svg viewBox="0 0 20 20" fill="currentColor" width="18" height="18"
                                                class="star-icon">
                                                <path
                                                    d="M9.049 2.927c.3-.921 1.603-.921 1.902 0l1.07 3.292a1 1 0 00.95.69h3.462c.969 0 1.371 1.24.588 1.81l-2.8 2.034a1 1 0 00-.364 1.118l1.07 3.292c.3.921-.755 1.688-1.54 1.118l-2.8-2.034a1 1 0 00-1.175 0l-2.8 2.034c-.784.57-1.838-.197-1.539-1.118l1.07-3.292a1 1 0 00-.364-1.118L2.98 8.72c-.783-.57-.38-1.81.588-1.81h3.461a1 1 0 00.951-.69l1.07-3.292z">
                                                </path>
                                            </svg>
                                        </div>
                                        <p class="review-text">“Kirkpatrick Racing is the real deal. They tuned up my
                                            dirt bike before the season and it ran better than when I bought it.
                                            Family-owned shops like this are rare to find anymore.”</p>
                                    </div>
                                    <div class="review-footer">
                                        <div class="author-name">Jake Denton</div>
                                        <div class="author-role">Amateur Motocross Racer - Texas</div>
                                    </div>
                                </div>
                            </div>

                            <!-- Card 4 (Right Partial) -->
                            <div class="swiper-slide">
                                <div class="review-card">
                                    <div class="review-content">
                                        <div class="stars mb-3">
                                            <svg viewBox="0 0 20 20" fill="currentColor" width="18" height="18"
                                                class="star-icon">
                                                <path
                                                    d="M9.049 2.927c.3-.921 1.603-.921 1.902 0l1.07 3.292a1 1 0 00.95.69h3.462c.969 0 1.371 1.24.588 1.81l-2.8 2.034a1 1 0 00-.364 1.118l1.07 3.292c.3.921-.755 1.688-1.54 1.118l-2.8-2.034a1 1 0 00-1.175 0l-2.8 2.034c-.784.57-1.838-.197-1.539-1.118l1.07-3.292a1 1 0 00-.364-1.118L2.98 8.72c-.783-.57-.38-1.81.588-1.81h3.461a1 1 0 00.951-.69l1.07-3.292z">
                                                </path>
                                            </svg>
                                            <svg viewBox="0 0 20 20" fill="currentColor" width="18" height="18"
                                                class="star-icon">
                                                <path
                                                    d="M9.049 2.927c.3-.921 1.603-.921 1.902 0l1.07 3.292a1 1 0 00.95.69h3.462c.969 0 1.371 1.24.588 1.81l-2.8 2.034a1 1 0 00-.364 1.118l1.07 3.292c.3.921-.755 1.688-1.54 1.118l-2.8-2.034a1 1 0 00-1.175 0l-2.8 2.034c-.784.57-1.838-.197-1.539-1.118l1.07-3.292a1 1 0 00-.364-1.118L2.98 8.72c-.783-.57-.38-1.81.588-1.81h3.461a1 1 0 00.951-.69l1.07-3.292z">
                                                </path>
                                            </svg>
                                            <svg viewBox="0 0 20 20" fill="currentColor" width="18" height="18"
                                                class="star-icon">
                                                <path
                                                    d="M9.049 2.927c.3-.921 1.603-.921 1.902 0l1.07 3.292a1 1 0 00.95.69h3.462c.969 0 1.371 1.24.588 1.81l-2.8 2.034a1 1 0 00-.364 1.118l1.07 3.292c.3.921-.755 1.688-1.54 1.118l-2.8-2.034a1 1 0 00-1.175 0l-2.8 2.034c-.784.57-1.838-.197-1.539-1.118l1.07-3.292a1 1 0 00-.364-1.118L2.98 8.72c-.783-.57-.38-1.81.588-1.81h3.461a1 1 0 00.951-.69l1.07-3.292z">
                                                </path>
                                            </svg>
                                            <svg viewBox="0 0 20 20" fill="currentColor" width="18" height="18"
                                                class="star-icon">
                                                <path
                                                    d="M9.049 2.927c.3-.921 1.603-.921 1.902 0l1.07 3.292a1 1 0 00.95.69h3.462c.969 0 1.371 1.24.588 1.81l-2.8 2.034a1 1 0 00-.364 1.118l1.07 3.292c.3.921-.755 1.688-1.54 1.118l-2.8-2.034a1 1 0 00-1.175 0l-2.8 2.034c-.784.57-1.838-.197-1.539-1.118l1.07-3.292a1 1 0 00-.364-1.118L2.98 8.72c-.783-.57-.38-1.81.588-1.81h3.461a1 1 0 00.951-.69l1.07-3.292z">
                                                </path>
                                            </svg>
                                            <svg viewBox="0 0 20 20" fill="currentColor" width="18" height="18"
                                                class="star-icon">
                                                <path
                                                    d="M9.049 2.927c.3-.921 1.603-.921 1.902 0l1.07 3.292a1 1 0 00.95.69h3.462c.969 0 1.371 1.24.588 1.81l-2.8 2.034a1 1 0 00-.364 1.118l1.07 3.292c.3.921-.755 1.688-1.54 1.118l-2.8-2.034a1 1 0 00-1.175 0l-2.8 2.034c-.784.57-1.838-.197-1.539-1.118l1.07-3.292a1 1 0 00-.364-1.118L2.98 8.72c-.783-.57-.38-1.81.588-1.81h3.461a1 1 0 00.951-.69l1.07-3.292z">
                                                </path>
                                            </svg>
                                        </div>
                                        <p class="review-text">"Amazing suspension work. Fair price and excellent
                                            service from start to finish. Highly recommend."</p>
                                    </div>
                                    <div class="review-footer">
                                        <div class="author-name">David L.</div>
                                        <div class="author-role">Pro Rider</div>
                                    </div>
                                </div>
                            </div>

                            <!-- Card 5 (Hidden/Edge) -->
                            <div class="swiper-slide">
                                <div class="review-card">
                                    <div class="review-content">
                                        <div class="stars mb-3">
                                            <svg viewBox="0 0 20 20" fill="currentColor" width="18" height="18"
                                                class="star-icon">
                                                <path
                                                    d="M9.049 2.927c.3-.921 1.603-.921 1.902 0l1.07 3.292a1 1 0 00.95.69h3.462c.969 0 1.371 1.24.588 1.81l-2.8 2.034a1 1 0 00-.364 1.118l1.07 3.292c.3.921-.755 1.688-1.54 1.118l-2.8-2.034a1 1 0 00-1.175 0l-2.8 2.034c-.784.57-1.838-.197-1.539-1.118l1.07-3.292a1 1 0 00-.364-1.118L2.98 8.72c-.783-.57-.38-1.81.588-1.81h3.461a1 1 0 00.951-.69l1.07-3.292z">
                                                </path>
                                            </svg>
                                            <svg viewBox="0 0 20 20" fill="currentColor" width="18" height="18"
                                                class="star-icon">
                                                <path
                                                    d="M9.049 2.927c.3-.921 1.603-.921 1.902 0l1.07 3.292a1 1 0 00.95.69h3.462c.969 0 1.371 1.24.588 1.81l-2.8 2.034a1 1 0 00-.364 1.118l1.07 3.292c.3.921-.755 1.688-1.54 1.118l-2.8-2.034a1 1 0 00-1.175 0l-2.8 2.034c-.784.57-1.838-.197-1.539-1.118l1.07-3.292a1 1 0 00-.364-1.118L2.98 8.72c-.783-.57-.38-1.81.588-1.81h3.461a1 1 0 00.951-.69l1.07-3.292z">
                                                </path>
                                            </svg>
                                            <svg viewBox="0 0 20 20" fill="currentColor" width="18" height="18"
                                                class="star-icon">
                                                <path
                                                    d="M9.049 2.927c.3-.921 1.603-.921 1.902 0l1.07 3.292a1 1 0 00.95.69h3.462c.969 0 1.371 1.24.588 1.81l-2.8 2.034a1 1 0 00-.364 1.118l1.07 3.292c.3.921-.755 1.688-1.54 1.118l-2.8-2.034a1 1 0 00-1.175 0l-2.8 2.034c-.784.57-1.838-.197-1.539-1.118l1.07-3.292a1 1 0 00-.364-1.118L2.98 8.72c-.783-.57-.38-1.81.588-1.81h3.461a1 1 0 00.951-.69l1.07-3.292z">
                                                </path>
                                            </svg>
                                            <svg viewBox="0 0 20 20" fill="currentColor" width="18" height="18"
                                                class="star-icon">
                                                <path
                                                    d="M9.049 2.927c.3-.921 1.603-.921 1.902 0l1.07 3.292a1 1 0 00.95.69h3.462c.969 0 1.371 1.24.588 1.81l-2.8 2.034a1 1 0 00-.364 1.118l1.07 3.292c.3.921-.755 1.688-1.54 1.118l-2.8-2.034a1 1 0 00-1.175 0l-2.8 2.034c-.784.57-1.838-.197-1.539-1.118l1.07-3.292a1 1 0 00-.364-1.118L2.98 8.72c-.783-.57-.38-1.81.588-1.81h3.461a1 1 0 00.951-.69l1.07-3.292z">
                                                </path>
                                            </svg>
                                            <svg viewBox="0 0 20 20" fill="currentColor" width="18" height="18"
                                                class="star-icon">
                                                <path
                                                    d="M9.049 2.927c.3-.921 1.603-.921 1.902 0l1.07 3.292a1 1 0 00.95.69h3.462c.969 0 1.371 1.24.588 1.81l-2.8 2.034a1 1 0 00-.364 1.118l1.07 3.292c.3.921-.755 1.688-1.54 1.118l-2.8-2.034a1 1 0 00-1.175 0l-2.8 2.034c-.784.57-1.838-.197-1.539-1.118l1.07-3.292a1 1 0 00-.364-1.118L2.98 8.72c-.783-.57-.38-1.81.588-1.81h3.461a1 1 0 00.951-.69l1.07-3.292z">
                                                </path>
                                            </svg>
                                        </div>
                                        <p class="review-text">"Service exceeded my expectations. Will definitely return
                                            for next season's tune-up."</p>
                                    </div>
                                    <div class="review-footer">
                                        <div class="author-name">Chris P.</div>
                                        <div class="author-role">Enthusiast</div>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>

                    <div class="nav-controls d-flex justify-content-center align-items-center mt-4">
                        <button class="btn btn-slider btn-prev mx-2 d-flex justify-content-center align-items-center">
                            <svg width="20" height="20" viewBox="0 0 24 24" fill="none" stroke="currentColor"
                                stroke-width="1.5" stroke-linecap="round" stroke-linejoin="round">
                                <line x1="19" y1="12" x2="5" y2="12"></line>
                                <polyline points="12 19 5 12 12 5"></polyline>
                            </svg>
                        </button>
                        <button class="btn btn-slider btn-next mx-2 d-flex justify-content-center align-items-center">
                            <svg width="20" height="20" viewBox="0 0 24 24" fill="none" stroke="currentColor"
                                stroke-width="1.5" stroke-linecap="round" stroke-linejoin="round">
                                <line x1="5" y1="12" x2="19" y2="12"></line>
                                <polyline points="12 5 19 12 12 19"></polyline>
                            </svg>
                        </button>
                    </div>

                </div>
            </div>
        </div>
    </section>

    <section class="built-sec">
        <div class="container-fluid p-0">
            <div class="row">
                <div class="col-md-9">
                    <div class="built-flex">
                        <figure class="built-img img-shine-box" data-aos="zoom-in" data-duration="3000">
                            <span class="img-shine-layer"></span>
                            <img src="{{ asset('front/images/built1.webp') }}" class="img-fluid" alt="">
                        </figure>
                        <figure class="built-img img-shine-box" data-aos="zoom-in" data-duration="3000">
                            <span class="img-shine-layer"></span>
                            <img src="{{ asset('front/images/built2.webp') }}" class="img-fluid" alt="">
                        </figure>
                    </div>
                </div>
            </div>
            <div class="row justify-content-center text-center" data-aos="fade-up" data-duration="3000">
                <div class="col-md-6">
                    <h2 class="mainHead">Built For Riders</h2>
                </div>
            </div>
            <div class="row justify-content-end">
                <div class="col-md-9">
                    <div class="built-flex">
                        <figure class="built-img img-shine-box" data-aos="zoom-in" data-duration="3000">
                            <span class="img-shine-layer"></span>
                            <img src="{{ asset('front/images/built3.webp') }}" class="img-fluid" alt="">
                        </figure>
                        <figure class="built-img img-shine-box" data-aos="zoom-in" data-duration="3000">
                            <span class="img-shine-layer"></span>
                            <img src="{{ asset('front/images/built4.webp') }}" class="img-fluid" alt="">
                        </figure>
                    </div>
                </div>
            </div>
        </div>
    </section>

    <section class="rt-section">
        <div class="container rt-container">
            <div class="row rt-row" data-aos="fade-up" data-duration="3000">
                <div class="col-lg-5 rt-col-left d-flex flex-column justify-content-between mb-5 mb-lg-0">
                    <div class="rt-intro-wrapper">
                        <h2 class="mainHead">Growing with the <br>
                            Riding Community</h2>
                        <p class="rt-intro">
                            Kirkpatrick Racing continues expanding<br class="d-none d-xl-block">
                            its inventory, partnerships and rider<br class="d-none d-xl-block">
                            resources to become a trusted destination<br class="d-none d-xl-block">
                            for electric bikes, accessories, service and<br class="d-none d-xl-block">
                            motorsports support.
                        </p>
                    </div>
                </div>
                <div class="col-lg-7 rt-col-right">
                    <h3 class="rt-title">Roadmap Timeline</h3>
                    <div class="rt-list">
                        <div class="rt-item">
                            <div class="row">
                                <div class="col-4 col-md-3">
                                    <div class="rt-phase-num">PHASE 01</div>
                                </div>
                                <div class="col-8 col-md-9">
                                    <div class="rt-phase-content">
                                        <h4 class="rt-phase-title">Dealer Launch</h4>
                                        <p class="rt-phase-desc">Official electric bike dealership goes live</p>
                                    </div>
                                </div>
                            </div>
                        </div>
                        <div class="rt-item">
                            <div class="row">
                                <div class="col-4 col-md-3">
                                    <div class="rt-phase-num">PHASE 02</div>
                                </div>
                                <div class="col-8 col-md-9">
                                    <div class="rt-phase-content">
                                        <h4 class="rt-phase-title">Ecommerce Expansion</h4>
                                        <p class="rt-phase-desc">Full online store with parts and accessories</p>
                                    </div>
                                </div>
                            </div>
                        </div>
                        <div class="rt-item">
                            <div class="row">
                                <div class="col-4 col-md-3">
                                    <div class="rt-phase-num">PHASE 03</div>
                                </div>
                                <div class="col-8 col-md-9">
                                    <div class="rt-phase-content">
                                        <h4 class="rt-phase-title">New Brands Added</h4>
                                        <p class="rt-phase-desc">Multi-brand catalog covering more segments</p>
                                    </div>
                                </div>
                            </div>
                        </div>
                        <div class="rt-item">
                            <div class="row">
                                <div class="col-4 col-md-3">
                                    <div class="rt-phase-num">PHASE 04</div>
                                </div>
                                <div class="col-8 col-md-9">
                                    <div class="rt-phase-content">
                                        <h4 class="rt-phase-title">Accessories Catalog</h4>
                                        <p class="rt-phase-desc">Rides, races and rider clinics for all skill levels</p>
                                    </div>
                                </div>
                            </div>
                        </div>
                        <div class="rt-item">
                            <div class="row">
                                <div class="col-4 col-md-3">
                                    <div class="rt-phase-num">PHASE 05</div>
                                </div>
                                <div class="col-8 col-md-9">
                                    <div class="rt-phase-content">
                                        <h4 class="rt-phase-title">Community Events</h4>
                                        <p class="rt-phase-desc">Rides, races and rider clinics for all skill levels</p>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
            <img src="{{ asset('front/images/tireimg.webp') }}" class="img-fluid tire-img left" alt="">
        </div>
    </section>

    <section class="ready-sec">
        <div class="container">
            <div class="row justify-content-center text-center">
                <div class="col-md-7">
                    <div class="ready-flex">
                        <div class="ready-content" data-aos="fade-up" data-duration="3000">
                            <h2 class="mainHead">Ready To Ride?</h2>
                            <p>Shop electric bikes, schedule service or speak with our team today.</p>
                        </div>
                        <div class="btn-group" data-aos="fade-up" data-duration="3000">
                            <a href="{{ route('front.shop') }}" class="themeBtn">Shop Bikes</a>
                            <a href="{{ route('front.services') }}" class="themeBtn">Book Service</a>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </section>


    @endsection