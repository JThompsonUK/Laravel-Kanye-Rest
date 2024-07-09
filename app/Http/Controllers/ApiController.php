<?php

namespace App\Http\Controllers;

use App\Services\ApiManager;
use Symfony\Component\HttpFoundation\Response;

class ApiController extends Controller
{
    protected $apiManager;

    /**
     * ApiController constructor.
     *
     * @param ApiManager $apiManager
     */
    public function __construct(ApiManager $apiManager)
    {
        $this->apiManager = $apiManager;
    }

    /**
     * Get data from the API.
     *
     * @return JsonResponse
     */
    public function getData()
    {
        $quotes = $this->apiManager->driver()->fetchData();

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
        $quotes = $this->apiManager->driver()->refreshData();

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
