# Timeline Component

Interactive narrative timeline powered by [TimelineJS3](https://timeline.knightlab.com/) by Knight Lab. Renders rich, interactive timelines with slide-based navigation, grouping, eras, and media support.

> **Note:** TimelineJS3 is loaded from CDN. No npm dependency required.

## Why This Component?

The Timeline component brings narrative storytelling to FancyFlux:
- Rich slide-based event display with headlines, text, and media
- Time navigation bar with era highlighting
- Grouping for categorized events
- Lazy loading for carousel-nested timelines
- Dark mode with automatic theming
- Programmatic control from both JS and Livewire

## Basic Usage

### Data-Driven (Full Data Source)

```blade
@php
$timeline = [
    'title' => [
        'text' => ['headline' => 'Company History', 'text' => 'Our journey from startup to scale.'],
    ],
    'events' => [
        ['start_date' => ['year' => 2020], 'text' => ['headline' => 'Founded', 'text' => 'Started in a garage.']],
        ['start_date' => ['year' => 2022], 'text' => ['headline' => 'Series A', 'text' => 'Raised $10M.']],
        ['start_date' => ['year' => 2024], 'text' => ['headline' => 'IPO', 'text' => 'Went public.']],
    ],
];
@endphp

<flux:timeline :data="$timeline" />
```

### Shorthand Events Array

Pass just an events array for simpler usage:

```blade
<flux:timeline :events="[
    ['start_date' => ['year' => 2020], 'text' => ['headline' => 'Founded']],
    ['start_date' => ['year' => 2023], 'text' => ['headline' => 'Series A']],
]" />
```

### With Custom Controls Slot

Use the slot to add overlay controls:

```blade
<flux:timeline name="history" :data="$timeline">
    <div class="flex gap-2 p-2">
        <flux:button size="xs" icon="chevron-left" x-on:click="Flux.timeline('history').goToPrev()" />
        <flux:button size="xs" icon="chevron-right" x-on:click="Flux.timeline('history').goToNext()" />
        <flux:button size="xs" icon="minus" x-on:click="Flux.timeline('history').zoomOut()" />
        <flux:button size="xs" icon="plus" x-on:click="Flux.timeline('history').zoomIn()" />
    </div>
</flux:timeline>
```

### Inside a Carousel

Timelines lazy-load when their carousel panel becomes visible:

```blade
<flux:carousel name="timelines" variant="directional">
    <flux:carousel.panels>
        <flux:carousel.panel name="history">
            <flux:timeline name="company-history" :data="$historyTimeline" />
        </flux:carousel.panel>
        <flux:carousel.panel name="roadmap">
            <flux:timeline name="product-roadmap" :events="$roadmapEvents" />
        </flux:carousel.panel>
    </flux:carousel.panels>
    <flux:carousel.controls />
</flux:carousel>
```

## Data Format

### Full Data Source

```php
$data = [
    'title' => [
        'text' => [
            'headline' => 'Timeline Title',
            'text' => 'Optional subtitle/description.',
        ],
    ],
    'eras' => [
        [
            'start_date' => ['year' => 2018],
            'end_date' => ['year' => 2020],
            'text' => ['headline' => 'Phase 1'],
        ],
    ],
    'events' => [
        [
            'start_date' => ['year' => 2020, 'month' => 3, 'day' => 15],
            'end_date' => ['year' => 2020, 'month' => 6],   // Optional range
            'text' => [
                'headline' => 'Event Title',
                'text' => '<p>HTML description supported.</p>',
            ],
            'media' => [                                      // Optional
                'url' => 'https://example.com/image.jpg',
                'caption' => 'Photo credit',
            ],
            'group' => 'Category Name',                       // Optional grouping
            'unique_id' => 'custom-id',                       // Optional for goToId()
        ],
    ],
];
```

### Date Formats

TimelineJS3 supports flexible date objects:

```php
['year' => 2024]                            // Year only
['year' => 2024, 'month' => 6]             // Year + month
['year' => 2024, 'month' => 6, 'day' => 15] // Full date
```

### Media Types

The `media.url` field supports images, YouTube, Vimeo, Google Maps, Wikipedia, and more. See [TimelineJS3 docs](https://timeline.knightlab.com/docs/media-types.html).

## Programmatic Control (JavaScript)

Use the `Flux.timeline()` helper from Alpine or browser JS:

```js
// Navigation
Flux.timeline('my-timeline').goToNext();
Flux.timeline('my-timeline').goToPrev();
Flux.timeline('my-timeline').goToStart();
Flux.timeline('my-timeline').goToEnd();
Flux.timeline('my-timeline').goTo(3);          // By slide index
Flux.timeline('my-timeline').goToId('custom-id'); // By unique_id

// Zoom
Flux.timeline('my-timeline').zoomIn();
Flux.timeline('my-timeline').zoomOut();
Flux.timeline('my-timeline').setZoom(5);

// Data manipulation
Flux.timeline('my-timeline').add({ start_date: { year: 2025 }, text: { headline: 'New Event' } });
Flux.timeline('my-timeline').remove(2);        // By index
Flux.timeline('my-timeline').removeId('id');    // By unique_id

// Display
Flux.timeline('my-timeline').refresh();        // Force redraw
Flux.timeline('my-timeline').updateData(newData); // Replace all data
```

**Important:** The JS helper requires importing `timeline.js` in your app's JS bundle:

```js
// resources/js/app.js
import '../../packages/fancy-flux/resources/js/timeline.js';
```

## Programmatic Control (Livewire)

Use the FANCY facade in Livewire components:

```php
use FancyFlux\Facades\Fancy;

class MyComponent extends Component
{
    public function nextEvent(): void
    {
        FANCY::timeline('my-timeline')->goToNext();
    }

    public function jumpToMilestone(): void
    {
        FANCY::timeline('my-timeline')->goToId('milestone-1');
    }

    public function addEvent(): void
    {
        FANCY::timeline('my-timeline')->add([
            'start_date' => ['year' => 2025],
            'text' => ['headline' => 'New Milestone'],
        ]);
    }

    public function zoomToOverview(): void
    {
        FANCY::timeline('my-timeline')->zoomOut();
    }

    public function replaceData(): void
    {
        FANCY::timeline('my-timeline')->updateData($this->newTimelineData);
    }
}
```

### Available Facade Methods

| Method | Description |
|--------|-------------|
| `goTo(int $index)` | Navigate to slide by index |
| `goToId(string $id)` | Navigate to slide by unique_id |
| `goToStart()` | Navigate to first slide |
| `goToEnd()` | Navigate to last slide |
| `goToPrev()` | Navigate to previous slide |
| `goToNext()` | Navigate to next slide |
| `zoomIn()` | Zoom into the timeline |
| `zoomOut()` | Zoom out of the timeline |
| `setZoom(int $level)` | Set specific zoom level |
| `add(array $event)` | Add an event to the timeline |
| `remove(int $index)` | Remove event by index |
| `removeId(string $id)` | Remove event by unique_id |
| `updateData(array $data)` | Replace the entire data source |
| `refresh()` | Force display refresh |

## Props Reference

| Prop | Type | Default | Description |
|------|------|---------|-------------|
| `name` | string | null | Instance name for external control |
| `data` | array | null | Full TimelineJS3 JSON data source |
| `events` | array | null | Shorthand: just the events array |
| `height` | string | '600px' | Container height (CSS value) |
| `start-at-slide` | int | 0 | Initial slide index |
| `start-at-end` | bool | false | Start on the last slide |
| `timenav-position` | string | 'bottom' | Nav bar position: 'top' or 'bottom' |
| `timenav-height` | int | null | Nav bar height in px |
| `language` | string | 'en' | Language code |
| `font` | string | null | Font pair name |
| `hash-bookmark` | bool | false | Enable URL hash navigation |
| `dragging` | bool | true | Enable drag navigation |
| `options` | array | [] | Passthrough for additional TL.Timeline options |
| `lazy` | bool | true | Viewport-triggered init (for carousel contexts) |

## Dark Mode

Dark mode is automatic. The component applies scoped CSS overrides when the `dark` class is present on `<html>`, re-theming TimelineJS3's default styles with zinc tones matching Flux's dark palette.

## CSS Isolation

The component includes scoped styles that prevent Tailwind's CSS preflight from interfering with TimelineJS3's rendering. This is handled automatically - no configuration needed.

## Examples

### Impact Milestones Dashboard

```blade
<flux:timeline
    name="impact-timeline"
    :data="[
        'title' => ['text' => ['headline' => 'Our Impact Journey']],
        'eras' => [
            ['start_date' => ['year' => 2018], 'end_date' => ['year' => 2021], 'text' => ['headline' => 'Foundation']],
            ['start_date' => ['year' => 2021], 'end_date' => ['year' => 2024], 'text' => ['headline' => 'Growth']],
        ],
        'events' => $milestones,
    ]"
    height="450px"
    timenav-position="bottom"
    :timenav-height="120"
/>
```

### Grouped Events

```blade
@php
$events = [
    ['start_date' => ['year' => 2023, 'month' => 1], 'text' => ['headline' => 'Feature A'], 'group' => 'Product'],
    ['start_date' => ['year' => 2023, 'month' => 3], 'text' => ['headline' => 'Hire #50'], 'group' => 'Team'],
    ['start_date' => ['year' => 2023, 'month' => 6], 'text' => ['headline' => 'Series B'], 'group' => 'Funding'],
];
@endphp

<flux:timeline :events="$events" height="500px" />
```
