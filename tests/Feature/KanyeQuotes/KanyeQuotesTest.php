<?php

namespace Tests\Feature\KanyeQuotes;

use App\Models\APIApp;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Support\Facades\Artisan;
use Illuminate\Support\Facades\Cache;
use Illuminate\Support\Facades\Http;
use Illuminate\Support\Facades\Log;
use Tests\TestCase;
use PHPUnit\Framework\Attributes\Test;

class KanyeQuotesTest extends TestCase
{
    use RefreshDatabase;

    protected $cacheKey = 'kanye_api_data';
    protected $cacheDuration = 30;

    #[Test]
    public function test_fetch_data_caches_correctly()
    {
        // get quotes - use factory
        $items = [
            "quote1",
            "quote2",
            "quote3",
            "quote4",
            "quote5",
        ];

        // Fake the HTTP request
        Http::fake([
            config('services.kanye_api.endpoint') => function ($request) use ($items) {
                Log::info('Intercepted request', ['url' => $request->url()]);
                return Http::response([
                    'status' => 'success',
                    'items' => $items,
                ], 200);
            },
        ]);

        Cache::forget($this->cacheKey);



        // dd($refreshResponse->json());

        // Fake the HTTP request
        // Http::fake([
        //     config('services.kanye_api.endpoint') => Http::response([
        //         'message' => 'Quotes fetched and cached successfully',
        //         'items' => $items,
        //     ], 200),
        // ]);

        $headers = $this->getHeaders();

        // Call the endpoint to fetch quotes and cache it
        $refreshResponse = $this->getJson('/api/data', $headers);
        $refreshResponse->assertStatus(200);

        // Call the endpoint to fetch quotes and cache it
        $cachedData = Cache::get($this->cacheKey);
        $this->assertNotNull($cachedData);

        // Assert response has 5 quotes
        $refreshResponse->assertJsonCount(5, 'items');
    }

    #[Test]
    public function test_refresh_data_returns_5_new_quotes()
    {
        $headers = $this->getHeaders();

        // Mock the initial data and cache it
        $fakeData = $this->getFakeData();
        Cache::put($this->cacheKey, $fakeData['items'], now()->addMinutes($this->cacheDuration));

        // Call the refresh-data endpoint to get new random quotes
        $refreshResponse = $this->getJson('/api/refresh-data', $headers);
        $this->assertNotNull(Cache::get($this->cacheKey));
        $refreshResponse->assertStatus(200);

        // Assert that the response contains the expected structure
        $refreshResponse->assertJsonStructure([
            'message',
            'items' => [
                '*' => ['id', 'quote']
            ],
        ]);

        // Assert that 5 items are returned
        $refreshResponse->assertJsonCount(5, 'items');

        // Verify that the items in the response are in the cached data
        $cachedData = Cache::get($this->cacheKey);
        $responseData = $refreshResponse->json('items');
        foreach ($responseData as $item) {
            $this->assertContains($item, $cachedData);
        }
    }

    protected function getHeaders()
    {
        $name = 'TestApp';
        Artisan::call('generate-keys', ['appName' => $name]);

        $apiApp = APIApp::where('name', $name)->first();

        return [
            'app-id' => $apiApp->app_access_id,
            'Authorization' =>  "Bearer " . $apiApp->accessToken->first()->access_token,
        ];
    }

    protected function getFakeData()
    {
        $faker = \Faker\Factory::create();
        $items = [];

        for ($i = 0; $i < 50; $i++) {
            $items[] = [
                'id' => $i + 1,
                'quote' => fake()->sentence(),
            ];
        }

        return ['items' => $items];
    }
}
