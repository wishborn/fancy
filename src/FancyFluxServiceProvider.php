<?php

namespace FancyFlux;

use Illuminate\Support\ServiceProvider;
use Illuminate\Support\Facades\Blade;
use FancyFlux\Concerns\InteractsWithCarousel;
use Livewire\Component;

class FancyFluxServiceProvider extends ServiceProvider
{
    use InteractsWithCarousel;

    /**
     * Register any application services.
     */
    public function register(): void
    {
        $this->mergeConfigFrom(
            __DIR__.'/../config/fancy-flux.php',
            'fancy-flux'
        );
    }

    /**
     * Bootstrap any application services.
     */
    public function boot(): void
    {
        $this->bootComponentPath();
        $this->bootCarousel();
        $this->publishAssets();
        $this->publishConfig();
    }

    /**
     * Register the component path for fancy-flux components.
     * 
     * Components can be registered with a custom prefix to avoid conflicts
     * with official Flux components or other custom components.
     * 
     * Configuration:
     *   - FANCY_FLUX_PREFIX: Custom prefix (e.g., 'fancy' for <fancy:carousel>)
     *   - FANCY_FLUX_USE_FLUX_NAMESPACE: Also register in 'flux' namespace (default: true)
     * 
     * Examples:
     *   - No prefix: <flux:carousel> (default)
     *   - Prefix 'fancy': <fancy:carousel> (and optionally <flux:carousel>)
     */
    protected function bootComponentPath(): void
    {
        $prefix = config('fancy-flux.prefix');
        $useFluxNamespace = config('fancy-flux.use_flux_namespace', true);
        $componentPath = __DIR__.'/../stubs/resources/views/flux';

        // Register components with custom prefix if set
        if ($prefix) {
            Blade::anonymousComponentPath($componentPath, $prefix);
        }

        // Register components in 'flux' namespace (for backward compatibility or default)
        if ($useFluxNamespace || !$prefix) {
            Blade::anonymousComponentPath($componentPath, 'flux');
        }
    }

    /**
     * Publish JavaScript assets.
     */
    protected function publishAssets(): void
    {
        if ($this->app->runningInConsole()) {
            $this->publishes([
                __DIR__.'/../resources/js' => public_path('vendor/fancy-flux/js'),
            ], 'fancy-flux-assets');
        }
    }

    /**
     * Publish configuration file.
     */
    protected function publishConfig(): void
    {
        if ($this->app->runningInConsole()) {
            $this->publishes([
                __DIR__.'/../config/fancy-flux.php' => config_path('fancy-flux.php'),
            ], 'fancy-flux-config');
        }
    }
}
