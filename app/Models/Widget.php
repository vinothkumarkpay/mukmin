<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\Storage;

class Widget extends Model
{
    public const HOME_HERO_SLUG = 'home-hero';

    /** Measured outcomes / stats strip below the hero on the home page. */
    public const HOME_IMPACT_STATS_SLUG = 'home-impact-stats';

    /** Testimonials / “Voices of Change” horizontal strip on the home page. */
    public const HOME_VOICES_SLUG = 'home-voices';

    /** Gradient CTA banner (“Join the Movement”) below voices on the home page. */
    public const HOME_JOIN_MOVEMENT_SLUG = 'home-join-movement';

    /** Main site footer (columns, links, social) — zone <code>footer</code>, slug {@see self::SITE_FOOTER_SLUG}. */
    public const SITE_FOOTER_SLUG = 'site-footer';

    /** Number of initiative banner slots configurable in admin. */
    public const HERO_SLIDE_SLOTS = 3;

    /**
     * Curated hero background stock: clean, professional sceneries (soft mountain / lake / forest)
     * chosen to sit quietly behind the headline without competing with the hero text.
     * Picked by {@see heroSidePanelDefaultImageUrl()} on a daily rotation.
     *
     * @return array<int, string>
     */
    public static function heroSidePanelDefaultImageCandidates(): array
    {
        return [
            // Calm beach at golden hour — hope, journey (donation impact)
            'https://images.unsplash.com/photo-1507525428034-b723cf961d3e?auto=format&fit=crop&w=1800&h=1200&q=82',
            // Mt Hood lake reflection — pristine green/alpine (reflection, growth)
            'https://images.unsplash.com/photo-1470770841072-f978cf4d019e?auto=format&fit=crop&w=1800&h=1200&q=82',
            // Soft green meadow with sunlight — abundance, what generosity enables
            'https://images.unsplash.com/photo-1500382017468-9049fed747ef?auto=format&fit=crop&w=1800&h=1200&q=82',
            // Open ocean horizon — opportunity, openness, hope
            'https://images.unsplash.com/photo-1473773508845-188df298d2d1?auto=format&fit=crop&w=1800&h=1200&q=82',
        ];
    }

    /**
     * One of {@see heroSidePanelDefaultImageCandidates()} — changes by calendar day so the hero can show variety when no custom URL is set.
     */
    public static function heroSidePanelDefaultImageUrl(): string
    {
        $pool = self::heroSidePanelDefaultImageCandidates();
        $n = count($pool);
        if ($n < 1) {
            return '';
        }
        $ix = (int) (now()->dayOfYear % $n);

        return $pool[$ix];
    }

    /**
     * @deprecated Use {@see heroSidePanelDefaultImageUrl()}. Kept so older migrations that reference this constant keep resolving.
     */
    public const HERO_SIDE_PANEL_DEFAULT_IMAGE_URL = 'https://images.unsplash.com/photo-1507525428034-b723cf961d3e?auto=format&fit=crop&w=1920&q=80';

    /** Stat cards in the impact outcomes widget. */
    public const IMPACT_STAT_CARD_SLOTS = 3;

    /** Testimonial slots in the voices widget (empty slots are ignored on the site). */
    public const VOICES_MAX_SLOTS = 10;

    /** Link columns in the site footer widget. */
    public const SITE_FOOTER_MAX_COLUMNS = 6;

    /** Link rows per column (admin form); empty rows are ignored on the site. */
    public const SITE_FOOTER_MAX_LINKS_PER_COLUMN = 10;

    /** Social icon slots in the site footer widget. */
    public const SITE_FOOTER_MAX_SOCIAL = 8;

    /** Link rows for CMS “lead” layout CTAs in admin. */
    public const CMS_LEAD_MAX_CTAS = 6;

    protected $fillable = [
        'slug',
        'title',
        'zone',
        'cms_page_id',
        'content',
        'settings',
        'sort_order',
        'is_active',
    ];

