<?php

namespace FancyFlux\Demos;

use Livewire\Component;

/**
 * Emoji Select Examples Demo
 *
 * Demonstrates different emoji select variants and usage patterns.
 */
class EmojiSelectExamples extends Component
{
    public string $basicEmoji = '';
    public string $withLabelEmoji = '';
    public string $smallEmoji = '';
    public string $defaultEmoji = '';
    public string $largeEmoji = '';
    public string $outlineEmoji = '';
    public string $filledEmoji = '';
    public string $preselectedEmoji = '🎉';
    public string $reactionEmoji = '';
    public string $groupEmoji = '';

    public function render()
    {
        return view('fancy-flux-demos::emoji-select-examples.emoji-select-examples');
    }
}
