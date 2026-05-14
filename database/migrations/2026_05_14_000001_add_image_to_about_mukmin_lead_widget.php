<?php

use App\Models\Widget;
use Illuminate\Database\Migrations\Migration;

return new class extends Migration
{
    /**
     * Add image to the about-mukmin lead widget for a professional visual layout.
     */
    public function up(): void
    {
        $widget = Widget::query()->where('slug', 'about-mukmin-lead')->first();
        if (! $widget) {
            return;
        }

        $settings = $widget->settings ?? [];
        $settings['image_url'] = 'https://images.unsplash.com/photo-1529156069898-49953e39b3ac?auto=format&fit=crop&w=1200&h=800&q=80';

        $widget->update(['settings' => $settings]);
    }

    public function down(): void
    {
        $widget = Widget::query()->where('slug', 'about-mukmin-lead')->first();
        if (! $widget) {
            return;
        }

        $settings = $widget->settings ?? [];
        unset($settings['image_url']);
        $widget->update(['settings' => $settings]);
    }
};
