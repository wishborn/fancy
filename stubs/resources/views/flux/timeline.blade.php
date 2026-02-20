@blaze

{{--
    Timeline Component: Interactive narrative timeline powered by TimelineJS3.

    Renders a rich, interactive timeline with slide-based navigation and a time
    navigation bar. Supports media (images, video, maps), grouping, eras, and
    full programmatic control from both JavaScript and Livewire.

    Why: Brings Knight Lab's TimelineJS3 to the Flux ecosystem with lazy loading,
    dark mode support, responsive sizing, and the standard Manager/Controller pattern.

    @see https://timeline.knightlab.com/ for TimelineJS3 documentation

    Usage (data-driven with full data source):

        <flux:timeline :data="[
            'title' => ['text' => ['headline' => 'Company History']],
            'events' => [
                ['start_date' => ['year' => 2020], 'text' => ['headline' => 'Founded']],
                ['start_date' => ['year' => 2023], 'text' => ['headline' => 'Series A']],
            ],
        ]" />

    Usage (shorthand with events array):

        <flux:timeline :events="[
            ['start_date' => ['year' => 2020], 'text' => ['headline' => 'Founded', 'text' => '<p>We started.</p>']],
            ['start_date' => ['year' => 2023], 'text' => ['headline' => 'Series A'], 'media' => ['url' => '/img/funding.jpg']],
        ]" />

    Usage (with slot for overlay controls):

        <flux:timeline name="history" :events="$events">
            <div class="flex justify-end p-2">
                <flux:button size="sm" x-on:click="Flux.timeline('history').goToStart()">Start</flux:button>
            </div>
        </flux:timeline>

    Props:
        - name: Instance name for external control (string, optional)
        - data: Full TimelineJS3 JSON data source (array, optional)
        - events: Shorthand events array - auto-wrapped into {events: [...]} (array, optional)
        - height: Container height as CSS value (string, default: '600px')
        - startAtSlide: Initial slide index (int, default: 0)
        - startAtEnd: Start on last slide (bool, default: false)
        - timenavPosition: Nav bar position - 'top' or 'bottom' (string, default: 'bottom')
        - timenavHeight: Nav bar height in px (int, optional)
        - language: Language code (string, default: 'en')
        - font: Font pair name (string, optional)
        - hashBookmark: Enable URL hash navigation (bool, default: false)
        - dragging: Enable drag navigation (bool, default: true)
        - options: Passthrough for additional TL.Timeline options (array, default: [])
        - lazy: Enable viewport-triggered init (bool, default: true)
        - watermark: Show TimelineJS attribution (bool, default: true)

    Programmatic control (JS):
        Flux.timeline('name').goToNext()
        Flux.timeline('name').zoomIn()
        Flux.timeline('name').add({ start_date: { year: 2025 }, text: { headline: 'New' } })

    Programmatic control (Livewire):
        FANCY::timeline('name')->goToNext()
        FANCY::timeline('name')->zoomIn()
        FANCY::timeline('name')->add(['start_date' => ['year' => 2025], 'text' => ['headline' => 'New']])
--}}

@props([
    'name' => null,             // Instance name for external control
    'data' => null,             // Full TimelineJS3 JSON data source
    'events' => null,           // Shorthand: just the events array
    'height' => '600px',        // Container height (CSS value)
    'startAtSlide' => 0,        // Initial slide index
    'startAtEnd' => false,      // Start on last slide
    'timenavPosition' => 'bottom', // 'top' or 'bottom'
    'timenavHeight' => null,    // Nav bar height in px
    'language' => 'en',         // Language code
    'font' => null,             // Font pair name
    'hashBookmark' => false,    // URL hash navigation
    'dragging' => true,         // Enable drag navigation
    'options' => [],            // Passthrough for additional TL.Timeline options
    'lazy' => true,             // Viewport-triggered init
    'watermark' => true,        // Show TimelineJS attribution
])

@php
$timelineId = $name ?? 'timeline-' . uniqid();

// Normalize data: if events prop is provided but data is not, wrap into data source
$dataSource = $data;
if ($dataSource === null && $events !== null) {
    $dataSource = ['events' => $events];
}
$dataSourceJson = json_encode($dataSource ?? ['events' => []]);