    public function cmsPage()
    {
        return $this->belongsTo(CmsPage::class);
    }

    /**
     * JSON settings: home/footer widgets use structured keys; CMS widgets may set `cms_layout`
     * to a Blade name under `resources/views/widgets/cms/` (see CmsLayoutWidgetsSeeder).
     */
    protected $casts = [
        'is_active' => 'boolean',
        'settings' => 'array',
    ];

    /**
     * Resolved background image URL for the home hero (uploaded file or external URL).
     */
    public function heroBackgroundPublicUrl(): ?string
    {
        if ($this->slug !== self::HOME_HERO_SLUG) {
            return null;
        }

        $settings = $this->settings ?? [];
        $path = $settings['hero_bg_image_path'] ?? null;
        if (is_string($path) && $path !== '' && Storage::disk('public')->exists($path)) {
            return Storage::disk('public')->url($path);
        }

        $url = $settings['hero_bg_external_url'] ?? null;
        if (is_string($url) && trim($url) !== '') {
            return trim($url);
        }

        // Fallback to a curated stock image if nothing is configured
        return self::heroSidePanelDefaultImageUrl();
    }

    /**
     * Resolved image URL for the home hero right-hand visual panel (upload, custom URL, or default stock).
     */
    public function heroSidePanelPublicUrl(): ?string
    {
        if ($this->slug !== self::HOME_HERO_SLUG) {
            return null;
        }

        $settings = $this->settings ?? [];
        $path = $settings['hero_side_panel_image_path'] ?? null;
        if (is_string($path) && $path !== '' && Storage::disk('public')->exists($path)) {
            return Storage::disk('public')->url($path);
        }

        $url = isset($settings['hero_side_panel_external_url']) ? trim((string) $settings['hero_side_panel_external_url']) : '';
        if ($url !== '') {
            // Abstract stock URL was unreliable for some clients; fall back to rotating defaults.
            if (strpos($url, 'photo-1614850523296') !== false) {
                return self::heroSidePanelDefaultImageUrl();
            }

            return $url;
        }

        return self::heroSidePanelDefaultImageUrl();
    }

    /**
     * Public image URL for a hero banner slide (uploaded file takes precedence over external URL).
     */
    public static function heroSlideImageUrl(?array $slide): ?string
    {
        if (! is_array($slide)) {
            return null;
        }

        $path = $slide['image_path'] ?? null;
        if (is_string($path) && $path !== '' && Storage::disk('public')->exists($path)) {
            return Storage::disk('public')->url($path);
        }

        $url = isset($slide['image_url']) ? trim((string) $slide['image_url']) : '';

        return $url !== '' ? $url : null;
    }

    /**
     * Default seeded slides (titles, example links, placeholder images).
     *
     * @return array<int, array<string, mixed>>
     */
    public static function defaultHeroSlides(): array
    {
        return [
            [
                'title' => __('SIRAT Series'),
                'description' => __('Talks, stories, and learning journeys that inspire purposeful action.'),
                'cta_label' => __('View SIRAT Series'),
                'link_url' => '/page/about-mukmin',
                'image_url' => 'https://images.unsplash.com/photo-1518837695005-2083093ee35b?auto=format&fit=crop&w=1920&q=80',
                'image_path' => null,
                'new_tab' => false,
            ],
            [
                'title' => __('MUKMIN Future Leaders Scholarship'),
                'description' => __('Supporting the next generation of leaders through education and mentorship.'),
                'cta_label' => __('Apply Now'),
                'link_url' => '/apply-scholarship',
                'image_url' => 'https://images.unsplash.com/photo-1500382017468-9049fed747ef?auto=format&fit=crop&w=1920&q=80',
                'image_path' => null,
                'new_tab' => false,
            ],
            [
                'title' => __('Kembara Ramadhan MUKMIN'),
                'description' => __('Seasonal outreach and gatherings that bring people together in Ramadhan.'),
                'cta_label' => __('Learn more'),
                'link_url' => '/page/about-mukmin',
                'image_url' => 'https://images.unsplash.com/photo-1473773508845-188df298d2d1?auto=format&fit=crop&w=1920&q=80',
                'image_path' => null,
                'new_tab' => false,
            ],
        ];
    }

