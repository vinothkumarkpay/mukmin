<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\CmsPage;
use App\Models\Widget;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Facades\Validator;
use Illuminate\Support\Str;
use Illuminate\Validation\Rule;
use Illuminate\Validation\ValidationException;
use Illuminate\View\View;

class WidgetController extends Controller
{
    public function index(): View
    {
        $widgets = Widget::query()
            ->with('cmsPage:id,title,slug')
            ->orderBy('zone')
            ->orderBy('sort_order')
            ->orderBy('id')
            ->paginate(25);

        return view('admin.widgets.index', compact('widgets'));
    }

    public function create(): View
    {
        return view('admin.widgets.create', [
            'cmsPages' => $this->cmsPageOptions(),
        ]);
    }

    public function store(Request $request): RedirectResponse
    {
        $validated = $this->validated($request, null);
        $slug = Str::slug($validated['slug']);
        $validated['slug'] = $slug;
        if (in_array($slug, [Widget::HOME_HERO_SLUG, Widget::HOME_IMPACT_STATS_SLUG, Widget::HOME_VOICES_SLUG, Widget::HOME_JOIN_MOVEMENT_SLUG], true)) {
            $validated['zone'] = 'home';
        } elseif ($slug === Widget::SITE_FOOTER_SLUG) {
            $validated['zone'] = 'footer';
        }
        $validated['cms_page_id'] = $this->normalizedCmsPageId($validated);
        $validated['settings'] = $this->syncHeroSettings($request, $slug, null);
        if ($slug === Widget::HOME_IMPACT_STATS_SLUG) {
            $validated['settings'] = $this->syncImpactStatsSettings($request, null);
        }
        if ($slug === Widget::HOME_VOICES_SLUG) {
            $validated['settings'] = $this->syncVoicesSettings($request, null);
        }
        if ($slug === Widget::HOME_JOIN_MOVEMENT_SLUG) {
            $validated['settings'] = $this->syncJoinMovementSettings($request, null);
        }
        if ($slug === Widget::SITE_FOOTER_SLUG) {
            $validated['settings'] = $this->syncSiteFooterSettings($request, null);
        }
        $validated = $this->onlyWidgetColumns($validated);

        $widget = Widget::query()->create($validated);

        if ($slug === Widget::HOME_HERO_SLUG) {
            $withUploads = $this->processHeroSlideUploads($request, $widget, $widget->settings ?? []);
            $widget->update(['settings' => $withUploads]);
        }

        return redirect()->route('admin.widgets.index')->with('status', __('Widget created.'));
    }

    public function edit(Widget $widget): View
    {
        return view('admin.widgets.edit', [
            'widget' => $widget,
            'cmsPages' => $this->cmsPageOptions(),
        ]);
    }

    public function update(Request $request, Widget $widget): RedirectResponse
    {
        $validated = $this->validated($request, $widget->id);
        $slug = Str::slug($validated['slug']);
        $validated['slug'] = $slug;
        if (in_array($slug, [Widget::HOME_HERO_SLUG, Widget::HOME_IMPACT_STATS_SLUG, Widget::HOME_VOICES_SLUG, Widget::HOME_JOIN_MOVEMENT_SLUG], true)) {
            $validated['zone'] = 'home';
        } elseif ($slug === Widget::SITE_FOOTER_SLUG) {
            $validated['zone'] = 'footer';
        }
        $validated['cms_page_id'] = $this->normalizedCmsPageId($validated);
        $validated['settings'] = $this->syncHeroSettings($request, $slug, $widget);
        if ($slug === Widget::HOME_IMPACT_STATS_SLUG) {
            $validated['settings'] = $this->syncImpactStatsSettings($request, $widget);
        }
        if ($slug === Widget::HOME_VOICES_SLUG) {
            $validated['settings'] = $this->syncVoicesSettings($request, $widget);
        }
        if ($slug === Widget::HOME_JOIN_MOVEMENT_SLUG) {
            $validated['settings'] = $this->syncJoinMovementSettings($request, $widget);
        }
        if ($slug === Widget::SITE_FOOTER_SLUG) {
            $validated['settings'] = $this->syncSiteFooterSettings($request, $widget);
        }
        $validated = $this->onlyWidgetColumns($validated);

        $widget->update($validated);

        return redirect()->route('admin.widgets.index')->with('status', __('Widget updated.'));
    }

    public function destroy(Widget $widget): RedirectResponse
    {
        $this->deleteHeroBackgroundFile($widget->settings ?? []);
        $this->deleteHeroSidePanelFile($widget->settings ?? []);
        $this->deleteHeroSlideFiles($widget->settings ?? []);
        if ($widget->slug === Widget::HOME_HERO_SLUG && $widget->getKey()) {
            Storage::disk('public')->deleteDirectory('hero-slides/'.$widget->getKey());
        }

        $widget->delete();

        return redirect()->route('admin.widgets.index')->with('status', __('Widget removed.'));
    }

