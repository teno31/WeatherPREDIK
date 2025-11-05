<?php

namespace App\Livewire\App;

use App\Services\WeatherService;
use Illuminate\Support\Facades\Log;
use Livewire\Attributes\On;
use Livewire\Component;

class Dashboard extends Component
{

    public ?array $weatherData = null;
    public ?string $error = null;
    public bool $loading = true;
    public bool $locationRequested = false;
    public bool $useGeolocation = true; // Flag to enable auto-request

    // Default coordinates (fallback if user denies location)
    public float $latitude = 10.3157;  // Cebu, Philippines
    public float $longitude = 123.8854;

    /**
     * Component initialization
     * 
     * Instead of immediately fetching weather, we wait for the browser
     * to provide the user's location via JavaScript
     */
    public function mount()
    {
        // Don't fetch weather yet - let JavaScript get location first
        // The loading state will show until location is obtained
        $this->loading = true;
    }

    /**
     * Called by JavaScript after getting user's location
     * or when user denies location permission
     */
    #[On('location-obtained')]
    public function handleLocationObtained(?float $lat = null, ?float $lon = null)
    {
        $this->locationRequested = true;

        if ($lat !== null && $lon !== null) {
            // User allowed location access
            $this->latitude = $lat;
            $this->longitude = $lon;
            Log::info("Lan and Lon $lat , $lon");
        }
        // If lat/lon are null, use default coordinates (already set)

        $this->fetchWeather();
    }

    /**
     * Fetch weather data from API
     */
    public function fetchWeather()
    {
        $this->loading = true;
        $this->error = null;

        try {
            $weatherService = app(WeatherService::class);
            $this->weatherData = $weatherService->getFormattedWeather(
                $this->latitude,
                $this->longitude
            );
        } catch (\Exception $e) {
            $this->error = 'Failed to load weather data. Please try again.';
            Log::error('Weather fetch error: ' . $e->getMessage());
        } finally {
            $this->loading = false;
        }
    }

    /**
     * Manual refresh button
     */
    #[On('refresh-weather')]
    public function refresh()
    {
        $this->fetchWeather();
    }

    /**
     * Update location manually (for location search feature)
     */
    public function updateLocation(float $lat, float $lon)
    {
        $this->latitude = $lat;
        $this->longitude = $lon;
        $this->fetchWeather();
    }

    /**
     * User manually denied location - use default
     */
    #[On('use-default-location')]
    public function useDefaultLocation()
    {
        $this->locationRequested = true;
        $this->fetchWeather();
    }

    public function render()
    {
        return view('livewire.app.dashboard')->layout('components.layouts.app');
    }
}
