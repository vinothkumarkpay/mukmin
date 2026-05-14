<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Schema;

/**
 * Points the home hero side panel at the greenery + people default and replaces
 * the abstract URL that could fail to load for some environments.
 */
class HomeHeroSidePanelGreeneryPeopleImage extends Migration
{
    public function up()
    {
        if (! Schema::hasTable('widgets')) {
            return;
        }

        $abstract = 'https://images.unsplash.com/photo-1614850523296-d8c1af93d866?auto=format&fit=crop&w=1800&h=1200&q=82';
        $team = 'https://images.unsplash.com/photo-1522071820081-009f0129c71c?auto=format&fit=crop&w=1800&h=1200&q=82';

        $rows = DB::table('widgets')->where('slug', 'home-hero')->get();
        foreach ($rows as $row) {
            $settings = [];
            if (! empty($row->settings)) {
                $decoded = json_decode($row->settings, true);
                $settings = is_array($decoded) ? $decoded : [];
            }
            if (! empty($settings['hero_side_panel_image_path'])) {
                continue;
            }
            $cur = isset($settings['hero_side_panel_external_url']) ? trim((string) $settings['hero_side_panel_external_url']) : '';
            $should = $cur === '' || $cur === $abstract || $cur === $team || strpos($cur, 'photo-1614850523296') !== false;
            if (! $should) {
                continue;
            }
            unset($settings['hero_side_panel_external_url']);
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