    protected function validated(Request $request, ?int $ignoreId): array
    {
        if (Str::slug((string) $request->input('slug')) === Widget::HOME_HERO_SLUG) {
            $request->merge(['zone' => 'home', 'cms_page_id' => null]);
        }
        if (Str::slug((string) $request->input('slug')) === Widget::HOME_IMPACT_STATS_SLUG) {
            $request->merge(['zone' => 'home', 'cms_page_id' => null]);
        }
        if (Str::slug((string) $request->input('slug')) === Widget::HOME_VOICES_SLUG) {
            $request->merge(['zone' => 'home', 'cms_page_id' => null]);
        }
        if (Str::slug((string) $request->input('slug')) === Widget::HOME_JOIN_MOVEMENT_SLUG) {
            $request->merge(['zone' => 'home', 'cms_page_id' => null]);
        }
        if (Str::slug((string) $request->input('slug')) === Widget::SITE_FOOTER_SLUG) {
            $request->merge(['zone' => 'footer', 'cms_page_id' => null]);
        }

        $rules = [
            'slug' => [
                'required',
                'string',
                'max:255',
                'regex:/^[a-z0-9]+(?:-[a-z0-9]+)*$/',
                Rule::unique('widgets', 'slug')->ignore($ignoreId),
            ],
            'title' => ['required', 'string', 'max:255'],
            'zone' => ['required', 'string', 'in:home,header,sidebar,footer,cms'],
            'cms_page_id' => ['nullable', 'required_if:zone,cms', 'integer', 'exists:cms_pages,id'],
            'content' => ['nullable', 'string'],
            'sort_order' => ['nullable', 'integer', 'min:0', 'max:999999'],
            'is_active' => ['sometimes', 'boolean'],
            'hero_background_image' => ['nullable', 'image', 'max:4096'],
            'remove_hero_background' => ['sometimes', 'boolean'],
            'hero_side_panel_image' => ['nullable', 'image', 'max:4096'],
            'remove_hero_side_panel' => ['sometimes', 'boolean'],
            'hero_bg_external_url' => ['nullable', 'string', 'max:2048'],
            'hero_side_panel_external_url' => ['nullable', 'string', 'max:2048'],
            'hero_gradient_1' => ['nullable', 'string', 'max:7'],
            'hero_gradient_2' => ['nullable', 'string', 'max:7'],
            'hero_gradient_3' => ['nullable', 'string', 'max:7'],
            'hero_overlay' => ['nullable', 'numeric', 'between:0,0.92'],
        ];

        if (Str::slug((string) $request->input('slug')) === Widget::HOME_HERO_SLUG) {
            foreach (range(0, Widget::HERO_SLIDE_SLOTS - 1) as $i) {
                $rules["hero_slide_file.$i"] = ['nullable', 'image', 'max:4096'];
            }
            $rules['hero_slides'] = ['nullable', 'array'];
            $rules['hero_slides.*.title'] = ['nullable', 'string', 'max:255'];
            $rules['hero_slides.*.description'] = ['nullable', 'string', 'max:2000'];
            $rules['hero_slides.*.cta_label'] = ['nullable', 'string', 'max:120'];
            $rules['hero_slides.*.link_url'] = ['nullable', 'string', 'max:2048'];
            $rules['hero_slides.*.image_url'] = ['nullable', 'string', 'max:2048'];
            $rules['hero_slides.*.new_tab'] = ['sometimes', 'boolean'];
            $rules['hero_slides.*.clear_image'] = ['sometimes', 'boolean'];
            $rules['hero_slide_interval'] = ['nullable', 'integer', 'min:3', 'max:120'];
            $rules['hero_slides_autoplay'] = ['sometimes', 'boolean'];
        }

        if (Str::slug((string) $request->input('slug')) === Widget::HOME_IMPACT_STATS_SLUG) {
            $rules['impact_eyebrow'] = ['nullable', 'string', 'max:500'];
            $rules['impact_title'] = ['nullable', 'string', 'max:300'];
            $rules['impact_subtitle'] = ['nullable', 'string', 'max:1000'];
            $rules['impact_cards'] = ['nullable', 'array'];
            foreach (range(0, Widget::IMPACT_STAT_CARD_SLOTS - 1) as $i) {
                $rules["impact_cards.$i.stat"] = ['nullable', 'string', 'max:64'];
                $rules["impact_cards.$i.description"] = ['nullable', 'string', 'max:500'];
                $rules["impact_cards.$i.card_theme"] = ['nullable', 'string', 'in:blue,mint,cyan'];
            }
        }

        if (Str::slug((string) $request->input('slug')) === Widget::HOME_VOICES_SLUG) {
            $rules['voices_eyebrow'] = ['nullable', 'string', 'max:500'];
            $rules['voices_title'] = ['nullable', 'string', 'max:300'];
            $rules['voices_marquee_seconds'] = ['nullable', 'integer', 'min:0', 'max:180'];
            $rules['voices_marquee_autoplay'] = ['sometimes', 'boolean'];
            $rules['voices_items'] = ['nullable', 'array'];
            foreach (range(0, Widget::VOICES_MAX_SLOTS - 1) as $i) {
                $rules["voices_items.$i.name"] = ['nullable', 'string', 'max:120'];
                $rules["voices_items.$i.role"] = ['nullable', 'string', 'max:255'];
                $rules["voices_items.$i.quote"] = ['nullable', 'string', 'max:1200'];
                $rules["voices_items.$i.initial"] = ['nullable', 'string', 'max:4'];
            }
        }

        if (Str::slug((string) $request->input('slug')) === Widget::HOME_JOIN_MOVEMENT_SLUG) {
            $rules['join_title'] = ['nullable', 'string', 'max:300'];
            $rules['join_subtitle'] = ['nullable', 'string', 'max:1200'];
            $rules['join_primary_label'] = ['nullable', 'string', 'max:120'];
            $rules['join_primary_url'] = ['nullable', 'string', 'max:2048'];
            $rules['join_primary_new_tab'] = ['sometimes', 'boolean'];
            $rules['join_secondary_label'] = ['nullable', 'string', 'max:120'];
            $rules['join_secondary_url'] = ['nullable', 'string', 'max:2048'];
            $rules['join_secondary_new_tab'] = ['sometimes', 'boolean'];
        }

        if (Str::slug((string) $request->input('slug')) === Widget::SITE_FOOTER_SLUG) {
            $rules['footer_copyright_suffix'] = ['nullable', 'string', 'max:500'];
            $rules['footer_columns'] = ['nullable', 'array'];
            $iconIn = implode(',', Widget::siteFooterSocialIconKeys());
            foreach (range(0, Widget::SITE_FOOTER_MAX_COLUMNS - 1) as $c) {
                $rules["footer_columns.$c.title"] = ['nullable', 'string', 'max:120'];
                $rules["footer_columns.$c.links"] = ['nullable', 'array'];
                foreach (range(0, Widget::SITE_FOOTER_MAX_LINKS_PER_COLUMN - 1) as $l) {
                    $rules["footer_columns.$c.links.$l.label"] = ['nullable', 'string', 'max:200'];
                    $rules["footer_columns.$c.links.$l.url"] = ['nullable', 'string', 'max:2048'];
                    $rules["footer_columns.$c.links.$l.new_tab"] = ['sometimes', 'boolean'];
                }
            }
            $rules['footer_social'] = ['nullable', 'array'];
            foreach (range(0, Widget::SITE_FOOTER_MAX_SOCIAL - 1) as $i) {
                $rules["footer_social.$i.icon"] = ['nullable', 'string', 'in:'.$iconIn];
                $rules["footer_social.$i.url"] = ['nullable', 'string', 'max:2048'];
                $rules["footer_social.$i.label"] = ['nullable', 'string', 'max:120'];
                $rules["footer_social.$i.new_tab"] = ['sometimes', 'boolean'];
            }
        }

        $validated = $request->validate($rules);

        $validated['is_active'] = $request->boolean('is_active');
        $validated['sort_order'] = (int) $request->input('sort_order', 0);

        $slug = Str::slug($validated['slug']);
        if ($slug === Widget::HOME_HERO_SLUG) {
            foreach (['hero_gradient_1', 'hero_gradient_2', 'hero_gradient_3'] as $key) {
                if (! empty($validated[$key]) && ! $this->isHexColor($validated[$key])) {
                    throw ValidationException::withMessages([$key => __('Use a 6-digit hex colour like #1f6b4a.')]);
                }
            }
            $ext = trim((string) ($validated['hero_bg_external_url'] ?? ''));
            if ($ext !== '') {
                Validator::make(['hero_bg_external_url' => $ext], [
                    'hero_bg_external_url' => ['required', 'url', 'max:2048'],
                ])->validate();
            }

            $sideExt = trim((string) ($validated['hero_side_panel_external_url'] ?? ''));
            if ($sideExt !== '') {
                Validator::make(['hero_side_panel_external_url' => $sideExt], [
                    'hero_side_panel_external_url' => ['required', 'url', 'max:2048'],
                ])->validate();
            }

            foreach (range(0, Widget::HERO_SLIDE_SLOTS - 1) as $i) {
                $link = trim((string) data_get($validated, "hero_slides.$i.link_url", ''));
                if ($link !== '') {
                    if (! preg_match('#^https?://#i', $link) && ! str_starts_with($link, '/')) {
                        throw ValidationException::withMessages([
                            "hero_slides.$i.link_url" => __('Use a full URL (https://…) or a site path starting with /.'),
                        ]);
                    }
                    if (preg_match('#^https?://#i', $link)) {
                        Validator::make(['u' => $link], ['u' => ['required', 'url', 'max:2048']])->validate();
                    }
                }
                $imgUrl = trim((string) data_get($validated, "hero_slides.$i.image_url", ''));
                if ($imgUrl !== '') {
                    Validator::make(['u' => $imgUrl], ['u' => ['required', 'url', 'max:2048']])->validate();
                }
            }
        }

        if ($slug === Widget::HOME_JOIN_MOVEMENT_SLUG) {
            foreach (['join_primary_url', 'join_secondary_url'] as $urlKey) {
                $link = trim((string) ($validated[$urlKey] ?? ''));
                if ($link === '') {
                    continue;
                }
                if (! preg_match('#^https?://#i', $link) && ! str_starts_with($link, '/')) {
                    throw ValidationException::withMessages([
                        $urlKey => __('Use a full URL (https://…) or a site path starting with /.'),
                    ]);
                }
                if (preg_match('#^https?://#i', $link)) {
                    Validator::make(['u' => $link], ['u' => ['required', 'url', 'max:2048']])->validate();
                }
            }
        }

        if ($slug === Widget::SITE_FOOTER_SLUG) {
            $cols = $validated['footer_columns'] ?? [];
            foreach ($cols as $ci => $col) {
                if (! is_array($col)) {
                    continue;
                }
                foreach (($col['links'] ?? []) as $li => $link) {
                    if (! is_array($link)) {
                        continue;
                    }
                    $u = trim((string) ($link['url'] ?? ''));
                    if ($u !== '') {
                        $this->assertValidNavUrl($u, "footer_columns.$ci.links.$li.url");
                    }
                }
            }
            foreach (($validated['footer_social'] ?? []) as $si => $soc) {
                if (! is_array($soc)) {
                    continue;
                }
                $u = trim((string) ($soc['url'] ?? ''));
                if ($u !== '') {
                    $this->assertValidNavUrl($u, "footer_social.$si.url");
                }
            }
        }

        return $validated;
    }

