<?php

namespace Database\Seeders;

use App\Models\CmsPage;
use App\Models\MenuItem;
use App\Models\SiteSetting;
use App\Models\User;
use App\Models\Widget;
use App\Support\SiteBranding;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\Hash;

class PortalSeeder extends Seeder
{
    public function run()
    {
        SiteSetting::set('site_name', 'Mukmin');
        SiteSetting::set('site_logo_url', SiteBranding::defaultExternalLogoUrl());
        SiteSetting::set('site_logo_path', '');
        SiteSetting::forgetRuntimeCache();

        $cmsSeeds = [
            [
                'slug' => 'about-mukmin',
                'title' => 'About MUKMIN',
                'excerpt' => 'Mission, values, and how we serve communities.',
                'body' => '<p>Edit this overview in <strong>Admin → CMS pages</strong>. Use the menu <em>About MUKMIN</em> for Who We Are and The Team.</p>',
            ],
            [
                'slug' => 'who-we-are',
                'title' => 'Who We Are',
                'excerpt' => 'Identity, purpose, and the story behind MUKMIN.',
                'body' => '<p>Replace with your organisation narrative. Linked from <strong>About MUKMIN → Who We Are</strong>.</p>',
            ],
            [
                'slug' => 'the-team',
                'title' => 'The Team',
                'excerpt' => 'Leadership and people behind the work.',
                'body' => '<p>Add bios and roles here. Linked from <strong>About MUKMIN → The Team</strong>.</p>',
            ],
            [
                'slug' => 'our-ecosystem',
                'title' => 'Our Ecosystem',
                'excerpt' => 'Partners, programmes, and how the pieces connect.',
                'body' => '<p>Describe networks, alliances, and operating context. Edit in the CMS.</p>',
            ],
            [
                'slug' => 'impact-areas',
                'title' => 'Impact Areas',
                'excerpt' => 'Where we focus and how we measure change.',
                'body' => '<p>Outline thematic impact pillars. Update copy and media in the CMS.</p>',
            ],
            [
                'slug' => 'featured-initiatives',
                'title' => 'Featured Initiatives',
                'excerpt' => 'Highlighted programmes and campaigns.',
                'body' => '<p>Showcase flagship initiatives. Swap in cards, timelines, or stories as needed.</p>',
            ],
            [
                'slug' => 'cta-partners',
                'title' => 'CTA / Partners',
                'excerpt' => 'Partner with us and take the next step.',
                'body' => '<p>Calls to action, partnership tiers, and contact paths. Maintain alongside your live partner pipeline.</p>',
            ],
        ];

        foreach ($cmsSeeds as $row) {
            CmsPage::query()->updateOrCreate(
                ['slug' => $row['slug']],
                [
                    'title' => $row['title'],
                    'excerpt' => $row['excerpt'],
                    'body' => $row['body'],
                    'widgets_only' => false,
                    'is_published' => true,
                    'published_at' => now(),
                ]
            );
        }

        MenuItem::query()->whereNotNull('parent_id')->delete();
        MenuItem::query()->whereNull('parent_id')->delete();

        MenuItem::query()->create([
            'parent_id' => null,
            'label' => 'Home',
            'url' => '/',
            'sort_order' => 0,
            'is_active' => true,
            'open_new_tab' => false,
        ]);

        $aboutMukmin = MenuItem::query()->create([
            'parent_id' => null,
            'label' => 'About MUKMIN',
            'url' => '/page/about-mukmin',
            'sort_order' => 10,
            'is_active' => true,
            'open_new_tab' => false,
        ]);

        MenuItem::query()->create([
            'parent_id' => $aboutMukmin->id,
            'label' => 'Who We Are',
            'url' => '/page/who-we-are',
            'sort_order' => 0,
            'is_active' => true,
            'open_new_tab' => false,
        ]);

        MenuItem::query()->create([
            'parent_id' => $aboutMukmin->id,
            'label' => 'The Team',
            'url' => '/page/the-team',
            'sort_order' => 10,
            'is_active' => true,
            'open_new_tab' => false,
        ]);

        foreach ([
            ['Our Ecosystem', '/page/our-ecosystem', 20],
            ['Impact Areas', '/page/impact-areas', 30],
            ['Featured Initiatives', '/page/featured-initiatives', 40],
            ['CTA / Partners', '/page/cta-partners', 50],
        ] as $top) {
            MenuItem::query()->create([
                'parent_id' => null,
                'label' => $top[0],
                'url' => $top[1],
                'sort_order' => $top[2],
                'is_active' => true,
                'open_new_tab' => false,
            ]);
        }

        Widget::query()->updateOrCreate(
            ['slug' => 'home-hero'],
            [
                'title' => 'Home hero',
                'zone' => 'home',
                'cms_page_id' => null,
                'content' => <<<'HTML'
<h1 id="mukmin-hero-heading" class="mukmin-hero__headline">
    <span class="mukmin-hero__line">One Identity.</span>
    <span class="mukmin-hero__line">One Vision.</span>
    <span class="mukmin-hero__line">One Community<span class="mukmin-hero__side-panel-origin" aria-hidden="true"></span>.</span>
</h1>
<div class="mukmin-hero__sub">
    <p class="mukmin-hero__subline">Advancing inclusive communities through collaboration, opportunity, and shared purpose.</p>
    <p class="mukmin-hero__subline">Empowering people. Shaping futures. Driving lasting impact across Malaysia.</p>
</div>
HTML
                ,
                'settings' => Widget::defaultHeroSettings(),
                'sort_order' => 0,
                'is_active' => true,
            ]
        );

        Widget::query()->updateOrCreate(
            ['slug' => Widget::HOME_IMPACT_STATS_SLUG],
            [
                'title' => 'Measured outcomes',
                'zone' => 'home',
                'cms_page_id' => null,
                'content' => '',
                'settings' => Widget::defaultImpactStatsSettings(),
                'sort_order' => 10,
                'is_active' => true,
            ]
        );

        Widget::query()->updateOrCreate(
            ['slug' => Widget::HOME_VOICES_SLUG],
            [
                'title' => 'Voices of Change',
                'zone' => 'home',
                'cms_page_id' => null,
                'content' => '',
                'settings' => Widget::defaultVoicesSettings(),
                'sort_order' => 15,
                'is_active' => true,
            ]
        );

        Widget::query()->updateOrCreate(
            ['slug' => Widget::HOME_JOIN_MOVEMENT_SLUG],
            [
                'title' => 'Join the Movement',
                'zone' => 'home',
                'cms_page_id' => null,
                'content' => '',
                'settings' => Widget::defaultJoinMovementSettings(),
                'sort_order' => 18,
                'is_active' => true,
            ]
        );

        Widget::query()->updateOrCreate(
            ['slug' => 'home-intro'],
            [
                'title' => 'Getting started',
                'zone' => 'home',
                'cms_page_id' => null,
                'content' => '<p>Add more blocks in <strong>Admin → Widgets</strong> with zone <code>home</code>.</p>',
                'sort_order' => 20,
                'is_active' => true,
            ]
        );

        Widget::query()->updateOrCreate(
            ['slug' => Widget::SITE_FOOTER_SLUG],
            [
                'title' => 'Site footer',
                'zone' => 'footer',
                'cms_page_id' => null,
                'content' => '',
                'settings' => Widget::defaultSiteFooterSettings(),
                'sort_order' => 0,
                'is_active' => true,
            ]
        );

        Widget::query()->updateOrCreate(
            ['slug' => 'footer-note'],
            [
                'title' => 'Footer note (legacy)',
                'zone' => 'footer',
                'cms_page_id' => null,
                'content' => '<p class="muted" style="margin:0">&copy; '.date('Y').' Mukmin.</p>',
                'sort_order' => 10,
                'is_active' => false,
            ]
        );

        User::query()->updateOrCreate(
            ['email' => 'admin@example.com'],
            [
                'name' => 'Administrator',
                'password' => Hash::make(env('ADMIN_PASSWORD', 'password')),
                'is_admin' => true,
            ]
        );
    }
}
