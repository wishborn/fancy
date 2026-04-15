{{-- Fancy Flux Demo Hub --}}
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Fancy Flux Demos</title>
    @vite(['resources/css/app.css'])
</head>
<body class="bg-zinc-50 dark:bg-zinc-900 text-zinc-800 dark:text-zinc-100">
    <div class="max-w-3xl mx-auto p-8">
        <h1 class="text-3xl font-bold mb-2">Fancy Flux Demos</h1>
        <p class="text-zinc-500 mb-8">Self-contained component demos shipped with the fancy-flux package.</p>
        <ul class="space-y-2">
            @foreach ([
                'action-examples' => 'Action — button states, sizes, icons, emoji, chat simulation',
                'basic-carousel' => 'Carousel — basic usage',
                'color-picker-examples' => 'Color Picker — palette, swatches, custom colors',
                'drawer-examples' => 'Drawer — slide-over panels',
                'dynamic-carousel' => 'Carousel — dynamic slides',
                'emoji-select-examples' => 'Emoji Select — picker with skin tones',
                'nested-carousel' => 'Carousel — nested',
                'timeline-examples' => 'Timeline — vertical, horizontal',
                'wizard-form' => 'Wizard — multi-step form',
            ] as $slug => $label)
                <li>
                    <a href="{{ route('fancy-flux-demos.' . $slug) }}" class="block rounded-lg border border-zinc-200 dark:border-zinc-700 p-4 hover:bg-white dark:hover:bg-zinc-800">
                        <span class="font-semibold">{{ $slug }}</span>
                        <span class="block text-sm text-zinc-500">{{ $label }}</span>
                    </a>
                </li>
            @endforeach
        </ul>
    </div>
</body>
</html>
