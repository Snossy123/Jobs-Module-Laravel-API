<?php

namespace Modules\Jobs\App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class JobCreationMiddleware
{
    /**
     * Handle an incoming request.
     */
    public function handle(Request $request, Closure $next)
    {
        // Check if the authenticated user has the required type_id
        $allowedTypes = [2, 3];

        if(in_array(Auth::user()->type_id, $allowedTypes)){
            return $next($request);
        }

        return response()->json([
            'success' => false,
            'message' => __("Unauterized Access")
        ], 403); 
    }
}
