<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Schema;

class UpdateHomeHeroSeaGreenLandscapes extends Migration
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

        // Main hero background: calm beach at golden hour — symbolises hope and the impact of giving
        $newHeroBg = 'https://images.unsplash.com/photo-1507525428034-b723cf961d3e?auto=format&fit=crop&w=1920&q=80';

        if (empty($settings['hero_bg_image_path'])) {
            $settings['hero_bg_external_url'] = $newHeroBg;
        }

        if (empty($settings['hero_side_panel_image_path'])) {
            $settings['hero_side_panel_external_url'] = '';
        }

        // Slightly stronger overlay so text always reads on top of the scenery
        $settings['hero_overlay'] = 0.55;

        // 3 visually distinct sea/green scenery photos, each thematically tied to giving
        $sceneryUrls = [
            'https://images.unsplash.com/photo-1470770841072-f978cf4d019e?auto=format&fit=crop&w=1920&q=80', // Alpine lake reflection — reflection & calm
            'https://images.unsplash.com/photo-1500382017468-9049fed747ef?auto=format&fit=crop&w=1920&q=80', // Green meadow with light — growth & abundance
            'https://images.unsplash.com/photo-1473773508845-188df298d2d1?auto=format&fit=crop&w=1920&q=80', // Open sea horizon — hope & opportunity
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
