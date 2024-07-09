<?php

namespace Database\Factories;

use App\Models\APIApp;
use Illuminate\Database\Eloquent\Factories\Factory;
use Illuminate\Support\Str;

class APIAppFactory extends Factory
{
    /**
     * The name of the factory's corresponding model.
     *
     * @var string
     */
    protected $model = APIApp::class;

    /**
     * Define the model's default state.
     *
     * @return array
     */
    public function definition()
    {
        return [
            'name' => fake()->name(),
            'app_access_id' => bin2hex(random_bytes(16)),
            'app_secret' => bin2hex(random_bytes(32)),
        ];
    }
}
