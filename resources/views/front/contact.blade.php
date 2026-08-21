@extends('front.include.app')
@section('title','Kirkpatrick Racing')
@section('bodyClass','inner-body bg-black-color')
@section('content')




    <section class="inner-banner">
        <div class="container">
            <div class="row">
                <div class="col-md-7">
                    <div class="inner-content white-space">
                        <h3>PRO-GRADE PERFORMANCE</h3>
                        <h2>GET IN <span>TOUCH</span></h2>
                        <p>Whether you're looking for high-performance electric upgrades or
                            professional mechanical tuning, our team is ready to get you back on the track.</p>
                    </div>
                </div>
            </div>
        </div>
    </section>

    <section id="get-section" class="py-5 custom-dark-theme">
        <div class="container my-5">
            <div class="row mb-5">
                <!-- Form Section -->
                <div class="col-lg-7 pr-lg-4 mb-5 mb-lg-0" id="form-target-section">
                    <div class="bordered-panel p-4 p-md-5 h-100">
                        <form id="transmission-form">
                            <div class="form-row">
                                <div class="form-group col-md-6 mb-4">
                                    <label class="custom-label" for="fullName">FULL NAME</label>
                                    <input type="text" class="form-control custom-input" id="fullName"
                                        placeholder="WESLEY KIRKPATRICK">
                                </div>
                                <div class="form-group col-md-6 mb-4">
                                    <label class="custom-label" for="emailAddress">EMAIL ADDRESS</label>
                                    <input type="email" class="form-control custom-input" id="emailAddress"
                                        placeholder="WESLEY@RACING.COM">
                                </div>
                            </div>
                            <div class="form-row">
                                <div class="form-group col-md-6 mb-4">
                                    <label class="custom-label" for="phoneNumber">PHONE NUMBER</label>
                                    <input type="tel" class="form-control custom-input" id="phoneNumber"
                                        placeholder="+1 (234) 567-8901">
                                </div>
                                <div class="form-group col-md-6 mb-4">
                                    <label class="custom-label" for="primaryInterest">PRIMARY INTEREST</label>
                                    <div class="custom-select-wrapper">
                                        <select class="form-control custom-input custom-select-dropdown"
                                            id="primaryInterest">
                                            <option>General Inquiry</option>
                                            <option>Sales</option>
                                            <option>Service</option>
                                        </select>
                                        <svg class="select-arrow" viewBox="0 0 24 24" fill="none" stroke="currentColor"
                                            stroke-width="2" stroke-linecap="round" stroke-linejoin="round">
                                            <polyline points="6 9 12 15 18 9"></polyline>
                                        </svg>
                                    </div>
                                </div>
                            </div>
                            <div class="form-group mb-4">
                                <label class="custom-label" for="message">YOUR MESSAGE</label>
                                <textarea class="form-control custom-input custom-textarea" id="message" rows="4"
                                    placeholder="TELL US ABOUT YOUR PERFORMANCE NEEDS..."></textarea>
                            </div>
                            <button type="submit" class="btn btn-custom-red mt-2">
                                SEND TRANSMISSION
                                <svg class="btn-icon" viewBox="0 0 24 24" fill="currentColor">
                                    <path d="M8 5v14l11-7z" />
                                </svg>
                            </button>
                        </form>
                    </div>
                </div>

                <!-- Info Section -->
                <div class="col-lg-5 pl-lg-4" id="info-target-section">
                    <!-- Command Center -->
                    <div class="bordered-panel p-4 p-md-5 mb-4">
                        <h4 class="panel-title mb-4">COMMAND CENTER</h4>

                        <div class="info-item d-flex align-items-start mb-4">
                            <div class="info-icon-box mr-3">
                                <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"
                                    stroke-linecap="round" stroke-linejoin="round">
                                    <path d="M21 10c0 7-9 13-9 13s-9-6-9-13a9 9 0 0 1 18 0z"></path>
                                    <circle cx="12" cy="10" r="3"></circle>
                                </svg>
                            </div>
                            <div>
                                <div class="info-label">HEADQUARTERS</div>
                                <div class="info-value">{{ \App\Models\Setting::getValue('footer_address', '7820 Industrial Way, San Antonio, TX 78249') }}</div>
                            </div>
                        </div>

                        <div class="info-item d-flex align-items-start mb-4">
                            <div class="info-icon-box mr-3">
                                <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"
                                    stroke-linecap="round" stroke-linejoin="round">
                                    <path
                                        d="M4 4h16c1.1 0 2 .9 2 2v12c0 1.1-.9 2-2 2H4c-1.1 0-2-.9-2-2V6c0-1.1.9-2 2-2z">
                                    </path>
                                    <polyline points="22,6 12,13 2,6"></polyline>
                                </svg>
                            </div>
                            <div>
                                <div class="info-label">DIRECT INBOX</div>
                                <div class="info-value">{{ \App\Models\Setting::getValue('footer_email', 'info@kirkpatrickracing.com') }}</div>
                            </div>
                        </div>

                        <div class="info-item d-flex align-items-start">
                            <div class="info-icon-box mr-3">
                                <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"
                                    stroke-linecap="round" stroke-linejoin="round">
                                    <path
                                        d="M22 16.92v3a2 2 0 0 1-2.18 2 19.79 19.79 0 0 1-8.63-3.07 19.5 19.5 0 0 1-6-6 19.79 19.79 0 0 1-3.07-8.67A2 2 0 0 1 4.11 2h3a2 2 0 0 1 2 1.72 12.84 12.84 0 0 0 .7 2.81 2 2 0 0 1-.45 2.11L8.09 9.91a16 16 0 0 0 6 6l1.27-1.27a2 2 0 0 1 2.11-.45 12.84 12.84 0 0 0 2.81.7A2 2 0 0 1 22 16.92z">
                                    </path>
                                </svg>
                            </div>
                            <div>
                                <div class="info-label">DIRECT LINE</div>
                                <div class="info-value">{{ \App\Models\Setting::getValue('footer_phone', '+1 (234) 567-8900') }}</div>
                            </div>
                        </div>
                    </div>

                    <!-- Track Hours -->
                    <div class="red-panel p-4 p-md-5">
                        <h4 class="panel-title mb-4 d-flex align-items-center">
                            <svg class="mr-2" width="18" height="18" viewBox="0 0 24 24" fill="none"
                                stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round">
                                <circle cx="12" cy="12" r="10"></circle>
                                <polyline points="12 6 12 12 16 14"></polyline>
                            </svg>
                            TRACK HOURS
                        </h4>
                        <ul class="track-hours-list list-unstyled mb-0">
                            <li class="d-flex justify-content-between">
                                <span>MON - FRI</span>
                                <span>08:00 AM - 07:00 PM</span>
                            </li>
                            <li class="d-flex justify-content-between">
                                <span>SATURDAY</span>
                                <span>09:00 AM - 05:00 PM</span>
                            </li>
                            <li class="d-flex justify-content-between">
                                <span>SUNDAY</span>
                                <span>TRACK CLOSED</span>
                            </li>
                        </ul>
                    </div>
                </div>
            </div>

            <!-- Divider -->
            <div class="section-divider"></div>

            <!-- Visit Section -->
            <div class="row align-items-center mt-5" id="visit-target-section">
                <div class="col-lg-6 pr-lg-5 mb-5 mb-lg-0" id="visit-text-section">
                    <h2 class="section-heading mb-4">VISIT OUR <span class="text-red">SHOWROOM</span></h2>
                    <p class="section-description mb-5">
                        Experience the future of motocross in person. Our San Antonio
                        facility features a full showroom of the latest electric models, a
                        precision tuning workshop, and expert staff ready to consult on your
                        next build.
                    </p>
                    <div class="row">
                        <div class="col-sm-6 mb-4 mb-sm-0">
                            <div class="bordered-panel p-3 stat-box">
                                <div class="info-label mb-1">FACILITY</div>
                                <div class="stat-value">12,000 SQ FT PRECISION SHOP</div>
                            </div>
                        </div>
                        <div class="col-sm-6">
                            <div class="bordered-panel p-3 stat-box">
                                <div class="info-label mb-1">STATUS</div>
                                <div class="stat-value">AUTHORIZED SERVICE CENTER</div>
                            </div>
                        </div>
                    </div>
                </div>

                <div class="col-lg-6" id="visit-map-section">
                    <div class="map-container bordered-panel">
                        <div class="map-grid-overlay"></div>
                        <img src="{{ asset('front/images/mapimg.png') }}" alt="Map Background" class="img-fluid map-bg">
                        <div class="map-glow-point">
                            <div class="core"></div>
                            <div class="pulse"></div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </section>




    @endsection