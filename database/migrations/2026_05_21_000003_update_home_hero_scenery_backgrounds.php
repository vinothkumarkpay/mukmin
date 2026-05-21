<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Schema;

class UpdateHomeHeroSceneryBackgrounds extends Migration
{
    public function up()
    {
        if (! Schema::hasTable('widgets')) {
            return;
        }

        $row = DB::table('widgets')->where('slug', 'home-hero')->first();
        if (! $row) {
            return;
        }

        $settings = [];
        if (! empty($row->settings)) {
            $decoded = json_decode($row->settings, true);
            $settings = is_array($decoded) ? $decoded : [];
        }

        // Main hero background: layered misty mountain peaks (calm, professional)
        $newHeroBg = 'https://images.unsplash.com/photo-1469474968028-56623f02e42e?auto=format&fit=crop&w=1920&q=80';

        // Only overwrite if the admin hasn't uploaded a custom image
        if (empty($settings['hero_bg_image_path'])) {
            $settings['hero_bg_external_url'] = $newHeroBg;
        }

        if (empty($settings['hero_side_panel_image_path'])) {
            $settings['hero_side_panel_external_url'] = '';
        }

        // Soften the overlay a touch so text reads cleanly on photography
        $settings['hero_overlay'] = 0.52;

        $sceneryUrls = [
            'https://images.unsplash.com/photo-1469474968028-56623f02e42e?auto=format&fit=crop&w=1920&q=80', // Misty mountain peaks
            'https://images.unsplash.com/photo-1418065460487-3956c3a31867?auto=format&fit=crop&w=1920&q=80', // Sun rays through forest
            'https://images.unsplash.com/photo-1501785888041-af3ef285b470?auto=format&fit=crop&w=1920&q=80', // Alpine lake reflection
        ];

        $existingSlides = isset($settings['hero_slides']) && is_array($settings['hero_slides']) ? $settings['hero_slides'] : [];
        for ($i = 0; $i < 3; $i++) {
            $slide = isset($existingSlides[$i]) && is_array($existingSlides[$i]) ? $existingSlides[$i] : [];

            // Don't touch slides where admin has uploaded a real image file
            if (empty($slide['image_path']) && isset($sceneryUrls[$i])) {
                $slide['image_url'] = $sceneryUrls[$i];
            }

            $existingSlides[$i] = $slide;
        }
        $settings['hero_slides'] = $existingSlides;

        DB::table('widgets')->where('id', $row->id)->update([
            'settings' => json_encode($settings),
            'updated_at' => now(),
        ]);
    }

    public function down()
    {
        //
    }
}
