# Component Prefix Configuration

Fancy Flux supports configurable component prefixes to avoid naming conflicts with official Flux components or other custom component packages.

## Why Use a Prefix?

- **Avoid Conflicts**: If Flux releases an official `carousel` component, your prefixed version won't conflict
- **Multiple Packages**: If you use multiple custom Flux component packages, prefixes prevent naming collisions
- **Clear Ownership**: Makes it clear which components are from Fancy Flux vs official Flux

## Configuration

### Step 1: Publish the Config File

```bash
php artisan vendor:publish --tag=fancy-flux-config
```

This creates `config/fancy-flux.php` in your application.

### Step 2: Configure in `.env`

```env
# Custom prefix for Fancy Flux components (e.g., 'fancy', 'custom', 'myapp')
FANCY_FLUX_PREFIX=fancy

# When true, components are also available in 'flux' namespace (default: true)
# Set to false to use ONLY the prefixed namespace
FANCY_FLUX_USE_FLUX_NAMESPACE=true
```

## Usage Examples

### Default (No Prefix)

When `FANCY_FLUX_PREFIX` is not set or empty:

```blade
<!-- Components available as: -->
<flux:carousel :data="$slides" />
<flux:color-picker wire:model="color" />
<flux:emoji-select wire:model="emoji" />
```

### With Custom Prefix

When `FANCY_FLUX_PREFIX=fancy` and `FANCY_FLUX_USE_FLUX_NAMESPACE=true`:

```blade
<!-- Components available with prefix: -->
<fancy:carousel :data="$slides" />
<fancy:color-picker wire:model="color" />
<fancy:emoji-select wire:model="emoji" />

<!-- Also available in flux namespace (backward compatibility): -->
<flux:carousel :data="$slides" />
<flux:color-picker wire:model="color" />
<flux:emoji-select wire:model="emoji" />
```

### Prefix Only (No Flux Namespace)

When `FANCY_FLUX_PREFIX=fancy` and `FANCY_FLUX_USE_FLUX_NAMESPACE=false`:

```blade
<!-- Components ONLY available with prefix: -->
<fancy:carousel :data="$slides" />
<fancy:color-picker wire:model="color" />
<fancy:emoji-select wire:model="emoji" />

<!-- flux: namespace will NOT work (prevents conflicts) -->
```

## Configuration Options

### `prefix`

- **Type**: `string|null`
- **Default**: `null`
- **Description**: Custom prefix for Fancy Flux components
- **Examples**: `'fancy'`, `'custom'`, `'myapp'`

### `use_flux_namespace`

- **Type**: `boolean`
- **Default**: `true`
- **Description**: When `true`, components are also registered in the `flux` namespace for backward compatibility. When `false`, components are ONLY available with the custom prefix.

## Migration Guide

### Migrating to a Prefix

If you want to start using a prefix in an existing project:

1. **Set the prefix** in `.env`:
   ```env
   FANCY_FLUX_PREFIX=fancy
   FANCY_FLUX_USE_FLUX_NAMESPACE=true
   ```

2. **Gradually update templates**: Components will still work with `flux:` namespace, but you can gradually migrate to the prefixed version:
   ```blade
   <!-- Old -->
   <flux:carousel :data="$slides" />
   
   <!-- New -->
   <fancy:carousel :data="$slides" />
   ```

3. **Eventually disable flux namespace** (optional):
   ```env
   FANCY_FLUX_USE_FLUX_NAMESPACE=false
   ```

### Removing a Prefix

To remove a prefix and return to default behavior:

1. Remove or set `FANCY_FLUX_PREFIX` to empty/null:
   ```env
   FANCY_FLUX_PREFIX=
   ```

2. Components will be available as `<flux:component-name>` again

## Best Practices

1. **Use a prefix from the start** if you're concerned about future conflicts
2. **Keep `use_flux_namespace=true`** during development for flexibility
3. **Set `use_flux_namespace=false`** in production if you want strict namespace separation
4. **Use descriptive prefixes** that match your project/company name (e.g., `fancy`, `acme`, `myapp`)

## Troubleshooting

### Components Not Found

If components aren't working after setting a prefix:

1. **Clear config cache**:
   ```bash
   php artisan config:clear
   ```

2. **Verify config**:
   ```bash
   php artisan tinker
   >>> config('fancy-flux.prefix')
   >>> config('fancy-flux.use_flux_namespace')
   ```

3. **Check namespace**: Make sure you're using the correct namespace in your Blade templates

### Both Namespaces Work When They Shouldn't

If `use_flux_namespace=false` but both namespaces still work:

1. **Clear all caches**:
   ```bash
   php artisan optimize:clear
   ```

2. **Restart your development server** if using `php artisan serve`

## See Also

- [README](../README.md) - Main package documentation
- [Component Documentation](carousel.md) - Detailed component guides
