<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Schema;

class UpdateSiratSlideToBlueOcean extends Migration
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

        $newSiratImage = 'https://images.unsplash.com/photo-1518837695005-2083093ee35b?auto=format&fit=crop&w=1920&q=80';

        $slides = isset($settings['hero_slides']) && is_array($settings['hero_slides']) ? $settings['hero_slides'] : [];

        if (isset($slides[0]) && is_array($slides[0]) && empty($slides[0]['image_path'])) {
            $slides[0]['image_url'] = $newSiratImage;
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
