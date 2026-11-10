@php
    $faqField = fn ($data, $key, $default = null) => Statamic\View\Blade\value(data_get(Statamic\View\Blade\value($data), $key, $default));
    $faqHeader = $faqField($faqs ?? [], 'header');
    $questions = $faqField($faqs ?? [], 'questions', []) ?? [];
@endphp

@if($faqHeader || count($questions))
    <section class="bg-white px-6 pb-24 sm:px-10 sm:pb-36 lg:px-20 lg:pb-64" aria-labelledby="faqs-heading">
        <div class="mx-auto max-w-7xl">
            <h2 id="faqs-heading" class="font-display text-5xl leading-heading font-semibold text-green sm:text-6xl lg:text-[150px]">{{ $faqHeader ?: __('FAQs') }}</h2>

            @if(count($questions))
                <div class="mt-6 border-t border-zinc-500 sm:mt-10 lg:mt-14">
                    @foreach($questions as $question)
                        @php
                            $openOnLoad = (bool) $faqField($question, 'open_on_load', false);
                        @endphp
                        <div x-data="{ open: {{ $openOnLoad ? 'true' : 'false' }} }" class="border-b border-zinc-500">
                            <h3>
                                <button type="button" id="faqs-trigger-{{ $loop->iteration }}" aria-controls="faqs-panel-{{ $loop->iteration }}" aria-expanded="{{ $openOnLoad ? 'true' : 'false' }}" x-bind:aria-expanded="open" x-on:click="open = !open" class="flex w-full cursor-pointer text-left items-center justify-between gap-4 py-3 text-blue focus-visible:outline-2 focus-visible:outline-offset-4 focus-visible:outline-blue sm:gap-6 sm:py-4 lg:gap-10 lg:py-6">
                                    <span class="font-display text-2xl leading-heading font-semibold sm:text-4xl lg:text-6xl">{{ $faqField($question, 'header') }}</span>
                                    <svg x-bind:class="open ? 'rotate-0' : 'rotate-180'" class="size-5 shrink-0 transition-transform motion-reduce:transition-none sm:size-6 lg:size-9" viewBox="0 0 20 20" fill="currentColor" aria-hidden="true" focusable="false">
                                        <path d="m2 6 8 9 8-9H2Z" />
                                    </svg>
                                </button>
                            </h3>
                            <div id="faqs-panel-{{ $loop->iteration }}" aria-labelledby="faqs-trigger-{{ $loop->iteration }}" x-show="open" x-collapse @if(!$openOnLoad) x-cloak @endif>
                                <div class="rich-copy pb-6 text-lg leading-normal text-zinc-900 sm:text-2xl lg:pb-10 lg:text-4xl">
                                    {!! $faqField($question, 'copy') !!}
                                </div>
                            </div>
                        </div>
                    @endforeach
                </div>
            @endif
        </div>
    </section>
@endif
