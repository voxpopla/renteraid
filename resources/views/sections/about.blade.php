@php
    $aboutField = fn ($key) => Statamic\View\Blade\value(data_get(Statamic\View\Blade\value($about ?? []), $key));
    $aboutHeader = $aboutField('header');
    $aboutCopy = $aboutField('copy');
@endphp

@if($aboutHeader || $aboutCopy)
    <section class="bg-white px-6 pb-24 text-zinc-950 sm:px-10 sm:pb-36 lg:px-20 lg:pb-64" aria-labelledby="about-heading">
        <div class="mx-auto max-w-7xl">
            <h2 id="about-heading" class="font-display text-3xl leading-heading font-semibold sm:text-4xl lg:text-6xl">{{ $aboutHeader ?: __('About') }}</h2>
            @if($aboutCopy)
                <div class="rich-copy mt-5 text-lg leading-normal sm:mt-6 sm:text-2xl lg:mt-10 lg:text-3xl">
                    {!! $aboutCopy !!}
                </div>
            @endif
        </div>
    </section>
@endif
