<?php

namespace Modules\Jobs\App\Http\Controllers;

use App\Http\Controllers\Controller;
use Illuminate\Http\JsonResponse;
use Illuminate\Support\Facades\Log;
use Modules\Jobs\App\Services\JobCompanyTypeService;

class JobCompanyTypeController extends Controller
{
    public function __construct(protected JobCompanyTypeService $jobCompTypeService)
    {
    }
    public function getCompanyTypes():JsonResponse
    {
        try {
            $response = $this->jobCompTypeService->all();
            return response()->json($response, 200);
        } catch (\Exception $e) {
            Log::error($e->getMessage());
            return response()->json(['message' => 'Internal Server Error'], 500);
        }
    }
}
