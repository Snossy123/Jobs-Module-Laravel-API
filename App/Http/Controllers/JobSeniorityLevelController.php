<?php

namespace Modules\Jobs\App\Http\Controllers;

use App\Http\Controllers\Controller;
use Illuminate\Http\JsonResponse;
use Illuminate\Support\Facades\Log;
use Modules\Jobs\App\Services\JobSeniorityLevelService;

class JobSeniorityLevelController extends Controller
{
    public function __construct(protected JobSeniorityLevelService $jobSenLevelService)
    {
    }
    public function getSeniorityLevels():JsonResponse
    {
        try {
            $response = $this->jobSenLevelService->all();
            return response()->json($response, 200);
        } catch (\Exception $e) {
            Log::error($e->getMessage());
            return response()->json(['message' => 'Internal Server Error'], 500);
        }
    }
}
