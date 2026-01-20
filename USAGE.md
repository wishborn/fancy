# Fancy Flux Usage Guide

Comprehensive usage instructions for all Fancy Flux components with tested examples.

## Components

- **[Carousel](docs/carousel.md)** - Flexible carousel/slideshow with multiple variants
- **[Color Picker](docs/color-picker.md)** - Native color input with enhanced UI
- **[Emoji Select](docs/emoji-select.md)** - Composable emoji picker with category navigation

## Quick Links

### Carousel
- [Data-Driven Carousel](docs/carousel.md#data-driven-carousel)
- [Wizard Variant](docs/carousel.md#wizard-variant)
- [Nested Carousels](docs/carousel.md#nested-carousels)
- [Programmatic Navigation](docs/carousel.md#programmatic-navigation)

### Color Picker
- [Basic Usage](docs/color-picker.md#basic-usage)
- [Size Variants](docs/color-picker.md#size-variants)
- [Custom Presets](docs/color-picker.md#custom-presets)

### Emoji Select
- [Basic Usage](docs/emoji-select.md#basic-usage)
- [Size Variants](docs/emoji-select.md#size-variants)
- [In Form Groups](docs/emoji-select.md#in-form-groups)

## Demos

Ready-to-use examples are available in the [demos folder](demos/):

- [Basic Carousel](demos/basic-carousel/)
- [Wizard Form](demos/wizard-form/)
- [Nested Carousel](demos/nested-carousel/)
- [Dynamic Carousel](demos/dynamic-carousel/)
- [Color Picker Examples](demos/color-picker-examples/)
- [Emoji Select Examples](demos/emoji-select-examples/)

## Multiple Components on One Page

When using multiple carousels on the same page, always provide unique `name` props:

```blade
<flux:carousel name="carousel-1" :data="$slides1" />
<flux:carousel name="carousel-2" :data="$slides2" />
```

If you're listening to carousel events in Livewire, filter by carousel ID:

```php
#[On('carousel-navigated')]
public function onCarouselNavigated(string $id, string $name): void
{
    // Only respond to specific carousel
    if ($id === 'my-carousel') {
        $this->currentStep = $name;
    }
}
```
