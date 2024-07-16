<?php

namespace App\Http\Controllers;

use App\Services\QuoteManager;
use Symfony\Component\HttpFoundation\Response;

class ApiController extends Controller
{
    protected $quoteManager;

    /**
     * ApiController constructor.
     *
     * @param QuoteManager $quoteManager
     */
    public function __construct(QuoteManager $quoteManager)
    {
        $this->quoteManager = $quoteManager;
    }

    /**
     * Get data from the API.
     *
     * @return JsonResponse
     */
    public function getData()
    {
        $quotes = $this->quoteManager->driver()->fetchQuotes();

        if ($quotes) {
            $data = [
                'message' => 'Quotes fetched and cached successfully',
                'items' => $quotes,
            ];
            return response()->json($data);
        } else {
            return response()->json(['error' => 'Failed to fetch quotes'], Response::HTTP_INTERNAL_SERVER_ERROR);
        }
    }

    /**
     * Refresh data from the cached API data.
     *
     * @return JsonResponse
     */
    public function refreshData()
    {
        $quotes = $this->quoteManager->driver()->refreshQuotes();

        if ($quotes) {
            $data = [
                'message' => 'New quotes fetched successfully from cache',
                'items' => $quotes,
            ];
            return response()->json($data);
        } else {
            return response()->json(['error' => 'No cached quotes found'], Response::HTTP_NOT_FOUND);
        }
    }
}
