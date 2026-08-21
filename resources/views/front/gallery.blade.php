@extends('front.include.app')
@section('title','Kirkpatrick Racing')
@section('bodyClass','inner-body bg-black-color')
@section('content')




    <section class="inner-banner">
        <div class="container">
            <div class="row">
                <div class="col-md-7">
                    <div class="inner-content">
                        <h3>ARCHIVES</h3>
                        <h2>VISUAL<span>VELOCITY</span></h2>
                        <p>Experience the raw power and technical precision of Kirkpatrick
                            Racing. From the dirt of the track to the silence of electric
                            innovation.</p>
                    </div>
                </div>
            </div>
        </div>
    </section>

    <section id="section-tz8d4" class="p-0">
        <div id="electric-bike-page-wrapper">

            <!-- Filter Section -->
            <section id="electric-bike-filter-section">
                <div class="container">
                    <div class="electric-bike-filter-container">
                        <ul class="electric-bike-filter-list">
                            <li class="filter-label">FILTER BY:</li>
                            <li class="filter-item active"><a href="#">ALL ACCESS</a></li>
                            <li class="filter-item"><a href="#">RACE DAY</a></li>
                            <li class="filter-item"><a href="#">ELECTRIC FLEET</a></li>
                            <li class="filter-item"><a href="#">WORKSHOP</a></li>
                            <li class="filter-item"><a href="#">COMMUNITY</a></li>
                        </ul>
                    </div>
                </div>
            </section>

            <!-- Gallery Section -->
            <section id="electric-bike-gallery-section">
                <div class="container electric-bike-container">
                    <div class="electric-bike-gallery-layout">

                        <!-- Left Column (Approx 62%) -->
                        <div class="electric-bike-col-left">

                            <!-- Dirt Bike (Top Full Width) -->
                            <div class="electric-bike-img-box img-dirtbike gsap-reveal">
                                <img src="{{asset('front/images/gallery1.png') }}" alt="Dirtbike Rider">
                            </div>

                            <!-- Middle Split Row -->
                            <div class="electric-bike-inner-row">

                                <!-- Inner Left (Sunset + Custom) -->
                                <div class="electric-bike-inner-col">
                                    <div class="electric-bike-img-box img-sunset gsap-reveal">
                                        <img src="{{asset('front/images/gallery3.png') }}" alt="Sunset Bikers">
                                    </div>
                                    <div class="electric-bike-img-box img-custom gsap-reveal">
                                        <img src="{{asset('front/images/gallery5.png') }}" alt="Custom Motorcycle">
                                    </div>
                                </div>

                                <!-- Inner Right (Red Banner) -->
                                <div class="electric-bike-inner-col">
                                    <div class="electric-bike-banner gsap-reveal">
                                        <h2>JOIN THE<br>INNER CIRCLE</h2>
                                        <a href="{{ route('front.contact') }}" class="electric-bike-btn-dark">BECOME A MEMBER</a>
                                    </div>
                                </div>

                            </div>

                            <!-- Futuristic Bike (Bottom Full Width) -->
                            <div class="electric-bike-img-box img-futuristic gsap-reveal">
                                <img src="{{asset('front/images/gallery7.png') }}" alt="Futuristic Sportbike">
                            </div>

                        </div>

                        <!-- Right Column (Approx 38%) -->
                        <div class="electric-bike-col-right">

                            <!-- Forest Bike (Top) -->
                            <div class="electric-bike-img-box img-forest gsap-reveal">
                                <img src="{{asset('front/images/gallery2.png') }}" alt="Electric Bike in Forest">
                                <span class="electric-bike-tag">ELECTRIC FLEET</span>
                            </div>

                            <!-- Engine (Middle Tall) -->
                            <div class="electric-bike-img-box img-engine gsap-reveal">
                                <img src="{{asset('front/images/gallery4.png') }}" alt="Engine Block">
                            </div>

                            <!-- Buggy (Bottom) -->
                            <div class="electric-bike-img-box img-buggy gsap-reveal">
                                <img src="{{asset('front/images/gallery6.png') }}" alt="Desert Dune Buggy">
                            </div>

                        </div>

                    </div>
                </div>
            </section>
            <section id="electric-bike-newsletter-section">
                <div class="container electric-bike-container">
                    <div class="row align-items-center">
                        <div class="col-lg-6 electric-bike-news-text gsap-slide-right">
                            <h6 class="news-subtitle">STAY ACCELERATED</h6>
                            <h2 class="news-title">GET THE LATEST<br>TECHNICAL TEARDOWNS<br>AND RACE UPDATES.</h2>
                            <p class="news-desc">
                                Join over 50,000 racing enthusiasts who get our weekly dispatch<br>
                                on electric evolution and traditional performance tuning.
                            </p>
                        </div>
                        <div class="col-lg-5 offset-lg-1 electric-bike-news-form-wrap gsap-slide-left">
                            <form class="electric-bike-newsletter-form">
                                <input type="email" placeholder="ENTER YOUR EMAIL" required>
                                <button type="submit">SUBSCRIBE</button>
                            </form>
                            <p class="news-disclaimer">
                                BY SUBSCRIBING, YOU AGREE TO OUR PRIVACY POLICY AND TERMS OF SERVICE.
                            </p>
                        </div>
                    </div>
                </div>
            </section>
        </div>
    </section>


    @endsection