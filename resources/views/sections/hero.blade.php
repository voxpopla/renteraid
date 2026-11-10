@php
    $field = fn ($data, $key, $default = null) => Statamic\View\Blade\value(data_get(Statamic\View\Blade\value($data), $key, $default));
    $heroData = $hero ?? [];
    $header = $field($heroData, 'header') ?: ($title ?? config('app.name'));
    $copy = $field($heroData, 'copy');
    $button = $field($heroData, 'button', []);
    $label = $field($button, 'label');
    $link = $field($button, 'link');
    $newTab = $field($button, 'open_in_new_tab', false);
    $logoTagline = $field($heroData, 'logo_tagline');
    $logos = $field($heroData, 'logos', []);
@endphp

<section class="bg-white px-6 pt-16 pb-24 sm:px-10 sm:pt-24 sm:pb-36 lg:px-20 lg:pt-44 lg:pb-64" aria-labelledby="hero-heading">
    <div class="mx-auto max-w-7xl">
        <a href="{{ Statamic\Facades\Site::current()->url() }}" class="inline-block max-w-full focus-visible:outline-2 focus-visible:outline-offset-4 focus-visible:outline-blue" aria-label="{{ __('RenterAid home') }}">
            <img src="{{ asset('logo.svg') }}" alt="RenterAid" width="980" height="186" class="h-auto w-[968px] max-w-full">
        </a>

        <div class="relative isolate mt-6 min-h-[5.75em] pb-6 text-5xl sm:mt-10 sm:pb-8 sm:text-6xl lg:mt-14 lg:pb-10 lg:text-8xl">
            <svg class="pointer-events-none absolute right-0 top-0 -z-10 h-auto w-[5.5em] max-w-full" viewBox="0 0 220 220" fill="none" aria-hidden="true" focusable="false">
                <circle cx="110" cy="110" r="110" fill="#F3F0D7" />
                <path d="M132 72a22 22 0 1 0-33 19l-11 80h44l-11-80a22 22 0 0 0 11-19Z" fill="white" />
            </svg>

            <h1 id="hero-heading" class="max-w-5xl font-display text-5xl leading-heading font-semibold text-blue sm:text-6xl lg:text-8xl">{{ $header }}</h1>
            @if($copy)
                <p class="mt-4 max-w-5xl whitespace-pre-line text-lg leading-normal text-zinc-900 sm:mt-6 sm:text-2xl lg:mt-10">{{ $copy }}</p>
            @endif
            @if($label && $link)
                <a href="{{ $link }}" @if($newTab) target="_blank" rel="noopener noreferrer" @endif class="mt-4 inline-flex bg-green px-5 py-2 font-display text-3xl leading-tight font-bold uppercase tracking-wide text-white transition hover:bg-green/90 focus-visible:outline-2 focus-visible:outline-offset-4 focus-visible:outline-blue sm:mt-6 sm:text-5xl lg:mt-8 lg:text-7xl">
                    {{ $label }}
                    @if($newTab)
                        <span class="sr-only">{{ __('(opens in a new tab)') }}</span>
                    @endif
                </a>
            @endif
        </div>

        @if($logoTagline || count($logos ?? []))
            <div class="border-t border-slate-400 pt-4 sm:pt-6 lg:pt-8">
                @if($logoTagline)
                    <p class="text-lg leading-normal sm:text-2xl font-bold text-zinc-900">{{ $logoTagline }}</p>
                @endif
                @if(count($logos ?? []))
                    <div class="mt-4 flex flex-wrap items-center justify-center gap-x-8 gap-y-6 sm:mt-6 lg:mt-8">
                        @foreach($logos as $logo)
                            <img src="{{ $logo->url() }}" alt="{{ $logo->get('alt') ?? '' }}" class="h-27 w-auto max-w-full object-contain sm:max-w-81" loading="lazy">
                        @endforeach
                    </div>
                @endif
            </div>
        @endif
    </div>
</section>
