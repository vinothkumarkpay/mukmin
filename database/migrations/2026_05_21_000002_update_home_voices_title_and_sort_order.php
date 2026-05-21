<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Schema;

class UpdateHomeVoicesTitleAndSortOrder extends Migration
{
    public function up()
    {
        if (! Schema::hasTable('widgets')) {
            return;
        }

        $row = DB::table('widgets')->where('slug', 'home-voices')->first();
        if ($row) {
            $settings = [];
            if (! empty($row->settings)) {
                $decoded = json_decode($row->settings, true);
                $settings = is_array($decoded) ? $decoded : [];
            }

            $settings['voices_title'] = 'Voices of Mukmin';
            $settings['voices_eyebrow'] = '';

            DB::table('widgets')->where('id', $row->id)->update([
                'title' => 'Voices of Mukmin',
                'sort_order' => 15,
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
