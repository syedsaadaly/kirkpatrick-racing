@php
    $footerLogoUrl = \App\Models\Setting::where('key', 'footer_logo')->first()?->getFirstMediaUrl('logos') ?: asset('front/images/logo.png');
@endphp
<footer class="kp-site-footer">
    <div class="kp-social-bar">
        <div class="container-fluid p-0">
            <div class="row no-gutters">
                <div class="col-6 col-md-3 kp-social-cell">
                    <a href="{{ \App\Models\Setting::getValue('facebook_url', 'https://facebook.com') }}" class="kp-social-link" target="_blank" rel="noopener">
                        <i class="fab fa-facebook-f"></i> Facebook
                    </a>
                </div>
                <div class="col-6 col-md-3 kp-social-cell">
                    <a href="{{ \App\Models\Setting::getValue('twitter_url', 'https://x.com') }}" class="kp-social-link" target="_blank" rel="noopener">
                        <i class="fab fa-twitter"></i> Twitter (X)
                    </a>
                </div>
                <div class="col-6 col-md-3 kp-social-cell">
                    <a href="{{ \App\Models\Setting::getValue('linkedin_url', 'https://linkedin.com') }}" class="kp-social-link" target="_blank" rel="noopener">
                        <i class="fab fa-linkedin"></i> linkedin
                    </a>
                </div>
                <div class="col-6 col-md-3 kp-social-cell">
                    <a href="{{ \App\Models\Setting::getValue('instagram_url', 'https://instagram.com') }}" class="kp-social-link" target="_blank" rel="noopener">
                        <i class="fab fa-instagram"></i> Instagram
                    </a>
                </div>
            </div>
        </div>
    </div>
    <div class="kp-main-footer-area">
        <div class="container">
            <div class="row">
                <div class="col-lg-4 col-md-6 kp-footer-widget kp-widget-about">
                    <a href="{{ route('front.index') }}">
                        <figure class="footer-logo">
                            <img src="{{ $footerLogoUrl }}" class="img-fluid" alt="">
                        </figure>
                    </a>
                    <p class="kp-about-text">
                        {{ \App\Models\Setting::getValue('footer_description', 'Family-owned American motorsports dealership specializing in electric dirt bikes, expert repairs and full-service maintenance for every type of rider.') }}
                    </p>
                </div>
                <div class="col-lg-3 col-md-6 kp-footer-widget kp-widget-contact">
                    <h4 class="kp-widget-title">Contact Us</h4>
                    <div class="kp-contact-details">
                        <a class="kp-email-link">{{ \App\Models\Setting::getValue('footer_address', '7820 Industrial Way, San Antonio, TX 78249') }}</a>
                        <a href="mailto:{{ \App\Models\Setting::getValue('footer_email', 'info@kirkpatrickracing.com') }}" class="kp-email-link">{{ \App\Models\Setting::getValue('footer_email', 'info@kirkpatrickracing.com') }}</a>
                        <a class="kp-email-link d-block">{{ \App\Models\Setting::getValue('footer_phone', '+1 (234) 567-8900') }}</a>
                    </div>
                </div>
                <div class="col-lg-2 col-md-6 kp-footer-widget kp-widget-links">
                    <h4 class="kp-widget-title">Links</h4>
                    <ul class="kp-footer-menu">
                        <li><a href="{{ route('front.index') }}">Home</a></li>
                        <li><a href="{{ route('front.electric-bike') }}">Electric Bikes</a></li>
                        <li><a href="{{ route('front.services') }}">Services</a></li>
                        <li><a href="{{ route('front.shop') }}">Shop</a></li>
                        <li><a href="{{ route('front.gallery') }}">Gallery</a></li>
                        <li><a href="{{ route('front.contact') }}">Contact</a></li>
                    </ul>
                </div>
                <div class="col-lg-3 col-md-6 kp-footer-widget kp-widget-newsletter">
                    <h4 class="kp-widget-title">Newsletter</h4>
                    <form class="kp-newsletter-form" onsubmit="event.preventDefault();">
                        <div class="kp-input-wrapper">
                            <span class="kp-input-icon"><i class="far fa-envelope"></i></span>
                            <input type="email" class="kp-email-input" placeholder="Enter Your Email Address">
                            <button type="submit" class="kp-submit-button"><i class="fas fa-arrow-right"></i></button>
                        </div>
                        <div class="kp-terms-checkbox">
                            <label class="kp-custom-checkbox">
                                <input type="checkbox">
                                <span class="kp-checkmark-circle"></span>
                                <span class="kp-checkbox-label-text">I agree to the <a href="{{ route('front.privacy-policy') }}">Privacy
                                        Policy</a></span>
                            </label>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </div>
    <div class="kp-copyright-area">
        <div class="container">
            <div class="row align-items-center">
                <div class="col-lg-8 col-md-12 kp-copy-left">
                    <p class="kp-copyright-text">
                        {!! \App\Models\Setting::getValue('website_copyright', 'Kirkpatrick Racing © ' . date('Y') . '. All rights reserved.') !!}
                        {!! \App\Models\Setting::getValue('designed_developed', 'Design & Developed by <a href="https://creativedesign360.com" target="_blank">creativedesign360.com</a>') !!}
                    </p>
                </div>
                <div class="col-lg-4 col-md-12 kp-copy-right">
                    <ul class="kp-legal-menu">
                        <li><a href="{{ route('front.privacy-policy') }}">Privacy Policy</a></li>
                        <li class="kp-separator">·</li>
                        <li><a href="{{ route('front.terms-of-service') }}">Terms of Service</a></li>
                    </ul>
                </div>
            </div>
        </div>
    </div>
</footer>
