<?php
 
use Illuminate\Database\Migrations\Migration;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Schema;

class UpdateExistingHomeVoicesWidget extends Migration
{
    public function up()
    {
        if (! Schema::hasTable('widgets')) {
            return;
        }

        $rows = DB::table('widgets')->where('slug', 'home-voices')->get();
        foreach ($rows as $row) {
            $settings = [];
            if (! empty($row->settings)) {
                $decoded = json_decode($row->settings, true);
                $settings = is_array($decoded) ? $decoded : [];
            }
            
            $settings['voices_eyebrow'] = '';
            $settings['voices_title'] = 'Voice of MUKMIN';

            $items = isset($settings['voices_items']) && is_array($settings['voices_items']) ? $settings['voices_items'] : [];
            $dummyUrls = [
                'https://www.youtube.com/watch?v=9No-FiE9yyo',
                'https://www.youtube.com/watch?v=k1w5Z7d5a5s',
                'https://www.youtube.com/watch?v=Y8Z65v_bX2g',
                'https://www.youtube.com/watch?v=x7Mh-gL6kik',
                'https://www.youtube.com/watch?v=tgbNymZ7vqY',
            ];

            for ($i = 0; $i < count($items); $i++) {
                if (isset($dummyUrls[$i])) {
                    $items[$i]['quote'] = $dummyUrls[$i];
                }
            }
            $settings['voices_items'] = $items;

            DB::table('widgets')->where('id', $row->id)->update([
                'title' => 'Voice of MUKMIN',
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
