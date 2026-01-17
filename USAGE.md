# Fancy Flux Usage Guide

Comprehensive usage instructions for all Fancy Flux components with tested examples.

## Table of Contents

- [Carousel Component](#carousel-component)
  - [Data-Driven Carousel](#data-driven-carousel)
  - [Slot-Based Carousel](#slot-based-carousel)
  - [Wizard Variant](#wizard-variant)
  - [Thumbnail Variant](#thumbnail-variant)
  - [Custom Content](#custom-content)
  - [Dynamic Slides](#dynamic-slides)
  - [Headless Wizard Mode](#headless-wizard-mode)
  - [Programmatic Navigation](#programmatic-navigation)
  - [Nested Carousels](#nested-carousels)
- [Color Picker Component](#color-picker-component)
  - [Basic Usage](#basic-usage)
  - [Size Variants](#size-variants)
  - [Style Variants](#style-variants)
  - [Custom Presets](#custom-presets)

---

## Carousel Component

The carousel component supports multiple variants and usage patterns.

### Data-Driven Carousel

The simplest way to create a carousel - pass an array of slides via the `data` prop:

```blade
@php
$slides = [
    [
        'name' => 'mountains',
        'label' => 'Explore Nature',
        'description' => 'Discover breathtaking mountain views.',
        'src' => '/images/mountains.jpg'
    ],
    [
        'name' => 'city',
        'label' => 'Urban Adventure',
        'description' => 'Experience the vibrant city life.',
        'src' => '/images/city.jpg'
    ],
    [
        'name' => 'beach',
        'label' => 'Beach Paradise',
        'description' => 'Relax on pristine sandy shores.',
        'src' => '/images/beach.jpg'
    ],
];
@endphp

{{-- One line! All panels, controls, and steps auto-generated --}}
<flux:carousel :data="$slides" autoplay />
```

**Slide Data Structure:**

| Key | Required | Description |
|-----|----------|-------------|
| `name` | Yes | Unique identifier for the slide |
| `label` | No | Display title for the slide |
| `description` | No | Subtitle or description text |
| `src` | No | Image URL for image-based slides |
| `thumbnail` | No | Separate thumbnail URL (defaults to `src`) |

### Slot-Based Carousel

For full control over content, use the slot-based approach:

```blade
<flux:carousel>
    <flux:carousel.panels>
        <flux:carousel.step.item name="first" label="First Slide" />
        <flux:carousel.step.item name="second" label="Second Slide" />
        <flux:carousel.step.item name="third" label="Third Slide" />
    </flux:carousel.panels>
    
    <flux:carousel.controls />
    
    <flux:carousel.steps>
        <flux:carousel.step name="first" />
        <flux:carousel.step name="second" />
        <flux:carousel.step name="third" />
    </flux:carousel.steps>
</flux:carousel>
```

### Wizard Variant

Create step-by-step wizards with numbered indicators:

```blade
@php
$steps = [
    ['name' => 'account', 'label' => 'Account'],
    ['name' => 'profile', 'label' => 'Profile'],
    ['name' => 'review', 'label' => 'Review'],
];
@endphp

<flux:carousel :data="$steps" variant="wizard" :loop="false" />
```

#### Wizard with Form Submission

Use `wire:submit` to handle form submission when the user clicks "Finish":

**Shorthand Syntax:**

```blade
<flux:carousel 
    :data="$steps" 
    variant="wizard" 
    :loop="false" 
    wire:submit="submitWizard" 
/>
```

**Slot-Based Syntax with Custom Content:**

```blade
<flux:carousel variant="wizard" :loop="false" name="wizard-demo">
    <flux:carousel.steps>
        <flux:carousel.step name="account" label="Account" />
        <flux:carousel.step name="profile" label="Profile" />
        <flux:carousel.step name="review" label="Review" />
    </flux:carousel.steps>
    
    <flux:carousel.panels>
        <flux:carousel.step.item name="account">
            <div class="p-6">
                <flux:heading size="md">Create Your Account</flux:heading>
                <flux:text class="mt-2 mb-4">Enter your email and password.</flux:text>
                <div class="space-y-4 max-w-sm">
                    <flux:input label="Email" type="email" wire:model.blur="email" />
                    <flux:input label="Password" type="password" wire:model.blur="password" />
                </div>
            </div>
        </flux:carousel.step.item>
        
        <flux:carousel.step.item name="profile">
            <div class="p-6">
                <flux:heading size="md">Complete Your Profile</flux:heading>
                <div class="space-y-4 max-w-sm">
                    <flux:input label="Full Name" wire:model.blur="fullName" />
                    <flux:textarea label="Bio" wire:model.blur="bio" />
                </div>
            </div>
        </flux:carousel.step.item>
        
        <flux:carousel.step.item name="review">
            <div class="p-6">
                <flux:heading size="md">Review & Confirm</flux:heading>
                {{-- Display collected data --}}
            </div>
        </flux:carousel.step.item>
    </flux:carousel.panels>
    
    {{-- wire:submit calls submitWizard() when Complete is clicked --}}
    <flux:carousel.controls finishLabel="Complete" wire:submit="submitWizard" />
</flux:carousel>
```

**Livewire Component:**

```php
class WizardDemo extends Component
{
    public string $email = '';
    public string $password = '';
    public string $fullName = '';
    public string $bio = '';
    public bool $showSuccessModal = false;
    
    public function submitWizard(): void
    {
        $this->validate();
        // Save data...
        $this->showSuccessModal = true;
    }
}
```

### Thumbnail Variant

Shows small preview images for navigation:

```blade
@php
$slides = [
    ['name' => 'forest', 'label' => 'Forest', 'src' => '/images/forest.jpg'],
    ['name' => 'ocean', 'label' => 'Ocean', 'src' => '/images/ocean.jpg'],
    ['name' => 'desert', 'label' => 'Desert', 'src' => '/images/desert.jpg'],
];
@endphp

{{-- Thumbnail variant uses src for main image and thumbnail --}}
{{-- Provide separate 'thumbnail' key for smaller images --}}
<flux:carousel :data="$slides" variant="thumbnail" />
```

### Custom Content

Create slides with custom HTML content:

```blade
<flux:carousel :autoplay="false">
    <flux:carousel.panels>
        <flux:carousel.step.item name="welcome">
            <div class="flex items-center justify-center h-64 bg-gradient-to-br from-blue-500 to-blue-600 rounded-xl text-white">
                <div class="text-center">
                    <flux:heading size="lg" class="text-white!">Welcome</flux:heading>
                    <flux:text class="text-blue-100 mt-2">This is the first slide.</flux:text>
                </div>
            </div>
        </flux:carousel.step.item>
        
        <flux:carousel.step.item name="features">
            <div class="flex items-center justify-center h-64 bg-gradient-to-br from-purple-500 to-purple-600 rounded-xl text-white">
                <div class="text-center">
                    <flux:heading size="lg" class="text-white!">Features</flux:heading>
                    <flux:text class="text-purple-100 mt-2">Discover amazing features.</flux:text>
                </div>
            </div>
        </flux:carousel.step.item>
    </flux:carousel.panels>
    
    <flux:carousel.controls />
    
    <flux:carousel.steps>
        <flux:carousel.step name="welcome" />
        <flux:carousel.step name="features" />
    </flux:carousel.steps>
</flux:carousel>
```

### Dynamic Slides

Add or remove slides dynamically without resetting carousel position:

**Livewire Component:**

```php
class DynamicCarouselDemo extends Component
{
    public array $slides = [
        ['name' => 'slide-1', 'label' => 'Welcome', 'description' => 'First slide', 'color' => 'blue'],
        ['name' => 'slide-2', 'label' => 'Features', 'description' => 'Second slide', 'color' => 'purple'],
    ];
    
    public function appendSlide(): void
    {
        $count = count($this->slides) + 1;
        $this->slides[] = [
            'name' => 'slide-' . $count,
            'label' => 'Slide ' . $count,
            'description' => 'Dynamically added slide',
            'color' => 'green',
        ];
        
        // Refresh the carousel to recognize new slides
        $this->dispatch('carousel-refresh', id: 'dynamic-carousel');
    }
    
    public function removeLastSlide(): void
    {
        if (count($this->slides) > 1) {
            array_pop($this->slides);
            $this->dispatch('carousel-refresh', id: 'dynamic-carousel');
        }
    }
    
    public function prependSlide(): void
    {
        $count = count($this->slides) + 1;
        array_unshift($this->slides, [
            'name' => 'slide-' . $count,
            'label' => 'New First',
            'description' => 'Prepended slide',
            'color' => 'orange',
        ]);
        $this->dispatch('carousel-refresh', id: 'dynamic-carousel');
    }
}
```

**Blade Template:**

```blade
<flux:carousel name="dynamic-carousel" :autoplay="false" :loop="true">
    <flux:carousel.panels>
        @foreach($slides as $slide)
            <flux:carousel.step.item 
                :name="$slide['name']" 
                :label="$slide['label']" 
                wire:key="slide-{{ $slide['name'] }}"
            >
                {{-- Custom slide content --}}
            </flux:carousel.step.item>
        @endforeach
    </flux:carousel.panels>
    
    <flux:carousel.controls position="overlay" />
    
    <flux:carousel.steps>
        @foreach($slides as $slide)
            <flux:carousel.step :name="$slide['name']" wire:key="step-{{ $slide['name'] }}" />
        @endforeach
    </flux:carousel.steps>
</flux:carousel>

<flux:button wire:click="prependSlide">Prepend Slide</flux:button>
<flux:button wire:click="appendSlide">Append Slide</flux:button>
<flux:button wire:click="removeLastSlide">Remove Last</flux:button>
```

### Headless Wizard Mode

For agentic workflows with non-linear navigation, use headless mode:

```blade
{{-- Headless mode hides step indicators for programmatic control --}}
<flux:carousel variant="wizard" headless name="agent-wizard">
    <flux:carousel.panels>
        @foreach ($stepHistory as $index => $stepId)
            <flux:carousel.step.item :name="'step-' . $index . '-' . $stepId">
                {{-- Dynamic step content based on $stepId --}}
            </flux:carousel.step.item>
        @endforeach
    </flux:carousel.panels>
    
    {{-- Hidden steps for Alpine.js to track --}}
    <flux:carousel.steps>
        @foreach ($stepHistory as $index => $stepId)
            <flux:carousel.step :name="'step-' . $index . '-' . $stepId" />
        @endforeach
    </flux:carousel.steps>
</flux:carousel>
```

**Livewire Component for Agentic Workflow:**

```php
use FancyFlux\Traits\HasCarousel;

class AgentWizard extends Component
{
    use HasCarousel;
    
    public array $stepHistory = ['welcome'];
    
    public function goToStep(string $stepId): void
    {
        $this->stepHistory[] = $stepId;
        $stepName = 'step-' . (count($this->stepHistory) - 1) . '-' . $stepId;
        
        // Refresh carousel and navigate to new step
        $this->carousel('agent-wizard')->refreshAndGoTo($stepName);
    }
    
    public function goBack(): void
    {
        if (count($this->stepHistory) > 1) {
            array_pop($this->stepHistory);
            $lastIndex = count($this->stepHistory) - 1;
            $lastStepId = $this->stepHistory[$lastIndex];
            $stepName = 'step-' . $lastIndex . '-' . $lastStepId;
            
            $this->carousel('agent-wizard')->refreshAndGoTo($stepName);
        }
    }
}
```

### Programmatic Navigation

#### From Alpine.js

Use `Flux.carousel('name')` helper for programmatic control:

```blade
<div x-data="{ 
    get carousel() { return Flux.carousel('my-carousel'); }
}">
    <flux:button x-on:click="carousel.prev()" x-bind:disabled="!carousel.canGoPrev()">
        Previous
    </flux:button>
    
    <flux:button x-on:click="carousel.next()" x-bind:disabled="!carousel.canGoNext()">
        Next
    </flux:button>
    
    <flux:button x-on:click="carousel.goTo('specific-slide')">
        Go to Specific Slide
    </flux:button>
    
    {{-- Display current state --}}
    <span x-text="carousel.active"></span>
    <span x-text="(carousel.activeIndex + 1) + '/' + carousel.totalSteps"></span>
</div>
```

#### From Livewire

Use the `HasCarousel` trait:

```php
use FancyFlux\Traits\HasCarousel;

class MyComponent extends Component
{
    use HasCarousel;
    
    public function navigateToStep(string $stepName): void
    {
        $this->carousel('my-carousel')->goTo($stepName);
    }
    
    public function refreshAndNavigate(string $stepName): void
    {
        $this->carousel('my-carousel')->refreshAndGoTo($stepName);
    }
}
```

#### Via JavaScript Events

Dispatch events to control carousels:

```javascript
// Navigate to next slide
window.dispatchEvent(new CustomEvent('carousel-next', { 
    detail: { id: 'my-carousel' } 
}));

// Navigate to previous slide
window.dispatchEvent(new CustomEvent('carousel-prev', { 
    detail: { id: 'my-carousel' } 
}));

// Go to specific slide by name
window.dispatchEvent(new CustomEvent('carousel-goto', { 
    detail: { id: 'my-carousel', name: 'slide-name' } 
}));

// Refresh carousel (after dynamic changes)
window.dispatchEvent(new CustomEvent('carousel-refresh', { 
    detail: { id: 'my-carousel' } 
}));
```

### Nested Carousels

Nested carousels are fully supported and operate independently. Each carousel maintains its own state and controls, ensuring complete isolation between parent and nested instances.

#### Basic Nested Carousel

Nest carousels inside carousel step items using custom content:

```blade
<flux:carousel variant="wizard" :loop="false" name="parent-wizard">
    <flux:carousel.steps>
        <flux:carousel.step name="step1" label="Step 1" />
        <flux:carousel.step name="step2" label="Step 2" />
        <flux:carousel.step name="step3" label="Step 3" />
    </flux:carousel.steps>
    
    <flux:carousel.panels>
        <flux:carousel.step.item name="step1">
            <div class="p-6">Parent step 1 content</div>
        </flux:carousel.step.item>
        
        <flux:carousel.step.item name="step2">
            <div class="p-6">
                <flux:heading size="md">Parent Step 2 - Contains Nested Wizard</flux:heading>
                
                {{-- Nested wizard inside step 2 --}}
                <flux:carousel variant="wizard" :loop="false" name="nested-wizard" parentCarousel="parent-wizard">
                    <flux:carousel.steps>
                        <flux:carousel.step name="nested1" label="Nested 1" />
                        <flux:carousel.step name="nested2" label="Nested 2" />
                    </flux:carousel.steps>
                    
                    <flux:carousel.panels>
                        <flux:carousel.step.item name="nested1">
                            <div class="p-4">First nested step content.</div>
                        </flux:carousel.step.item>
                        
                        <flux:carousel.step.item name="nested2">
                            <div class="p-4">Second nested step content.</div>
                        </flux:carousel.step.item>
                    </flux:carousel.panels>
                    
                    <flux:carousel.controls />
                </flux:carousel>
            </div>
        </flux:carousel.step.item>
        
        <flux:carousel.step.item name="step3">
            <div class="p-6">Parent step 3 content</div>
        </flux:carousel.step.item>
    </flux:carousel.panels>
    
    <flux:carousel.controls />
</flux:carousel>
```

#### Key Behaviors

**Independence:**
- Nested carousels do **NOT** inherit properties from parent carousels
- Each carousel maintains its own independent state
- Controls only affect the carousel they belong to

**Isolation:**
- Parent carousel controls do **NOT** affect nested carousels
- Nested carousel controls do **NOT** affect parent carousel
- Each carousel only collects its own direct step items (not nested steps)

**Parent Advancement (Wizard Variants Only):**
- On the final step of a nested wizard, the Next button can advance the parent wizard
- Use the `parentCarousel` prop to enable this functionality
- The nested carousel's final step Next button will call `parent.next()` automatically

#### Nested Wizard with Parent Advancement

When a nested wizard completes, it can automatically advance the parent wizard:

```blade
{{-- Parent wizard --}}
<flux:carousel variant="wizard" :loop="false" name="parent-wizard">
    {{-- ... parent steps ... --}}
    
    <flux:carousel.panels>
        <flux:carousel.step.item name="step2">
            {{-- Nested wizard with parentCarousel prop --}}
            <flux:carousel 
                variant="wizard" 
                :loop="false" 
                name="nested-wizard" 
                parentCarousel="parent-wizard"
            >
                {{-- ... nested steps ... --}}
                
                {{-- On final step, Next button will advance parent wizard --}}
                <flux:carousel.controls />
            </flux:carousel>
        </flux:carousel.step.item>
    </flux:carousel.panels>
</flux:carousel>
```

**Using wire:submit with Parent Advancement:**

If you need to perform actions before advancing the parent, use `wire:submit`:

```blade
{{-- Nested wizard with wire:submit --}}
<flux:carousel variant="wizard" :loop="false" name="nested-wizard" parentCarousel="parent-wizard">
    {{-- ... nested steps ... --}}
    
    {{-- wire:submit handler can call parent.next() --}}
    <flux:carousel.controls finishLabel="Complete" wire:submit="completeNestedWizard" />
</flux:carousel>
```

**Livewire Component:**

```php
use FancyFlux\Concerns\InteractsWithCarousel;

class WizardDemo extends Component
{
    use InteractsWithCarousel;
    
    public function completeNestedWizard(): void
    {
        // Perform validation, save data, etc.
        $this->validate();
        
        // Advance the parent wizard
        $this->carousel('parent-wizard')->next();
    }
}
```

#### Important Notes

- **Nested carousels work with all variants** (wizard, directional, thumbnail)
- **Parent advancement only works in wizard variants** - other variants don't support `parent.next()`
- **Each carousel must have a unique `name`** when using nested carousels
- **The `parentCarousel` prop** should match the parent carousel's `name` prop
- **Nested carousels are completely independent** - no property inheritance or shared state

---

## Color Picker Component

A native color input with enhanced UI, swatch preview, and preset support.

### Basic Usage

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

### Size Variants

Three size options: `sm`, default, and `lg`:

```blade
<flux:color-picker label="Small" size="sm" wire:model="smallColor" />
<flux:color-picker label="Default" wire:model="defaultColor" />
<flux:color-picker label="Large" size="lg" wire:model="largeColor" />
```

### Style Variants

Two style variants: `outline` (default) and `filled`:

```blade
<flux:color-picker label="Outline" variant="outline" wire:model="outlineColor" />
<flux:color-picker label="Filled" variant="filled" wire:model="filledColor" />
```

### Custom Presets

Provide custom preset colors for quick selection:

```blade
<flux:color-picker 
    label="Brand Colors" 
    wire:model="brandColor"
    :presets="['3b82f6', '8b5cf6', 'ec4899', 'ef4444', 'f59e0b', '10b981']"
/>
```

**Note:** Preset colors should be hex values without the `#` prefix.

### Without Label

Use standalone without a label:

```blade
<flux:color-picker wire:model="standaloneColor" />
```

---

## Component Props Reference

### Carousel Props

| Prop | Type | Default | Description |
|------|------|---------|-------------|
| `data` | array | `[]` | Array of slide data for data-driven mode |
| `variant` | string | `'directional'` | `'directional'`, `'wizard'`, or `'thumbnail'` |
| `name` | string | auto | Unique identifier for programmatic control |
| `autoplay` | bool | `false` | Enable auto-advancing slides |
| `autoplayInterval` | int | `5000` | Milliseconds between slides |
| `loop` | bool | `true` | Loop back to start after last slide |
| `headless` | bool | `false` | Hide step indicators (wizard variant) |
| `wire:submit` | string | `null` | Livewire method to call on wizard finish |
| `parentCarousel` | string | `null` | Parent carousel ID/name (for nested carousels in wizard variants) |

### Color Picker Props

| Prop | Type | Default | Description |
|------|------|---------|-------------|
| `label` | string | `null` | Label text above the picker |
| `size` | string | `null` | `'sm'`, default, or `'lg'` |
| `variant` | string | `'outline'` | `'outline'` or `'filled'` |
| `presets` | array | default colors | Custom preset hex colors |
| `wire:model` | string | - | Livewire model binding |

---

## Multiple Carousels on One Page

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