    protected function onlyWidgetColumns(array $data): array
    {
        return array_intersect_key($data, array_flip([
            'slug', 'title', 'zone', 'cms_page_id', 'content', 'settings', 'sort_order', 'is_active',
        ]));
    }

    protected function syncHeroSettings(Request $request, string $slug, ?Widget $existing): ?array
    {
        if ($slug !== Widget::HOME_HERO_SLUG) {
            if ($existing && $existing->slug === Widget::HOME_HERO_SLUG) {
                $this->deleteHeroBackgroundFile($existing->settings ?? []);
                $this->deleteHeroSidePanelFile($existing->settings ?? []);
                $this->deleteHeroSlideFiles($existing->settings ?? []);
            }

            return null;
        }

        $settings = ($existing && $existing->slug === Widget::HOME_HERO_SLUG)
            ? ($existing->settings ?? [])
            : Widget::defaultHeroSettings();

        if ($request->boolean('remove_hero_background')) {
            $this->deleteHeroBackgroundFile($settings);
            unset($settings['hero_bg_image_path']);
        }

        if ($request->boolean('remove_hero_side_panel')) {
            $this->deleteHeroSidePanelFile($settings);
            unset($settings['hero_side_panel_image_path']);
        }

        if ($request->hasFile('hero_background_image')) {
            if (! empty($settings['hero_bg_image_path'])) {
                Storage::disk('public')->delete($settings['hero_bg_image_path']);
            }
            $settings['hero_bg_image_path'] = $request->file('hero_background_image')->store('hero-backgrounds', 'public');
            unset($settings['hero_bg_external_url']);
        } else {
            $url = trim((string) $request->input('hero_bg_external_url', ''));
            if ($url !== '') {
                $settings['hero_bg_external_url'] = $url;
            } elseif (empty($settings['hero_bg_image_path'])) {
                unset($settings['hero_bg_external_url']);
            }
        }

        if ($request->hasFile('hero_side_panel_image')) {
            if (! empty($settings['hero_side_panel_image_path'])) {
                Storage::disk('public')->delete($settings['hero_side_panel_image_path']);
            }
            $settings['hero_side_panel_image_path'] = $request->file('hero_side_panel_image')->store('hero-side-panels', 'public');
            unset($settings['hero_side_panel_external_url']);
        } else {
            $sideUrl = trim((string) $request->input('hero_side_panel_external_url', ''));
            if ($sideUrl !== '') {
                $settings['hero_side_panel_external_url'] = $sideUrl;
            } elseif (empty($settings['hero_side_panel_image_path'])) {
                unset($settings['hero_side_panel_external_url']);
            }
        }

        if (! empty($settings['hero_side_panel_image_path'])) {
            unset($settings['hero_side_panel_external_url']);
        }

        $settings['hero_gradient_1'] = $this->normalizeHexColor($request->input('hero_gradient_1'), $settings['hero_gradient_1'] ?? '#fff7ed');
        $settings['hero_gradient_2'] = $this->normalizeHexColor($request->input('hero_gradient_2'), $settings['hero_gradient_2'] ?? '#ffd9b3');
        $settings['hero_gradient_3'] = $this->normalizeHexColor($request->input('hero_gradient_3'), $settings['hero_gradient_3'] ?? '#5bbfaa');
        $settings['hero_overlay'] = max(0, min(0.92, (float) $request->input('hero_overlay', $settings['hero_overlay'] ?? 0.42)));

        if (! empty($settings['hero_bg_image_path'])) {
            unset($settings['hero_bg_external_url']);
        }

        $settings = $this->mergeHeroSlideMetadata($request, $settings, $existing);

        if ($existing !== null) {
            $settings = $this->processHeroSlideUploads($request, $existing, $settings);
        }

        return $settings;
    }

