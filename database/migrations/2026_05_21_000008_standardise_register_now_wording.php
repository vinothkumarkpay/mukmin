<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Schema;

class StandardiseRegisterNowWording extends Migration
{
    public function up()
    {
        if (! Schema::hasTable('widgets')) {
            return;
        }

        $registerLabels = [
            'register as a member',
            'register as member',
            'register your interest',
            'register your details',
            'join the movement',
            'register',
            'sign up',
            'sign up now',
            'become a member',
        ];

        $now = now();

        // 1) Home "Join the Movement" widget — update join_primary_label
        $row = DB::table('widgets')->where('slug', 'home-join-movement')->first();
        if ($row) {
            $settings = [];
            if (! empty($row->settings)) {
                $decoded = json_decode($row->settings, true);
                $settings = is_array($decoded) ? $decoded : [];
            }

            $current = strtolower(trim((string) ($settings['join_primary_label'] ?? '')));
            $currentUrl = trim((string) ($settings['join_primary_url'] ?? ''));

            // Update if the current label is a known register-style label, OR the URL points to /register
            $urlPointsToRegister = $currentUrl !== '' && (
                stripos($currentUrl, '/register') !== false ||
                stripos($currentUrl, 'register-now') !== false
            );

            if (in_array($current, $registerLabels, true) || $urlPointsToRegister) {
                $settings['join_primary_label'] = 'Register Now';
                DB::table('widgets')->where('id', $row->id)->update([
                    'settings' => json_encode($settings),
                    'updated_at' => $now,
                ]);
            }
        }

        // 2) Any other widgets storing a generic register-style cta_label inside settings
        $candidates = DB::table('widgets')
            ->whereNotNull('settings')
            ->get();

        foreach ($candidates as $w) {
            if (empty($w->settings)) {
                continue;
            }
            $settings = json_decode($w->settings, true);
            if (! is_array($settings)) {
                continue;
            }

            $changed = false;

            // Single-CTA widgets that use a top-level cta_label + cta_url
            if (isset($settings['cta_label']) && isset($settings['cta_url'])) {
                $label = strtolower(trim((string) $settings['cta_label']));
                $url = trim((string) $settings['cta_url']);
                $isRegisterUrl = $url !== '' && (
                    stripos($url, '/register') !== false ||
                    stripos($url, 'register-now') !== false
                );

                if (in_array($label, $registerLabels, true) || $isRegisterUrl) {
                    if ($settings['cta_label'] !== 'Register Now') {
                        $settings['cta_label'] = 'Register Now';
                        $changed = true;
                    }
                }
            }

            // Multi-CTA widgets that use a `ctas` array of label/url pairs
            if (isset($settings['ctas']) && is_array($settings['ctas'])) {
                foreach ($settings['ctas'] as $i => $cta) {
                    if (! is_array($cta)) {
                        continue;
                    }
                    $label = strtolower(trim((string) ($cta['label'] ?? '')));
                    $url = trim((string) ($cta['url'] ?? ''));
                    $isRegisterUrl = $url !== '' && (
                        stripos($url, '/register') !== false ||
                        stripos($url, 'register-now') !== false
                    );

                    if (in_array($label, $registerLabels, true) || $isRegisterUrl) {
                        if (($settings['ctas'][$i]['label'] ?? '') !== 'Register Now') {
                            $settings['ctas'][$i]['label'] = 'Register Now';
                            $changed = true;
                        }
                    }
                }
            }

            if ($changed) {
                DB::table('widgets')->where('id', $w->id)->update([
                    'settings' => json_encode($settings),
                    'updated_at' => $now,
                ]);
            }
        }
    }

    public function down()
    {
        //
    }
}
