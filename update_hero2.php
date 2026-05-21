<?php

use App\Models\Widget;

$widget = Widget::find(1);
if ($widget) {
    $settings = $widget->settings;
    if (isset($settings['hero_slides'][0]) && $settings['hero_slides'][0]['title'] === 'SIRAT Series') {
        $settings['hero_slides'][0]['link_url'] = '/page/featured-initiatives#featured-sirat';
        $widget->settings = $settings;
        $widget->save();
        echo "Successfully updated hero carousel SIRAT Series link to anchor.";
    } else {
        echo "Could not find SIRAT Series slide at index 0.";
    }
} else {
    echo "Could not find home-hero widget.";
}
