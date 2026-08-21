@extends('front.include.app')
@section('title','Kirkpatrick Racing')
@section('bodyClass','inner-body bg-black-color')
@section('content')




    <section class="inner-banner">
        <div class="container">
            <div class="row">
                <div class="col-md-7">
                    <div class="inner-content">
                        <h3>CERTIFIED MECHANICAL TEAM</h3>
                        <h2>PROFESSIONAL<span>RACING SERVICE</span></h2>
                        <p>Expert maintenance for every type of rider. From track-day prep to
                            professional engine rebuilds, our family-owned dealership ensures your
                            machine performs at its absolute peak.</p>
                        <div class="btn-group">
                            <a href="{{ route('front.contact') }}" class="themeBtn">GET A QUOTE</a>
                            <a href="#services-page-packages" class="themeBtn">EXPLORE SERVICES</a>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </section>

    <section class="services-page-ticker">
        <div class="container-fluid p-0">
            <div class="services-page-ticker-wrapper">
                <div class="services-page-ticker-content">
                    <span class="services-page-ticker-item">FACTORY CERTIFIED TECHNICIANS</span>
                    <span class="services-page-ticker-item">FACTORY CERTIFIED TECHNICIANS</span>
                    <span class="services-page-ticker-item">FULL ENGINE DIAGNOSTICS</span>
                    <span class="services-page-ticker-item">RACING SUSPENSION TUNING</span>
                    <span class="services-page-ticker-item">FACTORY CERTIFIED TECHNICIANS</span>
                    <span class="services-page-ticker-item">FACTORY CERTIFIED TECHNICIANS</span>
                    <span class="services-page-ticker-item">FULL ENGINE DIAGNOSTICS</span>
                    <span class="services-page-ticker-item">RACING SUSPENSION TUNING</span>
                </div>
            </div>
        </div>
    </section>

    <section class="services-page">
        <div class="container">
            <div class="row mb-4">
                <div class="col-lg-7 mb-4 mb-lg-0">
                    <div class="services-page-dirt-bike h-100">
                        <div class="row no-gutters h-100">
                            <div
                                class="col-md-6 order-2 order-md-1 d-flex flex-column p-4 p-xl-5 services-page-card-content">
                                <div>
                                    <h2 class="services-page-title">DIRT BIKE<br>SERVICE</h2>
                                    <ul class="list-unstyled services-page-dirt-bike-list">
                                        <li>
                                            <svg width="18" height="18" viewBox="0 0 24 24" fill="none" stroke="#d81b1b"
                                                stroke-width="2" stroke-linecap="round" stroke-linejoin="round">
                                                <circle cx="12" cy="12" r="10"></circle>
                                                <polyline points="8 12 11 15 16 9"></polyline>
                                            </svg>
                                            Precision Engine Tuning
                                        </li>
                                        <li>
                                            <svg width="18" height="18" viewBox="0 0 24 24" fill="none" stroke="#d81b1b"
                                                stroke-width="2" stroke-linecap="round" stroke-linejoin="round">
                                                <circle cx="12" cy="12" r="10"></circle>
                                                <polyline points="8 12 11 15 16 9"></polyline>
                                            </svg>
                                            Race-Ready Track Preparation
                                        </li>
                                        <li>
                                            <svg width="18" height="18" viewBox="0 0 24 24" fill="none" stroke="#d81b1b"
                                                stroke-width="2" stroke-linecap="round" stroke-linejoin="round">
                                                <circle cx="12" cy="12" r="10"></circle>
                                                <polyline points="8 12 11 15 16 9"></polyline>
                                            </svg>
                                            Valve Clearances & Top Ends
                                        </li>
                                    </ul>
                                </div>
                                <div class="d-flex justify-content-between align-items-center mt-auto pt-4">
                                    <span class="services-page-dirt-bike-price">Starting at $149</span>
                                    <a href="{{ route('front.contact') }}" class="services-page-dirt-bike-arrow">
                                        <!-- <svg width="20" height="20" viewBox="0 0 24 24" fill="none" stroke="#ffffff"
                                            stroke-width="2" stroke-linecap="round" stroke-linejoin="round">
                                            <line x1="5" y1="12" x2="19" y2="12"></line>
                                            <polyline points="12 5 19 12 12 19"></polyline>
                                        </svg> -->
                                    </a>
                                </div>
                            </div>
                            <div class="col-md-6 order-1 order-md-2">
                                <figure class="bike-subimages">
                                    <img src="{{ asset('front/images/bike1.png') }}" alt="Dirt Bike Service"
                                        class="w-100 h-100 services-page-img-cover">
                                    <a href="{{ route('front.contact') }}"><svg width="16" height="16" viewBox="0 0 24 24" fill="none" stroke="#fff"
                                            stroke-width="3" stroke-linecap="round" stroke-linejoin="round">
                                            <line x1="5" y1="12" x2="19" y2="12"></line>
                                            <polyline points="12 5 19 12 12 19"></polyline>
                                        </svg></a>
                                </figure>
                            </div>
                        </div>
                    </div>
                </div>
                <div class="col-lg-5">
                    <div class="services-page-atv d-flex flex-column p-4 p-xl-5 h-100">
                        <div class="services-page-atv-subtitle">UTILITY & SPORT</div>
                        <h2 class="services-page-title mb-3" style="font-size: 22px;">ATV & FOUR WHEELER</h2>
                        <p class="services-page-text mb-4">Complete drivetrain repairs and deep-dive inspections for
                            utility and sport quads.</p>
                        <div class="flex-grow-1 mb-4">
                            <img src="{{ asset('front/images/bike2.png') }}" alt="ATV Repair" class="w-100 h-100 services-page-img-cover">
                        </div>
                        <a href="{{ route('front.contact') }}" class="services-page-atv-btn">BOOK INSPECTION</a>
                    </div>
                </div>
            </div>
            <div class="row">
                <div class="col-12">
                    <div class="services-page-side-by-side">
                        <div class="row no-gutters h-100">
                            <div class="col-lg-6 order-1">
                                <img src="{{ asset('front/images/bike3.png') }}" alt="Side-by-side Repair"
                                    class="w-100 h-100 services-page-img-cover">
                            </div>
                            <div
                                class="col-lg-6 order-2 d-flex flex-column justify-content-center p-4 p-xl-5 services-page-card-content">
                                <h2 class="services-page-title mb-4" style="font-size: 48px;">
                                    SIDE-BY-SIDE<br>PERFORMANCE REPAIR</h2>
                                <div class="row mb-2">
                                    <div class="col-sm-6 mb-4">
                                        <div class="services-page-side-by-side-feature">
                                            <h4>LIFT KITS</h4>
                                            <p>Professional suspension geometry correction and lift installation.</p>
                                        </div>
                                    </div>
                                    <div class="col-sm-6 mb-4">
                                        <div class="services-page-side-by-side-feature">
                                            <h4>CUSTOM ARMOR</h4>
                                            <p>Cage upgrades, skid plates, and lighting installations.</p>
                                        </div>
                                    </div>
                                </div>
                                <div>
                                    <a href="{{ route('front.contact') }}" class="services-page-side-by-side-link">
                                        REQUEST SERVICE QUOTE
                                        <svg width="16" height="16" viewBox="0 0 24 24" fill="none" stroke="#CC0000"
                                            stroke-width="3" stroke-linecap="round" stroke-linejoin="round">
                                            <line x1="5" y1="12" x2="19" y2="12"></line>
                                            <polyline points="12 5 19 12 12 19"></polyline>
                                        </svg>
                                    </a>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </section>

    <section class="services-page-packages" id="services-page-packages">
        <div class="container">
            <div class="row">
                <div class="col-12 text-center">
                    <h2 class="services-page-packages__title">SERVICE PACKAGES</h2>
                    <p class="services-page-packages__subtitle">Standardized maintenance tiers built for consistency and
                        performance.</p>
                </div>
            </div>
            <div class="row align-items-stretch services-page-packages__cards-wrapper">

                <!-- Essential Card -->
                <div class="col-lg-4 col-md-12 mb-4 services-page-packages-col">
                    <div class="services-page-packages__card h-100 d-flex flex-column">
                        <div class="services-page-packages__bg-icon">
                            <svg width="45" height="65" viewBox="0 0 24 36" fill="none"
                                xmlns="http://www.w3.org/2000/svg">
                                <path d="M13.5 0L1.5 20H11.5L9.5 36L23.5 14H14.5L13.5 0Z" fill="#1e1e1e" />
                            </svg>
                        </div>
                        <div class="services-page-packages__card-content">
                            <div class="services-page-packages__category">ESSENTIAL</div>
                            <h3 class="services-page-packages__card-title">QUICK-TUNE</h3>
                            <div class="services-page-packages__price">
                                $89 <span class="services-page-packages__price-suffix">+ Parts</span>
                            </div>
                            <ul class="services-page-packages__list">
                                <li>Oil & Filter Change</li>
                                <li>Chain Cleaning & Tension</li>
                                <li>Brake Inspection</li>
                                <li>Full Chassis Lubrication</li>
                            </ul>
                        </div>
                        <div class="services-page-packages__card-footer mt-auto">
                            <a href="{{ route('front.contact') }}"
                                class="btn services-page-packages__btn services-page-packages__btn--outline">SCHEDULE</a>
                        </div>
                    </div>
                </div>

                <!-- Comprehensive Card -->
                <div class="col-lg-4 col-md-12 mb-4 services-page-packages-col">
                    <div
                        class="services-page-packages__card services-page-packages__card--highlighted h-100 d-flex flex-column">
                        <div class="services-page-packages__badge">MOST POPULAR</div>
                        <div class="services-page-packages__card-content">
                            <div class="services-page-packages__category">COMPREHENSIVE</div>
                            <h3 class="services-page-packages__card-title">THE WORKS</h3>
                            <div class="services-page-packages__price">
                                $249 <span class="services-page-packages__price-suffix">+ Parts</span>
                            </div>
                            <ul class="services-page-packages__list">
                                <li>Standard Quick-Tune Plus:</li>
                                <li>Valve Clearance Check</li>
                                <li>Coolant System Flush</li>
                                <li>Suspension Health Check</li>
                                <li>Diagnostic Scan</li>
                            </ul>
                        </div>
                        <div class="services-page-packages__card-footer mt-auto">
                            <a href="{{ route('front.contact') }}"
                                class="btn services-page-packages__btn services-page-packages__btn--solid">SCHEDULE</a>
                        </div>
                    </div>
                </div>

                <!-- Performance Card -->
                <div class="col-lg-4 col-md-12 mb-4 services-page-packages-col">
                    <div class="services-page-packages__card h-100 d-flex flex-column">
                        <div class="services-page-packages__card-content">
                            <div class="services-page-packages__category">PERFORMANCE</div>
                            <h3 class="services-page-packages__card-title">RACE PREP</h3>
                            <div class="services-page-packages__price">
                                $499 <span class="services-page-packages__price-suffix">+ Parts</span>
                            </div>
                            <ul class="services-page-packages__list">
                                <li>Full Dyno Tuning</li>
                                <li>Suspension Re-valving</li>
                                <li>Linkage Overhaul</li>
                                <li>Engine Top-End Review</li>
                                <li>Race Map Installation</li>
                            </ul>
                        </div>
                        <div class="services-page-packages__card-footer mt-auto">
                            <a href="{{ route('front.contact') }}"
                                class="btn services-page-packages__btn services-page-packages__btn--outline">CONTACT
                                EXPERT</a>
                        </div>
                    </div>
                </div>

            </div>
        </div>
    </section>

    <section class="services-page-facility">
        <div class="container">
            <div class="row justify-content-center">
                <div class="col-lg-8 text-center">
                    <h2>INSIDE OUR HIGH-TECH FACILITY</h2>
                    <p class="facility-desc">We believe in transparency. Our workshop is equipped with state-of-the-art
                        diagnostic tools, dynos, and clean rooms for precision engine building.</p>
                </div>
            </div>
            <div class="row justify-content-center">
                <div class="col-md-6">
                    <div class="row justify-content-center">
                        <div class="col-6 col-md-3 text-center stat-item">
                            <div class="stat-number">12</div>
                            <div class="stat-label">SERVICE BAYS</div>
                        </div>
                        <div class="col-6 col-md-3 text-center stat-item">
                            <div class="stat-number">3</div>
                            <div class="stat-label">DYNO ROOMS</div>
                        </div>
                        <div class="col-6 col-md-3 text-center stat-item">
                            <div class="stat-number">24/7</div>
                            <div class="stat-label">SECURITY TRACKING</div>
                        </div>
                        <div class="col-6 col-md-3 text-center stat-item">
                            <div class="stat-number">45+</div>
                            <div class="stat-label">YEARS COMBINED EXP.</div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </section>




    @endsection