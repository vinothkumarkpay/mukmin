<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Schema;

class UpdateSiratSlideTropicalSea extends Migration
{
    /**
     * Replace the SIRAT hero slide image with a clear tropical sea photo (better for split layout).
     */
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

        $newSiratImage = 'https://images.unsplash.com/photo-1507525428034-b723cf961d3e?auto=format&fit=crop&w=1920&h=1200&q=82';

        $slides = isset($settings['hero_slides']) && is_array($settings['hero_slides']) ? $settings['hero_slides'] : [];

        if (isset($slides[0]) && is_array($slides[0]) && empty($slides[0]['image_path'])) {
            $cur = trim((string) ($slides[0]['image_url'] ?? ''));
            if ($cur === '' || str_contains($cur, 'photo-1518837695005') || str_contains($cur, 'photo-1544551763')) {
                $slides[0]['image_url'] = $newSiratImage;
            }
        }

        $settings['hero_slides'] = $slides;

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
