<?php

namespace App\Http\Middleware;

use App\Models\User;
use Closure;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class ApiKeyMiddleware
{
    /**
     * Handle an incoming request.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \Closure(\Illuminate\Http\Request): (\Illuminate\Http\Response|\Illuminate\Http\RedirectResponse)  $next
     * @return \Illuminate\Http\Response|\Illuminate\Http\RedirectResponse
     */
    public function handle(Request $request, Closure $next)
    {
        $apiKey     = $request->header('X-API-KEY');

        if (!$apiKey) {
            return response()->json([
                'message' => 'Unauthorized: API key missing'
            ], 401);
        }


        if ($apiKey !== config('services.api.key')) {
            return response()->json([
                'message' => 'Unauthorized: Invalid API key'
            ], 403);
        }

        return $next($request);
    }
}