    public static function defaultHeroSettings(): array
    {
        return [
            'hero_gradient_1' => '#dcd4cb',
            'hero_gradient_2' => '#6f5f52',
            'hero_gradient_3' => '#555c52',
            'hero_overlay' => 0.46,
            'hero_bg_external_url' => self::HERO_SIDE_PANEL_DEFAULT_IMAGE_URL,
            'hero_side_panel_external_url' => '',
            'hero_slides' => self::defaultHeroSlides(),
            'hero_slide_interval' => 6,
            'hero_slides_autoplay' => true,
        ];
    }

    /**
     * Defaults for the home “measured outcomes” stats widget (slug {@see self::HOME_IMPACT_STATS_SLUG}).
     *
     * @return array<string, mixed>
     */
    public static function defaultImpactStatsSettings(): array
    {
        return [
            'impact_eyebrow' => __('MEASURED OUTCOMES FROM OUR PROGRAMMES AND COMMUNITY INITIATIVES'),
            'impact_title' => __('Impact You Can See. Change You Can Feel'),
            'impact_subtitle' => __('Delivering real outcomes through education, women & youth empowerment, and community-driven initiatives.'),
            'impact_cards' => [
                [
                    'stat' => '2,000+',
                    'description' => __('Delegates convened across 3 SIRAT programmes'),
                    'card_theme' => 'blue',
                ],
                [
                    'stat' => '2mil ++',
                    'description' => __('Scholarship pathways pledged with partner institutions'),
                    'card_theme' => 'mint',
                ],
                [
                    'stat' => '5000',
                    'description' => __('Families supported through Ramadhan food & donation drives'),
                    'card_theme' => 'cyan',
                ],
            ],
        ];
    }

    /**
     * Defaults for the home testimonials widget (slug {@see self::HOME_VOICES_SLUG}).
     *
     * @return array<string, mixed>
     */
    public static function defaultVoicesSettings(): array
    {
        return [
            'voices_eyebrow' => '',
            'voices_title' => __('Voices of Mukmin'),
            'voices_marquee_seconds' => 0,
            'voices_marquee_autoplay' => true,
            'voices_items' => [
                [
                    'name' => 'Nur Aisyah',
                    'role' => __('Global Program – Youth Delegate'),
                    'quote' => 'https://www.youtube.com/watch?v=9No-FiE9yyo',
                    'initial' => 'N',
                ],
                [
                    'name' => 'Marcus Lee',
                    'role' => __('Community Fellow'),
                    'quote' => 'https://www.youtube.com/watch?v=k1w5Z7d5a5s',
                    'initial' => 'M',
                ],
                [
                    'name' => 'Aisha Rahman',
                    'role' => __('Scholarship alumna'),
                    'quote' => 'https://www.youtube.com/watch?v=Y8Z65v_bX2g',
                    'initial' => 'A',
                ],
                [
                    'name' => 'Daniel Kumar',
                    'role' => __('Volunteer lead'),
                    'quote' => 'https://www.youtube.com/watch?v=x7Mh-gL6kik',
                    'initial' => 'D',
                ],
                [
                    'name' => 'Siti Hajar',
                    'role' => __('Programme partner'),
                    'quote' => 'https://www.youtube.com/watch?v=tgbNymZ7vqY',
                    'initial' => 'S',
                ],
            ],
        ];
    }

