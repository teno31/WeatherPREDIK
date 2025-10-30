<div
    x-data="toast"
    x-show="open"
    x-transition:enter="transition ease-out duration-300"
    x-transition:enter-start="opacity-0 translate-y-4"
    x-transition:enter-end="opacity-100 translate-y-0"
    x-transition:leave="transition ease-in duration-300"
    x-transition:leave-start="opacity-100 translate-y-0"
    x-transition:leave-end="opacity-0 translate-y-4"
    class="fixed top-4 left-1/2 transform -translate-x-1/2 max-w-xs bg-gray-800 text-sm text-white rounded-xl shadow-lg dark:bg-neutral-900"
    role="alert"
    tabindex="-1"
    aria-labelledby="toast-label"
>
    <div
        class="max-w-xs bg-gray-800 text-sm text-white rounded-xl shadow-lg dark:bg-neutral-900 pointer-events-auto"
    >
        <div id="toast-label" class="flex p-4">
            <span x-text="message">Hello</span>
            <div class="ms-auto">
                <button
                    @click="hide"
                    type="button"
                    class="inline-flex shrink-0 justify-center items-center size-5 rounded-lg text-white hover:text-white opacity-50 hover:opacity-100 focus:outline-hidden focus:opacity-100"
                    aria-label="Close"
                >
                    <span class="sr-only">Close</span>
                    <svg
                        class="shrink-0 size-4"
                        xmlns="http://www.w3.org/2000/svg"
                        width="24"
                        height="24"
                        viewBox="0 0 24 24"
                        fill="none"
                        stroke="currentColor"
                        stroke-width="2"
                        stroke-linecap="round"
                        stroke-linejoin="round"
                    >
                        <path d="M18 6 6 18"></path>
                        <path d="m6 6 12 12"></path>
                    </svg>
                </button>
            </div>
        </div>
    </div>
</div>

<script>
    document.addEventListener('alpine:init', () => {
        Alpine.data('toast', () => ({
            open: @entangle('open'),
            message: @entangle('message'),
            hide() {
                this.open = false
            }
        }))
    })
</script>
