<?php

namespace Modules\Jobs\App\Http\Controllers;

use App\Http\Controllers\Controller;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
use Modules\Jobs\App\Http\Requests\JobApplyRequest;
use Modules\Jobs\App\Services\JobApplyService;

class JobApplyController extends Controller
{
    public function __construct(protected JobApplyService $jobApplyService){
    }
    public function apply(JobApplyRequest $request): JsonResponse
    {
        try{
            $result = $this->jobApplyService->apply($request);
            return response()->json(['success'=> true, 'message'=> 'Successfully Applying on Job', 'data'=>$result], 200);
        }catch(\Exception $e){
            return response()->json(['error' => $e->getMessage()], 500);
        }
    }

    public function getApplications(Request $request):JsonResponse
    {
        try{
            $request->validate(['job_id' => 'required|integer|exists:medical_jobs,id']);
            $result = $this->jobApplyService->getApplications($request);
            return response()->json(['success'=>true, 'message'=> 'Successfully Fetching job applications', 'data'=>$result], 200);
        }catch(\Exception $e){
            return response()->json(['error'=>$e->getMessage()], 500);
        }
    }
}
