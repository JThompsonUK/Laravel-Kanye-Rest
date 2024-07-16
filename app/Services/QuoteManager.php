<?php

namespace App\Services;

use Illuminate\Support\Manager;
use App\Services\QuoteService;

class QuoteManager extends Manager
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
     * Create an instance of the Quote service.
     *
     * @return \App\Services\QuoteService
     */
    protected function createExternalDriver()
    {
        return new QuoteService();
    }
}
