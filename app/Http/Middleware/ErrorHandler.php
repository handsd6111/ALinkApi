<?php

namespace App\Http\Middleware;

use Closure;
use Exception;
use Illuminate\Support\Facades\Log;
use App\Helpers\ApiFeature;
use App\Models\Interfaces\IStatusCode;

class ErrorHandler
{
    /**
     * Get the path the user should be redirected to when they are not authenticated.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return string|null
     */
    public function handle($request, Closure $next)
    {
        try {
            $response = $next($request);
        } catch (Exception $ex) {
            Log::error($ex); // 錯誤記錄起來
            return ApiFeature::sendResponse(null, IStatusCode::INTERNAL_SERVER_ERROR);
        }
        return $response;
    }
}
