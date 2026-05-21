<?php

use App\Models\Widget;
use App\Models\CmsPage;
use App\Models\MenuItem;

// Update widget
$widget = Widget::find(19);
if ($widget) {
    $settings = $widget->settings;
    unset($settings['note']);
    $settings['cta_url'] = '/page/sirat-series';
    $widget->settings = $settings;
    $widget->save();
    echo "Updated widget 19.\n";
}

// Create CMS page
$page = CmsPage::firstOrCreate(
    ['slug' => 'sirat-series'],
    [
        'title' => 'SIRAT Series',
        'excerpt' => 'Placeholder page for SIRAT Series.',
        'is_published' => true,
        'published_at' => now(),
    ]
);
echo "Ensured CMS page 'sirat-series' exists.\n";

// Add widget to page
Widget::firstOrCreate(
    ['slug' => 'sirat-series-placeholder', 'cms_page_id' => $page->id],
    [
        'title' => 'SIRAT Series',
        'zone' => 'cms',
        'content' => '<div style="padding: 4rem 2rem; text-align: center; max-width: 800px; margin: 0 auto;"><h2>SIRAT Series</h2><p style="font-size: 1.25rem; color: #475569; margin-top: 1rem;">Coming soon. The SIRAT Series is MUKMIN\'s flagship capacity-building platform.</p></div>',
        'settings' => ['cms_layout' => 'html', 'suppress_title' => true],
        'sort_order' => 10,
        'is_active' => true,
    ]
);
echo "Ensured placeholder widget exists.\n";

// Add menu item
$parent = MenuItem::where('label', 'Featured Initiatives')->first();
if ($parent) {
    MenuItem::firstOrCreate(
        ['label' => 'SIRAT Series', 'parent_id' => $parent->id],
        [
            'url' => '/page/sirat-series',
            'sort_order' => 15,
        ]
    );
    echo "Ensured menu item exists under Featured Initiatives.\n";
}

