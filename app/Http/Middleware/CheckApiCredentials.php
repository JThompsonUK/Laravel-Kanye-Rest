<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use App\Models\APIApp;
use App\Models\AccessToken;
use Carbon\Carbon;
use Symfony\Component\HttpFoundation\Response;

class CheckApiCredentials
{
    /**
     * Handle an incoming request.
     *
     * @param \Illuminate\Http\Request $request
     * @param \Closure $next
     * @return mixed
     */
    public function handle(Request $request, Closure $next)
    {
        $appId = $request->header('app-id');
        $accessToken = $request->header('Authorization');

        if (!$appId || !$accessToken) {
            return response()->json([
                'error' => 'Invalid API credentials',
            ], Response::HTTP_UNAUTHORIZED);
        }

        $apiApp = APIApp::where('app_access_id', $appId)->first();
        if (!$apiApp) {
            return response()->json([
                'error' => 'No App ID found',
            ], Response::HTTP_UNAUTHORIZED);
        }

        $accessToken = AccessToken::where('access_token', $accessToken)
            ->where('app_id', $apiApp->id)
            ->where('expiration_date', '>', Carbon::now())
            ->first();

        if (!$accessToken) {
            return response()->json([
                'error' => 'Invalid Access Token',
            ], Response::HTTP_UNAUTHORIZED);
        }

        return $next($request);
    }
}
