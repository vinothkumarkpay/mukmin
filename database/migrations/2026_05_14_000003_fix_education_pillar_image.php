<?php

use App\Models\Widget;
use Illuminate\Database\Migrations\Migration;

return new class extends Migration
{
    /**
     * Fix the broken Education & Talent Pipeline pillar image URL.
     */
    public function up(): void
    {
        $widget = Widget::query()->where('slug', 'impact-areas-pillars')->first();
        if (! $widget) {
            return;
        }

        $settings = $widget->settings ?? [];
        $pillars = $settings['pillars'] ?? [];

        // Pillar index 1 = Education & Talent Pipeline — fix the broken URL
        if (isset($pillars[1])) {
            $pillars[1]['image_url'] = 'https://images.unsplash.com/photo-1524178232363-1fb2b075b655?auto=format&fit=crop&w=800&h=400&q=80';
        }

        $settings['pillars'] = $pillars;
        $widget->update(['settings' => $settings]);
    }

    public function down(): void
    {
        // No rollback needed — original was broken anyway
    }
};
