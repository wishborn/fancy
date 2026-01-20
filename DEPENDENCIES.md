# Flux Component Dependencies

This document provides a comprehensive review of component dependencies between Fancy Flux custom components and Flux UI Free vs Flux Pro components.

## Flux Component Inventory

### Flux Free Components (24 components)

The following components are available in the free edition of Flux UI:

- `avatar` - User avatar display
- `badge` - Badge/label component
- `brand` - Brand logo component
- `breadcrumbs` - Navigation breadcrumbs
- `button` - Button component
- `callout` - Callout/alert component
- `checkbox` - Checkbox input
- `dropdown` - Dropdown menu
- `field` - Form field wrapper
- `heading` - Heading text component
- `icon` - Icon component (with variants)
- `input` - Text input component
- `modal` - Modal dialog
- `navbar` - Navigation bar
- `otp-input` - OTP code input
- `profile` - User profile component
- `radio` - Radio button input
- `select` - Select dropdown
- `separator` - Visual separator
- `skeleton` - Loading skeleton
- `switch` - Toggle switch
- `text` - Text component
- `textarea` - Textarea input
- `tooltip` - Tooltip component

### Flux Pro Components (20 additional components)

Flux Pro includes all Free components plus the following Pro-only components:

- `accordion` - Accordion/collapsible component
- `autocomplete` - Autocomplete input
- `calendar` - Calendar date picker
- `card` - Card container component
- `chart` - Chart/graph component
- `command` - Command palette
- `composer` - Code editor component
- `context` - Context menu
- `date-picker` - Date picker input
- `editor` - Rich text editor
- `file-upload` - File upload component
- `kanban` - Kanban board component
- `pagination` - Pagination component
- `pillbox` - Pillbox input
- `popover` - Popover component
- `slider` - Slider input
- `table` - Data table component
- `tabs` - Tabs component
- `time-picker` - Time picker input
- `toast` - Toast notification

**Note:** Flux Pro includes all Free components, so Pro users have access to all 44 components total.

## Custom Component Dependencies

### Carousel Component

**Files:**
- `stubs/resources/views/flux/carousel/index.blade.php`
- `stubs/resources/views/flux/carousel/controls.blade.php`
- `stubs/resources/views/flux/carousel/panels.blade.php`
- `stubs/resources/views/flux/carousel/steps.blade.php`
- `stubs/resources/views/flux/carousel/step/index.blade.php`
- `stubs/resources/views/flux/carousel/step/item.blade.php`

**Flux Components Used:**
- `flux:icon.chevron-left` (Free) - Used in controls for previous button
- `flux:icon.chevron-right` (Free) - Used in controls for next button
- `flux:icon` (Free) - Generic icon component used in step indicators (optional icon prop)

**Dependency Status:** ✅ **Free Only** - Carousel works with Flux Free

**Notes:**
- Uses `Flux::classes()` helper (available in both Free and Pro)
- No Pro-specific dependencies
- All icon variants used (`micro`, `mini`) are available in Free

---

### Color Picker Component

**Files:**
- `stubs/resources/views/flux/color-picker.blade.php`

**Flux Components Used:**
- `flux:label` (Free) - Optional label component

**Dependency Status:** ✅ **Free Only** - Color Picker works with Flux Free

**Notes:**
- Uses `Flux::classes()` helper (available in both Free and Pro)
- Label is optional - component works without it
- No Pro-specific dependencies

---

### Emoji Select Component

**Files:**
- `stubs/resources/views/flux/emoji-select/index.blade.php`

**Flux Components Used:**
- `flux:icon.magnifying-glass` (Free) - Search icon in search input
- `flux:icon.x-mark` (Free) - Clear search button icon
- `flux:icon.face-frown` (Free) - Empty state icon
- `flux:icon.chevron-down` (Free) - Dropdown indicator icon
- `flux:label` (Free) - Optional label component

**Dependency Status:** ✅ **Free Only** - Emoji Select works with Flux Free

**Notes:**
- Uses `Flux::classes()` helper (available in both Free and Pro)
- All icon variants used (`micro`, `mini`) are available in Free
- Label is optional - component works without it
- Uses `ui-dropdown` component (Alpine.js component, not Flux)

