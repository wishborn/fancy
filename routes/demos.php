<?php

use Illuminate\Support\Facades\Route;

/*
|--------------------------------------------------------------------------
| Fancy Flux Demo Routes
|--------------------------------------------------------------------------
|
| Self-contained demo routes. All Livewire classes ship with the package
| under the FancyFlux\Demos namespace — consumers do not need to copy any
| files into their app. Enable these routes by setting
| FANCY_FLUX_ENABLE_DEMO_ROUTES=true or config('fancy-flux.enable_demo_routes', true).
|
| When enabled, demos are reachable at:
|   /fancy-flux-demos
|   /fancy-flux-demos/action-examples
|   /fancy-flux-demos/basic-carousel
|   /fancy-flux-demos/color-picker-examples
|   /fancy-flux-demos/drawer-examples
|   /fancy-flux-demos/dynamic-carousel
|   /fancy-flux-demos/emoji-select-examples
|   /fancy-flux-demos/nested-carousel
|   /fancy-flux-demos/timeline-examples
|   /fancy-flux-demos/wizard-form
|
*/

Route::prefix('fancy-flux-demos')->name('fancy-flux-demos.')->group(function () {
    Route::get('/', function () {
        return view('fancy-flux-demos::index');
    })->name('index');

    Route::get('/action-examples',       \FancyFlux\Demos\ActionExamples::class)->name('action-examples');
    Route::get('/basic-carousel',        \FancyFlux\Demos\BasicCarousel::class)->name('basic-carousel');
    Route::get('/color-picker-examples', \FancyFlux\Demos\ColorPickerExamples::class)->name('color-picker-examples');
    Route::get('/drawer-examples',       \FancyFlux\Demos\DrawerExamples::class)->name('drawer-examples');
    Route::get('/dynamic-carousel',      \FancyFlux\Demos\DynamicCarousel::class)->name('dynamic-carousel');
    Route::get('/emoji-select-examples', \FancyFlux\Demos\EmojiSelectExamples::class)->name('emoji-select-examples');
    Route::get('/nested-carousel',       \FancyFlux\Demos\NestedCarousel::class)->name('nested-carousel');
    Route::get('/timeline-examples',     \FancyFlux\Demos\TimelineExamples::class)->name('timeline-examples');
    Route::get('/wizard-form',           \FancyFlux\Demos\WizardForm::class)->name('wizard-form');
});
