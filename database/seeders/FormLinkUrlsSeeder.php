<?php

namespace Database\Seeders;

use App\Models\Widget;
use App\Support\FormUrls;
use Illuminate\Database\Seeder;

/**
 * Patches widget settings so register / scholarship / contact CTAs use live form URLs.
 *
 * php artisan db:seed --class=FormLinkUrlsSeeder
 */
class FormLinkUrlsSeeder extends Seeder
{
    public function run(): void
    {
        Widget::query()->each(function (Widget $widget) {
            $settings = $widget->settings ?? [];
            if (! is_array($settings)) {
                return;
            }

            $patched = FormUrls::patchWidgetSettings($settings);

            if ($patched !== $settings) {
                $widget->settings = $patched;
                $widget->save();
            }
        });

        if ($this->command) {
            $this->command->info('Form CTA URLs patched on all widgets.');
        }
    }
}
