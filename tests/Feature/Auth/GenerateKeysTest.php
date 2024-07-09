<?php

namespace Tests\Feature\Auth;

use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;
use App\Models\APIApp;
use Illuminate\Support\Facades\Artisan;
use PHPUnit\Framework\Attributes\Test;

class GenerateKeysTest extends TestCase
{
    use RefreshDatabase;

    #[Test]
    public function it_generates_new_application_keys_and_stores_them_in_database()
    {
        $name = 'TestApp';
        Artisan::call('generate-keys', ['appName' => $name]);

        $apiApp = APIApp::where('name', $name)->first();

        $this->assertNotNull($apiApp);
        $this->assertNotNull($apiApp->app_access_id);
        $this->assertNotNull($apiApp->app_secret);
    }
}
