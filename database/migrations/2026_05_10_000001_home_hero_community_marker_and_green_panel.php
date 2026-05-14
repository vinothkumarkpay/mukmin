<?php

use App\Models\Widget;
use Illuminate\Database\Migrations\Migration;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Schema;

/**
 * Inserts the zero-width anchor after “Community” for side-panel alignment, and
 * points default side-panel URLs at the green/teal abstract when still on the old team photo.
 */
class HomeHeroCommunityMarkerAndGreenPanel extends Migration
{
    public function up()
    {
        if (! Schema::hasTable('widgets')) {
            return;
        }

        $needle = '<span class="mukmin-hero__line">One Community.</span>';
        $replacement = '<span class="mukmin-hero__line">One Community<span class="mukmin-hero__side-panel-origin" aria-hidden="true"></span>.</span>';
        $newPanelUrl = Widget::HERO_SIDE_PANEL_DEFAULT_IMAGE_URL;

        $rows = DB::table('widgets')->where('slug', 'home-hero')->get();
        foreach ($rows as $row) {
            $origContent = (string) $row->content;
            $content = $origContent;
            if (strpos($content, 'mukmin-hero__side-panel-origin') === false && strpos($content, $needle) !== false) {
                $content = str_replace($needle, $replacement, $content);
            }

            $settings = [];
            if (! empty($row->settings)) {
                $decoded = json_decode($row->settings, true);
                $settings = is_array($decoded) ? $decoded : [];
            }

            $cur = isset($settings['hero_side_panel_external_url']) ? trim((string) $settings['hero_side_panel_external_url']) : '';
            $settingsChanged = false;
            if (empty($settings['hero_side_panel_image_path']) && ($cur === '' || strpos($cur, 'photo-1522071820081') !== false)) {
                $settings['hero_side_panel_external_url'] = $newPanelUrl;
                $settingsChanged = true;
            }

            $updates = ['updated_at' => now()];
            if ($content !== $origContent) {
                $updates['content'] = $content;
            }
            if ($settingsChanged) {
                $updates['settings'] = json_encode($settings);
            }

            if (count($updates) > 1) {
                DB::table('widgets')->where('id', $row->id)->update($updates);
            }
        }
    }

    public function down()
    {
        //
    }
}
