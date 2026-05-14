<?php

use App\Models\Widget;
use Illuminate\Database\Migrations\Migration;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Schema;

/**
 * Points the hero side panel away from carousel image photo-1529156069898 and
 * older defaults, toward {@see Widget::HERO_SIDE_PANEL_DEFAULT_IMAGE_URL}.
 */
class UpdateHomeHeroSidePanelImageAwayFromCarousel extends Migration
{
    public function up()
    {
        if (! Schema::hasTable('widgets')) {
            return;
        }

        $next = Widget::HERO_SIDE_PANEL_DEFAULT_IMAGE_URL;
        $legacyWorkshop = 'https://images.unsplash.com/photo-1552664730-d307ca884978?auto=format&fit=crop&w=1400&h=1050&q=82';
        $carouselDup = 'https://images.unsplash.com/photo-1529156069898-49953e39b3ac?auto=format&fit=crop&w=1800&h=1200&q=82';

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
            if ($cur === '') {
                continue;
            }
            $isCarouselPhoto = strpos($cur, 'images.unsplash.com/photo-1529156069898') !== false;
            if ($cur !== $legacyWorkshop && $cur !== $carouselDup && ! $isCarouselPhoto) {
                continue;
            }
            $settings['hero_side_panel_external_url'] = $next;
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
