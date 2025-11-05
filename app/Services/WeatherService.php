<?php

namespace App\Services;

use App\Repositories\WeatherRepository;
use Carbon\Carbon;
use Illuminate\Support\Facades\Log;

class WeatherService
{
    
    public function __construct(private WeatherRepository $weatherRepository)
    {
    }

    /**
     * Get formatted weather data
     */
    public function getFormattedWeather(float $latitude, float $longitude): array
    {
        try {
            Log::info('WeatherService: Getting formatted weather', [
                'latitude' => $latitude,
                'longitude' => $longitude
            ]);

            $weatherData = $this->weatherRepository->getWeatherData($latitude, $longitude);
            $location = $this->weatherRepository->getLocationName($latitude, $longitude);

            Log::info('WeatherService: Data retrieved successfully', [
                'has_current' => isset($weatherData['current']),
                'has_hourly' => isset($weatherData['hourly']),
                'location' => $location['name'] ?? 'unknown'
            ]);

            return [
                'location' => $location['name'] ?? 'Unknown Location',
                'current' => $this->formatCurrentWeather($weatherData['current']),
                'hourly' => $this->formatHourlyForecast($weatherData['hourly'], $weatherData['timezone'] ?? 'UTC'),
                'timezone' => $weatherData['timezone'] ?? 'UTC',
            ];
        } catch (\Exception $e) {
            Log::error('WeatherService: Error in getFormattedWeather', [
                'message' => $e->getMessage(),
                'trace' => $e->getTraceAsString()
            ]);
            throw $e;
        }
    }

    /**
     * Format current weather data
     */
    private function formatCurrentWeather(array $current): array
    {
        try {
            return [
                'temperature' => round($current['temperature_2m'] ?? 0, 1),
                'feels_like' => round($current['apparent_temperature'] ?? 0, 1),
                'humidity' => $current['relative_humidity_2m'] ?? 0,
                'wind_speed' => round($current['wind_speed_10m'] ?? 0, 1),
                'precipitation' => $current['precipitation'] ?? 0,
                'condition' => $this->getWeatherCondition($current['weather_code'] ?? 0),
                'icon' => $this->getWeatherIcon($current['weather_code'] ?? 0),
                'time' => isset($current['time']) 
                    ? Carbon::parse($current['time'])->format('g:i A')
                    : now()->format('g:i A'),
            ];
        } catch (\Exception $e) {
            Log::error('Error formatting current weather', [
                'message' => $e->getMessage(),
                'current_data' => $current
            ]);
            throw new \Exception('Failed to format current weather data: ' . $e->getMessage());
        }
    }

    /**
     * Format hourly forecast data
     */
    private function formatHourlyForecast(array $hourly, string $timezone): array
    {
        try {
            $formatted = [];
            
            if (!isset($hourly['time']) || !is_array($hourly['time'])) {
                Log::warning('Invalid hourly data structure', ['hourly' => $hourly]);
                return [];
            }

            $count = min(24, count($hourly['time']));

            for ($i = 0; $i < $count; $i++) {
                $formatted[] = [
                    'time' => Carbon::parse($hourly['time'][$i], $timezone)->format('g A'),
                    'temperature' => round($hourly['temperature_2m'][$i] ?? 0, 1),
                    'precipitation_probability' => $hourly['precipitation_probability'][$i] ?? 0,
                    'icon' => $this->getWeatherIcon($hourly['weather_code'][$i] ?? 0),
                ];
            }

            return $formatted;
        } catch (\Exception $e) {
            Log::error('Error formatting hourly forecast', [
                'message' => $e->getMessage(),
                'hourly_data' => $hourly
            ]);
            throw new \Exception('Failed to format hourly forecast: ' . $e->getMessage());
        }
    }

    /**
     * Get weather condition from WMO code
     */
    private function getWeatherCondition(int $code): string
    {
        return match (true) {
            $code === 0 => 'Clear sky',
            in_array($code, [1, 2, 3]) => 'Partly cloudy',
            in_array($code, [45, 48]) => 'Foggy',
            in_array($code, [51, 53, 55]) => 'Drizzle',
            in_array($code, [61, 63, 65]) => 'Rain',
            in_array($code, [71, 73, 75]) => 'Snow',
            in_array($code, [95, 96, 99]) => 'Thunderstorm',
            default => 'Unknown',
        };
    }

    /**
     * Get weather icon from WMO code
     */
    private function getWeatherIcon(int $code): string
    {
        return match (true) {
            $code === 0 => '☀️',
            in_array($code, [1, 2]) => '🌤️',
            $code === 3 => '☁️',
            in_array($code, [45, 48]) => '🌫️',
            in_array($code, [51, 53, 55, 61, 63, 65]) => '🌧️',
            in_array($code, [71, 73, 75]) => '❄️',
            in_array($code, [95, 96, 99]) => '⛈️',
            default => '🌡️',
        };
    }
}
