<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Schema;

/**
 * Existing home-hero rows store gradient colours in JSON; those values override
 * code defaults, so earlier palette tweaks did not appear until the widget was
 * re-saved. This migration applies the mud / earth-tone hero defaults in the DB.
 */
class UpdateHomeHeroWidgetMudGradients extends Migration
{
    public function up()
    {
        if (! Schema::hasTable('widgets')) {
            return;
        }

        $rows = DB::table('widgets')->where('slug', 'home-hero')->get();
        foreach ($rows as $row) {
            $settings = [];
            if (! empty($row->settings)) {
                $decoded = json_decode($row->settings, true);
                $settings = is_array($decoded) ? $decoded : [];
            }
            $settings['hero_gradient_1'] = '#dcd4cb';
            $settings['hero_gradient_2'] = '#6f5f52';
            $settings['hero_gradient_3'] = '#555c52';
            $settings['hero_overlay'] = 0.46;

            DB::table('widgets')->where('id', $row->id)->update([
                'settings' => json_encode($settings),
                'updated_at' => now(),
            ]);
        }
    }

    public function down()
    {
        //
    }
}
