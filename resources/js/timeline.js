/**
 * Flux Timeline Helper Extension
 *
 * Extends the window.Flux object to include timeline helpers.
 * Usage in Alpine: Flux.timeline('timeline-name').goToNext()
 * Usage in JS: Flux.timeline('timeline-name').goToNext()
 */

/**
 * Create the timeline helper function.
 * This creates a helper for controlling TimelineJS3 instances programmatically.
 *
 * @param {string} name - The timeline name/id
 * @returns {object} Timeline control methods
 */
function createTimelineHelper(name) {
    // Find the timeline element
    const getEl = () => document.getElementById(name)
        || document.querySelector(`[data-flux-timeline="${name}"]`);

    // Get Alpine data from the timeline
    const getData = () => {
        const el = getEl();
        return el && window.Alpine ? Alpine.$data(el) : null;
    };

    // Dispatch a CustomEvent to the window for the Alpine component to handle
    const dispatch = (event, detail) => {
        window.dispatchEvent(new CustomEvent(event, { detail }));
    };

    return {
        // Element reference
        get el() { return getEl(); },

        // State getters
        get isLoading() { return getData()?.isLoading ?? true; },
        get initialized() { return getData()?.initialized ?? false; },
        get instance() { return getData()?.timeline ?? null; },

        // ── Navigation ──────────────────────────────────────────

        goTo(index) {
            dispatch('timeline-goto', { id: name, index });
        },

        goToId(uniqueId) {
            dispatch('timeline-goto', { id: name, uniqueId });
        },

        goToStart() {
            dispatch('timeline-goto', { id: name, position: 'start' });
        },

        goToEnd() {
            dispatch('timeline-goto', { id: name, position: 'end' });
        },

        goToPrev() {
            dispatch('timeline-goto', { id: name, position: 'prev' });
        },

        goToNext() {
            dispatch('timeline-goto', { id: name, position: 'next' });
        },

        // ── Zoom ────────────────────────────────────────────────

        zoomIn() {
            dispatch('timeline-zoom', { id: name, direction: 'in' });
        },

        zoomOut() {
            dispatch('timeline-zoom', { id: name, direction: 'out' });
        },

        setZoom(level) {
            dispatch('timeline-zoom', { id: name, level });
        },

        // ── Data Manipulation ───────────────────────────────────

        add(event) {
            dispatch('timeline-add', { id: name, event });
        },

        remove(index) {
            dispatch('timeline-remove', { id: name, index });
        },

        removeId(uniqueId) {
            dispatch('timeline-remove', { id: name, uniqueId });
        },

        // ── Display ─────────────────────────────────────────────

        refresh() {
            dispatch('timeline-refresh', { id: name });
        },

        updateDisplay() {
            dispatch('timeline-refresh', { id: name });
        },

        updateData(data) {
            dispatch('timeline-update-data', { id: name, data });
        },

        // ── Direct Access ───────────────────────────────────────

        /**
         * Get event data by index from the TimelineJS3 instance.
         * @param {number} index
         * @returns {object|null}
         */
        getData(index) {
            return this.instance?.getData(index) ?? null;
        },

        /**
         * Get event data by unique_id from the TimelineJS3 instance.
         * @param {string} uniqueId
         * @returns {object|null}
         */
        getDataById(uniqueId) {
            return this.instance?.getDataById(uniqueId) ?? null;
        },
    };
}

// Register the helper on alpine:init which fires BEFORE Alpine evaluates components
// This ensures Flux.timeline is available when x-data is evaluated
document.addEventListener('alpine:init', () => {
    if (window.Flux && !window.Flux.timeline) {
        window.Flux.timeline = createTimelineHelper;
    }
});

// Also try to register immediately if Flux is already available (for non-Livewire pages)
if (window.Flux && !window.Flux.timeline) {
    window.Flux.timeline = createTimelineHelper;
}