// Build TimelineJS3 options from props
$timelineOptions = array_filter([
    'start_at_slide' => $startAtSlide,
    'start_at_end' => $startAtEnd ?: null,
    'timenav_position' => $timenavPosition,
    'timenav_height' => $timenavHeight,
    'language' => $language !== 'en' ? $language : null,
    'font' => $font,
    'hash_bookmark' => $hashBookmark ?: null,
    'dragging' => $dragging ? null : false,
    'trackResize' => false, // We handle resize ourselves
], fn ($v) => $v !== null);

// Merge with passthrough options (passthrough takes precedence)
$mergedOptions = array_merge($timelineOptions, $options);
$optionsJson = json_encode((object) $mergedOptions);

$containerClasses = Flux::classes()
    ->add('relative')
    ->add(!$watermark ? 'hide-watermark' : '')
    ;
@endphp

<div
    x-data="fancyTimeline({
        id: '{{ $timelineId }}',
        data: {{ $dataSourceJson }},
        height: '{{ $height }}',
        options: {{ $optionsJson }},
        lazy: {{ $lazy ? 'true' : 'false' }},
    })"
    @if ($lazy)
        x-intersect.once.threshold.0.1="initTimeline()"
        x-init="checkVisibility()"
    @else
        x-init="$nextTick(() => initTimeline())"
    @endif
    {{ $attributes->class($containerClasses) }}
    data-flux-timeline="{{ $timelineId }}"
    id="{{ $timelineId }}"
    x-resize.document="handleResize()"
    {{-- External control event listeners --}}
    x-on:timeline-goto.window="if ($event.detail.id === '{{ $timelineId }}') handleGoTo($event.detail)"
    x-on:timeline-zoom.window="if ($event.detail.id === '{{ $timelineId }}') handleZoom($event.detail)"
    x-on:timeline-add.window="if ($event.detail.id === '{{ $timelineId }}') handleAdd($event.detail)"
    x-on:timeline-remove.window="if ($event.detail.id === '{{ $timelineId }}') handleRemove($event.detail)"
    x-on:timeline-refresh.window="if ($event.detail.id === '{{ $timelineId }}') handleRefresh()"
    x-on:timeline-update-data.window="if ($event.detail.id === '{{ $timelineId }}') handleUpdateData($event.detail)"
    {{-- Carousel integration --}}
    x-on:carousel-navigated.window="if (!initialized) checkVisibility()"
>
    {{-- Loading overlay --}}
    <div
        class="absolute inset-0 flex items-center justify-center bg-white/95 dark:bg-zinc-800/95 backdrop-blur-md rounded-lg z-20 transition-opacity duration-200"
        :class="{ 'opacity-100 pointer-events-auto': isLoading, 'opacity-0 pointer-events-none': !isLoading }"
    >
        <div class="flex flex-col items-center gap-3">
            <div class="relative size-12">
                <div class="absolute inset-0 border-4 border-zinc-200 dark:border-zinc-600 rounded-full"></div>
                <div class="absolute inset-0 border-4 border-transparent border-t-blue-500 dark:border-t-blue-400 rounded-full animate-spin"></div>
            </div>
            <p class="text-sm font-medium text-zinc-700 dark:text-zinc-200">Loading timeline...</p>
        </div>
    </div>

    {{-- Slot for overlay content (controls, legends, etc.) --}}
    @if (!$slot->isEmpty())
        <div class="relative z-10" data-flux-timeline-slot>
            {{ $slot }}
        </div>
    @endif

    {{-- TimelineJS3 render container --}}
    <div
        x-ref="timelineContainer"
        class="tl-timeline-container"
        :style="{ height: '{{ $height }}' }"
        data-flux-timeline-container
    ></div>
</div>

@once
@push('scripts')
{{-- TimelineJS3 CDN assets --}}
<link rel="stylesheet" href="https://cdn.knightlab.com/libs/timeline3/latest/css/timeline.css" />
<script src="https://cdn.knightlab.com/libs/timeline3/latest/js/timeline.js"></script>