    /**
     * @return array<string, mixed>
     */
    protected function syncImpactStatsSettings(Request $request, ?Widget $existing): array
    {
        $base = ($existing && $existing->slug === Widget::HOME_IMPACT_STATS_SLUG)
            ? ($existing->settings ?? [])
            : Widget::defaultImpactStatsSettings();

        $defaults = Widget::defaultImpactStatsSettings();
        $defCards = $defaults['impact_cards'] ?? [];
        $input = $request->input('impact_cards', []);
        $cards = [];

        for ($i = 0; $i < Widget::IMPACT_STAT_CARD_SLOTS; $i++) {
            $row = isset($input[$i]) && is_array($input[$i]) ? $input[$i] : [];
            $prev = isset($base['impact_cards'][$i]) && is_array($base['impact_cards'][$i]) ? $base['impact_cards'][$i] : ($defCards[$i] ?? []);
            $theme = strtolower((string) ($row['card_theme'] ?? $prev['card_theme'] ?? 'blue'));
            if (! in_array($theme, ['blue', 'mint', 'cyan'], true)) {
                $theme = 'blue';
            }
            $cards[] = [
                'stat' => trim((string) ($row['stat'] ?? $prev['stat'] ?? $defCards[$i]['stat'] ?? '')),
                'description' => trim(strip_tags((string) ($row['description'] ?? $prev['description'] ?? $defCards[$i]['description'] ?? ''))),
                'card_theme' => $theme,
            ];
        }

        return [
            'impact_eyebrow' => trim((string) $request->input('impact_eyebrow', $base['impact_eyebrow'] ?? $defaults['impact_eyebrow'] ?? '')),
            'impact_title' => trim((string) $request->input('impact_title', $base['impact_title'] ?? $defaults['impact_title'] ?? '')),
            'impact_subtitle' => trim(strip_tags((string) $request->input('impact_subtitle', $base['impact_subtitle'] ?? $defaults['impact_subtitle'] ?? ''))),
            'impact_cards' => $cards,
        ];
    }

