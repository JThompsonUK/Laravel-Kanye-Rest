<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;
use App\Models\APIApp;
use App\Models\AccessToken;

class GenerateAppKeys extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'generate-keys {appName}';


    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Generate new application keys and access token';

    /**
     * Execute the console command.
     */
    public function handle()
    {
        $this->info('App ID, Secret and Access Token created');

        $appName = $this->argument('appName');
        $apiApp = APIApp::generate($appName);
        $appKey = $apiApp->app_access_id;
        $token = AccessToken::generateNewAccessToken($apiApp);

        $this->info('Add these to your env:');
        $this->info('App Key (KANYE_API_APP_ID): ' . $appKey);
        $this->info('App Secret (KANYE_API_APP_SECRET): ' . $token);

        return 1;
    }
}
