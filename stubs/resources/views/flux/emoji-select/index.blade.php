@props([
    'name' => $attributes->whereStartsWith('wire:model')->first(),
    'size' => null,
    'variant' => 'outline',
    'placeholder' => 'Select emoji...',
    'searchable' => true,
    'label' => null,
    'categories' => null,
    'position' => 'bottom',
    'align' => 'start',
    'square' => false,
])

@php
use FancyFlux\EmojiData;
use Illuminate\Support\Js;

$emojiCategories = $categories ?? EmojiData::categories();

// Normalize size to support sm, md, lg, xl (null defaults to md)
$size = $size ?? 'md';

// For 'group' variant, use square button-like styling for input groups
$isGroupVariant = $variant === 'group';
$isSquare = $square || $isGroupVariant;

$triggerClasses = Flux::classes()
    ->add('group/emoji-trigger cursor-pointer')
    ->add('flex items-center justify-center')
    ->add($isSquare ? '' : 'gap-2')
    ->add('bg-white dark:bg-white/10')
    ->add('hover:bg-zinc-50 dark:hover:bg-zinc-600/75')
    ->add(match ($size) {
        'sm' => $isSquare ? 'h-8 w-8 rounded-md' : 'h-8 text-sm rounded-md px-2',
        'lg' => $isSquare ? 'h-12 w-12 rounded-lg' : 'h-12 text-base rounded-lg px-4',
        'xl' => $isSquare ? 'h-14 w-14 rounded-lg' : 'h-14 text-lg rounded-lg px-5',
        default => $isSquare ? 'h-10 w-10 rounded-lg' : 'h-10 text-sm rounded-lg px-3', // md
    })
    ->add(match ($variant) {
        'filled' => 'bg-zinc-100 dark:bg-zinc-800 border border-zinc-200 dark:border-zinc-700',
        'group' => 'border border-zinc-200 hover:border-zinc-200 border-b-zinc-300/80 dark:border-zinc-600 dark:hover:border-zinc-600 shadow-xs',
        default => 'border border-zinc-200 border-b-zinc-300/80 dark:border-white/10 shadow-xs',
    })
    ;

$emojiDisplayClasses = Flux::classes()
    ->add(match ($size) {
        'sm' => 'text-lg',
        'lg' => 'text-2xl',
        'xl' => 'text-3xl',
        default => 'text-xl', // md
    })
    ;

$placeholderClasses = Flux::classes()
    ->add('text-zinc-400 dark:text-zinc-500')
    ->add(match ($size) {
        'sm' => 'text-xs',
        'lg' => 'text-base',
        'xl' => 'text-lg',
        default => 'text-sm', // md
    })
    ;

$popoverClasses = Flux::classes()
    ->add('rounded-lg shadow-lg')
    ->add('border border-zinc-200 dark:border-zinc-600')
    ->add('bg-white dark:bg-zinc-700')
    ->add('overflow-hidden')
    ->add('flex flex-col')
    ->add('not-data-open:hidden')
    ->add(match ($size) {
        'sm' => 'w-64 max-h-72',
        'lg' => 'w-96 max-h-[28rem]',
        'xl' => 'w-[28rem] max-h-[32rem]',
        default => 'w-80 max-h-96', // md
    })
    ;

$searchInputClasses = Flux::classes()
    ->add('w-full bg-transparent border-0 outline-none focus:ring-0 text-zinc-800 dark:text-white placeholder-zinc-400 dark:placeholder-zinc-500')
    ->add(match ($size) {
        'sm' => 'h-8 pl-8 pr-3 text-xs',
        'lg' => 'h-12 pl-10 pr-3 text-base',
        'xl' => 'h-14 pl-11 pr-3 text-lg',
        default => 'h-10 pl-9 pr-3 text-sm', // md
    })
    ;

$searchIconClasses = match ($size) {
    'sm' => 'left-2.5',
    'lg' => 'left-3.5',
    'xl' => 'left-4',
    default => 'left-3', // md
};