    /**
     * @return array<string, mixed>
     */
    protected function syncVoicesSettings(Request $request, ?Widget $existing): array
    {
        $base = ($existing && $existing->slug === Widget::HOME_VOICES_SLUG)
            ? ($existing->settings ?? [])
            : Widget::defaultVoicesSettings();

        $defaults = Widget::defaultVoicesSettings();
        $defItems = $defaults['voices_items'] ?? [];
        $input = $request->input('voices_items', []);
        $items = [];

        for ($i = 0; $i < Widget::VOICES_MAX_SLOTS; $i++) {
            $row = isset($input[$i]) && is_array($input[$i]) ? $input[$i] : [];
            $prev = isset($base['voices_items'][$i]) && is_array($base['voices_items'][$i]) ? $base['voices_items'][$i] : ($defItems[$i] ?? []);
            $name = trim((string) ($row['name'] ?? $prev['name'] ?? $defItems[$i]['name'] ?? ''));
            $initialIn = trim((string) ($row['initial'] ?? $prev['initial'] ?? $defItems[$i]['initial'] ?? ''));
            $initial = mb_strtoupper(mb_substr($initialIn, 0, 1, 'UTF-8'), 'UTF-8');
            if ($initial === '' && $name !== '') {
                $initial = mb_strtoupper(mb_substr($name, 0, 1, 'UTF-8'), 'UTF-8');
            }

            $items[] = [
                'name' => $name,
                'role' => trim(strip_tags((string) ($row['role'] ?? $prev['role'] ?? $defItems[$i]['role'] ?? ''))),
                'quote' => trim(strip_tags((string) ($row['quote'] ?? $prev['quote'] ?? $defItems[$i]['quote'] ?? ''))),
                'initial' => $initial,
            ];
        }

        $sec = (int) $request->input('voices_marquee_seconds', $base['voices_marquee_seconds'] ?? 0);

        return [
            'voices_eyebrow' => trim((string) $request->input('voices_eyebrow', $base['voices_eyebrow'] ?? $defaults['voices_eyebrow'] ?? '')),
            'voices_title' => trim((string) $request->input('voices_title', $base['voices_title'] ?? $defaults['voices_title'] ?? '')),
            'voices_marquee_seconds' => max(0, min(180, $sec)),
            'voices_marquee_autoplay' => $request->boolean('voices_marquee_autoplay'),
            'voices_items' => $items,
        ];
    }

