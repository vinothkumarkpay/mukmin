<?php

use App\Models\Widget;
use Illuminate\Database\Migrations\Migration;

return new class extends Migration
{
    /**
     * Add default header images to each impact area pillar for a visual grid layout.
     * Images are thematic Unsplash photos matching each pillar's focus area.
     */
    public function up(): void
    {
        $widget = Widget::query()->where('slug', 'impact-areas-pillars')->first();
        if (! $widget) {
            return;
        }

        $settings = $widget->settings ?? [];
        $pillars = $settings['pillars'] ?? [];

        // Thematic images for each of the 5 impact pillars
        $images = [
            // 1. Socio-Economic Mobility — community market / enterprise
            'https://images.unsplash.com/photo-1556761175-5973dc0f32e7?auto=format&fit=crop&w=800&h=400&q=80',
            // 2. Education & Talent Pipeline — students / learning
            'https://images.unsplash.com/photo-1524178232363-1fb2b075b655?auto=format&fit=crop&w=800&h=400&q=80',
            // 3. Leadership & Capacity Building — conference / leadership
            'https://images.unsplash.com/photo-1540575467063-178a50c2df87?auto=format&fit=crop&w=800&h=400&q=80',
            // 4. Entrepreneurship & Innovation — startup / technology
            'https://images.unsplash.com/photo-1519389950473-47ba0277781c?auto=format&fit=crop&w=800&h=400&q=80',
            // 5. Faith, Identity & Social Cohesion — community / mosque
            'https://images.unsplash.com/photo-1564769625905-50e93615e769?auto=format&fit=crop&w=800&h=400&q=80',
        ];

        foreach ($pillars as $idx => &$pillar) {
            if (isset($images[$idx])) {
                $pillar['image_url'] = $images[$idx];
            }
        }
        unset($pillar);

        $settings['pillars'] = $pillars;
        $widget->update(['settings' => $settings]);
    }

    public function down(): void
    {
        $widget = Widget::query()->where('slug', 'impact-areas-pillars')->first();
        if (! $widget) {
            return;
        }

        $settings = $widget->settings ?? [];
        $pillars = $settings['pillars'] ?? [];

        foreach ($pillars as &$pillar) {
            unset($pillar['image_url']);
        }
        unset($pillar);

        $settings['pillars'] = $pillars;
        $widget->update(['settings' => $settings]);
    }
};
