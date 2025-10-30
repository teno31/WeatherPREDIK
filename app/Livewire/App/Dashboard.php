<?php

namespace App\Livewire\App;

use Illuminate\Support\Facades\Http;
use Illuminate\Support\Facades\Log;
use Livewire\Component;

class Dashboard extends Component
{

    public $query = '';
    public $weather = null;
    public $suggestions = [];

    public function updatedQuery()
    {
        if (strlen($this->query) < 2) {
            $this->suggestions = [];
            return;
        }

        $geoURL = env('GEO_URL');
        $apiKey = env('WEATHER_API_KEY');

        $response = Http::get($geoURL, [
            'q' => $this->query,
            'limit' => 5,
            'appid' => $apiKey
        ]);

        $this->suggestions = $response->json();

        Log::info('hello');
    }

    public function setLocation($lat, $lon, $name)
    {
        $url = env('WEATHER_URL');
        $apiKey = env('WEATHER_API_KEY');

        $response = Http::get($url, [
            'lat' => $lat,
            'lon' => $lon,
            'appid' => $apiKey,
            'units' => 'metric'
        ]);

        if ($response->ok()) {
            $this->weather = $response->json();
            $this->query = $name;
            $this->suggestions = [];
        }

        Log::info('wmasdasd');
    }

    public function render()
    {
        return view('livewire.app.dashboard')->layout('components.layouts.app');
    }
}
