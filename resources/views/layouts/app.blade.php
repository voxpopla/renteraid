<!doctype html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">
    <head>
        <meta charset="utf-8">
        <meta name="viewport" content="width=device-width, initial-scale=1">
        @include('partials.seo')
        <link rel="stylesheet" href="https://use.typekit.net/kfw6yxh.css">
        @vite(['resources/css/site.css', 'resources/js/site.js'])
    </head>
    <body {{ $attributes->class('antialiased bg-white font-sans text-zinc-900 border-t-[21px] border-green') }}>
        <a href="#main-content" class="sr-only focus:not-sr-only focus:absolute focus:p-4 focus:bg-white">{{ __('Skip to content') }}</a>
        {{ $slot }}
        @include('partials.footer')
    </body>
</html>
