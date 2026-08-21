<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Spatie\MediaLibrary\HasMedia;
use Spatie\MediaLibrary\InteractsWithMedia;

class Setting extends Model implements HasMedia
{
    use InteractsWithMedia;

    protected $fillable = ['key', 'value', 'group'];

    protected $casts = [
        'value' => 'array'
    ];

    public function registerMediaCollections(): void
    {
        $this->addMediaCollection('logos')
             ->singleFile()
             ->acceptsMimeTypes(['image/jpeg', 'image/png', 'image/gif', 'image/webp']);
    }

    public function getLogoUrl($type = 'dashboard')
    {
        return $this->getFirstMediaUrl('logos', $type);
    }

    public static function getValue($key, $default = null)
    {
        $setting = static::where('key', $key)->first();
        return $setting ? $setting->value : $default;
    }

    /**
     * Resolve which Stripe key pair is currently active, based on the
     * "Enable Stripe" / "Sandbox mode" toggles in admin settings.
     */
    public static function stripeKeys(): array
    {
        if (static::getValue('stripe_enabled') !== 'on') {
            return ['enabled' => false, 'key' => null, 'secret' => null];
        }

        $sandbox = static::getValue('stripe_sandbox_mode') === 'on';

        $key = $sandbox ? static::getValue('stripe_test_key') : static::getValue('stripe_live_secret_key');
        $secret = $sandbox ? static::getValue('stripe_private_test_key') : static::getValue('stripe_private_live_key');

        if (empty($key) || empty($secret)) {
            return ['enabled' => false, 'key' => null, 'secret' => null];
        }

        return ['enabled' => true, 'key' => $key, 'secret' => $secret];
    }
}
