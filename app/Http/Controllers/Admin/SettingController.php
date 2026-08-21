<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Setting;
use Illuminate\Http\Request;

class SettingController extends Controller
{
    public function index()
    {
        $settings = Setting::all()->pluck('value', 'key')->toArray();
        return view('admin.settings.index', compact('settings'));
    }

    public function store(Request $request)
    {
        try {
            $this->updateOrCreateSetting('dashboard_title', $request->dashboard_title, 'dashboard');
            $this->updateOrCreateSetting('website_copyright', $request->website_copyright, 'dashboard');
            $this->updateOrCreateSetting('designed_developed', $request->designed_developed, 'dashboard');

            $this->updateOrCreateSetting('website_title', $request->website_title, 'website');
            $this->updateOrCreateSetting('website_copyright', $request->website_copyright, 'website');

            $this->updateOrCreateSetting('stripe_enabled', $request->stripe_enabled ?? 'off', 'stripe');
            $this->updateOrCreateSetting('stripe_sandbox_mode', $request->stripe_sandbox_mode ?? 'off', 'stripe');
            $this->updateOrCreateSetting('stripe_test_key', $request->stripe_test_key, 'stripe');
            $this->updateOrCreateSetting('stripe_private_test_key', $request->stripe_private_test_key, 'stripe');
            $this->updateOrCreateSetting('stripe_live_secret_key', $request->stripe_live_secret_key, 'stripe');
            $this->updateOrCreateSetting('stripe_private_live_key', $request->stripe_private_live_key, 'stripe');

            $this->updateOrCreateSetting('paypal_sandbox_client_id', $request->paypal_sandbox_client_id, 'paypal');
            $this->updateOrCreateSetting('paypal_sandbox_client_secret', $request->paypal_sandbox_client_secret, 'paypal');
            $this->updateOrCreateSetting('paypal_client_id', $request->paypal_client_id, 'paypal');
            $this->updateOrCreateSetting('paypal_client_secret', $request->paypal_client_secret, 'paypal');
            $this->updateOrCreateSetting('paypal_enabled', $request->paypal_enabled ?? 'off', 'paypal');

            $this->updateOrCreateSetting('signup_title', $request->signup_title, 'website');
            $this->updateOrCreateSetting('contact_heading', $request->contact_heading, 'website');
            
            $this->updateOrCreateSetting('footer_description', $request->footer_description, 'website');
            $this->updateOrCreateSetting('footer_address', $request->footer_address, 'website');
            $this->updateOrCreateSetting('footer_email', $request->footer_email, 'website');
            $this->updateOrCreateSetting('footer_phone', $request->footer_phone, 'website');

            
            $this->updateOrCreateSetting('facebook_url', $request->facebook_url, 'social');
            $this->updateOrCreateSetting('twitter_url', $request->twitter_url, 'social');
            $this->updateOrCreateSetting('linkedin_url', $request->linkedin_url, 'social');
            $this->updateOrCreateSetting('instagram_url', $request->instagram_url, 'social');
            $this->updateOrCreateSetting('followus_description', $request->followus_description, 'website');

            $this->updateOrCreateSetting('login_title', $request->login_title, 'website');

            if ($request->hasFile('dashboard_logo')) {
                $setting = Setting::where('key', 'dashboard_logo')->first();
                if (!$setting) {
                    $setting = Setting::create(['key' => 'dashboard_logo', 'group' => 'dashboard']);
                }
                $setting->clearMediaCollection('logos');
                $setting->addMediaFromRequest('dashboard_logo')->toMediaCollection('logos');
            }

            if ($request->hasFile('header_logo')) {
                $setting = Setting::where('key', 'header_logo')->first();
                if (!$setting) {
                    $setting = Setting::create(['key' => 'header_logo', 'group' => 'website']);
                }
                $setting->clearMediaCollection('logos');
                $setting->addMediaFromRequest('header_logo')->toMediaCollection('logos');
            }

            if ($request->hasFile('footer_logo')) {
                $setting = Setting::where('key', 'footer_logo')->first();
                if (!$setting) {
                    $setting = Setting::create(['key' => 'footer_logo', 'group' => 'website']);
                }
                $setting->clearMediaCollection('logos');
                $setting->addMediaFromRequest('footer_logo')->toMediaCollection('logos');
            }

            if ($request->hasFile('login_image')) {
                $setting = Setting::where('key', 'login_image')->first();
                if (!$setting) {
                    $setting = Setting::create(['key' => 'login_image', 'group' => 'website']);
                }
                $setting->clearMediaCollection('logos');
                $setting->addMediaFromRequest('login_image')->toMediaCollection('logos');
            }

            if ($request->hasFile('favicon')) {
                $setting = Setting::where('key', 'favicon')->first();
                if (!$setting) {
                    $setting = Setting::create(['key' => 'favicon', 'group' => 'website']);
                }
                $setting->clearMediaCollection('logos');
                $setting->addMediaFromRequest('favicon')->toMediaCollection('logos');
            }

            return back()->with('success', 'Settings updated successfully!');
        } catch (\Exception $e) {
            return back()->with('error', 'Error updating settings: ' . $e->getMessage());
        }
    }

    private function updateOrCreateSetting($key, $value, $group)
    {
        Setting::updateOrCreate(
            ['key' => $key],
            ['value' => $value, 'group' => $group]
        );
    }
}
