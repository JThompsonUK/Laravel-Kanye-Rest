<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class APIApp extends Model
{
    use HasFactory;

    protected $table = 'api_apps';
    protected $fillable = ['name', 'app_access_id', 'app_secret'];

    /**
     * Generate a new API application with a unique access ID and secret.
     *
     * @return self
     */
    public static function generate($name)
    {
        $app = new self();
        $app->name = $name;
        $app->app_access_id = bin2hex(random_bytes(16));
        $app->app_secret = bin2hex(random_bytes(32));
        $app->save();

        return $app;
    }

    public function accessToken()
    {
        return $this->hasMany(AccessToken::class, 'app_id');
    }
}
