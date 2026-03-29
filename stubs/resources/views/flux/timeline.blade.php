{{--
    Timeline Component: A lightweight timeline for displaying events.

    Pure Tailwind CSS + Alpine.js. No external dependencies.

    Features:
    - Stacked (default), alternating, or horizontal layout variants
    - Per-event color, icon, or emoji accents
    - Scroll-reveal animation
    - Dark mode via Tailwind dark: classes
    - Responsive: alternating collapses to stacked, horizontal scrolls on mobile

    Usage:
        <flux:timeline :events="$events" />
        <flux:timeline :events="$events" variant="alternating" />
        <flux:timeline :events="$events" variant="horizontal" />
        <flux:timeline :events="$events" heading="Our Journey" />

    Event structure:
        [
            'date' => 'March 2024',
            'title' => 'Series A',
            'description' => 'Raised $10M in funding.',
            'icon' => 'rocket-launch',
            'emoji' => '🚀',
            'color' => 'blue',
        ]
--}}

@props([
    'events' => [],
    'variant' => 'stacked',
    'heading' => null,
    'description' => null,
    'animated' => true,
])

@php
$isAlternating = $variant === 'alternating';
$isHorizontal = $variant === 'horizontal';
$isVertical = !$isHorizontal;
@endphp

<div
    {{ $attributes->class('') }}
    @if($animated)
        x-data="{ shown: Array({{ count($events) }}).fill(false) }"
    @endif
    data-flux-timeline
    data-variant="{{ $variant }}"
