@php
    $footer = Statamic\Facades\GlobalSet::findByHandle('footer')?->inCurrentSite();
    $footerCopy = Statamic\View\Blade\value($footer?->augmentedValue('copy'));
@endphp

@if($footerCopy)
    <footer class="bg-white px-6 pb-16 text-zinc-900 sm:px-10 sm:pb-24 lg:px-20 lg:pb-44">
        <div class="mx-auto max-w-7xl border-t border-slate-400 pt-4 sm:pt-6 lg:pt-10">
            <div class="rich-copy body-copy-secondary">
                {!! $footerCopy !!}
            </div>
        </div>
    </footer>
@endif
