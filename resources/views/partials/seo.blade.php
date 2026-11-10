@php
  $seoSettings = Statamic\Facades\GlobalSet::findByHandle('seo')?->inCurrentSite()?->values()->all() ?? [];
  $seo = App\Support\Seo::metadata($seoSettings, $title ?? null);
@endphp

<title>{{ $seo['title'] }}</title>
<link rel="canonical" href="{{ $seo['canonical'] }}">
@foreach($seo['meta'] as $name => $value)
  <meta name="{{ $name }}" content="{{ $value }}">
@endforeach
@foreach($seo['properties'] as $property => $value)
  <meta property="{{ $property }}" content="{{ $value }}">
@endforeach
@if($seo['favicon'])
  <link rel="icon" href="{{ $seo['favicon'] }}">
@endif
@if($seo['touch_icon'])
  <link rel="apple-touch-icon" href="{{ $seo['touch_icon'] }}">
@endif
@if($seo['schema'])
  <script type="application/ld+json">{!! json_encode($seo['schema'], JSON_HEX_TAG | JSON_HEX_AMP | JSON_HEX_APOS | JSON_HEX_QUOT | JSON_UNESCAPED_SLASHES | JSON_THROW_ON_ERROR) !!}</script>
@endif
