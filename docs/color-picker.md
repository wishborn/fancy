# Color Picker Component

A native color input component with enhanced UI, swatch preview, and preset support.

## Quick Start

```blade
<flux:color-picker label="Primary Color" wire:model="primaryColor" />
```

## Basic Usage

```blade
<flux:color-picker label="Primary Color" wire:model="primaryColor" />
<flux:color-picker label="Secondary Color" wire:model="secondaryColor" />
```

**Livewire Component:**

```php
class MyComponent extends Component
{
    public string $primaryColor = '#3b82f6';
    public string $secondaryColor = '#8b5cf6';
}
```

## Size Variants

Three size options: `sm`, default, and `lg`:

```blade
<flux:color-picker label="Small" size="sm" wire:model="smallColor" />
<flux:color-picker label="Default" wire:model="defaultColor" />
<flux:color-picker label="Large" size="lg" wire:model="largeColor" />
```

## Style Variants

Two style variants: `outline` (default) and `filled`:

```blade
<flux:color-picker label="Outline" variant="outline" wire:model="outlineColor" />
<flux:color-picker label="Filled" variant="filled" wire:model="filledColor" />
```

## Custom Presets

Provide custom preset colors for quick selection:

```blade
<flux:color-picker 
    label="Brand Colors" 
    wire:model="brandColor"
    :presets="['3b82f6', '8b5cf6', 'ec4899', 'ef4444', 'f59e0b', '10b981']"
/>
```

**Note:** Preset colors should be hex values without the `#` prefix.

## Without Label

Use standalone without a label:

```blade
<flux:color-picker wire:model="standaloneColor" />
```

## Props Reference

| Prop | Type | Default | Description |
|------|------|---------|-------------|
| `label` | string | `null` | Label text above the picker |
| `size` | string | `null` | `'sm'`, default, or `'lg'` |
| `variant` | string | `'outline'` | `'outline'` or `'filled'` |
| `presets` | array | default colors | Custom preset hex colors |
| `wire:model` | string | - | Livewire model binding |

## Examples

See the [Color Picker Examples demo](../demos/color-picker-examples/) for complete working examples.
