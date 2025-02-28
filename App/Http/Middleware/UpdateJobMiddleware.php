<?php

namespace Modules\Jobs\App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Modules\Jobs\App\Models\JobApply;

class updateJobMiddleware
{
    /**
     * Handle an incoming request.
     */
    public function handle(Request $request, Closure $next)
    {
        $applications = JobApply::where('medical_job_id', $request->id)->first();
        if(!$applications)
            return $next($request);
        return response()->json([
            'error' => true,
            'message' => 'You are not able to update this job because it has applications on it. Please Create a new Job.',
        ], 403);
    }
}
