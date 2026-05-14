<?php

use App\Models\Widget;
use Illuminate\Database\Migrations\Migration;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Schema;

/**
 * Ensures existing home-hero widgets get the default side-panel image URL in settings
 * so the admin form and exports stay in sync with code defaults.
 */
class AddHomeHeroSidePanelUrlToWidgets extends Migration
{
    public function up()
    {
        if (! Schema::hasTable('widgets')) {
            return;
        }

        $defaultUrl = Widget::HERO_SIDE_PANEL_DEFAULT_IMAGE_URL;
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
            $hasUrl = isset($settings['hero_side_panel_external_url'])
                && is_string($settings['hero_side_panel_external_url'])
                && trim($settings['hero_side_panel_external_url']) !== '';
            if ($hasUrl) {
                continue;
            }
            $settings['hero_side_panel_external_url'] = $defaultUrl;
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