$categoryNavClasses = Flux::classes()
    ->add('flex items-center border-b border-zinc-200 dark:border-zinc-600 overflow-x-auto flux-no-scrollbar')
    ->add(match ($size) {
        'sm' => 'gap-0 px-1.5 py-1.5',
        'lg' => 'gap-1 px-3 py-2.5',
        'xl' => 'gap-1.5 px-3 py-3',
        default => 'gap-0.5 px-2 py-2', // md
    })
    ;

$categoryButtonClasses = Flux::classes()
    ->add('flex-shrink-0 flex items-center justify-center rounded transition-colors')
    ->add(match ($size) {
        'sm' => 'w-6 h-6 text-sm',
        'lg' => 'w-10 h-10 text-xl',
        'xl' => 'w-12 h-12 text-2xl',
        default => 'w-8 h-8 text-lg', // md
    })
    ;

$emojiGridClasses = Flux::classes()
    ->add('grid')
    ->add(match ($size) {
        'sm' => 'grid-cols-8 gap-0',
        'lg' => 'grid-cols-8 gap-1',
        'xl' => 'grid-cols-8 gap-1.5',
        default => 'grid-cols-8 gap-0.5', // md
    })
    ;

$emojiButtonClasses = Flux::classes()
    ->add('flex items-center justify-center rounded transition-colors')
    ->add(match ($size) {
        'sm' => 'w-6 h-6 text-base',
        'lg' => 'w-10 h-10 text-2xl',
        'xl' => 'w-12 h-12 text-3xl',
        default => 'w-8 h-8 text-xl', // md
    })
    ;

$labelClasses = match ($size) {
    'sm' => 'px-2 py-1 text-[10px]',
    'lg' => 'px-4 py-2 text-sm',
    'xl' => 'px-4 py-2.5 text-base',
    default => 'px-3 py-1.5 text-xs', // md
};

$alpineData = "{
    search: '',
    activeCategory: 'smileys',
    categories: " . Js::from($emojiCategories) . ",
    " . ($attributes->whereStartsWith('wire:model')->first()
        ? "selected: \$wire.entangle('" . $attributes->whereStartsWith('wire:model')->first() . "')" . ($attributes->whereStartsWith('wire:model.live')->first() ? '.live' : '') . ","
        : "selected: " . Js::from($attributes->get('value') ?? '') . ",") . "
    get filteredEmojis() {
        if (!this.search.trim()) {
            return this.categories[this.activeCategory]?.emojis || [];
        }
        const searchLower = this.search.toLowerCase();
        let results = [];
        Object.values(this.categories).forEach(cat => {
            cat.emojis.forEach(emoji => {
                if (emoji.name.toLowerCase().includes(searchLower)) {
                    results.push(emoji);
                }
            });
        });
        return results;
    },
    selectEmoji(emoji) {
        this.selected = emoji;
        this.search = '';
        \$refs.dropdown.close();
    },
    onOpen() {
        this.\$nextTick(() => {
            if (\$refs.searchInput) \$refs.searchInput.focus();
        });
    }
}";

$dropdownAttrs = $attributes->except(['wire:model', 'wire:model.live', 'value', 'size', 'variant', 'placeholder', 'searchable', 'label', 'categories', 'position', 'align', 'class', 'square']);
@endphp

@if($isGroupVariant)
{{-- Group variant: ui-dropdown is direct child for input.group CSS to work --}}
<ui-dropdown
    position="{{ $position }} {{ $align }}"
    x-ref="dropdown"
    x-data="{{ $alpineData }}"
    @open="onOpen()"
    {{ $dropdownAttrs }}
    data-flux-emoji-select
