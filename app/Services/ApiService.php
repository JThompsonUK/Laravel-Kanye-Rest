<?php

namespace App\Services;

use Illuminate\Support\Facades\Http;
use Illuminate\Support\Facades\Cache;

class ApiService
{
    protected $url;
    protected $token;
    protected $cacheKey = 'kanye_api_data';
    protected $cacheDuration = 30;

    public function __construct()
    {
        $this->url = config('services.kanye_api.url');
        $this->token = config('services.kanye_api.token');
    }

    public function fetchData()
    {
        $data = Http::withHeaders([
            'Authorization' => 'Bearer ' . $this->token,
        ])->get($this->url);

        if ($data->successful()) {
            $responseData = $data->json();

            // Cache the fetched data for 30 minutes
            Cache::put($this->cacheKey, $responseData, now()->addMinutes($this->cacheDuration));

            return collect($responseData)->random(5)->all();
        }

        return null;
    }

    public function refreshData()
    {
        $data = Cache::get($this->cacheKey);
        return collect($data)->random(5)->all();
    }
}
