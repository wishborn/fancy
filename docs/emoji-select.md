# Emoji Select Component

A composable emoji picker component with category navigation, search, and customizable styling.

## Quick Start

```blade
<flux:emoji-select wire:model.live="selectedEmoji" />
```

## Basic Usage

Simple emoji picker with search and category navigation:

```blade
<flux:emoji-select wire:model.live="basicEmoji" />
```

**Livewire Component:**

```php
class MyComponent extends Component
{
    public string $basicEmoji = '';
}
```

## With Label

Emoji picker with an integrated label:

```blade
<flux:emoji-select 
    wire:model.live="reactionEmoji" 
    label="Reaction" 
    placeholder="Choose reaction..." 
/>
```

## Size Variants

Three size options: `sm`, default (`md`), `lg`, and `xl`:

```blade
<flux:emoji-select wire:model.live="smallEmoji" size="sm" />
<flux:emoji-select wire:model.live="defaultEmoji" />
<flux:emoji-select wire:model.live="largeEmoji" size="lg" />
<flux:emoji-select wire:model.live="xlargeEmoji" size="xl" />
```

## Style Variants

Two style variants: `outline` (default) and `filled`:

```blade
<flux:emoji-select wire:model.live="outlineEmoji" variant="outline" />
<flux:emoji-select wire:model.live="filledEmoji" variant="filled" />
```

## Preselected Value

Set an initial emoji value:

```blade
<flux:emoji-select wire:model.live="preselectedEmoji" label="Party Emoji" />
```

**Livewire Component:**

```php
class MyComponent extends Component
{
    public string $preselectedEmoji = '🎉';
}
```

## In Form Groups

Use with Flux form groups for integrated styling:

```blade
<flux:field>
    <flux:label>Post Reaction</flux:label>
    <flux:input.group>
        <flux:emoji-select wire:model.live="reactionEmoji" label="Post Reaction" />
        <flux:input placeholder="Add a comment..." />
    </flux:input.group>
</flux:field>
```

## Custom Placeholder

Customize the placeholder text:

```blade
<flux:emoji-select 
    wire:model.live="emoji" 
    placeholder="Search for an emoji..." 
/>
```

## Disable Search

Disable the search functionality:

```blade
<flux:emoji-select wire:model.live="emoji" :searchable="false" />
```

## Square Variant

Use square button-like styling (useful for form groups):

```blade
<flux:emoji-select wire:model.live="emoji" square />
```

## Popover Positioning

Control where the emoji picker popover appears:

```blade
{{-- Position: bottom (default), top --}}
<flux:emoji-select wire:model.live="emoji" position="top" />

{{-- Alignment: start (default), center, end --}}
<flux:emoji-select wire:model.live="emoji" align="end" />
```

## Custom Categories

Provide custom emoji categories:

```blade
@php
use FancyFlux\EmojiData;

$customCategories = [
    'smileys' => [
        'icon' => '😀',
        'label' => 'Smileys',
        'emojis' => [
            ['char' => '😀', 'name' => 'grinning'],
            ['char' => '😃', 'name' => 'smiley'],
        ],
    ],
];
@endphp

<flux:emoji-select wire:model.live="emoji" :categories="$customCategories" />
```

## Props Reference

| Prop | Type | Default | Description |
|------|------|---------|-------------|
| `label` | string | `null` | Label text above the picker |
| `size` | string | `'md'` | `'sm'`, `'md'`, `'lg'`, or `'xl'` |
| `variant` | string | `'outline'` | `'outline'` or `'filled'` |
| `placeholder` | string | `'Select emoji...'` | Placeholder text |
| `searchable` | bool | `true` | Enable search functionality |
| `square` | bool | `false` | Use square button-like styling |
| `position` | string | `'bottom'` | Popover position: `'bottom'` or `'top'` |
| `align` | string | `'start'` | Popover alignment: `'start'`, `'center'`, or `'end'` |
| `categories` | array | default | Custom emoji categories |
| `wire:model` | string | - | Livewire model binding |

## Examples

See the [Emoji Select Examples demo](../demos/emoji-select-examples/) for complete working examples.