>
    @if ($heading || $description)
        <div class="mb-8">
            @if ($heading)
                <h2 class="text-xl font-semibold text-zinc-900 dark:text-white">{{ $heading }}</h2>
            @endif
            @if ($description)
                <p class="mt-1 text-sm text-zinc-500 dark:text-zinc-400">{{ $description }}</p>
            @endif
        </div>
    @endif

    @if (!$slot->isEmpty())
        <div class="mb-6" data-flux-timeline-slot>
            {{ $slot }}
        </div>
    @endif

    @if ($isHorizontal)
    {{-- ═══════════════════════════════════════════════════════
         HORIZONTAL VARIANT
         Scrollable row: dots on a horizontal line, content below
         ═══════════════════════════════════════════════════════ --}}
    <div
        class="overflow-x-auto pb-4 -mb-4"
        style="scrollbar-width: thin; scrollbar-color: rgb(161 161 170) transparent;"
        x-on:wheel.prevent="$el.scrollLeft += $event.deltaY"
    >
        <div class="flex items-start min-w-max">
            @foreach ($events as $index => $event)
                @php
                    $hasIcon = !empty($event['icon']);
                    $hasEmoji = !empty($event['emoji']);
                    $isLargeDot = $hasIcon || $hasEmoji;
                    $isLast = $loop->last;

                    $dotColor = match($event['color'] ?? null) {
                        'red' => 'bg-red-500', 'orange' => 'bg-orange-500', 'amber' => 'bg-amber-500',
                        'yellow' => 'bg-yellow-500', 'lime' => 'bg-lime-500', 'green' => 'bg-green-500',
                        'emerald' => 'bg-emerald-500', 'teal' => 'bg-teal-500', 'cyan' => 'bg-cyan-500',
                        'sky' => 'bg-sky-500', 'blue' => 'bg-blue-500', 'indigo' => 'bg-indigo-500',
                        'violet' => 'bg-violet-500', 'purple' => 'bg-purple-500', 'fuchsia' => 'bg-fuchsia-500',
                        'pink' => 'bg-pink-500', 'rose' => 'bg-rose-500',
                        default => 'bg-zinc-300 dark:bg-zinc-600',
                    };
                @endphp

                <div
                    class="flex flex-col items-center {{ $isLast ? '' : 'mr-0' }}
                        {{ $animated ? 'transition duration-500 ease-out' : '' }}"
                    style="{{ $isLast ? '' : 'min-width: 10rem;' }}"
                    @if($animated)
                        x-intersect.once.threshold.20="shown[{{ $index }}] = true"
                        :class="shown[{{ $index }}] ? 'opacity-100 translate-y-0' : 'opacity-0 translate-y-4'"
                    @endif
                >
                    {{-- Dot + Horizontal Line (fixed height so lines always align) --}}
                    <div class="flex items-center w-full h-8">
                        {{-- Left line segment --}}
                        @if (!$loop->first)
                            <div class="flex-1 h-px bg-zinc-200 dark:bg-zinc-700"></div>
                        @else
                            <div class="flex-1"></div>
                        @endif

                        {{-- Dot --}}
                        @if ($hasEmoji)
                            <div class="w-8 h-8 rounded-full bg-zinc-100 dark:bg-zinc-800 border border-zinc-200 dark:border-zinc-700 flex items-center justify-center shrink-0">
                                <span class="text-sm">{{ $event['emoji'] }}</span>
                            </div>
                        @elseif ($hasIcon)
                            <div class="w-8 h-8 rounded-full {{ $dotColor }} flex items-center justify-center shrink-0">
                                <flux:icon :name="$event['icon']" variant="micro" class="size-4 text-white" />
                            </div>
                        @else
                            <div class="w-3 h-3 rounded-full {{ $dotColor }} shrink-0"></div>
                        @endif

                        {{-- Right line segment --}}
                        @if (!$isLast)
                            <div class="flex-1 h-px bg-zinc-200 dark:bg-zinc-700"></div>
                        @else
                            <div class="flex-1"></div>
                        @endif
                    </div>

                    {{-- Content (below the line) --}}
                    <div class="mt-3 text-center px-2 max-w-40">
                        @if (!empty($event['date']))
                            <time class="text-xs font-medium uppercase tracking-wide text-zinc-500 dark:text-zinc-400">{{ $event['date'] }}</time>
                        @endif
                        @if (!empty($event['title']))
                            <h3 class="font-semibold text-sm text-zinc-900 dark:text-white">{{ $event['title'] }}</h3>
                        @endif
                        @if (!empty($event['description']))
                            <div class="mt-1 text-xs text-zinc-600 dark:text-zinc-400 leading-relaxed">{!! $event['description'] !!}</div>
                        @endif
                    </div>
                </div>
            @endforeach
        </div>
    </div>

    @else
    {{-- ═══════════════════════════════════════════════════════
         VERTICAL VARIANTS (stacked + alternating)
         ═══════════════════════════════════════════════════════ --}}
    <div>
        @foreach ($events as $index => $event)
            @php
                $hasIcon = !empty($event['icon']);
                $hasEmoji = !empty($event['emoji']);
                $isLargeDot = $hasIcon || $hasEmoji;
                $isEven = $index % 2 === 0;

                $dotColor = match($event['color'] ?? null) {
                    'red' => 'bg-red-500', 'orange' => 'bg-orange-500', 'amber' => 'bg-amber-500',
                    'yellow' => 'bg-yellow-500', 'lime' => 'bg-lime-500', 'green' => 'bg-green-500',
                    'emerald' => 'bg-emerald-500', 'teal' => 'bg-teal-500', 'cyan' => 'bg-cyan-500',
                    'sky' => 'bg-sky-500', 'blue' => 'bg-blue-500', 'indigo' => 'bg-indigo-500',
                    'violet' => 'bg-violet-500', 'purple' => 'bg-purple-500', 'fuchsia' => 'bg-fuchsia-500',
                    'pink' => 'bg-pink-500', 'rose' => 'bg-rose-500',
                    default => 'bg-zinc-300 dark:bg-zinc-600',
                };
            @endphp

            <div
                class="{{ $isAlternating
                    ? 'flex gap-x-4 md:grid md:grid-cols-[1fr_1.5rem_1fr] md:gap-x-6'
                    : 'flex gap-x-4' }}
                    {{ $loop->last ? '' : 'pb-8' }}
                    {{ $animated ? 'transition duration-500 ease-out' : '' }}"
                @if($animated)
                    x-intersect.once.threshold.20="shown[{{ $index }}] = true"
                    :class="shown[{{ $index }}] ? 'opacity-100 translate-y-0' : 'opacity-0 translate-y-4'"
                @endif
            >
                {{-- Dot + Line --}}
                <div class="flex flex-col items-center shrink-0 {{ $isAlternating ? 'md:col-start-2 md:row-start-1' : '' }}">
                    @if ($hasEmoji)
                        <div class="w-8 h-8 rounded-full bg-zinc-100 dark:bg-zinc-800 border border-zinc-200 dark:border-zinc-700 flex items-center justify-center shrink-0">
                            <span class="text-sm">{{ $event['emoji'] }}</span>
                        </div>
                    @elseif ($hasIcon)
                        <div class="w-8 h-8 rounded-full {{ $dotColor }} flex items-center justify-center shrink-0">
                            <flux:icon :name="$event['icon']" variant="micro" class="size-4 text-white" />
                        </div>
                    @else
                        <div class="w-3 h-3 rounded-full {{ $dotColor }} shrink-0 mt-1.5"></div>
                    @endif

                    @if (!$loop->last)
                        <div class="w-px flex-1 min-h-4 {{ $isLargeDot ? 'mt-1' : '' }} bg-zinc-200 dark:bg-zinc-700"></div>
                    @endif
                </div>

                {{-- Content --}}
                <div class="{{ $isLargeDot ? 'pt-1' : '' }} {{ $isAlternating && $isEven ? 'md:col-start-1 md:row-start-1 md:text-right' : '' }} {{ $isAlternating && !$isEven ? 'md:col-start-3' : '' }}">
                    @if (!empty($event['date']))
                        <time class="text-xs font-medium uppercase tracking-wide text-zinc-500 dark:text-zinc-400">{{ $event['date'] }}</time>
                    @endif
                    @if (!empty($event['title']))
                        <h3 class="font-semibold text-zinc-900 dark:text-white">{{ $event['title'] }}</h3>
                    @endif
                    @if (!empty($event['description']))
                        <div class="mt-1 text-sm text-zinc-600 dark:text-zinc-400 leading-relaxed">{!! $event['description'] !!}</div>
                    @endif
                </div>
            </div>
        @endforeach
    </div>
    @endif
</div>
