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
                'title' => 'About Us',
                'excerpt' => 'Hub for MUKMIN identity, team, and ecosystem entry points.',
                'body' => '',
            ],
            [
                'slug' => 'who-we-are',
                'title' => 'Who We Are',
                'excerpt' => 'National ecosystem narrative and mission.',
                'body' => '',
            ],
            [
                'slug' => 'the-team',
                'title' => 'The Team',
                'excerpt' => 'Leadership structure and how to connect.',
                'body' => '',
            ],
            [
                'slug' => 'our-ecosystem',
                'title' => 'Our Ecosystem',
                'excerpt' => 'Shape, Connect, Deliver — FIKRAH, Gabungan MUKMIN, Yayasan MUKMIN.',
                'body' => '',
            ],
            [
                'slug' => 'impact-areas',
                'title' => 'Impact Areas',
                'excerpt' => 'Strategic initiatives 2026–2030 and five impact pillars.',
                'body' => '',
            ],
            [
                'slug' => 'featured-initiatives',
                'title' => 'Featured Initiatives',
                'excerpt' => 'MFLS, SIRAT, FIKRAH Blueprint, Digital Madrasah, and next steps.',
                'body' => '',
            ],
            [
                'slug' => 'cta-partners',
                'title' => 'CTA / Partners',
                'excerpt' => 'Partnerships, contact, registration, and giving.',
                'body' => '',
            ],
        ];

        foreach ($cmsSeeds as $row) {
            CmsPage::query()->updateOrCreate(
                ['slug' => $row['slug']],
                [
                    'title' => $row['title'],
                    'excerpt' => $row['excerpt'],
                    'body' => $row['body'],
                    'widgets_only' => true,
                    'is_published' => true,
                    'published_at' => now(),
                ]
            );
        }

        // CMS page copy lives in widgets (CmsLayoutWidgetsSeeder). Off by default so
        // `php artisan db:seed` does not overwrite content you edited in Admin.
        // Set SEED_CMS_LAYOUT_WIDGETS=true in .env to (re)apply template widgets, or run:
        // php artisan db:seed --class=CmsLayoutWidgetsSeeder
        if (filter_var(env('SEED_CMS_LAYOUT_WIDGETS', false), FILTER_VALIDATE_BOOLEAN)) {
            $this->call(CmsLayoutWidgetsSeeder::class);
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
            'label' => 'About Us',
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

        $mediaCenter = MenuItem::query()->create([
            'parent_id' => null,
            'label' => 'Media Center',
            'url' => '#',
            'sort_order' => 50,
            'is_active' => true,
            'open_new_tab' => false,
        ]);

        MenuItem::query()->create([
            'parent_id' => $mediaCenter->id,
            'label' => 'News Letter',
            'url' => '/page/news-letter',
            'sort_order' => 0,
            'is_active' => true,
            'open_new_tab' => false,
        ]);

        MenuItem::query()->create([
            'parent_id' => $mediaCenter->id,
            'label' => 'Gallery',
            'url' => '/page/gallery',
            'sort_order' => 10,
            'is_active' => true,
            'open_new_tab' => false,
        ]);

        MenuItem::query()->create([
            'parent_id' => null,
            'label' => 'Contact Us',
            'url' => '/contact-us',
            'sort_order' => 60,
            'is_active' => true,
            'open_new_tab' => false,
        ]);

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
                'title' => 'Voices of Mukmin',
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
