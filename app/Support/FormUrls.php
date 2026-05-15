<?php

namespace App\Support;

class FormUrls
{
    public static function register(): string
    {
        return route('register.show');
    }

    public static function scholarship(): string
    {
        return route('scholarship.show');
    }

    public static function contact(): string
    {
        return route('contact.show');
    }

    /**
     * Map CTA label (and optional current URL) to the correct public form path.
     */
    public static function resolveCtaUrl(?string $label, ?string $url = null): string
    {
        $labelNorm = self::normalize($label);
        $url = trim((string) $url);

        if ($labelNorm !== '' && self::matchesAny($labelNorm, self::scholarshipLabelPatterns())) {
            return self::scholarship();
        }

        if ($labelNorm !== '' && self::matchesAny($labelNorm, self::registerLabelPatterns())) {
            return self::register();
        }

        if ($labelNorm !== '' && self::matchesAny($labelNorm, self::contactLabelPatterns())) {
            return self::contact();
        }

        $urlLower = strtolower($url);

        if (str_starts_with($urlLower, 'mailto:register')) {
            return self::register();
        }

        if (str_starts_with($urlLower, 'mailto:hello') || str_starts_with($urlLower, 'mailto:contact')) {
            return self::contact();
        }

        if ($urlLower === '/register' || $urlLower === '/apply-scholarship' || $urlLower === '/contact-us') {
            return $url;
        }

        return $url;
    }

    /**
     * @param  array<string, mixed>  $settings
     * @return array<string, mixed>
     */
    public static function patchWidgetSettings(array $settings): array
    {
        if (isset($settings['join_primary_url'], $settings['join_primary_label'])) {
            $settings['join_primary_url'] = self::resolveCtaUrl(
                (string) $settings['join_primary_label'],
                (string) $settings['join_primary_url']
            );
        }

        if (isset($settings['join_secondary_url'], $settings['join_secondary_label'])) {
            $secondaryUrl = (string) $settings['join_secondary_url'];
            $secondaryLabel = (string) $settings['join_secondary_label'];
            if (self::matchesAny(self::normalize($secondaryLabel), self::registerLabelPatterns())
                || str_contains(self::normalize($secondaryLabel), 'join')) {
                $settings['join_secondary_url'] = self::resolveCtaUrl($secondaryLabel, $secondaryUrl);
            }
        }

        if (isset($settings['cta_label'], $settings['cta_url'])) {
            $settings['cta_url'] = self::resolveCtaUrl(
                (string) $settings['cta_label'],
                (string) $settings['cta_url']
            );
        }

        if (isset($settings['ctas']) && is_array($settings['ctas'])) {
            foreach ($settings['ctas'] as $i => $cta) {
                if (! is_array($cta)) {
                    continue;
                }
                $settings['ctas'][$i]['url'] = self::resolveCtaUrl(
                    (string) ($cta['label'] ?? ''),
                    (string) ($cta['url'] ?? '')
                );
            }
        }

        if (isset($settings['hero_slides']) && is_array($settings['hero_slides'])) {
            foreach ($settings['hero_slides'] as $i => $slide) {
                if (! is_array($slide)) {
                    continue;
                }
                $label = (string) ($slide['cta_label'] ?? $slide['title'] ?? '');
                $settings['hero_slides'][$i]['link_url'] = self::resolveCtaUrl(
                    $label,
                    (string) ($slide['link_url'] ?? '')
                );
            }
        }

        if (isset($settings['footer_columns']) && is_array($settings['footer_columns'])) {
            foreach ($settings['footer_columns'] as $ci => $col) {
                if (! is_array($col) || ! isset($col['links']) || ! is_array($col['links'])) {
                    continue;
                }
                foreach ($col['links'] as $li => $link) {
                    if (! is_array($link)) {
                        continue;
                    }
                    $settings['footer_columns'][$ci]['links'][$li]['url'] = self::resolveCtaUrl(
                        (string) ($link['label'] ?? ''),
                        (string) ($link['url'] ?? '')
                    );
                }
            }
        }

        return $settings;
    }

    private static function normalize(?string $value): string
    {
        return strtolower(trim((string) $value));
    }

    /**
     * @param  array<int, string>  $patterns
     */
    private static function matchesAny(string $haystack, array $patterns): bool
    {
        foreach ($patterns as $pattern) {
            if ($pattern === '') {
                continue;
            }
            if (str_contains($haystack, $pattern)) {
                return true;
            }
        }

        return false;
    }

    /** @return array<int, string> */
    private static function scholarshipLabelPatterns(): array
    {
        return [
            'apply now',
            'apply for',
            'scholarship',
            'mfls',
            'future leaders',
        ];
    }

    /** @return array<int, string> */
    private static function registerLabelPatterns(): array
    {
        return [
            'register',
            'join mukmin',
            'join the movement',
            'join us',
            'be part of',
            'register now',
            'register as',
            'membership registration',
        ];
    }

    /** @return array<int, string> */
    private static function contactLabelPatterns(): array
    {
        return [
            'contact',
            'get in touch',
            'reach out',
            'connect with',
            "let's connect",
            'lets connect',
        ];
    }
}
