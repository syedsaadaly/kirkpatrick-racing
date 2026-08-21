@extends('front.include.app')

@section('title','Kirkpatrick Racing')
@section('bodyClass','inner-body')
@section('content')



    <section class="inner-banner">
        <div class="container">
            <div class="row">
                <div class="col-md-7">
                    <div class="inner-content first-banner">
                        <h3>Performance Redefined</h3>
                        <h2>Ride the Future</h2>
                        <p>Competition-grade electric performance for the next generation of
                            riders. Engineered for speed, built for the track.</p>
                        <div class="btn-group">
                            <a href="{{ route('front.shop') }}" class="themeBtn">Shop Inventory</a>
                            <a href="#electric-bike-fleet" class="themeBtn">Learn More</a>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </section>

    <section class="electric-icon">
        <div class="container">
            <div class="row">
                <div class="col-md-12">
                    <ul class="electric-listing">
                        <li><img src="{{ asset('front/images/icon1.png') }}" class="img-fluid" alt="">Family Owned</li>
                        <li><img src="{{ asset('front/images/icon2.png') }}" class="img-fluid" alt="">Certified Service</li>
                        <li><img src="{{ asset('front/images/icon3.png') }}" class="img-fluid" alt="">Motocross Focused</li>
                        <li><img src="{{ asset('front/images/icon4.png') }}" class="img-fluid" alt="">Dealer Network</li>
                    </ul>
                </div>
            </div>
        </div>
    </section>

    <section class="electric-bike-fleet" id="electric-bike-fleet">
        <div class="container">
            <div class="row align-items-end mb-5">
                <div class="col-md-8">
                    <h2 class="electric-bike-fleet__title">Electric Fleet</h2>
                    <p class="electric-bike-fleet__subtitle">The cutting edge of dirt performance.</p>
                </div>
                <div class="col-md-4 text-md-right">
                    <a href="{{ route('front.shop') }}" class="electric-bike-fleet__link">
                        View All Models <i class="fas fa-arrow-right ml-1"></i>
                    </a>
                </div>
            </div>

            <div class="row">
                <!-- Card 1 -->
                <div class="col-md-4 mb-4">
                    <div class="electric-bike-fleet__card">
                        <div class="electric-bike-fleet__image-wrapper">
                            <div class="electric-bike-fleet__badge">New Release</div>
                            <img src="{{asset('front/images/fleet1.png') }}" alt="MX-5000 Electric"
                                class="electric-bike-fleet__image img-fluid">
                        </div>
                        <div class="electric-bike-fleet__content">
                            <h3 class="electric-bike-fleet__card-title">MX-5000 Electric</h3>
                            <p class="electric-bike-fleet__card-desc">Competition dirt bike for serious racers seeking
                                zero-emissions power.</p>

                            <div class="electric-bike-fleet__stats d-flex justify-content-between">
                                <div class="electric-bike-fleet__stat-box w-100 mr-2">
                                    <span class="electric-bike-fleet__stat-label">POWER</span>
                                    <span class="electric-bike-fleet__stat-value">40 hp</span>
                                </div>
                                <div class="electric-bike-fleet__stat-box w-100 ml-2">
                                    <span class="electric-bike-fleet__stat-label">RANGE</span>
                                    <span class="electric-bike-fleet__stat-value">80 mi</span>
                                </div>
                            </div>

                            <ul class="electric-bike-fleet__features">
                                <li><img src="{{ asset('front/images/fleetsub1.png') }}" class="img-fluid" alt=""> Adjustable suspension</li>
                                <li><img src="{{ asset('front/images/fleetsub2.png') }}" class="img-fluid" alt=""> Regenerative braking</li>
                            </ul>

                            <a href="{{ route('front.shop') }}" class="electric-bike-fleet__btn electric-bike-fleet__btn--primary">
                                Pre-order Now
                            </a>
                        </div>
                    </div>
                </div>

                <!-- Card 2 -->
                <div class="col-md-4 mb-4">
                    <div class="electric-bike-fleet__card">
                        <div class="electric-bike-fleet__image-wrapper">
                            <img src="{{ asset('front/images/fleet2.png') }}" alt="Trail-X Electric"
                                class="electric-bike-fleet__image img-fluid">
                        </div>
                        <div class="electric-bike-fleet__content">
                            <h3 class="electric-bike-fleet__card-title">Trail-X Electric</h3>
                            <p class="electric-bike-fleet__card-desc">Built for long-range trail adventure riding in the
                                toughest environments.</p>

                            <div class="electric-bike-fleet__stats d-flex justify-content-between">
                                <div class="electric-bike-fleet__stat-box w-100 mr-2">
                                    <span class="electric-bike-fleet__stat-label">POWER</span>
                                    <span class="electric-bike-fleet__stat-value">45 hp</span>
                                </div>
                                <div class="electric-bike-fleet__stat-box w-100 ml-2">
                                    <span class="electric-bike-fleet__stat-label">RANGE</span>
                                    <span class="electric-bike-fleet__stat-value">120 mi</span>
                                </div>
                            </div>

                            <ul class="electric-bike-fleet__features">
                                <li><img src="{{ asset('front/images/fleetsub3.png') }}" class="img-fluid" alt=""> Extended battery</li>
                                <li><img src="{{ asset('front/images/fleetsub4.png') }}" class="img-fluid" alt=""> All-terrain tyres</li>
                            </ul>

                            <a href="{{ route('front.shop') }}" class="electric-bike-fleet__btn electric-bike-fleet__btn--outline">
                                Pre-order Now
                            </a>
                        </div>
                    </div>
                </div>

                <!-- Card 3 -->
                <div class="col-md-4 mb-4">
                    <div class="electric-bike-fleet__card">
                        <div class="electric-bike-fleet__image-wrapper">
                            <img src="{{asset('front/images/fleet3.png') }}" alt="Junior EV-20"
                                class="electric-bike-fleet__image img-fluid">
                        </div>
                        <div class="electric-bike-fleet__content">
                            <h3 class="electric-bike-fleet__card-title">Junior EV-20</h3>
                            <p class="electric-bike-fleet__card-desc">Safe, fun electric riding for young riders just
                                starting their journey.</p>

                            <div class="electric-bike-fleet__stats d-flex justify-content-between">
                                <div class="electric-bike-fleet__stat-box w-100 mr-2">
                                    <span class="electric-bike-fleet__stat-label">WEIGHT</span>
                                    <span class="electric-bike-fleet__stat-value">48 lb</span>
                                </div>
                                <div class="electric-bike-fleet__stat-box w-100 ml-2">
                                    <span class="electric-bike-fleet__stat-label">SPEED</span>
                                    <span class="electric-bike-fleet__stat-value">15 mph</span>
                                </div>
                            </div>

                            <ul class="electric-bike-fleet__features">
                                <li><img src="{{asset('front/images/fleetsub5.png') }}" class="img-fluid" alt=""> Speed limiter</li>
                                <li><img src="{{asset('front/images/fleetsub6.png') }}" class="img-fluid" alt=""> Zero maintenance</li>
                            </ul>

                            <a href="{{ route('front.shop') }}" class="electric-bike-fleet__btn electric-bike-fleet__btn--outline">
                                Pre-order Now
                            </a>
                        </div>
                    </div>
                </div>

            </div>
        </div>
    </section>

    <section id="electric-bike-specifications">
        <div class="container">
            <div class="specs-card">
                <h2 class="specs-title text-center">Technical Specifications</h2>
                <div class="table-responsive">
                    <table class="specs-table">
                        <thead>
                            <tr>
                                <th class="col-feature">Feature</th>
                                <th class="col-mx">MX-5000</th>
                                <th class="col-trail">Trail-X</th>
                                <th class="col-junior">Junior EV-20</th>
                            </tr>
                        </thead>
                        <tbody>
                            <tr>
                                <td class="td-feature">Battery Capacity</td>
                                <td class="td-data">5.2 kWh</td>
                                <td class="td-data">7.8 kWh</td>
                                <td class="td-data">1.2 kWh</td>
                            </tr>
                            <tr>
                                <td class="td-feature">Motor Type</td>
                                <td class="td-data">Permanent Magnet</td>
                                <td class="td-data">High Torque Hub</td>
                                <td class="td-data">Brushless DC</td>
                            </tr>
                            <tr>
                                <td class="td-feature">Charge Time (240V)</td>
                                <td class="td-data">2.5 Hours</td>
                                <td class="td-data">3.5 Hours</td>
                                <td class="td-data">1 Hour</td>
                            </tr>
                            <tr>
                                <td class="td-feature">Frame Construction</td>
                                <td class="td-data">Carbon / Alloy Hybrid</td>
                                <td class="td-data">Reinforced Tubular Steel</td>
                                <td class="td-data">Lightweight Aluminum</td>
                            </tr>
                        </tbody>
                    </table>
                </div>
            </div>
        </div>
    </section>

    <section id="electric-bike-dealer-service" class="electric-bike-dealer-service">
        <div class="container">
            <div class="row align-items-center">
                <div class="col-lg-6 image-col position-relative">
                    <div class="main-image-wrapper">
                        <img src="{{ asset('front/images/factory1.png') }}" alt="Electric Bike Rider" class="img-fluid main-image">
                    </div>
                </div>
                <div class="col-lg-5  content-col">
                    <span class="overline">Factory Support</span>
                    <h2>Authorized<br>Dealer &<br>Service</h2>
                    <p>Kirkpatrick Racing is expanding into electric bike sales through a trusted dealer partnership,
                        bringing riders cutting-edge technology and reliable support you can count on.</p>
                    <ul class="features-list">
                        <li>
                            <img src="{{ asset('front/images/factorysub1.png') }}" class="img-fluid" alt="">
                            Full Warranty Support
                        </li>
                        <li>
                            <img src="{{ asset('front/images/factorysub2.png') }}" class="img-fluid" alt="">
                            Genuine Spare Parts
                        </li>
                        <li>
                            <img src="{{ asset('front/images/factorysub3.png') }}" class="img-fluid" alt="">
                            Financing Available
                        </li>
                    </ul>
                    <a href="{{ route('front.contact') }}" class="btn-black">Book Service Quote</a>
                </div>
            </div>
        </div>
        <img src="{{ asset('front/images/tireimg.webp') }}" class="img-fluid tire-img left" alt="">
    </section>

    <section class="ready-sec electric-ready">
        <div class="container">
            <div class="row justify-content-center text-center">
                <div class="col-md-7">
                    <div class="ready-flex">
                        <div class="ready-content" data-aos="fade-up" data-duration="3000">
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