{{-- CSS isolation: prevent Tailwind preflight from breaking TimelineJS3 --}}
<style>
    .tl-timeline-container,
    .tl-timeline-container *,
    .tl-timeline-container *::before,
    .tl-timeline-container *::after {
        box-sizing: content-box;
    }
    .tl-timeline-container img {
        max-width: none;
    }
    .tl-timeline-container h1,
    .tl-timeline-container h2,
    .tl-timeline-container h3,
    .tl-timeline-container p {
        margin: revert;
    }
    /* Dark mode overrides for TimelineJS3 */
    .dark .tl-timeline-container .tl-timeline {
        background-color: #18181b;
    }
    .dark .tl-timeline-container .tl-timemarker-content-container .tl-timemarker-content .tl-timemarker-text h2.tl-headline,
    .dark .tl-timeline-container .tl-headline,
    .dark .tl-timeline-container .tl-headline-date {
        color: #e4e4e7;
    }
    .dark .tl-timeline-container .tl-text p,
    .dark .tl-timeline-container .tl-text {
        color: #a1a1aa;
    }
    .dark .tl-timeline-container .tl-timenav {
        background-color: #27272a;
    }
    .dark .tl-timeline-container .tl-timenav-slider {
        background-color: #27272a;
    }
    .dark .tl-timeline-container .tl-timeaxis {
        background-color: #27272a;
    }
    .dark .tl-timeline-container .tl-timeaxis-background {
        background-color: #27272a;
    }
    .dark .tl-timeline-container .tl-timeaxis-tick:before {
        background-color: #52525b;
    }
    .dark .tl-timeline-container .tl-timeaxis-major .tl-timeaxis-tick-text span,
    .dark .tl-timeline-container .tl-timeaxis-minor .tl-timeaxis-tick-text span {
        color: #a1a1aa;
    }
    .dark .tl-timeline-container .tl-timemarker .tl-timemarker-content-container {
        background-color: #3f3f46;
        border-color: #52525b;
    }
    .dark .tl-timeline-container .tl-timemarker .tl-timemarker-timespan {
        background-color: #52525b;
    }
    .dark .tl-timeline-container .tl-timemarker .tl-timemarker-line-left,
    .dark .tl-timeline-container .tl-timemarker .tl-timemarker-line-right {
        border-color: #52525b;
    }
    .dark .tl-timeline-container .tl-slidenav-next .tl-slidenav-title,
    .dark .tl-timeline-container .tl-slidenav-previous .tl-slidenav-title,
    .dark .tl-timeline-container .tl-slidenav-next .tl-slidenav-description,
    .dark .tl-timeline-container .tl-slidenav-previous .tl-slidenav-description {
        color: #a1a1aa;
    }
    .dark .tl-timeline-container .tl-slide-content {
        background-color: #18181b;
    }
    .dark .tl-timeline-container .tl-menubar {
        background-color: #27272a;
    }
    .dark .tl-timeline-container .tl-menubar .tl-menubar-button {
        color: #a1a1aa;
    }
    /* Hide TimelineJS attribution watermark when opted out */
    .hide-watermark .tl-attribution {
        display: none !important;
    }
</style>

