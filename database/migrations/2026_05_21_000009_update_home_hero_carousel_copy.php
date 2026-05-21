<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Schema;

class UpdateHomeHeroCarouselCopy extends Migration
{
    public function up()
    {
        if (! Schema::hasTable('widgets')) {
            return;
        }

        $row = DB::table('widgets')->where('slug', 'home-hero')->first();
        if (! $row) {
            return;
        }

        $now = now();

        // 1) Slide 0 — refresh the HTML `content` field with the new body copy
        $newHeroContent = <<<'HTML'
<h1 id="mukmin-hero-heading" class="mukmin-hero__headline">
    <span class="mukmin-hero__line">One Identity.</span>
    <span class="mukmin-hero__line">One Vision.</span>
    <span class="mukmin-hero__line">One Community<span class="mukmin-hero__side-panel-origin" aria-hidden="true"></span>.</span>
</h1>
<div class="mukmin-hero__sub">
    <p class="mukmin-hero__subline">MUKMIN is a national platform advancing inclusive community development, empowering people, shaping future-ready talent and strengthening collaboration through a unified, values-driven ecosystem.</p>
    <p class="mukmin-hero__subline">We connect communities, align stakeholders and turn ideas into action&mdash;creating real opportunities across Malaysia.</p>
</div>
HTML;

        // 2) Slides 1..3 — update titles/subtitles/descriptions while preserving images & links
        $newSlides = [
            [
                'title' => 'SIRAT Series',
                'subtitle' => 'Developing Leaders. Connecting Communities. Driving Impact.',
                'description' => 'From youth development to global leadership engagement, the SIRAT Series nurtures future-ready leaders equipped with values, vision and purpose. Through meaningful dialogue, strategic collaboration and community-driven initiatives, we cultivate a generation committed to unity, progress and shared prosperity.',
            ],
            [
                'title' => 'MUKMIN Future Leaders Scholarship',
                'subtitle' => 'Unlock Your Future. Lead with Purpose.',
                'description' => 'The MUKMIN Future Leaders Scholarship empowers promising individuals through education, mentorship and leadership development opportunities. Beyond financial support, we invest in character, innovation and community impact — shaping future leaders who will contribute meaningfully to society and nation-building.',
            ],
            [
                'title' => 'Kembara Ramadhan MUKMIN',
                'subtitle' => 'Compassion in Action. Unity in Giving.',
                'description' => 'Kembara Ramadhan MUKMIN brings communities together through meaningful outreach, humanitarian support and acts of service during the blessed month of Ramadan. From food aid initiatives to community engagement programmes, we strengthen the spirit of compassion, dignity and collective responsibility across society.',
            ],
        ];

        $settings = [];
        if (! empty($row->settings)) {
            $decoded = json_decode($row->settings, true);
            $settings = is_array($decoded) ? $decoded : [];
        }

        $existingSlides = $settings['hero_slides'] ?? [];
        if (! is_array($existingSlides)) {
            $existingSlides = [];
        }

        $merged = [];
        foreach ($newSlides as $i => $payload) {
            $prev = isset($existingSlides[$i]) && is_array($existingSlides[$i]) ? $existingSlides[$i] : [];
            // Merge new copy onto existing slide so image_url / link_url / image_path / cta_label / new_tab survive
            $merged[$i] = array_merge($prev, $payload);
            // Ensure all required keys exist
            $merged[$i] += [
                'cta_label' => $prev['cta_label'] ?? '',
                'link_url' => $prev['link_url'] ?? '',
                'image_url' => $prev['image_url'] ?? '',
                'image_path' => $prev['image_path'] ?? null,
                'new_tab' => $prev['new_tab'] ?? false,
            ];
        }

        $settings['hero_slides'] = $merged;

        DB::table('widgets')->where('id', $row->id)->update([
            'content' => $newHeroContent,
            'settings' => json_encode($settings),
            'updated_at' => $now,
        ]);
    }

    public function down()
    {
        //
    }
}