>
    {{-- Trigger Button --}}
    <button
        type="button"
        class="{{ $triggerClasses }}"
        data-flux-emoji-trigger
        data-flux-group-target
    >
        <span x-show="selected" x-text="selected" class="{{ $emojiDisplayClasses }}"></span>
        <span x-show="!selected" class="{{ $emojiDisplayClasses }} text-zinc-400">😀</span>
    </button>

    {{-- Emoji Picker Popover --}}
    <div
        popover="manual"
        class="{{ $popoverClasses }}"
        data-flux-emoji-popover
    >
        @if ($searchable)
            {{-- Search Input --}}
            <div class="relative flex items-center border-b border-zinc-200 dark:border-zinc-600">
                <div class="absolute {{ $searchIconClasses }} text-zinc-400">
                    <flux:icon.magnifying-glass variant="micro" />
                </div>
                <input
                    type="text"
                    x-ref="searchInput"
                    x-model="search"
                    placeholder="Search emojis..."
                    class="{{ $searchInputClasses }}"
                />
                <button
                    type="button"
                    x-show="search.length > 0"
                    @click="search = ''; $refs.searchInput.focus()"
                    class="absolute right-2 p-1 text-zinc-400 hover:text-zinc-600 dark:hover:text-zinc-300"
                >
                    <flux:icon.x-mark variant="micro" />
                </button>
            </div>
        @endif

        {{-- Category Navigation --}}
        <div class="{{ $categoryNavClasses }}" x-show="!search.trim()">
            <template x-for="(category, key) in categories" :key="key">
                <button
                    type="button"
                    @click="activeCategory = key"
                    :class="activeCategory === key
                        ? 'bg-zinc-100 dark:bg-zinc-600'
                        : 'hover:bg-zinc-50 dark:hover:bg-zinc-600/50'"
                    class="{{ $categoryButtonClasses }}"
                    :title="category.label"
                    :aria-label="category.label"
                >
                    <span x-text="category.icon"></span>
                </button>
            </template>
        </div>

        {{-- Category Label --}}
        <div class="{{ $labelClasses }} font-medium text-zinc-500 dark:text-zinc-400" x-show="!search.trim()">
            <span x-text="categories[activeCategory]?.label"></span>
        </div>

        {{-- Search Results Label --}}
        <div class="{{ $labelClasses }} font-medium text-zinc-500 dark:text-zinc-400" x-show="search.trim()">
            <span x-text="filteredEmojis.length + ' results'"></span>
        </div>

        {{-- Emoji Grid --}}
        <div class="flex-1 overflow-y-auto p-2" style="scrollbar-width: thin; scrollbar-color: rgb(161 161 170) transparent;">
            <div class="{{ $emojiGridClasses }}">
                <template x-for="emoji in filteredEmojis" :key="emoji.char + emoji.name">
                    <button
                        type="button"
                        @click="selectEmoji(emoji.char)"
                        :class="selected === emoji.char ? 'bg-blue-100 dark:bg-blue-900/50' : 'hover:bg-zinc-100 dark:hover:bg-zinc-600'"
                        class="{{ $emojiButtonClasses }}"
                        :title="emoji.name"
                    >
                        <span x-text="emoji.char"></span>
                    </button>
                </template>
            </div>

            {{-- Empty State --}}
            <div x-show="filteredEmojis.length === 0" class="py-8 text-center text-zinc-400 dark:text-zinc-500">
                <flux:icon.face-frown class="mx-auto mb-2 w-8 h-8" />
                <p class="text-sm">No emojis found</p>
            </div>
        </div>
    </div>

    {{-- Hidden input for form submission --}}
    @if($name)
        <input type="hidden" name="{{ $name }}" :value="selected" />
    @endif
</ui-dropdown>
@else
{{-- Standard variant: wrapper div with x-data --}}
<div
    {{ $attributes->only(['class'])->class('inline-block') }}
    x-data="{{ $alpineData }}"
    data-flux-emoji-select
