<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Schema;

class UpdateMenuItemsContactAndMediaCenter extends Migration
{
    public function up()
    {
        if (! Schema::hasTable('menu_items')) {
            return;
        }

        $now = now();

        DB::table('menu_items')
            ->where('label', 'CTA / Partners')
            ->orWhere('url', '/page/cta-partners')
            ->update([
                'label' => 'Contact Us',
                'url' => '/contact-us',
                'sort_order' => 60,
                'is_active' => true,
                'updated_at' => $now,
            ]);

        $mediaCenterId = DB::table('menu_items')
            ->where('label', 'Media Center')
            ->whereNull('parent_id')
            ->value('id');

        if (! $mediaCenterId) {
            $mediaCenterId = DB::table('menu_items')->insertGetId([
                'parent_id' => null,
                'label' => 'Media Center',
                'url' => '#',
                'sort_order' => 50,
                'is_active' => true,
                'open_new_tab' => false,
                'created_at' => $now,
                'updated_at' => $now,
            ]);
        } else {
            DB::table('menu_items')->where('id', $mediaCenterId)->update([
                'url' => '#',
                'sort_order' => 50,
                'is_active' => true,
                'updated_at' => $now,
            ]);
        }

        $children = [
            ['label' => 'News Letter', 'url' => '/page/news-letter', 'sort_order' => 0],
            ['label' => 'Gallery', 'url' => '/page/gallery', 'sort_order' => 10],
        ];

        foreach ($children as $child) {
            $existing = DB::table('menu_items')
                ->where('parent_id', $mediaCenterId)
                ->where('label', $child['label'])
                ->first();

            if ($existing) {
                DB::table('menu_items')->where('id', $existing->id)->update([
                    'url' => $child['url'],
                    'sort_order' => $child['sort_order'],
                    'is_active' => true,
                    'updated_at' => $now,
                ]);
            } else {
                DB::table('menu_items')->insert([
                    'parent_id' => $mediaCenterId,
                    'label' => $child['label'],
                    'url' => $child['url'],
                    'sort_order' => $child['sort_order'],
                    'is_active' => true,
                    'open_new_tab' => false,
                    'created_at' => $now,
                    'updated_at' => $now,
                ]);
            }
        }
    }

    public function down()
    {
        if (! Schema::hasTable('menu_items')) {
            return;
        }

        $now = now();

        $mediaCenterId = DB::table('menu_items')
            ->where('label', 'Media Center')
            ->whereNull('parent_id')
            ->value('id');

        if ($mediaCenterId) {
            DB::table('menu_items')->where('parent_id', $mediaCenterId)->delete();
            DB::table('menu_items')->where('id', $mediaCenterId)->delete();
        }

        DB::table('menu_items')
            ->where('label', 'Contact Us')
            ->where('url', '/contact-us')
            ->update([
                'label' => 'CTA / Partners',
                'url' => '/page/cta-partners',
                'sort_order' => 50,
                'updated_at' => $now,
            ]);
    }
}
