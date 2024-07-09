<?php

namespace Tests\Feature\Auth;

use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;
use App\Models\APIApp;
use App\Models\AccessToken;
use Carbon\Carbon;
use PHPUnit\Framework\Attributes\Test;

class MiddlewareTest extends TestCase
{
    use RefreshDatabase;

    protected $apiApp;
    protected $accessToken;

    protected function setUp(): void
    {
        parent::setUp();

        $this->apiApp = APIApp::factory()->create();

        // Create a valid access token
        $this->accessToken = AccessToken::create([
            'app_id' => $this->apiApp->id,
            'access_token' => bin2hex(random_bytes(64)),
            'expiration_date' => Carbon::now()->addHours(2),
        ]);
    }

    #[Test]
    public function it_returns_unauthorized_when_app_id_is_missing()
    {
        $response = $this->json('GET', '/api/data', [], [
            'Authorization' => 'Bearer ' . $this->accessToken->access_token,
        ]);

        $response->assertStatus(401)
            ->assertJson([
                'error' => 'Invalid API credentials',
            ]);
    }

    #[Test]
    public function it_returns_unauthorized_when_token_is_missing()
    {
        $response = $this->json('GET', '/api/data', [], [
            'app-id' => $this->apiApp->app_access_id,
        ]);

        $response->assertStatus(401)
            ->assertJson([
                'error' => 'Invalid API credentials',
            ]);
    }

    #[Test]
    public function it_returns_invalid_token_when_authorization_token_is_invalid()
    {
        $response = $this->json('GET', '/api/data', [], [
            'app-id' => $this->apiApp->app_access_id,
            'Authorization' => 'Bearer invalid_token',
        ]);

        $response->assertStatus(401)
            ->assertJson([
                'error' => 'Invalid Access Token',
            ]);
    }

    #[Test]
    public function it_returns_quotes_when_credentials_are_valid()
    {
        $response = $this->json('GET', '/api/data', [], [
            'app-id' => $this->apiApp->app_access_id,
            'Authorization' => $this->accessToken->access_token,
        ]);

        $response->assertStatus(200)
            ->assertJson([
                'message' => 'Quotes fetched and cached successfully',
                'items' => [],
            ]);
    }
}
