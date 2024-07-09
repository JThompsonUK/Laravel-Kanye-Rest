<?php

namespace App\Http\Controllers;

use App\Http\Requests\AccessTokenRequest;
use App\Models\AccessToken;
use App\Models\APIApp;
use App\Models\App;
use Carbon\Carbon;
use Illuminate\Http\Request;
use Symfony\Component\HttpFoundation\Response;

class TokenController extends Controller
{
    /**
     * Generates access token for provided app ID
     * This would be better in a new middleware?
     *
     * @param AccessTokenRequest $request
     * @return void
     */
    public function getToken(Request $request)
    {
        $app_id = $request->input('app_id');
        $apiApp = APIApp::where('app_access_id', $app_id)->first();

        if (!$apiApp) {
            return response()->json(['error' => 'App ID not found'], Response::HTTP_NOT_FOUND);
        }

        // Check if there is a valid access token associated with the APIApp
        $validToken = $apiApp->accessToken()
            ->where('expiration_date', '>', Carbon::now())
            ->first();

        if ($validToken) {
            return response()->json([
                'access_token' => $validToken->access_token,
            ]);
        }

        return response()->json(['error' => 'No valid access token found'], Response::HTTP_NOT_FOUND);
    }
}
