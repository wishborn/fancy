<?php

/**
 * Browser tests for the flux:emoji-select component.
 *
 * These tests verify visual appearance and interactive behavior of the emoji select,
 * ensuring compliance with Flux design standards. Tests live in the main project repo
 * so they can be reused across different component branches in the flux package.
 */

describe('emoji select component', function () {
    it('renders the trigger button with placeholder', function () {
        $page = visit('/emoji-select-demo');

        $page->assertCount('[data-flux-emoji-select]', fn ($count) => $count >= 1)
            ->assertSee('Select emoji...');
    });

    it('displays label when provided', function () {
        $page = visit('/emoji-select-demo');

        $page->assertSee('Reaction')
            ->assertSee('Post Reaction');
    });

    it('shows different size variants', function () {
        $page = visit('/emoji-select-demo');

        $page->assertSee('Small')
            ->assertSee('Default')
            ->assertSee('Large');
    });

    it('has no JavaScript errors on page load', function () {
        $page = visit('/emoji-select-demo');

        $page->assertNoJavascriptErrors();
    });

    it('has no console errors on page load', function () {
        $page = visit('/emoji-select-demo');

        $page->assertNoConsoleLogs();
    });
});

describe('emoji select in dark mode', function () {
    it('renders correctly in dark mode', function () {
        $page = visit('/emoji-select-demo')->inDarkMode();

        $page->assertCount('[data-flux-emoji-select]', fn ($count) => $count >= 1)
            ->assertNoJavascriptErrors();
    });
});

describe('emoji select interactions', function () {
    it('opens popover when trigger is clicked', function () {
        $page = visit('/emoji-select-demo');

        // Click the first emoji select trigger
        $page->click('[data-flux-emoji-trigger]');

        // Popover should be visible with search input
        $page->waitFor('[data-flux-emoji-popover]')
            ->assertVisible('[data-flux-emoji-popover]');
    });

    it('shows category navigation in popover', function () {
        $page = visit('/emoji-select-demo');

        $page->click('[data-flux-emoji-trigger]')
            ->waitFor('[data-flux-emoji-popover]');

        // Should see category icons - smileys is the default
        $page->assertSee('Smileys');
    });

    it('allows searching for emojis', function () {
        $page = visit('/emoji-select-demo');

        $page->click('[data-flux-emoji-trigger]')
            ->waitFor('[data-flux-emoji-popover]')
            ->fill('[data-flux-emoji-popover] input[type="text"]', 'heart')
            ->waitForText('results');

        // Should show search results
        $page->assertSee('results');
    });

    it('selects emoji and closes popover', function () {
        $page = visit('/emoji-select-demo');

        // Open the picker
        $page->click('[data-flux-emoji-trigger]')
            ->waitFor('[data-flux-emoji-popover]');

        // Click the first emoji button in the grid
        $page->click('[data-flux-emoji-popover] .grid button:first-child');

        // Popover should close and emoji should be selected
        $page->assertNotVisible('[data-flux-emoji-popover]');
    });

    it('closes popover on escape key', function () {
        $page = visit('/emoji-select-demo');

        $page->click('[data-flux-emoji-trigger]')
            ->waitFor('[data-flux-emoji-popover]')
            ->keys('[data-flux-emoji-popover] input', '{escape}')
            ->assertNotVisible('[data-flux-emoji-popover]');
    });

    it('switches between categories', function () {
        $page = visit('/emoji-select-demo');

        $page->click('[data-flux-emoji-trigger]')
            ->waitFor('[data-flux-emoji-popover]');

        // Initial category should be Smileys
        $page->assertSee('Smileys');

        // Click on Animals category (4th button based on our data)
        $page->click('[data-flux-emoji-popover] button[title="Animals"]')
            ->waitForText('Animals')
            ->assertSee('Animals');
    });

    it('clears search when x button is clicked', function () {
        $page = visit('/emoji-select-demo');

        $page->click('[data-flux-emoji-trigger]')
            ->waitFor('[data-flux-emoji-popover]')
            ->fill('[data-flux-emoji-popover] input[type="text"]', 'heart')
            ->waitForText('results');

        // Click the clear button
        $page->click('[data-flux-emoji-popover] button[aria-label="Clear search input"], [data-flux-emoji-popover] .absolute button');

        // Search should be cleared, showing category navigation again
        $page->assertSee('Smileys');
    });
});

describe('emoji select with pre-selected value', function () {
    it('displays pre-selected emoji in trigger', function () {
        $page = visit('/emoji-select-demo');

        // The pre-selected demo has party emoji
        $page->assertSee('Party Emoji');
    });
});

describe('emoji select accessibility', function () {
    it('has proper aria attributes on trigger', function () {
        $page = visit('/emoji-select-demo');

        $page->assertScript("document.querySelector('[data-flux-emoji-trigger]').hasAttribute('aria-haspopup')");
    });

    it('has popover attribute on emoji picker', function () {
        $page = visit('/emoji-select-demo');

        $page->click('[data-flux-emoji-trigger]')
            ->waitFor('[data-flux-emoji-popover]')
            ->assertScript("document.querySelector('[data-flux-emoji-popover]').hasAttribute('popover')");
    });
});
