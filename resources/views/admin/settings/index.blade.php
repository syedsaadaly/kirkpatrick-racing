@extends('admin.layouts.app')
@section('content')
    @include('admin.layouts.partials.topbar')

    <div class="card mb-3">
        <div class="card-body">
            <div class="row flex-between-center">
                <div class="col-md">
                    <h5 class="mb-2 mb-md-0">Website Settings</h5>
                </div>
            </div>
        </div>
    </div>

    <form action="{{ route('admin.settings.store') }}" method="POST" enctype="multipart/form-data" id="settingsForm">
        @csrf

        <div class="card">
            <div class="card-header bg-light">
                <ul class="nav nav-tabs card-header-tabs" id="settingsTabs" role="tablist">
                    <li class="nav-item" role="presentation">
                        <button class="nav-link active" id="login-tab" data-bs-toggle="tab" data-bs-target="#login"
                            type="button" role="tab" aria-controls="login" aria-selected="true">
                            <i class="fas fa-user-lock me-1"></i> Admin Settings
                        </button>
                    </li>
                    <li class="nav-item" role="presentation">
                        <button class="nav-link" id="footer-tab" data-bs-toggle="tab" data-bs-target="#footer"
                            type="button" role="tab" aria-controls="footer" aria-selected="false">
                            <i class="fas fa-sitemap me-1"></i> Footer
                        </button>
                    </li>
                    <li class="nav-item" role="presentation">
                        <button class="nav-link" id="payment-tab" data-bs-toggle="tab" data-bs-target="#payment"
                            type="button" role="tab" aria-controls="payment" aria-selected="false">
                            <i class="fas fa-credit-card me-1"></i> Payment
                        </button>
                    </li>
                    <li class="nav-item" role="presentation">
                        <button class="nav-link" id="logo-tab" data-bs-toggle="tab" data-bs-target="#logo" type="button"
                            role="tab" aria-controls="logo" aria-selected="false">
                            <i class="fas fa-images me-1"></i> Logo Settings
                        </button>
                    </li>
                </ul>
            </div>

            <div class="card-body">
                <div class="tab-content" id="settingsTabsContent">
                    <div class="tab-pane fade" id="footer" role="tabpanel" aria-labelledby="footer-tab" tabindex="0">

                        <div class="mb-3">
                            <label class="form-label fw-semibold">Footer About Text</label>
                            <textarea class="form-control" name="footer_description" rows="2">{{ \App\Models\Setting::getValue('footer_description', 'Family-owned American motorsports dealership specializing in electric dirt bikes, expert repairs and full-service maintenance for every type of rider.') }}</textarea>
                        </div>

                        <div class="mb-3">
                            <label class="form-label fw-semibold">Footer Address</label>
                            <textarea class="form-control" name="footer_address" rows="3">{{ \App\Models\Setting::getValue('footer_address', '7820 Industrial Way, San Antonio, TX 78249') }}</textarea>
                        </div>

                        <div class="mb-3">
                            <label class="form-label fw-semibold">Phone Number</label>
                            <input type="text" class="form-control" name="footer_phone"
                                value="{{ \App\Models\Setting::getValue('footer_phone', '+1 (234) 567-8900') }}">
                        </div>

                        <div class="mb-3">
                            <label class="form-label fw-semibold">Footer Email</label>
                            <input type="email" class="form-control" name="footer_email"
                                value="{{ \App\Models\Setting::getValue('footer_email', 'info@kirkpatrickracing.com') }}">
                        </div>

                        <div class="mb-3">
                            <label class="form-label fw-semibold">Facebook URL</label>
                            <input type="text" class="form-control" name="facebook_url"
                                value="{{ \App\Models\Setting::getValue('facebook_url', 'https://facebook.com') }}">
                        </div>

                        <div class="mb-3">
                            <label class="form-label fw-semibold">Twitter URL</label>
                            <input type="text" class="form-control" name="twitter_url"
                                value="{{ \App\Models\Setting::getValue('twitter_url', 'https://x.com') }}">
                        </div>

                        <div class="mb-3">
                            <label class="form-label fw-semibold">LinkedIn URL</label>
                            <input type="text" class="form-control" name="linkedin_url"
                                value="{{ \App\Models\Setting::getValue('linkedin_url', 'https://linkedin.com') }}">
                        </div>

                        <div class="mb-3">
                            <label class="form-label fw-semibold">Instagram URL</label>
                            <input type="text" class="form-control" name="instagram_url"
                                value="{{ \App\Models\Setting::getValue('instagram_url', 'https://instagram.com') }}">
                        </div>

                        <div class="mb-3">
                            <label class="form-label fw-semibold">Website Copyright</label>
                            <input type="text" class="form-control" name="website_copyright"
                                value="{{ \App\Models\Setting::getValue('website_copyright', 'Kirkpatrick Racing © ' . date('Y') . '. All rights reserved.') }}">
                        </div>

                        <div class="mb-3">
                            <label class="form-label fw-semibold">Designed &amp; Developed</label>
                            <input type="text" class="form-control" name="designed_developed"
                                value="{{ \App\Models\Setting::getValue('designed_developed', 'Design & Developed by <a href=\"https://creativedesign360.com\" target=\"_blank\">creativedesign360.com</a>') }}">
                        </div>

                    </div>

                    <div class="tab-pane fade" id="payment" role="tabpanel" aria-labelledby="payment-tab"
                        tabindex="0">

                        <div class="card border mb-4">
                            <div class="card-header bg-light">
                                <h6 class="mb-0"><i class="fas fa-cc-stripe me-2"></i>Stripe Settings</h6>
                            </div>
                            <div class="card-body">
                                @php
                                    $s_test_k = \App\Models\Setting::getValue('stripe_test_key');
                                    $s_test_p = \App\Models\Setting::getValue('stripe_private_test_key');
                                    $s_live_k = \App\Models\Setting::getValue('stripe_live_secret_key');
                                    $s_live_p = \App\Models\Setting::getValue('stripe_private_live_key');

                                    $stripeEnabled = \App\Models\Setting::getValue('stripe_enabled') == 'on';
                                    $stripeSandbox = \App\Models\Setting::getValue('stripe_sandbox_mode') == 'on';

                                    $showStripeSandbox = $stripeEnabled && ($stripeSandbox || !empty($s_test_k));
                                    $showStripeLive = $stripeEnabled && (!$stripeSandbox && !empty($s_live_k));
                                @endphp

                                <div class="form-check form-switch mb-3">
                                    <input class="form-check-input" type="checkbox" name="stripe_enabled" value="on"
                                        id="stripeEnabled" {{ $stripeEnabled ? 'checked' : '' }}>
                                    <label class="form-check-label fw-semibold">Enable Stripe Payment Method</label>
                                </div>

                                <div class="form-check form-switch mb-4">
                                    <input class="form-check-input" type="checkbox" name="stripe_sandbox_mode"
                                        value="on" id="stripeSandboxMode" {{ $stripeSandbox ? 'checked' : '' }}
                                        {{ !$stripeEnabled ? 'disabled' : '' }}>
                                    <label class="form-check-label fw-semibold">Enable Stripe Sandbox Mode</label>
                                </div>

                                <div id="stripeSandboxFields"
                                    style="display: {{ $showStripeSandbox ? 'block' : 'none' }};">
                                    <div class="mb-3">
                                        <label class="form-label fw-semibold">Stripe Test Key <span
                                                class="text-danger">*</span></label>
                                        <input type="text" class="form-control stripe-field" name="stripe_test_key"
                                            value="{{ $s_test_k }}">
                                    </div>
                                    <div class="mb-3">
                                        <label class="form-label fw-semibold">Stripe Private Test Key <span
                                                class="text-danger">*</span></label>
                                        <input type="text" class="form-control stripe-field"
                                            name="stripe_private_test_key" value="{{ $s_test_p }}">
                                    </div>
                                </div>

                                <div id="stripeLiveFields" style="display: {{ $showStripeLive ? 'block' : 'none' }};">
                                    <div class="mb-3">
                                        <label class="form-label fw-semibold">Stripe Live Secret Key <span
                                                class="text-danger">*</span></label>
                                        <input type="text" class="form-control stripe-field"
                                            name="stripe_live_secret_key" value="{{ $s_live_k }}">
                                    </div>
                                    <div class="mb-3">
                                        <label class="form-label fw-semibold">Stripe Private Live Key <span
                                                class="text-danger">*</span></label>
                                        <input type="text" class="form-control stripe-field"
                                            name="stripe_private_live_key" value="{{ $s_live_p }}">
                                    </div>
                                </div>
                            </div>
                        </div>

                        <div class="card border">
                            <div class="card-header bg-light">
                                <h6 class="mb-0"><i class="fab fa-paypal me-2"></i>PayPal Settings</h6>
                            </div>
                            <div class="card-body">
                                @php
                                    $p_test_id = \App\Models\Setting::getValue('paypal_sandbox_client_id');
                                    $p_test_sec = \App\Models\Setting::getValue('paypal_sandbox_client_secret');
                                    $p_live_id = \App\Models\Setting::getValue('paypal_client_id');
                                    $p_live_sec = \App\Models\Setting::getValue('paypal_client_secret');

                                    $paypalEnabled = \App\Models\Setting::getValue('paypal_enabled') == 'on';
                                    $paypalSandbox = \App\Models\Setting::getValue('paypal_sandbox_mode') == 'on';

                                    $showPaypalSandbox = $paypalEnabled && ($paypalSandbox || !empty($p_test_id));
                                    $showPaypalLive = $paypalEnabled && (!$paypalSandbox && !empty($p_live_id));
                                @endphp

                                <div class="form-check form-switch mb-3">
                                    <input class="form-check-input" type="checkbox" name="paypal_enabled" value="on"
                                        id="paypalEnabled" {{ $paypalEnabled ? 'checked' : '' }}>
                                    <label class="form-check-label fw-semibold">Enable PayPal Payment Method</label>
                                </div>

                                <div class="form-check form-switch mb-4">
                                    <input class="form-check-input" type="checkbox" name="paypal_sandbox_mode"
                                        value="on" id="paypalSandboxMode" {{ $paypalSandbox ? 'checked' : '' }}
                                        {{ !$paypalEnabled ? 'disabled' : '' }}>
                                    <label class="form-check-label fw-semibold">Enable PayPal Sandbox Mode</label>
                                </div>

                                <div id="paypalSandboxFields"
                                    style="display: {{ $showPaypalSandbox ? 'block' : 'none' }};">
                                    <div class="mb-3">
                                        <label class="form-label fw-semibold">PayPal Sandbox Client ID <span
                                                class="text-danger">*</span></label>
                                        <input type="text" class="form-control paypal-field"
                                            name="paypal_sandbox_client_id" value="{{ $p_test_id }}">
                                    </div>
                                    <div class="mb-3">
                                        <label class="form-label fw-semibold">PayPal Sandbox Client Secret <span
                                                class="text-danger">*</span></label>
                                        <input type="text" class="form-control paypal-field"
                                            name="paypal_sandbox_client_secret" value="{{ $p_test_sec }}">
                                    </div>
                                </div>

                                <div id="paypalLiveFields" style="display: {{ $showPaypalLive ? 'block' : 'none' }};">
                                    <div class="mb-3">
                                        <label class="form-label fw-semibold">PayPal Live Client ID <span
                                                class="text-danger">*</span></label>
                                        <input type="text" class="form-control paypal-field" name="paypal_client_id"
                                            value="{{ $p_live_id }}">
                                    </div>
                                    <div class="mb-3">
                                        <label class="form-label fw-semibold">PayPal Live Client Secret <span
                                                class="text-danger">*</span></label>
                                        <input type="text" class="form-control paypal-field"
                                            name="paypal_client_secret" value="{{ $p_live_sec }}">
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                    <div class="tab-pane fade" id="logo" role="tabpanel" aria-labelledby="logo-tab"
                        tabindex="0">

                        <div class="mb-3">
                            <label class="form-label fw-semibold">Header Logo</label>
                            <input type="file" class="form-control" name="header_logo" accept="image/*">
                            @php $headerLogo = \App\Models\Setting::where('key','header_logo')->first(); @endphp
                            @if ($headerLogo && $headerLogo->getFirstMediaUrl('logos'))
                                <img src="{{ $headerLogo->getFirstMediaUrl('logos') }}" class="img-thumbnail mt-2"
                                    style="max-height:80px;">
                            @endif
                            <small class="form-text text-muted">Shown in the site navigation bar.</small>
                        </div>

                        <div class="mb-3">
                            <label class="form-label fw-semibold">Footer Logo</label>
                            <input type="file" class="form-control" name="footer_logo" accept="image/*">
                            @php $footerLogo = \App\Models\Setting::where('key','footer_logo')->first(); @endphp
                            @if ($footerLogo && $footerLogo->getFirstMediaUrl('logos'))
                                <img src="{{ $footerLogo->getFirstMediaUrl('logos') }}" class="img-thumbnail mt-2"
                                    style="max-height:80px; background:#222; padding:4px;">
                            @endif
                            <small class="form-text text-muted">Shown in the site footer.</small>
                        </div>

                        <div class="mb-3">
                            <label class="form-label fw-semibold">Favicon</label>
                            <input type="file" class="form-control" name="favicon" accept="image/png,image/x-icon,image/svg+xml">
                            @php $favicon = \App\Models\Setting::where('key','favicon')->first(); @endphp
                            @if ($favicon && $favicon->getFirstMediaUrl('logos'))
                                <img src="{{ $favicon->getFirstMediaUrl('logos') }}" class="img-thumbnail mt-2"
                                    style="max-height:48px;">
                            @endif
                            <small class="form-text text-muted">Shown as the browser tab icon for the admin panel.</small>
                        </div>

                    </div>

                    <div class="tab-pane fade show active" id="login" role="tabpanel" aria-labelledby="login-tab"
                        tabindex="0">


                        <div class="mb-3">
                            <label class="form-label fw-semibold">Login Form Image</label>
                            <input type="file" class="form-control" name="login_image" accept="image/*">
                            @php $loginImage = \App\Models\Setting::where('key','login_image')->first(); @endphp
                            @if ($loginImage && $loginImage->getFirstMediaUrl('logos'))
                                <img src="{{ $loginImage->getFirstMediaUrl('logos') }}" class="img-thumbnail mt-2"
                                    style="max-height:80px;">
                            @endif
                        </div>
                        <div class="mb-3">
                            <label class="form-label fw-semibold">Dashboard Title</label>
                            <input type="text" class="form-control" name="dashboard_title"
                                value="{{ \App\Models\Setting::getValue('dashboard_title', 'Dashboard') }}">
                        </div>

                        <div class="mb-3">
                            <label class="form-label fw-semibold">Dashboard Logo</label>
                            <input type="file" class="form-control" name="dashboard_logo" accept="image/*">
                            @php $dashboardLogo = \App\Models\Setting::where('key','dashboard_logo')->first(); @endphp
                            @if ($dashboardLogo && $dashboardLogo->getFirstMediaUrl('logos'))
                                <img src="{{ $dashboardLogo->getFirstMediaUrl('logos') }}" class="img-thumbnail mt-2"
                                    style="max-height:80px;">
                            @endif
                        </div>
                        <div class="mb-3">
                            <label class="form-label fw-semibold">Sidebar Background Color</label>
                            <input type="color" class="form-control form-control-color" name="sidebar_bg_color"
                                value="{{ \App\Models\Setting::getValue('sidebar_bg_color', '#2c7be5') }}">
                        </div>
                    </div>
                </div>
            </div>

            <div class="card-footer bg-light">
                <div class="row justify-content-between align-items-center">
                    <div class="col-md">
                        <h5 class="mb-2 mb-md-0">Save Settings</h5>
                    </div>
                    <div class="col-auto">
                        <button type="reset" class="btn btn-secondary me-2">Reset</button>
                        <button type="submit" class="btn btn-primary">
                            <i class="fas fa-save me-1"></i> Save Settings
                        </button>
                    </div>
                </div>
            </div>
        </div>
    </form>

    <style>
        .card.border {
            border-color: #e3e6f0 !important;
        }

        .card-header.bg-light {
            background-color: #f8f9fc !important;
        }

        .text-danger {
            color: #e63757 !important;
        }

        .disabled-toggle {
            opacity: 0.6;
            cursor: not-allowed !important;
        }

        .disabled-toggle-label {
            color: #6c757d !important;
        }
    </style>

    <script>
        
        document.addEventListener('DOMContentLoaded', function() {
            const stripeEnabled = document.getElementById('stripeEnabled');
            const stripeSandboxMode = document.getElementById('stripeSandboxMode');
            const stripeSandboxFields = document.getElementById('stripeSandboxFields');
            const stripeLiveFields = document.getElementById('stripeLiveFields');
            const stripeFields = document.querySelectorAll('.stripe-field');

            const paypalEnabled = document.getElementById('paypalEnabled');
            const paypalSandboxMode = document.getElementById('paypalSandboxMode');
            const paypalSandboxFields = document.getElementById('paypalSandboxFields');
            const paypalLiveFields = document.getElementById('paypalLiveFields');
            const paypalFields = document.querySelectorAll('.paypal-field');

            function toggleFields() {
                const isStripeEnabled = stripeEnabled.checked;
                const isStripeSandbox = stripeSandboxMode.checked;
                stripeSandboxMode.disabled = !isStripeEnabled;

                if (isStripeEnabled) {
                    stripeSandboxFields.style.display = isStripeSandbox ? 'block' : 'none';
                    stripeLiveFields.style.display = isStripeSandbox ? 'none' : 'block';
                } else {
                    stripeSandboxFields.style.display = 'none';
                    stripeLiveFields.style.display = 'none';
                }

                stripeFields.forEach(field => {
                    const isRelevant = isStripeEnabled && (isStripeSandbox ? field.name.includes('test') :
                        field.name.includes('live'));
                    field.required = isRelevant;
                    field.closest('.mb-3').style.opacity = isRelevant ? '1' : '0.5';
                });

                const isPayPalEnabled = paypalEnabled.checked;
                const isPayPalSandbox = paypalSandboxMode.checked;
                paypalSandboxMode.disabled = !isPayPalEnabled;

                if (isPayPalEnabled) {
                    paypalSandboxFields.style.display = isPayPalSandbox ? 'block' : 'none';
                    paypalLiveFields.style.display = isPayPalSandbox ? 'none' : 'block';
                } else {
                    paypalSandboxFields.style.display = 'none';
                    paypalLiveFields.style.display = 'none';
                }

                paypalFields.forEach(field => {
                    const isRelevant = isPayPalEnabled && (isPayPalSandbox ? field.name.includes(
                        'sandbox') : !field.name.includes('sandbox'));
                    field.required = isRelevant;
                    field.closest('.mb-3').style.opacity = isRelevant ? '1' : '0.5';
                });
            }

            [stripeEnabled, stripeSandboxMode, paypalEnabled, paypalSandboxMode].forEach(el => {
                el.addEventListener('change', toggleFields);
            });

            const stripeTestKeyInput = document.getElementsByName('stripe_test_key')[0];
            const stripeLiveKeyInput = document.getElementsByName('stripe_live_secret_key')[0];
            [stripeTestKeyInput, stripeLiveKeyInput].forEach(field => {
                field.addEventListener('input', function() {
                    if (this.value.trim() === '') return;
                    stripeEnabled.checked = true;
                    if (field === stripeTestKeyInput && stripeLiveKeyInput.value.trim() === '') {
                        stripeSandboxMode.checked = true;
                    }
                    toggleFields();
                });
            });

            const paypalTestKeyInput = document.getElementsByName('paypal_sandbox_client_id')[0];
            const paypalLiveKeyInput = document.getElementsByName('paypal_client_id')[0];
            [paypalTestKeyInput, paypalLiveKeyInput].forEach(field => {
                field.addEventListener('input', function() {
                    if (this.value.trim() === '') return;
                    paypalEnabled.checked = true;
                    if (field === paypalTestKeyInput && paypalLiveKeyInput.value.trim() === '') {
                        paypalSandboxMode.checked = true;
                    }
                    toggleFields();
                });
            });

            toggleFields();

            document.getElementById('settingsForm').addEventListener('submit', function(e) {
                let isValid = true;
                const errorMessages = [];

                    
            });
        });
    </script>
@endsection
