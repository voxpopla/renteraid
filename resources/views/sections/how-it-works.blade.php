@php
    $getField = fn ($data, $key, $default = null) => Statamic\View\Blade\value(data_get(Statamic\View\Blade\value($data), $key, $default));
    $section = $how_it_works ?? [];
    $sectionHeader = $getField($section, 'header');
    $steps = $getField($section, 'steps', []) ?? [];
@endphp

@if($sectionHeader || count($steps))
    <section class="bg-white px-6 pb-24 sm:px-10 sm:pb-36 lg:px-20 lg:pb-64" aria-labelledby="how-it-works-heading">
        <div class="mx-auto max-w-7xl">
            <h2 id="how-it-works-heading" class="font-display text-5xl leading-heading font-semibold text-blue sm:text-6xl lg:text-8xl">{{ $sectionHeader ?: __('How It Works') }}</h2>

            @if(count($steps))
                <div class="mt-10 border-t border-zinc-500 sm:mt-14 lg:mt-20">
                    @foreach($steps as $step)
                        @php
                            $stepHeader = $getField($step, 'header');
                            $blocks = $getField($step, 'copy', []) ?? [];
                            $openOnLoad = (bool) $getField($step, 'open_on_load', false);
                        @endphp
                        <div x-data="{ open: {{ $openOnLoad ? 'true' : 'false' }} }" class="border-b border-zinc-500">
                            <h3>
                                <button type="button" id="how-it-works-trigger-{{ $loop->iteration }}" aria-controls="how-it-works-panel-{{ $loop->iteration }}" aria-expanded="{{ $openOnLoad ? 'true' : 'false' }}" x-bind:aria-expanded="open" x-on:click="open = !open" class="flex w-full cursor-pointer text-left items-center justify-between gap-4 px-2 py-3 text-blue focus-visible:outline-2 focus-visible:outline-offset-4 focus-visible:outline-blue sm:py-4 lg:px-6 lg:py-6">
                                    <span class="font-display text-2xl leading-heading font-semibold sm:text-4xl lg:text-6xl">{{ $loop->iteration }}. {{ $stepHeader }}</span>
                                    <svg x-bind:class="open ? 'rotate-0' : 'rotate-180'" class="size-5 shrink-0 transition-transform motion-reduce:transition-none sm:size-6 lg:size-9" viewBox="0 0 20 20" fill="currentColor" aria-hidden="true" focusable="false">
                                        <path d="m2 6 8 9 8-9H2Z" />
                                    </svg>
                                </button>
                            </h3>
                            <div id="how-it-works-panel-{{ $loop->iteration }}" aria-labelledby="how-it-works-trigger-{{ $loop->iteration }}" x-show="open" x-collapse @if(!$openOnLoad) x-cloak @endif>
                                <div class="space-y-5 pb-6 text-lg leading-normal text-zinc-900 sm:space-y-6 sm:text-2xl lg:space-y-8 lg:pb-10 lg:text-4xl">
                                    @if(is_string($blocks))
                                        <div class="step-copy">{!! $blocks !!}</div>
                                    @else
                                        @foreach($blocks as $block)
                                            @if($getField($block, 'type') === 'text')
                                                <div class="step-copy">{!! $getField($block, 'text') !!}</div>
                                            @elseif($getField($block, 'type') === 'callout')
                                                <div class="step-copy border border-zinc-400 p-5 shadow-sm sm:p-8 lg:p-14">
                                                    {!! $getField($block, 'copy') !!}
                                                </div>
                                            @endif
                                        @endforeach
                                    @endif
                                </div>
                            </div>
                        </div>
                    @endforeach
                </div>
            @endif
        </div>
    </section>
@endif
