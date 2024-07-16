<?php

namespace App\Interfaces;

interface QuoteInterface
{
    public function fetchQuotes();

    public function refreshQuotes();
}