    /**
     * @return array<string, mixed>
     */
    protected function syncJoinMovementSettings(Request $request, ?Widget $existing): array
    {
        $base = ($existing && $existing->slug === Widget::HOME_JOIN_MOVEMENT_SLUG)
            ? ($existing->settings ?? [])
            : Widget::defaultJoinMovementSettings();

        $defaults = Widget::defaultJoinMovementSettings();

        return [
            'join_title' => trim((string) $request->input('join_title', $base['join_title'] ?? $defaults['join_title'] ?? '')),
            'join_subtitle' => trim(strip_tags((string) $request->input('join_subtitle', $base['join_subtitle'] ?? $defaults['join_subtitle'] ?? ''))),
            'join_primary_label' => trim((string) $request->input('join_primary_label', $base['join_primary_label'] ?? $defaults['join_primary_label'] ?? '')),
            'join_primary_url' => trim((string) $request->input('join_primary_url', $base['join_primary_url'] ?? $defaults['join_primary_url'] ?? '')),
            'join_primary_new_tab' => $request->boolean('join_primary_new_tab'),
            'join_secondary_label' => trim((string) $request->input('join_secondary_label', $base['join_secondary_label'] ?? $defaults['join_secondary_label'] ?? '')),
            'join_secondary_url' => trim((string) $request->input('join_secondary_url', $base['join_secondary_url'] ?? $defaults['join_secondary_url'] ?? '')),
            'join_secondary_new_tab' => $request->boolean('join_secondary_new_tab'),
        ];
    }

