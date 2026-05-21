<?php

namespace App\Support;

use App\Models\SiteSetting;
use Illuminate\Support\Facades\Storage;

class SiteBranding
{
    /**
     * Default logo used in seeders when no custom branding exists.
     */
    public static function defaultExternalLogoUrl(): string
    {
        return '/images/mukmin_logo.png';
    }

    /**
     * Public URL for the logo image, or null when only the site name should show.
     */
    public static function logoUrlForDisplay(): ?string
    {
        $path = SiteSetting::get('site_logo_path');
        if (is_string($path) && $path !== '' && Storage::disk('public')->exists($path)) {
            return Storage::disk('public')->url($path);
        }

        $url = SiteSetting::get('site_logo_url');
        if (is_string($url) && trim($url) !== '') {
            return trim($url);
        }

        return null;
    }
}
