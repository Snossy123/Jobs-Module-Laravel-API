<?php

namespace Modules\Jobs\App\Http\Middleware;

use Algolia\ScoutExtended\Exceptions\ModelNotFoundException;
use Closure;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Modules\Jobs\App\Models\Job;
use Modules\Jobs\App\Models\JobApply;

class JobApplyMiddleware
{
    /**
     * Handle an incoming request.
     */
    public function handle(Request $request, Closure $next)
    {
        $request->validate([
            'job_id' => 'required|integer|exists:medical_jobs,id'
        ]);
        try {
            $job = Job::findOrFail($request->job_id);
            if (!$job->is_open) {
                return response()->json(['error' => 'Job is not open for apply'], 403);
            }
            if ($this->checkApply($request->job_id)) {
                return response()->json(['error' => 'User has already applied to this job'], 403);
            }
            return $next($request);
        } catch (ModelNotFoundException $e) {
            return response()->json(['error' => 'Job not found'], 404);
        }
    }

    public function checkApply(int $job_id):bool
    {
        $application = JobApply::where('medical_job_id','=',$job_id, 'and', 'user_id', '=', Auth::id())->first();
        return $application ? true : false;
    }
}
