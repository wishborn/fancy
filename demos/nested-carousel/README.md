# Nested Carousel Demo

Demonstrates nesting carousels inside carousel step items with parent advancement.

## Files

- `nested-carousel.php` → Copy to `app/Livewire/NestedCarousel.php`
- `nested-carousel.blade.php` → Copy to `resources/views/livewire/nested-carousel.blade.php`

## Usage

```blade
<livewire:nested-carousel />
```

## Features

- Parent wizard with 3 steps
- Nested wizard in step 2
- Parent advancement when nested wizard completes
- Complete isolation between parent and nested carousels
- Uses `parentCarousel` prop and `parent.next()` functionality

## Requirements

Requires `FancyFlux\Concerns\InteractsWithCarousel` trait for `parent.next()` functionality.
