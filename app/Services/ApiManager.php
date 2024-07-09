<?php

namespace App\Services;

use Illuminate\Support\Manager;
use App\Services\ApiService;

class ApiManager extends Manager
{
    /**
     * Get the default driver name.
     *
     * @return string
     */
    public function getDefaultDriver()
    {
        return 'external';
    }

    /**
     * Create an instance of the API service.
     *
     * @return \App\Services\ApiService
     */
    protected function createExternalDriver()
    {
        return new ApiService();
    }
}
