# Timeline Component

A lightweight vertical timeline for displaying events. Pure Tailwind CSS + Alpine.js with no external dependencies.

## Quick Start

```blade
<flux:timeline :events="$events" />
```

## Event Structure

Each event is an associative array:

```php
$events = [
    [
        'date' => 'March 2024',         // Free-form date string
        'title' => 'Series A',          // Event heading
        'description' => 'Details...',  // Optional, supports HTML
        'icon' => 'rocket-launch',      // Optional Heroicon name
        'emoji' => '🚀',               // Optional emoji (alternative to icon)
        'color' => 'blue',             // Optional accent color
    ],
];
```

All fields except `title` are optional.

## Layout Variants

### Stacked (Default)

All events on one side of the timeline:

```blade
<flux:timeline :events="$events" />
```

### Alternating

Events alternate left and right on desktop. Collapses to stacked on mobile:

```blade
<flux:timeline :events="$events" variant="alternating" />
```

## Heading and Description

```blade
<flux:timeline
    :events="$events"
    heading="Our Journey"
    description="Key milestones since founding."
/>
```

## Icons and Emoji

Events can display a Heroicon or emoji on the timeline dot:

```php
// Heroicon (colored circle with white icon)
['icon' => 'rocket-launch', 'color' => 'blue', ...]

// Emoji (neutral circle with emoji)
['emoji' => '🚀', ...]

// Plain dot (small colored circle)
['color' => 'green', ...]
```

When no icon or emoji is provided, a small colored dot is shown. When no color is specified, the dot defaults to zinc/gray.

## Colors

Supported accent colors for timeline dots:

`red`, `orange`, `amber`, `yellow`, `lime`, `green`, `emerald`, `teal`, `cyan`, `sky`, `blue`, `indigo`, `violet`, `purple`, `fuchsia`, `pink`, `rose`

```php
['color' => 'emerald', 'title' => 'Launched', ...]
```

## Animation

Events fade in on scroll by default. Disable with:

```blade
<flux:timeline :events="$events" :animated="false" />
```

## Slot Content

Add custom content above the timeline using the default slot:

```blade
<flux:timeline :events="$events" heading="Roadmap">
    <p class="text-sm text-zinc-500">Dates are approximate.</p>
</flux:timeline>
```

## Minimal Example

Just dates and titles:

```php
$events = [
    ['date' => '2020', 'title' => 'Founded'],
    ['date' => '2021', 'title' => 'First Customer'],
    ['date' => '2022', 'title' => 'Profitability'],
];
```

```blade
<flux:timeline :events="$events" />
```

## Props Reference

| Prop | Type | Default | Description |
|------|------|---------|-------------|
| `events` | array | `[]` | Array of event objects |
| `variant` | string | `'stacked'` | `'stacked'` or `'alternating'` |
| `heading` | string | `null` | Optional heading text |
| `description` | string | `null` | Optional description below heading |
| `animated` | bool | `true` | Enable scroll-reveal animation |

## Event Properties

| Property | Type | Required | Description |
|----------|------|----------|-------------|
| `title` | string | Yes | Event heading |
| `date` | string | No | Free-form date display |
| `description` | string | No | Body text (supports HTML) |
| `icon` | string | No | Heroicon name for the dot |
| `emoji` | string | No | Emoji character for the dot |
| `color` | string | No | Accent color for the dot |

## Dark Mode

Dark mode works automatically via Tailwind `dark:` classes. No configuration needed.

## Examples

See the [Timeline Examples demo](../demos/timeline-examples/) for complete working examples.
