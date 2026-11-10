<?php

namespace App\Support;

use Statamic\Facades\Asset;

class Seo
{
    public static function metadata(array $settings, ?string $pageTitle = null): array
    {
        $get = fn (string $key, mixed $fallback = null) => $settings[$key] ?? $fallback;
        $siteName = $get('site_name') ?: config('app.name');
        $title = $pageTitle ?: ($get('default_title') ?: $siteName);
        $separator = $get('title_separator') ?: '|';
        if (($suffix = $get('title_suffix')) && $title !== $suffix) {
            $title .= " {$separator} {$suffix}";
        }

        // Replace only the origin so every page retains its own canonical path.
        $base = rtrim($get('canonical_base_url') ?: request()->root(), '/');
        $canonical = $base.request()->getPathInfo();
        $image = self::asset($get('og_image'));
        $twitterImage = self::asset($get('twitter_image')) ?: $image;
        $description = $get('description');
        $meta = array_filter([
            'description' => $description,
            'author' => $get('author'),
            'robots' => implode(', ', [
                $get('noindex', false) ? 'noindex' : 'index',
                $get('nofollow', false) ? 'nofollow' : 'follow',
            ]),
            'google-site-verification' => $get('google_verification'),
            'msvalidate.01' => $get('bing_verification'),
            'p:domain_verify' => $get('pinterest_verification'),
            'theme-color' => $get('theme_color'),
            'twitter:card' => $get('twitter_card') ?: 'summary_large_image',
            'twitter:site' => $get('twitter_site'),
            'twitter:creator' => $get('twitter_creator'),
            'twitter:title' => $get('twitter_title') ?: ($get('og_title') ?: $title),
            'twitter:description' => $get('twitter_description') ?: ($get('og_description') ?: $description),
            'twitter:image' => $twitterImage?->absoluteUrl(),
            'twitter:image:alt' => $twitterImage
                ? ($get('twitter_image_alt') ?: ($get('twitter_image') ? $twitterImage->get('alt') : ($get('og_image_alt') ?: $twitterImage->get('alt'))))
                : null,
        ], fn ($value) => $value !== null && $value !== '');
        $properties = array_filter([
            'og:site_name' => $siteName,
            'og:type' => $get('og_type') ?: 'website',
            'og:locale' => $get('og_locale') ?: (str_replace('-', '_', app()->getLocale()) === 'en' ? 'en_US' : str_replace('-', '_', app()->getLocale())),
            'og:title' => $get('og_title') ?: $title,
            'og:description' => $get('og_description') ?: $description,
            'og:url' => $canonical,
            'og:image' => $image?->absoluteUrl(),
            'og:image:alt' => $image ? ($get('og_image_alt') ?: $image->get('alt')) : null,
            'fb:app_id' => $get('facebook_app_id'),
        ], fn ($value) => $value !== null && $value !== '');

        $schema = null;
        if ($get('structured_data', false)) {
            $organization = array_filter([
                '@type' => 'Organization',
                '@id' => $base.'/#organization',
                'name' => $get('organization_name') ?: $siteName,
                'url' => $base.'/',
                'logo' => self::asset($get('organization_logo'))?->absoluteUrl(),
                'email' => $get('organization_email'),
                'telephone' => $get('organization_phone'),
                'sameAs' => array_values(array_filter(array_column($get('social_profiles', []), 'url'))),
            ]);
            $schema = [
                '@context' => 'https://schema.org',
                '@graph' => [
                    $organization,
                    [
                        '@type' => 'WebSite',
                        '@id' => $base.'/#website',
                        'url' => $base.'/',
                        'name' => $siteName,
                        'publisher' => ['@id' => $base.'/#organization'],
                    ],
                ],
            ];
        }

        return [
            'title' => $title,
            'canonical' => $canonical,
            'meta' => $meta,
            'properties' => $properties,
            'favicon' => self::asset($get('favicon'))?->absoluteUrl(),
            'touch_icon' => self::asset($get('touch_icon'))?->absoluteUrl(),
            'schema' => $schema,
        ];
    }

    private static function asset(?string $path): ?\Statamic\Contracts\Assets\Asset
    {
        return $path ? Asset::find(str_contains($path, '::') ? $path : 'assets::'.$path) : null;
    }
}
