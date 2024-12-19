<?php

namespace App\Http\Middleware;

use App\Formatters\ResponseFormatter;
use App\Models\ApiKey;
use Closure;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Cache;
use Illuminate\Support\Facades\Crypt;
use Illuminate\Support\Facades\Log;
use Symfony\Component\HttpFoundation\Response;

class EnsureApiKeyIsValid
{
    /**
     * Handle an incoming request.
     *
     * @param  \Closure(\Illuminate\Http\Request): (\Symfony\Component\HttpFoundation\Response)  $next
     */
    public function handle(Request $request, Closure $next): Response
    {
        Log::info("Api accessed", [
            'ip_address' => $request->ip(),
            'endpoint' => $request->path(),
        ]);

        if($request->header('X-API-KEY')) {
            $apiKeyFromRequest = $request->header('X-API-KEY');
            $apiKeys = Cache::remember('api_keys', now()->addSecond(60), function () use ($apiKeyFromRequest) {
                return ApiKey::all();
            });
            foreach ($apiKeys as $key) {
                if($key->token == $apiKeyFromRequest) {
                    $request->merge([
                        'api_key_id' => $key->id,
                        'app_name' => $key->name
                    ]);
                    Log::info("Api key is valid", [
                        'ip_address' => $request->ip(),
                        'endpoint' => $request->path(),
                        'api_key' => $key->id
                    ]);
                    return $next($request);
                }
            }
        }
        Log::warning("Api key is invalid", [
            'ip_address' => $request->ip(),
            'endpoint' => $request->path(),
            'api_key' => $request->header('X-API-KEY')
        ]);
        return ResponseFormatter::error(null, 'Invalid API Key', 401);
    }
}
