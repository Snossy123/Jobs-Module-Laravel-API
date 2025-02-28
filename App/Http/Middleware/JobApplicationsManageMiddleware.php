<?php

namespace Modules\Jobs\App\Http\Middleware;

use Closure;
use Illuminate\Database\Eloquent\ModelNotFoundException;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Modules\Jobs\App\Models\Job;

class JobApplicationsManageMiddleware
{
    /**
     * Handle an incoming request.
     */
    public function handle(Request $request, Closure $next)
    {
        $request->validate(['job_id'=>'required|integer|exists:medical_jobs,id']);
        try {
            $job = Job::findOrFail($request->job_id);
            if ($job->created_by_user_id !== Auth::id()) {
                return response()->json([
                    'success' => false,
                    'message' => __("Unauthorized Access"),
                ], 403);
            }

            return $next($request);
        } catch (ModelNotFoundException $e) {
            return response()->json([
                'success' => false,
                'message' => 'Job not found Please enter valid job identifier',
            ], 404);
        }
    }
}