<script>
document.addEventListener('alpine:init', () => {
    Alpine.data('fancyTimeline', (config) => ({
        // Configuration
        id: config.id,
        data: config.data,
        height: config.height,
        options: config.options,
        lazy: config.lazy,

        // State
        timeline: null,
        isLoading: true,
        initialized: false,

        checkVisibility() {
            if (this.initialized) return;

            // Check if inside a carousel panel
            const parentPanel = this.$el.closest('[data-flux-carousel-step-item]');
            const panelIsActive = !parentPanel ||
                                window.getComputedStyle(parentPanel).display !== 'none' &&
                                !parentPanel.hasAttribute('aria-hidden') ||
                                parentPanel.getAttribute('aria-hidden') === 'false';

            const rect = this.$el.getBoundingClientRect();
            const isVisible = rect.width > 0 && rect.height > 0 &&
                             window.getComputedStyle(this.$el).display !== 'none';

            if (panelIsActive && isVisible && !this.initialized) {
                setTimeout(() => {
                    if (!this.initialized) {
                        this.initTimeline();
                    }
                }, 200);
                return;
            }

            // Fallback: IntersectionObserver
            const observer = new IntersectionObserver((entries) => {
                entries.forEach(entry => {
                    if (entry.isIntersecting && entry.intersectionRatio > 0 && !this.initialized) {
                        observer.disconnect();
                        setTimeout(() => {
                            if (!this.initialized) {
                                this.initTimeline();
                            }
                        }, 150);
                    }
                });
            }, {
                threshold: 0.1,
                rootMargin: '50px'
            });

            observer.observe(this.$el);
        },

        initTimeline() {
            if (this.initialized) return;
            this.initialized = true;
            this.isLoading = true;

            this.$nextTick(() => {
                // Wait for TL global to be available (CDN may still be loading)
                const waitForTL = (attempts = 0) => {
                    if (typeof TL !== 'undefined' && TL.Timeline) {
                        this.createTimeline();
                        return;
                    }
                    if (attempts < 50) {
                        setTimeout(() => waitForTL(attempts + 1), 100);
                    } else {
                        console.error('TimelineJS3 failed to load from CDN');
                        this.isLoading = false;
                    }
                };
                waitForTL();
            });
        },

        createTimeline() {
            const container = this.$refs.timelineContainer;
            if (!container) return;

            // Give container a unique ID for TimelineJS3 (it requires an ID string)
            const containerId = this.id + '-container';
            container.id = containerId;

            const startTime = Date.now();
            const minLoadingTime = 400;

            try {
                this.timeline = new TL.Timeline(containerId, this.data, this.options);

                // Listen for the loaded event to hide loading overlay
                this.timeline.on('loaded', () => {
                    const elapsed = Date.now() - startTime;
                    const remaining = Math.max(0, minLoadingTime - elapsed);
                    setTimeout(() => {
                        this.isLoading = false;
                    }, remaining);
                });

                // Fallback timeout in case loaded event never fires
                setTimeout(() => {
                    if (this.isLoading) {
                        this.isLoading = false;
                    }
                }, 5000);
            } catch (e) {
                console.error('TimelineJS3 initialization error:', e);
                this.isLoading = false;
            }
        },

        // ── Resize ──────────────────────────────────────────────

        handleResize() {
            if (this.timeline) {
                this.timeline.updateDisplay();
            }
        },

        // ── External Control Handlers ───────────────────────────

        handleGoTo(detail) {
            if (!this.timeline) return;

            if (detail.position === 'start') {
                this.timeline.goToStart();
            } else if (detail.position === 'end') {
                this.timeline.goToEnd();
            } else if (detail.position === 'prev') {
                this.timeline.goToPrev();
            } else if (detail.position === 'next') {
                this.timeline.goToNext();
            } else if (detail.uniqueId) {
                this.timeline.goToId(detail.uniqueId);
            } else if (detail.index !== undefined) {
                this.timeline.goTo(detail.index);
            }
        },

        handleZoom(detail) {
            if (!this.timeline) return;

            if (detail.direction === 'in') {
                this.timeline.zoomIn();
            } else if (detail.direction === 'out') {
                this.timeline.zoomOut();
            } else if (detail.level !== undefined) {
                this.timeline.setZoom(detail.level);
            }
        },

        handleAdd(detail) {
            if (!this.timeline || !detail.event) return;
            this.timeline.add(detail.event);
        },

        handleRemove(detail) {
            if (!this.timeline) return;

            if (detail.uniqueId) {
                this.timeline.removeId(detail.uniqueId);
            } else if (detail.index !== undefined) {
                this.timeline.remove(detail.index);
            }
        },

        handleRefresh() {
            if (!this.timeline) return;
            this.timeline.updateDisplay();
        },

        handleUpdateData(detail) {
            if (!detail.data) return;

            // Destroy and recreate with new data
            this.data = detail.data;
            const container = this.$refs.timelineContainer;
            if (container) {
                container.innerHTML = '';
            }
            this.timeline = null;
            this.createTimeline();
        },

        // Cleanup on Alpine component destroy
        destroy() {
            if (this.timeline) {
                this.timeline = null;
            }
        },
    }));
});
</script>
@endpush
@endonce
