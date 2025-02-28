<?php

namespace Modules\Jobs\App\Http\Controllers;

use App\Http\Controllers\Controller;
use Illuminate\Http\JsonResponse;
use Illuminate\Support\Facades\Log;
use Modules\Jobs\App\Services\JobCompanyIndustryService;

class JobCompanyIndustryController extends Controller
{
    public function __construct(protected JobCompanyIndustryService $jobCompanyIndustryService)
    {
    }
    public function getCompanyIndustries():JsonResponse
    {
        try {
            $response = $this->jobCompanyIndustryService->all();
            return response()->json($response, 200);
        } catch (\Exception $e) {
            Log::error($e->getMessage());
            return response()->json(['message' => 'Internal Server Error'], 500);
        }
    }
}
