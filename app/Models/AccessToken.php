<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Carbon\Carbon;

class AccessToken extends Model
{
    use HasFactory;

    protected $fillable = ['app_id', 'access_token', 'expiration_date'];

    public function apiApp()
    {
        return $this->belongsTo(APIApp::class, 'app_id');
    }

    /**
     * Generate a new unique access token for the given API application.
     *
     * @return string
     */
    public static function generateNewAccessToken(APIApp $app)
    {
        do {
            $randomToken = bin2hex(random_bytes(64));

            // Check if the token already exists in the database
            $tokenExists = self::where('access_token', $randomToken)->exists();
        } while ($tokenExists);

        // Create a new access token entry for the app
        $app->accessToken()->create([
            'access_token' => $randomToken,
            'expiration_date' => Carbon::now()->addHours(2)
        ]);

        return $randomToken;
    }
}