    /**
     * @return array<string, mixed>
     */
    protected function syncSiteFooterSettings(Request $request, ?Widget $existing): array
    {
        $base = ($existing && $existing->slug === Widget::SITE_FOOTER_SLUG)
            ? ($existing->settings ?? [])
            : Widget::defaultSiteFooterSettings();

        $defaults = Widget::defaultSiteFooterSettings();
        $defCols = $defaults['footer_columns'] ?? [];
        $defCols = array_values($defCols);
        while (count($defCols) < Widget::SITE_FOOTER_MAX_COLUMNS) {
            $defCols[] = ['title' => '', 'links' => []];
        }
        $baseCols = isset($base['footer_columns']) && is_array($base['footer_columns']) ? array_values($base['footer_columns']) : [];
        while (count($baseCols) < Widget::SITE_FOOTER_MAX_COLUMNS) {
            $baseCols[] = ['title' => '', 'links' => []];
        }
        $inputCols = $request->input('footer_columns', []);
        $allowedIcons = Widget::siteFooterSocialIconKeys();
        $columns = [];

        for ($c = 0; $c < Widget::SITE_FOOTER_MAX_COLUMNS; $c++) {
            $row = isset($inputCols[$c]) && is_array($inputCols[$c]) ? $inputCols[$c] : [];
            $prevCol = isset($baseCols[$c]) && is_array($baseCols[$c]) ? $baseCols[$c] : ($defCols[$c] ?? ['title' => '', 'links' => []]);
            $defCol = $defCols[$c] ?? ['title' => '', 'links' => []];
            $title = Widget::decodeFooterText((string) ($row['title'] ?? $prevCol['title'] ?? $defCol['title'] ?? ''));
            $linksIn = isset($row['links']) && is_array($row['links']) ? $row['links'] : [];
            $prevLinks = isset($prevCol['links']) && is_array($prevCol['links']) ? array_values($prevCol['links']) : [];
            $defLinks = isset($defCol['links']) && is_array($defCol['links']) ? array_values($defCol['links']) : [];
            $links = [];
            for ($l = 0; $l < Widget::SITE_FOOTER_MAX_LINKS_PER_COLUMN; $l++) {
                $lr = isset($linksIn[$l]) && is_array($linksIn[$l]) ? $linksIn[$l] : [];
                $pl = isset($prevLinks[$l]) && is_array($prevLinks[$l]) ? $prevLinks[$l] : [];
                $dl = isset($defLinks[$l]) && is_array($defLinks[$l]) ? $defLinks[$l] : [];
                $links[] = [
                    'label' => Widget::decodeFooterText((string) ($lr['label'] ?? $pl['label'] ?? $dl['label'] ?? '')),
                    'url' => trim((string) ($lr['url'] ?? $pl['url'] ?? $dl['url'] ?? '')),
                    'new_tab' => $request->boolean("footer_columns.$c.links.$l.new_tab"),
                ];
            }
            $columns[] = ['title' => $title, 'links' => $links];
        }

        $defSoc = isset($defaults['footer_social']) && is_array($defaults['footer_social']) ? array_values($defaults['footer_social']) : [];
        while (count($defSoc) < Widget::SITE_FOOTER_MAX_SOCIAL) {
            $defSoc[] = ['icon' => 'link', 'url' => '', 'label' => '', 'new_tab' => false];
        }
        $baseSoc = isset($base['footer_social']) && is_array($base['footer_social']) ? array_values($base['footer_social']) : [];
        while (count($baseSoc) < Widget::SITE_FOOTER_MAX_SOCIAL) {
            $baseSoc[] = ['icon' => 'link', 'url' => '', 'label' => '', 'new_tab' => false];
        }
        $inputSoc = $request->input('footer_social', []);
        $social = [];
        for ($i = 0; $i < Widget::SITE_FOOTER_MAX_SOCIAL; $i++) {
            $row = isset($inputSoc[$i]) && is_array($inputSoc[$i]) ? $inputSoc[$i] : [];
            $prev = isset($baseSoc[$i]) && is_array($baseSoc[$i]) ? $baseSoc[$i] : ($defSoc[$i] ?? []);
            $icon = strtolower(trim((string) ($row['icon'] ?? $prev['icon'] ?? $defSoc[$i]['icon'] ?? 'link')));
            if (! in_array($icon, $allowedIcons, true)) {
                $icon = 'link';
            }
            $social[] = [
                'icon' => $icon,
                'url' => trim((string) ($row['url'] ?? $prev['url'] ?? $defSoc[$i]['url'] ?? '')),
                'label' => Widget::decodeFooterText((string) ($row['label'] ?? $prev['label'] ?? $defSoc[$i]['label'] ?? '')),
                'new_tab' => $request->boolean("footer_social.$i.new_tab"),
            ];
        }

        return [
            'footer_copyright_suffix' => Widget::decodeFooterText((string) $request->input('footer_copyright_suffix', $base['footer_copyright_suffix'] ?? $defaults['footer_copyright_suffix'] ?? '')),
            'footer_columns' => $columns,
            'footer_social' => $social,
        ];
    }

    /**
     * @param  array<string, mixed>  $settings
     * @return array<string, mixed>
     */
    protected function mergeHeroSlideMetadata(Request $request, array $settings, ?Widget $existing): array
    {
        $defaults = Widget::defaultHeroSlides();
        $prevSlides = ($existing && $existing->slug === Widget::HOME_HERO_SLUG)
            ? (($existing->settings ?? [])['hero_slides'] ?? [])
            : [];
        $input = $request->input('hero_slides', []);
        $slides = [];

        for ($i = 0; $i < Widget::HERO_SLIDE_SLOTS; $i++) {
            $row = isset($input[$i]) && is_array($input[$i]) ? $input[$i] : [];
            $prev = isset($prevSlides[$i]) && is_array($prevSlides[$i]) ? $prevSlides[$i] : ($defaults[$i] ?? []);
            $path = $prev['image_path'] ?? null;

            if ($request->boolean("hero_slides.$i.clear_image")) {
                if (is_string($path) && $path !== '') {
                    Storage::disk('public')->delete($path);
                }
                $path = null;
            }

            $descRaw = (string) ($row['description'] ?? $prev['description'] ?? ($defaults[$i]['description'] ?? ''));
            $slides[] = [
                'title' => trim((string) ($row['title'] ?? $prev['title'] ?? $defaults[$i]['title'] ?? '')),
                'description' => trim(strip_tags($descRaw)),
                'cta_label' => trim((string) ($row['cta_label'] ?? $prev['cta_label'] ?? $defaults[$i]['cta_label'] ?? '')),
                'link_url' => trim((string) ($row['link_url'] ?? $prev['link_url'] ?? $defaults[$i]['link_url'] ?? '')),
                'image_url' => trim((string) ($row['image_url'] ?? $prev['image_url'] ?? $defaults[$i]['image_url'] ?? '')),
                'new_tab' => $request->boolean("hero_slides.$i.new_tab"),
                'image_path' => $path,
            ];
        }

        $settings['hero_slides'] = $slides;
        $settings['hero_slide_interval'] = max(3, min(120, (int) $request->input('hero_slide_interval', $settings['hero_slide_interval'] ?? 6)));

        $settings['hero_slides_autoplay'] = $request->boolean('hero_slides_autoplay');

        return $settings;
    }

