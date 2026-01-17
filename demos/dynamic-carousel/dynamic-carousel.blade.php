{{-- Dynamic Carousel Demo View --}}
{{-- Copy this file to resources/views/livewire/dynamic-carousel.blade.php --}}

<div class="max-w-4xl mx-auto p-6">
    <flux:heading size="xl" level="1">Dynamic Carousel</flux:heading>
    <flux:text class="mt-2 mb-6">
        Add or remove slides dynamically without resetting the carousel position.
    </flux:text>
    
    <flux:carousel name="dynamic-carousel" :autoplay="false" :loop="true" class="max-w-2xl mb-6">
        <flux:carousel.panels>
            @foreach($slides as $slide)
                <flux:carousel.step.item 
                    :name="$slide['name']" 
                    :label="$slide['label']" 
                    wire:key="slide-{{ $slide['name'] }}"
                >
                    <div class="flex items-center justify-center h-64 bg-gradient-to-br from-{{ $slide['color'] }}-500 to-{{ $slide['color'] }}-600 rounded-xl text-white">
                        <div class="text-center">
                            <flux:heading size="lg" class="text-white!">{{ $slide['label'] }}</flux:heading>
                            <flux:text class="text-white/90 mt-2">{{ $slide['description'] }}</flux:text>
                        </div>
                    </div>
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
    
    {{-- Control Buttons --}}
    <div class="flex gap-3 justify-center">
        <flux:button wire:click="prependSlide">Prepend Slide</flux:button>
        <flux:button wire:click="appendSlide">Append Slide</flux:button>
        <flux:button wire:click="removeFirstSlide" variant="ghost">Remove First</flux:button>
        <flux:button wire:click="removeLastSlide" variant="ghost">Remove Last</flux:button>
    </div>
</div>