>
    @if ($label)
        <flux:label class="mb-1.5">{{ $label }}</flux:label>
    @endif

    <ui-dropdown position="{{ $position }} {{ $align }}" x-ref="dropdown" @open="onOpen()" {{ $dropdownAttrs }}>
        {{-- Trigger Button --}}
        <button
            type="button"
            class="{{ $triggerClasses }}"
            data-flux-emoji-trigger
        >
            <span x-show="selected" x-text="selected" class="{{ $emojiDisplayClasses }}"></span>
            @if($isSquare)
                <span x-show="!selected" class="{{ $emojiDisplayClasses }} text-zinc-400">😀</span>
            @else
                <span x-show="!selected" class="{{ $placeholderClasses }}">{{ $placeholder }}</span>
                <flux:icon.chevron-down variant="mini" class="text-zinc-400 group-hover/emoji-trigger:text-zinc-600 dark:group-hover/emoji-trigger:text-zinc-300 transition-colors" />
            @endif
        </button>

        {{-- Emoji Picker Popover --}}
        <div
            popover="manual"
            class="{{ $popoverClasses }}"
            data-flux-emoji-popover
        >
            @if ($searchable)
                {{-- Search Input --}}
                <div class="relative flex items-center border-b border-zinc-200 dark:border-zinc-600">
                    <div class="absolute {{ $searchIconClasses }} text-zinc-400">
                        <flux:icon.magnifying-glass variant="micro" />
                    </div>
                    <input
                        type="text"
                        x-ref="searchInput"
                        x-model="search"
                        placeholder="Search emojis..."
                        class="{{ $searchInputClasses }}"
                    />
                    <button
                        type="button"
                        x-show="search.length > 0"
                        @click="search = ''; $refs.searchInput.focus()"
                        class="absolute right-2 p-1 text-zinc-400 hover:text-zinc-600 dark:hover:text-zinc-300"
                    >
                        <flux:icon.x-mark variant="micro" />
                    </button>
                </div>
            @endif

            {{-- Category Navigation --}}
            <div class="{{ $categoryNavClasses }}" x-show="!search.trim()">
                <template x-for="(category, key) in categories" :key="key">
                    <button
                        type="button"
                        @click="activeCategory = key"
                        :class="activeCategory === key
                            ? 'bg-zinc-100 dark:bg-zinc-600'
                            : 'hover:bg-zinc-50 dark:hover:bg-zinc-600/50'"
                        class="{{ $categoryButtonClasses }}"
                        :title="category.label"
                        :aria-label="category.label"
                    >
                        <span x-text="category.icon"></span>
                    </button>
                </template>
            </div>

            {{-- Category Label --}}
            <div class="{{ $labelClasses }} font-medium text-zinc-500 dark:text-zinc-400" x-show="!search.trim()">
                <span x-text="categories[activeCategory]?.label"></span>
            </div>

            {{-- Search Results Label --}}
            <div class="{{ $labelClasses }} font-medium text-zinc-500 dark:text-zinc-400" x-show="search.trim()">
                <span x-text="filteredEmojis.length + ' results'"></span>
            </div>

            {{-- Emoji Grid --}}
            <div class="flex-1 overflow-y-auto p-2" style="scrollbar-width: thin; scrollbar-color: rgb(161 161 170) transparent;">
                <div class="{{ $emojiGridClasses }}">
                    <template x-for="emoji in filteredEmojis" :key="emoji.char + emoji.name">
                        <button
                            type="button"
                            @click="selectEmoji(emoji.char)"
                            :class="selected === emoji.char ? 'bg-blue-100 dark:bg-blue-900/50' : 'hover:bg-zinc-100 dark:hover:bg-zinc-600'"
                            class="{{ $emojiButtonClasses }}"
                            :title="emoji.name"
                        >
                            <span x-text="emoji.char"></span>
                        </button>
                    </template>
                </div>

                {{-- Empty State --}}
                <div x-show="filteredEmojis.length === 0" class="py-8 text-center text-zinc-400 dark:text-zinc-500">
                    <flux:icon.face-frown class="mx-auto mb-2 w-8 h-8" />
                    <p class="text-sm">No emojis found</p>
                </div>
            </div>
        </div>
    </ui-dropdown>

    {{-- Hidden input for form submission --}}
    @if($name)
        <input type="hidden" name="{{ $name }}" :value="selected" />
    @endif
</div>
@endif
