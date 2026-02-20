{{-- Timeline Demo View --}}
{{-- Copy this file to resources/views/livewire/timeline-examples.blade.php --}}

<div class="max-w-6xl mx-auto p-6 space-y-12">
    <div>
        <flux:heading size="xl" level="1">Timeline Component</flux:heading>
        <flux:text class="mt-2 mb-6">Interactive narrative timelines powered by TimelineJS3, wrapped in a Flux-native component.</flux:text>
    </div>

    {{-- Section 1: Standalone Timeline --}}
    <section>
        <flux:heading size="lg" level="2">Standalone Timeline</flux:heading>
        <flux:text class="mt-1 mb-4">Full data-driven timeline with title, events, eras, and media.</flux:text>

        <flux:timeline
            name="tech-history"
            :data="$techTimeline"
            height="550px"
        />
    </section>

    <flux:separator />

    {{-- Section 2: Timeline with Controls Slot --}}
    <section>
        <flux:heading size="lg" level="2">Timeline with Custom Controls</flux:heading>
        <flux:text class="mt-1 mb-4">Use the slot to add overlay controls for navigation and zoom.</flux:text>

        <flux:timeline name="controlled" :data="$techTimeline" height="500px">
            <div class="flex items-center justify-between px-4 py-2 bg-white/80 dark:bg-zinc-800/80 backdrop-blur-sm rounded-t-lg border-b border-zinc-200 dark:border-zinc-700">
                <div class="flex gap-2">
                    <flux:button size="xs" variant="subtle" icon="chevron-left" x-on:click="Flux.timeline('controlled').goToPrev()" />
                    <flux:button size="xs" variant="subtle" icon="chevron-right" x-on:click="Flux.timeline('controlled').goToNext()" />
                </div>
                <div class="flex gap-2">
                    <flux:button size="xs" variant="subtle" icon="minus" x-on:click="Flux.timeline('controlled').zoomOut()" />
                    <flux:button size="xs" variant="subtle" icon="plus" x-on:click="Flux.timeline('controlled').zoomIn()" />
                </div>
                <div class="flex gap-2">
                    <flux:button size="xs" variant="subtle" x-on:click="Flux.timeline('controlled').goToStart()">Start</flux:button>
                    <flux:button size="xs" variant="subtle" x-on:click="Flux.timeline('controlled').goToEnd()">End</flux:button>
                </div>
            </div>
        </flux:timeline>
    </section>

    <flux:separator />

    {{-- Section 3: Timeline Inside a Carousel --}}
    <section>
        <flux:heading size="lg" level="2">Timeline Inside a Carousel</flux:heading>
        <flux:text class="mt-1 mb-4">Timelines lazy-load when their carousel panel becomes visible.</flux:text>

        <flux:carousel name="timeline-carousel" variant="directional">
            <flux:carousel.panels>
                <flux:carousel.panel name="computing">
                    <div class="p-4">
                        <flux:heading size="base" level="3" class="mb-3">History of Computing</flux:heading>
                        <flux:timeline
                            name="carousel-tech"
                            :data="$techTimeline"
                            height="500px"
                        />
                    </div>
                </flux:carousel.panel>

                <flux:carousel.panel name="laravel">
                    <div class="p-4">
                        <flux:heading size="base" level="3" class="mb-3">Laravel Through the Years</flux:heading>
                        <flux:timeline
                            name="carousel-laravel"
                            :events="$laravelTimeline['events']"
                            height="500px"
                        />
                    </div>
                </flux:carousel.panel>
            </flux:carousel.panels>

            <flux:carousel.controls />
        </flux:carousel>
    </section>

    <flux:separator />

    {{-- Section 4: Shorthand Events Array --}}
    <section>
        <flux:heading size="lg" level="2">Shorthand Events Syntax</flux:heading>
        <flux:text class="mt-1 mb-4">Pass just an events array for simpler usage.</flux:text>

        <flux:timeline :events="$laravelTimeline['events']" height="450px" />
    </section>

    <flux:separator />

    {{-- Section 5: Compact Container --}}
    <section>
        <flux:heading size="lg" level="2">Compact Container</flux:heading>
        <flux:text class="mt-1 mb-4">Timelines work in smaller containers too. Reduce the height and timenav to fit cards, sidebars, or dashboard widgets.</flux:text>

        <div class="grid grid-cols-1 lg:grid-cols-2 gap-6">
            {{-- Card-sized timeline --}}
            <flux:card>
                <flux:heading size="base" level="3" class="mb-3">Product Roadmap</flux:heading>
                <flux:timeline
                    name="compact-card"
                    :events="$compactTimeline['events']"
                    height="250px"
                    :timenav-height="50"
                    :watermark="false"
                />
            </flux:card>

            {{-- Minimal timeline with top nav --}}
            <flux:card>
                <flux:heading size="base" level="3" class="mb-3">Top Navigation</flux:heading>
                <flux:timeline
                    name="compact-topnav"
                    :events="$compactTimeline['events']"
                    height="250px"
                    :timenav-height="50"
                    timenav-position="top"
                    :watermark="false"
                />
            </flux:card>
        </div>
    </section>
</div>
