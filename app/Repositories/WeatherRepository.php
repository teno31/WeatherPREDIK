<?php

namespace App\Repositories;

use Illuminate\Support\Facades\Cache;
use Illuminate\Support\Facades\Http;
use Illuminate\Support\Facades\Log;

class WeatherRepository
{
    private string $base_url;
    private string $geolocation_url;
    private int $cache_ttl;

    public function __construct()
    {
        $this->base_url = config('services.weather_api.base_url', 'https://api.open-meteo.com/v1');
        $this->geolocation_url = config('services.weather_api.geolocation_url', 'https://nominatim.openstreetmap.org/reverse');
        $this->cache_ttl = 600;
    }

    /**
     * Get weather data from Open-Meteo API
     */
    public function getWeatherData(float $latitude, float $longitude): array
    {
        $roundedLat = round($latitude, 2);
        $roundedLon = round($longitude, 2);
        $cacheKey = "weather_{$roundedLat}_{$roundedLon}";

        try {
            return Cache::remember($cacheKey, $this->cache_ttl, function () use ($latitude, $longitude) {
                Log::info('Fetching weather data', [
                    'latitude' => $latitude,
                    'longitude' => $longitude,
                    'url' => $this->base_url . '/forecast'
                ]);

                $response = Http::timeout(10)
                    ->get($this->base_url . '/forecast', [
                        'latitude' => $latitude,
                        'longitude' => $longitude,
                        'current' => 'temperature_2m,relative_humidity_2m,apparent_temperature,precipitation,weather_code,wind_speed_10m',
                        'hourly' => 'temperature_2m,precipitation_probability,weather_code',
                        'timezone' => 'auto',
                        'forecast_days' => 1
                    ]);

                // Log the response for debugging
                Log::info('Weather API Response', [
                    'status' => $response->status(),
                    'body' => $response->json()
                ]);

                if ($response->failed()) {
                    Log::error('Weather API request failed', [
                        'status' => $response->status(),
                        'body' => $response->body()
                    ]);
                    throw new \Exception('Failed to fetch weather data from API. Status: ' . $response->status());
                }

                $data = $response->json();

                // Validate response structure
                if (!isset($data['current']) || !isset($data['hourly'])) {
                    Log::error('Invalid API response structure', ['data' => $data]);
                    throw new \Exception('Invalid response structure from weather API');
                }

                return $data;
            });
        } catch (\Exception $e) {
            Log::error('Weather repository error', [
                'message' => $e->getMessage(),
                'trace' => $e->getTraceAsString()
            ]);
            throw $e;
        }
    }

    /**
     * Get location name from coordinates
     */
    public function getLocationName(float $latitude, float $longitude): array
    {
        $cacheKey = "location_{$latitude}_{$longitude}";

        try {
            return Cache::remember($cacheKey, 86400, function () use ($latitude, $longitude) {
                Log::info('Fetching location name', [
                    'latitude' => $latitude,
                    'longitude' => $longitude,
                    'localityLanguage' => 'en'
                ]);

                $response = Http::timeout(10)
                    ->withHeaders([
                        'User-Agent' => 'WeatherApp/1.0'
                    ])
                    ->get($this->geolocation_url, [
                        'lat' => $latitude,
                        'lon' => $longitude,
                        'format' => 'json',
                        'addressdetails' => 1,
                        'zoom' => 10,
                        'accept-language' => 'en'
                    ]);

                if ($response->successful()) {
                    $data = $response->json();
                    Log::info('Location API response', ['data' => $data]);

                    // BigDataCloud returns a different structure
                    // Build location name from available data
                    $locationParts = [];
                    $address = $data['address'];

                    // Try to get city/locality
                    if (!empty($address['municipality'])) {
                        $locationParts[] = $address['municipality'];
                    } elseif (!empty($address['city'])) {
                        $locationParts[] = $address['city'];
                    } elseif (!empty($address['town'])) {
                        $locationParts[] = $address['town'];
                    } elseif (!empty($address['village'])) {
                        $locationParts[] = $address['village'];
                    }

                    if (!empty($address['state'])) {
                        $locationParts[] = $address['state'];
                    } elseif (!empty($address['province'])) {
                        $locationParts[] = $address['province'];
                    }

                    if (!empty($address['country'])) {
                        $locationParts[] = $address['country'];
                    }

                    $locationName = !empty($locationParts)
                        ? implode(', ', $locationParts)
                        : ($data['display_name'] ?? 'Unknown Location');

                    Log::info('Location found via Nominatim', [
                        'location' => $locationName,
                        'full_address' => $data['display_name'] ?? null
                    ]);

                    return [
                        'name' => $locationName,
                        'city' => $address['municipality'] ?? $address['city'] ?? $address['town'] ?? null,
                        'state' => $address['state'] ?? $address['province'] ?? null,
                        'country' => $address['country'] ?? null,
                        'country_code' => $address['country_code'] ?? null,
                        'full_address' => $data['display_name'] ?? null,
                        'source' => 'nominatim'
                    ];
                }


                Log::warning('Location not found, using default');
                return ['name' => 'Unknown Location'];
            });
        } catch (\Exception $e) {
            Log::error('Location fetch error', [
                'message' => $e->getMessage()
            ]);
            return ['name' => 'Unknown Location'];
        }
    }
}
