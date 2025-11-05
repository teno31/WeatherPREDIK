<div x-data="dashboard" class="max-w-4xl mx-auto p-6">

    

    <div class="flex flex-row items-center justify-end">
        <x-monoicon-notification class="size-6" />
        <img src="{{ asset('img/man.png') }}" class="size-12" alt="">
    </div>
    <div class="flex flex-col bg-white border border-black shadow-2xs rounded-xl p-4 md:p-5 mt-12">
        <p>{{ $weatherData['location'] ?? '' }}</p>
    </div>
    <div class="flex flex-row items-center gap-1 mt-4">
        <div class="w-full">
            <!-- SearchBox -->
            <div class="relative">
                <div class="relative">
                    <div class="absolute inset-y-0 start-0 flex items-center pointer-events-none z-20 ps-3.5">
                        <svg class="shrink-0 size-4 text-black" xmlns="http://www.w3.org/2000/svg" width="24"
                            height="24" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"
                            stroke-linecap="round" stroke-linejoin="round">
                            <circle cx="11" cy="11" r="8"></circle>
                            <path d="m21 21-4.3-4.3"></path>
                        </svg>
                    </div>
                    <input
                        class="w-full py-3 ps-10 pe-4 
                        rounded-lg sm:text-sm  
                        disabled:opacity-50 
                        disabled:pointer-events-none
                        border
                         bg-white 
                         border-black 
                         text-neutral-400
                          placeholder-neutral-500
                           focus:ring-neutral-600"
                        type="text" role="combobox" aria-expanded="false" placeholder="Search location"
                        value="" data-hs-combo-box-input="">
                </div>
                <!-- End SearchBox Dropdown -->
            </div>
            <!-- End SearchBox -->
        </div>
        <button @click="requestLocation()" type="button" class="border border-black rounded-lg p-3"><x-typ-location
                class="size-6" /></button>
    </div>

    @if (isset($weatherData['current']))
        <div class="flex flex-col bg-white border border-black shadow-2xs rounded-xl p-4 md:p-5 mt-12">
            <p class="text-5xl">{{$weatherData['current']['icon']}}</p>
            <p class="text-3xl">{{ $weatherData['current']['temperature'] }}°</p>

            <div class="flex flex-col gap-2 mt-6">
                <p class="text-xl">Temperature: {{ $weatherData['current']['temperature'] }}°</p>
                <p class="text-xl">Feels like: {{ $weatherData['current']['feels_like'] }}°</p>
                <p class="text-xl">Humidity: {{ $weatherData['current']['humidity'] }}°</p>
                <p class="text-xl">Wind: {{ $weatherData['current']['wind_speed'] }}km/h</p>
            </div>
        </div>
    @endif
</div>

<script>
    document.addEventListener('alpine:init', () => {
        Alpine.data('dashboard', () => ({
            isRefreshing: false,
            locationLoading: false,

            /**
             * INIT FUNCTION
             * Automatically runs when the component loads
             * Requests user's location immediately
             */
            init() {
                // Automatically request location when page loads
                this.requestLocation();
            },

            /**
             * REQUEST LOCATION
             * Uses browser's Geolocation API to get user's coordinates
             */
            requestLocation() {
                // Check if browser supports geolocation
                if (!navigator.geolocation) {
                    alert('Geolocation is not supported by your browser');
                    this.useDefault();
                    return;
                }

                this.locationLoading = true;

                // Request user's location
                navigator.geolocation.getCurrentPosition(
                    // SUCCESS CALLBACK - User allowed location
                    (position) => {
                        const lat = position.coords.latitude;
                        const lon = position.coords.longitude;

                        console.log('Location obtained:', lat, lon);

                        // Send coordinates to Livewire component
                        this.$wire.dispatch('location-obtained', {
                            lat: lat,
                            lon: lon
                        });

                        this.locationLoading = false;
                    },
                    // ERROR CALLBACK - User denied or error occurred
                    (error) => {
                        console.log('Location error:', error.message);

                        // Show user-friendly error message
                        let errorMessage = '';
                        switch (error.code) {
                            case error.PERMISSION_DENIED:
                                errorMessage =
                                    'Location access denied. Using default location.';
                                break;
                            case error.POSITION_UNAVAILABLE:
                                errorMessage = 'Location unavailable. Using default location.';
                                break;
                            case error.TIMEOUT:
                                errorMessage =
                                    'Location request timed out. Using default location.';
                                break;
                        }

                        // Optional: Show alert
                        // alert(errorMessage);

                        // Use default location (no coordinates)
                        this.$wire.dispatch('location-obtained', {
                            lat: null,
                            lon: null
                        });

                        this.locationLoading = false;
                    },
                    // OPTIONS
                    {
                        enableHighAccuracy: true, // Use GPS if available
                        timeout: 10000, // Wait max 10 seconds
                        maximumAge: 0 // Don't use cached position
                    }
                );
            },

            /**
             * USE DEFAULT LOCATION
             * User manually chose to use default location
             */
            useDefault() {
                this.$wire.dispatch('use-default-location');
            }
        }));
    })
</script>
