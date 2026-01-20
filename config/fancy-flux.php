<?php

return [
    /*
    |--------------------------------------------------------------------------
    | Component Prefix
    |--------------------------------------------------------------------------
    |
    | Set a custom prefix for fancy-flux components to avoid naming conflicts
    | with official Flux components or other custom components.
    |
    | Examples:
    |   - null (default): Components available as <flux:carousel>
    |   - 'fancy': Components available as <fancy:carousel>
    |   - 'custom': Components available as <custom:carousel>
    |
    | When a prefix is set, components will be registered in both namespaces:
    |   - <prefix:component-name> (e.g., <fancy:carousel>)
    |   - <flux:component-name> (if use_flux_namespace is true)
    |
    */
    'prefix' => env('FANCY_FLUX_PREFIX', null),

    /*
    |--------------------------------------------------------------------------
    | Use Flux Namespace
    |--------------------------------------------------------------------------
    |
    | When true, components will also be available in the 'flux' namespace
    | even when a custom prefix is set. This provides backward compatibility
    | but may cause conflicts if Flux releases an official component with
    | the same name.
    |
    | Set to false if you want to use ONLY the prefixed namespace to avoid
    | any potential conflicts with official Flux components.
    |
    */
    'use_flux_namespace' => env('FANCY_FLUX_USE_FLUX_NAMESPACE', true),

    /*
    |--------------------------------------------------------------------------
    | Components
    |--------------------------------------------------------------------------
    |
    | List of components provided by fancy-flux. This is used internally
    | for registration and documentation purposes.
    |
    */
    'components' => [
        'carousel',
        'color-picker',
        'emoji-select',
    ],
];
