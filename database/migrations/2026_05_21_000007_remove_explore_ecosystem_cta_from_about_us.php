<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Schema;

class RemoveExploreEcosystemCtaFromAboutUs extends Migration
{
    public function up()
    {
        if (! Schema::hasTable('widgets')) {
            return;
        }

        $row = DB::table('widgets')->where('slug', 'about-mukmin-lead')->first();
        if (! $row) {
            return;
        }

        $settings = [];
        if (! empty($row->settings)) {
            $decoded = json_decode($row->settings, true);
            $settings = is_array($decoded) ? $decoded : [];
        }

        if (! isset($settings['ctas']) || ! is_array($settings['ctas'])) {
            return;
        }

        $settings['ctas'] = array_values(array_filter($settings['ctas'], function ($cta) {
            if (! is_array($cta)) {
                return true;
            }
            $label = strtolower(trim((string) ($cta['label'] ?? '')));
            $url = strtolower(trim((string) ($cta['url'] ?? '')));

            // Drop the "Explore Our Ecosystem" CTA specifically
            if ($label === 'explore our ecosystem' || $url === '/page/our-ecosystem') {
                return false;
            }

            return true;
        }));

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
