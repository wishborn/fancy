# Dynamic Carousel Demo

Add or remove slides dynamically without resetting carousel position.

## Files

- `dynamic-carousel.php` → Copy to `app/Livewire/DynamicCarousel.php`
- `dynamic-carousel.blade.php` → Copy to `resources/views/livewire/dynamic-carousel.blade.php`

## Usage

```blade
<livewire:dynamic-carousel />
```

## Features

- Append/prepend slides dynamically
- Remove first/last slides
- Carousel position maintained during changes
- Uses `refresh()` method to update step collection
- Livewire wire:model integration