---

## Supporting Files Analysis

### PHP Files

**`src/Concerns/InteractsWithCarousel.php`**
- **Flux Dependencies:** None
- Uses Livewire Component macros and JavaScript dispatch events
- No Flux component dependencies

**`src/FancyFluxServiceProvider.php`**
- **Flux Dependencies:** None
- Standard Laravel service provider
- No Flux component dependencies

**`src/EmojiData.php`**
- **Flux Dependencies:** None
- Static data provider for emoji categories
- No Flux component dependencies

### JavaScript Files

**`resources/js/carousel.js`**
- **Flux Dependencies:** None
- Extends `window.Flux` object with carousel helper
- Uses Alpine.js and DOM APIs
- No Flux component dependencies

---

## Demo Files Analysis

Demo files use additional Flux components for presentation purposes, but these are **not dependencies** of the custom components themselves:

**Components Used in Demos:**
- `flux:heading` (Free) - Page headings
- `flux:text` (Free) - Text content
- `flux:card` (Pro) - Card containers in demo pages
- `flux:button` (Free) - Action buttons
- `flux:input` (Free) - Form inputs in wizard demos
- `flux:textarea` (Free) - Textarea inputs in wizard demos
- `flux:modal` (Free) - Success modal in wizard demo
- `flux:icon.check` (Free) - Success icon

**Note:** Demo files are for reference only and are not part of the package distribution. The `flux:card` component used in demos is Pro-only, but this does not affect the custom components themselves.

---

## Dependency Matrix

| Custom Component | Flux Components Used | Free/Pro Status | Compatibility |
|-----------------|---------------------|-----------------|---------------|
| **Carousel** | `flux:icon.chevron-left`<br>`flux:icon.chevron-right`<br>`flux:icon` (generic) | All Free | ✅ Free Compatible |
| **Color Picker** | `flux:label` | Free | ✅ Free Compatible |
| **Emoji Select** | `flux:icon.magnifying-glass`<br>`flux:icon.x-mark`<br>`flux:icon.face-frown`<br>`flux:icon.chevron-down`<br>`flux:label` | All Free | ✅ Free Compatible |

## Compatibility Summary

### ✅ All Custom Components Are Free-Compatible

All three custom components (`carousel`, `color-picker`, `emoji-select`) use **only Flux Free components**. This means:

1. **No Pro Dependencies:** None of the custom components require Flux Pro
2. **Free Edition Compatible:** All components work with `livewire/flux` (Free) only
3. **Pro Enhancement Ready:** Components will automatically benefit from Pro features if Pro is installed, but don't require it

### Composer Requirements

The package's `composer.json` correctly specifies:
```json
{
  "require": {
    "livewire/flux": "^2.0"
  }
}
```

This is correct - the package only requires Flux Free, not Flux Pro.

## Recommendations

### ✅ Current State: Excellent

1. **Maintain Free Compatibility:** Continue using only Free components in custom components
2. **Optional Pro Features:** If future enhancements could benefit from Pro components, make them optional/conditional
3. **Documentation:** Keep this dependency document updated when adding new components or dependencies

### Future Considerations

If you want to add Pro-specific features in the future:

1. **Conditional Rendering:** Check for Pro component availability before using
2. **Feature Flags:** Allow users to opt-in to Pro features
3. **Graceful Degradation:** Provide Free alternatives when Pro components aren't available

Example pattern for optional Pro features:
```blade
@if (component_exists('flux.card'))
    <flux:card>
        {{-- Pro-enhanced content --}}
    </flux:card>
@else
    <div class="border rounded-lg p-6">
        {{-- Free fallback --}}
    </div>
@endif
```

## Testing Recommendations

1. **Test with Flux Free Only:** Verify all components work with only `livewire/flux` installed
2. **Test with Flux Pro:** Verify components still work correctly when Pro is installed
3. **Icon Variant Testing:** Ensure all icon variants (`micro`, `mini`) work correctly
4. **Optional Component Testing:** Test components with and without optional labels

---

**Last Updated:** 2026-01-20  
**Package Version:** 1.0.7  
**Flux Version Tested:** 2.0+
