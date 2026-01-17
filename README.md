# Fancy Flux

Custom Flux UI components for Laravel Livewire applications.
[![Guided by Tynn](https://img.shields.io/endpoint?url=https%3A%2F%2Ftynn.ai%2Fu%2Fwishborn%2Fflux-dev%2Fbadge.json)](https://tynn.ai/u/wishborn/flux-dev)
[![Latest Version](https://img.shields.io/github/v/release/wishborn/fancy-flux?style=flat-square)](https://github.com/wishborn/fancy-flux/releases)
[![License](https://img.shields.io/badge/license-MIT-brightgreen.svg?style=flat-square)](LICENSE)

## Components

### Carousel

A flexible carousel/slideshow component with multiple variants:

- **Directional**: Navigation with prev/next arrows, supports autoplay
- **Wizard**: Step-based navigation with numbered indicators
- **Thumbnail**: Image-based navigation with preview thumbnails

### Color Picker

A native color input component with swatch preview and preset support.

## Installation

```bash
composer require wishborn/fancy-flux
```

## Usage

### Carousel

```blade
<flux:carousel :data="$slides" autoplay />
```

See the demo app for more examples.

### Color Picker

```blade
<flux:color-picker wire:model="color" />
```

## Documentation

For comprehensive usage instructions, examples, and API reference, see the [Usage Guide](USAGE.md).

## Demos

Ready-to-use examples are available in the `/demos` folder. Copy the demo files into your Laravel application to get started quickly:

- **Basic Carousel** - Simple data-driven carousel
- **Wizard Form** - Multi-step form with validation
- **Nested Carousel** - Nested carousels with parent advancement
- **Dynamic Carousel** - Add/remove slides dynamically
- **Color Picker Examples** - All color picker variants

See the [demos README](../../demos/README.md) for details.

## Requirements

- PHP 8.2+
- Laravel 10+ / 11+ / 12+
- Livewire 3.7+ / 4.0+
- Flux UI 3.0+