    /**
     * @param  array<string, mixed>  $settings
     * @return array<string, mixed>
     */
    protected function processHeroSlideUploads(Request $request, Widget $widget, array $settings): array
    {
        if ($widget->slug !== Widget::HOME_HERO_SLUG || ! $widget->getKey()) {
            return $settings;
        }

        $slides = $settings['hero_slides'] ?? [];
        foreach (range(0, Widget::HERO_SLIDE_SLOTS - 1) as $i) {
            if (! $request->hasFile("hero_slide_file.$i")) {
                continue;
            }
            $oldPath = $slides[$i]['image_path'] ?? null;
            if (is_string($oldPath) && $oldPath !== '') {
                Storage::disk('public')->delete($oldPath);
            }
            $file = $request->file("hero_slide_file.$i");
            $ext = $file->getClientOriginalExtension() ?: 'jpg';
            $stored = $file->storeAs('hero-slides/'.$widget->getKey(), $i.'.'.$ext, 'public');
            $slides[$i]['image_path'] = $stored;
            $slides[$i]['image_url'] = '';
        }
        $settings['hero_slides'] = $slides;

        return $settings;
    }

    protected function deleteHeroSlideFiles(array $settings): void
    {
        $slides = $settings['hero_slides'] ?? [];
        if (! is_array($slides)) {
            return;
        }
        foreach ($slides as $slide) {
            if (! is_array($slide)) {
                continue;
            }
            $path = $slide['image_path'] ?? null;
            if (is_string($path) && $path !== '') {
                Storage::disk('public')->delete($path);
            }
        }
    }

    protected function deleteHeroBackgroundFile(array $settings): void
    {
        $path = $settings['hero_bg_image_path'] ?? null;
        if (is_string($path) && $path !== '') {
            Storage::disk('public')->delete($path);
        }
    }

    protected function deleteHeroSidePanelFile(array $settings): void
    {
        $path = $settings['hero_side_panel_image_path'] ?? null;
        if (is_string($path) && $path !== '') {
            Storage::disk('public')->delete($path);
        }
    }

    protected function normalizeHexColor($value, string $fallback): string
    {
        $v = is_string($value) ? trim($value) : '';
        if ($v === '') {
            return $this->ensureHashPrefix($fallback);
        }
        if ($v[0] !== '#') {
            $v = '#'.$v;
        }

        return strlen($v) === 7 && $this->isHexColor($v) ? $v : $this->ensureHashPrefix($fallback);
    }

    protected function ensureHashPrefix(string $hex): string
    {
        $hex = trim($hex);

        return strpos($hex, '#') === 0 ? $hex : '#'.$hex;
    }

    protected function isHexColor(string $value): bool
    {
        return (bool) preg_match('/^#?[0-9A-Fa-f]{6}$/', $value);
    }

    protected function cmsPageOptions()
    {
        return CmsPage::query()
            ->orderBy('title')
            ->get(['id', 'title', 'slug']);
    }

    protected function normalizedCmsPageId(array $data): ?int
    {
        if (($data['zone'] ?? '') !== 'cms') {
            return null;
        }

        return isset($data['cms_page_id']) ? (int) $data['cms_page_id'] : null;
    }

    protected function assertValidNavUrl(string $link, string $errorKey): void
    {
        if ($link === '' || $link === '#') {
            return;
        }
        if (! preg_match('#^https?://#i', $link) && ! str_starts_with($link, '/')) {
            throw ValidationException::withMessages([
                $errorKey => __('Use a full URL (https://…) or a site path starting with /.'),
            ]);
        }
        if (preg_match('#^https?://#i', $link)) {
            Validator::make(['u' => $link], ['u' => ['required', 'url', 'max:2048']])->validate();
        }
    }
}
