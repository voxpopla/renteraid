<?php

namespace Tests\Feature;

use App\Support\Seo;
use Illuminate\Http\Request;
use Tests\TestCase;

class SeoTest extends TestCase
{
    public function test_homepage_renders_the_site_seo_settings(): void
    {
        $response = $this->get('https://renteraid.test/?source=email');

        $response->assertOk()
            ->assertSee('<title>Home | RenterAid</title>', false)
            ->assertSee('<meta property="og:site_name" content="RenterAid">', false)
            ->assertSee('<meta name="robots" content="index, follow">', false)
            ->assertSee('<meta name="description" content="RenterAid can help you fight eviction', false)
            ->assertSee('<link rel="canonical" href="https://renteraid.test/">', false);

        $this->assertSame(1, substr_count($response->getContent(), '<title>'));
    }

    public function test_title_fallback_does_not_repeat_the_site_name(): void
    {
        $seo = Seo::metadata(['default_title' => 'RenterAid', 'title_suffix' => 'RenterAid']);

        $this->assertSame('RenterAid', $seo['title']);
        $this->assertArrayNotHasKey('og:image', $seo['properties']);
        $this->assertArrayNotHasKey('twitter:image', $seo['meta']);
    }

    public function test_social_overrides_robots_and_organization_data(): void
    {
        $seo = Seo::metadata([
            'site_name' => 'RenterAid',
            'canonical_base_url' => 'https://example.org',
            'noindex' => true,
            'nofollow' => true,
            'og_title' => 'Sharing title',
            'twitter_title' => 'X title',
            'google_verification' => 'verification-code',
            'structured_data' => true,
            'organization_name' => 'Organization name',
            'social_profiles' => [['url' => 'https://example.org/profile']],
        ], 'Page title');

        $this->assertSame('noindex, nofollow', $seo['meta']['robots']);
        $this->assertSame('Sharing title', $seo['properties']['og:title']);
        $this->assertSame('X title', $seo['meta']['twitter:title']);
        $this->assertSame('verification-code', $seo['meta']['google-site-verification']);
        $this->assertSame('Organization name', $seo['schema']['@graph'][0]['name']);
        $this->assertSame(['https://example.org/profile'], $seo['schema']['@graph'][0]['sameAs']);
        $this->assertSame('https://example.org/#organization', $seo['schema']['@graph'][1]['publisher']['@id']);
    }

    public function test_canonical_override_preserves_the_page_path_without_query_parameters(): void
    {
        $this->app->instance('request', Request::create('https://local.test/news/example?source=email'));

        $seo = Seo::metadata([
            'canonical_base_url' => 'https://example.org/',
            'title_suffix' => 'Example',
            'description' => 'Site description',
        ], 'News');

        $this->assertSame('https://example.org/news/example', $seo['canonical']);
        $this->assertSame('News | Example', $seo['title']);
        $this->assertSame($seo['canonical'], $seo['properties']['og:url']);
        $this->assertSame('Site description', $seo['meta']['twitter:description']);
        $this->assertNull($seo['schema']);
    }
}
