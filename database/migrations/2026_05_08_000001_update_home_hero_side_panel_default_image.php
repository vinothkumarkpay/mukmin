<?php

use App\Models\Widget;
use Illuminate\Database\Migrations\Migration;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Schema;

/**
 * Replaces the previous workshop stock default with the community-themed default
 * when the stored URL still matches the old value (no custom image upload).
 */
class UpdateHomeHeroSidePanelDefaultImage extends Migration
{
    public function up()
    {
        if (! Schema::hasTable('widgets')) {
            return;
        }

        $legacy = 'https://images.unsplash.com/photo-1552664730-d307ca884978?auto=format&fit=crop&w=1400&h=1050&q=82';
        $next = Widget::HERO_SIDE_PANEL_DEFAULT_IMAGE_URL;

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
            if ($cur !== $legacy) {
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
