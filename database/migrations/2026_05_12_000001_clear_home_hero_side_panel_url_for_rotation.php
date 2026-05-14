<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Schema;

/**
 * Removes baked-in Unsplash side-panel URLs so {@see Widget::heroSidePanelDefaultImageUrl()}
 * can rotate through green / community stock when no custom URL is set.
 */
class ClearHomeHeroSidePanelUrlForRotation extends Migration
{
    public function up()
    {
        if (! Schema::hasTable('widgets')) {
            return;
        }

        $needles = [
            'photo-1559027615',
            'photo-1593113598338',
            'photo-1523580494863',
            'photo-1491438590914',
            'photo-1469571486292',
            'photo-1614850523296',
            'photo-1522071820081',
            'photo-1552664730',
            'photo-1529156069898',
        ];

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
            if ($cur === '' || strpos($cur, 'images.unsplash.com') === false) {
                continue;
            }
            $clear = false;
            foreach ($needles as $id) {
                if (strpos($cur, $id) !== false) {
                    $clear = true;
                    break;
                }
            }
            if (! $clear) {
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
