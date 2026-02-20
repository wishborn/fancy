<?php

namespace FancyFlux\Managers;

/**
 * Manager for programmatic timeline control.
 *
 * Provides a fluent API for controlling TimelineJS3 instances from Livewire components
 * via the FANCY facade. Dispatches Alpine events to the timeline component.
 *
 * Why: Centralizes timeline control logic and provides a clean facade interface
 * that's more ergonomic than directly dispatching events.
 *
 * @example FANCY::timeline('my-timeline')->goToNext()
 * @example FANCY::timeline('history')->goToId('event-1')
 */
class TimelineManager
{
    /**
     * Get a timeline controller instance for the given timeline name.
     *
     * @param string $name The timeline's unique name/id
     * @return TimelineController
     */
    public function get(string $name): TimelineController
    {
        return new TimelineController($name);
    }
}

/**
 * Controller for a specific TimelineJS3 instance.
 *
 * Provides methods to navigate, zoom, and manipulate data in a timeline
 * by dispatching Alpine.js events that the timeline component listens for.
 */
class TimelineController
{
    public function __construct(
        protected string $name
    ) {}

    /**
     * Get the timeline name.
     *
     * @return string
     */
    public function getName(): string
    {
        return $this->name;
    }

    // ── Navigation ──────────────────────────────────────────────────

    /**
     * Navigate to a specific slide by index.
     *
     * @param int $index Zero-based slide index
     * @return $this
     */
    public function goTo(int $index): self
    {
        $this->dispatchToLivewire('timeline-goto', ['id' => $this->name, 'index' => $index]);

        return $this;
    }

    /**
     * Navigate to a specific slide by its unique_id.
     *
     * @param string $uniqueId The event's unique_id
     * @return $this
     */
    public function goToId(string $uniqueId): self
    {
        $this->dispatchToLivewire('timeline-goto', ['id' => $this->name, 'uniqueId' => $uniqueId]);

        return $this;
    }

    /**
     * Navigate to the first slide.
     *
     * @return $this
     */
    public function goToStart(): self
    {
        $this->dispatchToLivewire('timeline-goto', ['id' => $this->name, 'position' => 'start']);

        return $this;
    }

    /**
     * Navigate to the last slide.
     *
     * @return $this
     */
    public function goToEnd(): self
    {
        $this->dispatchToLivewire('timeline-goto', ['id' => $this->name, 'position' => 'end']);

        return $this;
    }

    /**
     * Navigate to the previous slide.
     *
     * @return $this
     */
    public function goToPrev(): self
    {
        $this->dispatchToLivewire('timeline-goto', ['id' => $this->name, 'position' => 'prev']);

        return $this;
    }

    /**
     * Navigate to the next slide.
     *
     * @return $this
     */
    public function goToNext(): self
    {
        $this->dispatchToLivewire('timeline-goto', ['id' => $this->name, 'position' => 'next']);

        return $this;
    }

    // ── Zoom ────────────────────────────────────────────────────────

    /**
     * Zoom in on the timeline navigation bar.
     *
     * @return $this
     */
    public function zoomIn(): self
    {
        $this->dispatchToLivewire('timeline-zoom', ['id' => $this->name, 'direction' => 'in']);

        return $this;
    }

    /**
     * Zoom out on the timeline navigation bar.
     *
     * @return $this
     */
    public function zoomOut(): self
    {
        $this->dispatchToLivewire('timeline-zoom', ['id' => $this->name, 'direction' => 'out']);

        return $this;
    }

    /**
     * Set a specific zoom level on the timeline.
     *
     * @param int $level Zoom level index into the zoom_sequence array
     * @return $this
     */
    public function setZoom(int $level): self
    {
        $this->dispatchToLivewire('timeline-zoom', ['id' => $this->name, 'level' => $level]);

        return $this;
    }

    // ── Data Manipulation ───────────────────────────────────────────

    /**
     * Dynamically add an event to the timeline.
     *
     * @param array{start_date: array, text?: array, media?: array, group?: string, unique_id?: string} $event
     * @return $this
     */
    public function add(array $event): self
    {
        $this->dispatchToLivewire('timeline-add', ['id' => $this->name, 'event' => $event]);

        return $this;
    }

    /**
     * Remove an event by index.
     *
     * @param int $index Zero-based event index
     * @return $this
     */
    public function remove(int $index): self
    {
        $this->dispatchToLivewire('timeline-remove', ['id' => $this->name, 'index' => $index]);

        return $this;
    }

    /**
     * Remove an event by its unique_id.
     *
     * @param string $uniqueId The event's unique_id
     * @return $this
     */
    public function removeId(string $uniqueId): self
    {
        $this->dispatchToLivewire('timeline-remove', ['id' => $this->name, 'uniqueId' => $uniqueId]);

        return $this;
    }

    /**
     * Replace the entire timeline data source and re-render.
     *
     * @param array{title?: array, events: array, eras?: array} $data
     * @return $this
     */
    public function updateData(array $data): self
    {
        $this->dispatchToLivewire('timeline-update-data', ['id' => $this->name, 'data' => $data]);

        return $this;
    }

    // ── Display ─────────────────────────────────────────────────────

    /**
     * Refresh the timeline display (recalculate layout).
     *
     * @return $this
     */
    public function refresh(): self
    {
        $this->dispatchToLivewire('timeline-refresh', ['id' => $this->name]);

        return $this;
    }

    // ── Internal ────────────────────────────────────────────────────

    /**
     * Dispatch an event to the current Livewire component's JS context.
     *
     * @param string $event Event name
     * @param array $data Event data
     */
    protected function dispatchToLivewire(string $event, array $data): void
    {
        $component = $this->getCurrentLivewireComponent();
        if ($component) {
            $dataJson = json_encode($data);
            $component->js("\$dispatch('{$event}', {$dataJson})");
        }
    }

    /**
     * Get the current Livewire component instance.
     *
     * @return \Livewire\Component|null
     */
    protected function getCurrentLivewireComponent(): ?\Livewire\Component
    {
        return app('livewire')->current();
    }
}