    /**
     * Defaults for the home “Join the Movement” CTA banner (slug {@see self::HOME_JOIN_MOVEMENT_SLUG}).
     *
     * @return array<string, mixed>
     */
    public static function defaultJoinMovementSettings(): array
    {
        return [
            'join_title' => __('Join the Movement'),
            'join_subtitle' => __('Be part of a national effort to connect communities, unlock opportunities, and build a more inclusive future.'),
            'join_primary_label' => __('Register as a member'),
            'join_primary_url' => '/register',
            'join_primary_new_tab' => false,
            'join_secondary_label' => __('Donate for a better future'),
            'join_secondary_url' => '/page/cta-partners#donate',
            'join_secondary_new_tab' => false,
        ];
    }

    /**
     * Icon keys allowed for {@see self::SITE_FOOTER_SLUG} social buttons.
     *
     * @return array<int, string>
     */
    public static function siteFooterSocialIconKeys(): array
    {
        return ['facebook', 'instagram', 'x', 'linkedin', 'youtube', 'link'];
    }

    /**
     * Normalise footer widget copy that was stored with HTML entities (e.g. "Research &amp; Policy").
     */
    public static function decodeFooterText(?string $value): string
    {
        $s = trim((string) $value);
        if ($s === '') {
            return '';
        }

        $prev = '';
        $guard = 0;
        while ($prev !== $s && $guard < 12) {
            $prev = $s;
            $s = html_entity_decode($s, ENT_QUOTES | ENT_HTML5, 'UTF-8');
            $guard++;
        }

        return trim($s);
    }

    /**
     * Defaults for the main site footer (slug {@see self::SITE_FOOTER_SLUG}).
     *
     * @return array<string, mixed>
     */
    public static function defaultSiteFooterSettings(): array
    {
        return [
            'footer_copyright_suffix' => __('Crafted for impact.'),
            'footer_columns' => [
                [
                    'title' => __('Partnership'),
                    'links' => [
                        ['label' => __('Partner With Us'), 'url' => '/page/cta-partners', 'new_tab' => false],
                        ['label' => __('Research & Policy'), 'url' => '#', 'new_tab' => false],
                        ['label' => __('Institutions'), 'url' => '#', 'new_tab' => false],
                    ],
                ],
                [
                    'title' => __('Community'),
                    'links' => [
                        ['label' => __('Join MUKMIN'), 'url' => '/register', 'new_tab' => false],
                        ['label' => __('2030 Blueprint'), 'url' => '#', 'new_tab' => false],
                        ['label' => __('Share Your Story'), 'url' => '#', 'new_tab' => false],
                    ],
                ],
                [
                    'title' => __('Leadership'),
                    'links' => [
                        ['label' => __('MUKMIN Academy'), 'url' => '#', 'new_tab' => false],
                        ['label' => __('Leadership Pipeline'), 'url' => '#', 'new_tab' => false],
                        ['label' => __('Learn with Us'), 'url' => '#', 'new_tab' => false],
                    ],
                ],
                [
                    'title' => __('Knowledge'),
                    'links' => [
                        ['label' => __('Read Research'), 'url' => '#', 'new_tab' => false],
                        ['label' => __('MUKMIN Podcast'), 'url' => '#', 'new_tab' => false],
                        ['label' => __('Our Publications'), 'url' => '#', 'new_tab' => false],
                    ],
                ],
            ],
            'footer_social' => [
                ['icon' => 'facebook', 'url' => '#', 'label' => 'Facebook', 'new_tab' => false],
                ['icon' => 'instagram', 'url' => '#', 'label' => 'Instagram', 'new_tab' => false],
                ['icon' => 'x', 'url' => '#', 'label' => 'X', 'new_tab' => false],
                ['icon' => 'linkedin', 'url' => '#', 'label' => 'LinkedIn', 'new_tab' => false],
            ],
        ];
    }

    public function scopeActive(Builder $query)
    {
        return $query->where('is_active', true);
    }

    public function scopeZone(Builder $query, string $zone)
    {
        return $query->where('zone', $zone);
    }
}
