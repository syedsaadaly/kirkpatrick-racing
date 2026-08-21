@extends('front.include.app')
@section('title','Kirkpatrick Racing')
@section('bodyClass','inner-body')
@section('content')




    <section class="inner-banner">
        <div class="container">
            <div class="row">
                <div class="col-md-7">
                    <div class="inner-content">
                        <h3>EST. 1994</h3>
                        <h2>BUILT FOR <span>RIDERS.</span></h2>
                        <p>A family-owned American motorsports dealership specializing in
                            the raw adrenaline of motocross and the precision of the electric
                            future.</p>
                        <div class="btn-group">
                            <a href="#about-roots" class="themeBtn">LEARN OUR STORY</a>
                            <a href="#about-roadmap" class="themeBtn">VIEW TIMELINE</a>
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

    <section class="aboutpage1" id="about-roots">
        <div class="container">
            <div class="row align-items-center">
                <div class="col-lg-5 col-md-12 aboutpage1__content-col">
                    <div class="aboutpage1__content">
                        <h2 class="aboutpage1__title">A Family Passion for the Ride</h2>
                        <p class="aboutpage1__desc-main">
                            Kirkpatrick Racing started the way most great things do — in the backyard. What began as a family obsession with dirt bikes grew into something bigger: a dealership built on the belief that the next generation of riders deserves the cleanest, quietest, most capable machines ever made.
                        </p>
                        <p class="aboutpage1__desc-sub">
                            We're a Louisiana family that lives and breathes motocross. We've raced, wrenched, and ridden everything from muddy trail loops to full-on MX tracks. When electric dirt bikes started proving themselves on the track, we knew it was time to bring them home.
                        </p>
                        <p class="aboutpage1__desc-sub">
                            Today, Kirkpatrick Racing is your local source for Electro and Company and Thorne Cycles — two brands leading the charge in electric off-road performance. We stock bikes for every rider, from first-time youth riders to seasoned pros chasing lap times.
                        </p>

                        <div class="aboutpage1__stats">
                            <div class="aboutpage1__stat-line"></div>
                            <div class="aboutpage1__stat-number">30+</div>
                            <div class="aboutpage1__stat-text">
                                <span>YEARS OF RACING</span>
                                <span>HERITAGE</span>
                            </div>
                        </div>
                    </div>
                </div>
                <div class="col-lg-6 offset-lg-1 col-md-12 aboutpage1__visual-col">
                    <div class="aboutpage1__visual-wrapper">
                        <div class="aboutpage1__dark-frame">
                            <img src="{{ asset('front/images/aboutt1.png') }}" alt="Garage Scene" class="aboutpage1__image img-fluid">
                        </div>
                        <div class="aboutpage1__red-card">
                            <h4 class="aboutpage1__red-title">PRO PERFORMANCE</h4>
                            <p class="aboutpage1__red-desc">Every bike that leaves our floor is race-ready.</p>
                        </div>

                    </div>
                </div>

            </div>
        </div>
    </section>

    <section class="about-future-section">
        <div class="container">
            <div class="row align-items-start about-future-header-row mb-5">
                <div class="col-lg-8 about-future-main-text">
                    <!-- <h2 class="about-future-outline-title">PRECISION</h2> -->
                    <h1 class="about-future-main-title">Our Brand Partners</h1>
                    <p class="about-future-description mt-3">
                        We carry two of the most respected names in electric off-road — each with a distinct philosophy, both built to perform.
                    </p>
                </div>
                <div class="col-lg-4 mt-4 mt-lg-0">
                    <div class="about-future-stats-card d-flex flex-column justify-content-center">
                        <span class="stats-subtitle">EV REVOLUTION</span>
                        <h4 class="stats-title mt-2">EXPANDING CATALOG 2024</h4>
                    </div>
                </div>
            </div>
            <div class="row no-gutters about-future-content-row">
                <div class="col-lg-4 d-flex">
                    <div class="about-future-sidebar w-100 d-flex flex-column">
                        <div>
                            <h3 class="sidebar-heading mb-2">Electro and Company</h3>
                        <h5>Trail-ready. Family-friendly. Zero emissions.</h5>
                        <p class="sidebar-paragraph mb-4">
                            Electro and Company builds electric dirt bikes that make the sport accessible without sacrificing performance. Their lineup spans youth entry-level models all the way to capable trail bikes for adults — all with smooth, predictable power delivery that's perfect for riders still building confidence.
                        </p>
                        <ul class="lineup">
                            <li><i class="fal fa-check-circle"></i> Youth-focused lineup starting at $2,099</li>
                            <li><i class="fal fa-check-circle"></i> Smooth, beginner-friendly power delivery</li>
                            <li><i class="fal fa-check-circle"></i> Lightweight frames for easy handling</li>
                            <li><i class="fal fa-check-circle"></i> Long battery life for all-day trail riding</li>
                        </ul>
                        </div>
                        <div>
                            <h3 class="sidebar-heading mb-2">Thorne Cycles</h3>
                        <h5>Track-proven. Competition-ready. No compromise.</h5>
                        <p class="sidebar-paragraph mb-4">
                            Thorne Cycles is built for riders who want to push limits. Engineered with race-grade components, fully adjustable suspension, and motors that deliver serious torque from the first corner.
                        </p>
                        <ul class="lineup">
                            <li><i class="fal fa-check-circle"></i> Race-grade suspension and chassis</li>
                            <li><i class="fal fa-check-circle"></i> High-torque motors for track performance</li>
                            <li><i class="fal fa-check-circle"></i> Fully adjustable for rider preference</li>
                            <li><i class="fal fa-check-circle"></i> Models from youth to pro-level competition</li>
                        </ul>
                        </div>
                    </div>
                </div>
                <div class="col-lg-8 d-flex">
                    <div class="about-future-image-wrapper position-relative w-100">
                        <img src="{{ asset('front/images/future1.png') }}" alt="Electric Motorcycle"
                            class="img-fluid w-100 h-100 object-fit-cover">
                        <div class="image-overlay-content position-absolute">
                            <span class="accent-text">NEXT-GEN TORQUE</span>
                            <h2 class="overlay-title text-white mt-2">ELECTRIC MASTERY</h2>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </section>

    <section class="aboutpage_roadmap" id="about-roadmap">
        <div class="container">
            <div class="row">
                <div class="col-12 text-center">
                    <h2 class="roadmap-title">ROADMAP TIMELINE</h2>
                    <div class="roadmap-divider"></div>
                </div>
            </div>
            <div class="row">
                <div class="col-12">
                    <div class="timeline">
                        <div class="timeline-line"></div>
                        <div class="timeline-item left">
                            <div class="marker">01</div>
                            <div class="timeline-content">
                                <div class="phase-label">PHASE 01</div>
                                <h3 class="phase-title">DEALER LAUNCH</h3>
                                <p class="phase-desc">Official electric bike dealership goes live, offering sales and<br
                                        class="d-none d-md-block">support.</p>
                            </div>
                        </div>
                        <div class="timeline-item right">
                            <div class="marker">02</div>
                            <div class="timeline-content">
                                <div class="phase-label">PHASE 02</div>
                                <h3 class="phase-title">ECOMMERCE EXPANSION</h3>
                                <p class="phase-desc">Full online store integration with parts, gear, and accessories.
                                </p>
                            </div>
                        </div>
                        <div class="timeline-item left">
                            <div class="marker">03</div>
                            <div class="timeline-content">
                                <div class="phase-label">PHASE 03</div>
                                <h3 class="phase-title">NEW BRANDS ADDED</h3>
                                <p class="phase-desc">Expansion into niche performance brands covering more racing<br
                                        class="d-none d-md-block">segments.</p>
                            </div>
                        </div>
                        <div class="timeline-item right">
                            <div class="marker">04</div>
                            <div class="timeline-content">
                                <div class="phase-label">PHASE 04</div>
                                <h3 class="phase-title">ACCESSORIES CATALOG</h3>
                                <p class="phase-desc">Premium performance parts and custom components for all skill<br
                                        class="d-none d-md-block">levels.</p>
                            </div>
                        </div>
                        <div class="timeline-item left">
                            <div class="marker">05</div>
                            <div class="timeline-content">
                                <div class="phase-label">PHASE 05</div>
                                <h3 class="phase-title">COMMUNITY EVENTS</h3>
                                <p class="phase-desc">Official rides, competitive races, and expert rider clinics.</p>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </section>

    <section class="aboutpage-community">
        <div class="container">
            <div class="row align-items-center">
                <div class="col-lg-6 pr-lg-5">
                    <h3 class="subHead"><i class="fal fa-map-marker-alt"></i> Louisiana</h3>
                    <h2 class="section-title">Come See Us</h2>
                    <p class="section-description mt-4 mb-5">We're a hands-on dealership. That means you can walk in, sit on the bikes, ask every question you have, and leave with the right machine for your rider. No pressure, no guessing — just honest advice from people who ride.</p>
                    <a href="javascript:;" class="themeBtn">Get in touch</a>
                </div>
                <div class="col-lg-6 mt-5 mt-lg-0">
                    <figure class="motorcross">
                        <img src="{{ asset('front/images/2198720658.webp') }}" alt="Motocross riders lined up"
                        class="img-fluid w-100 feature-image">
                    </figure>
                    </div>
            </div>
        </div>
    </section>

    <section class="about-cta-section aboutpage1">
        <div class="container">
            <div class="row justify-content-center">
                <div class="col-12 text-center">
                    <h2 class="aboutpage1-title">
                        READY TO <span class="aboutpage1-text-red">RIDE?</span>
                    </h2>
                    <div class="aboutpage1-buttons-wrapper">
                        <a href="{{ route('front.electric-bike') }}" class="aboutpage1-btn aboutpage1-btn-solid">EXPLORE ELECTRIC BIKES</a>
                        <a href="{{ route('front.services') }}" class="aboutpage1-btn aboutpage1-btn-outline">SCHEDULE SERVICE</a>
                    </div>
                </div>
            </div>
        </div>
    </section>







    @endsection