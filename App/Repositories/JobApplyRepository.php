<?php

namespace Modules\Jobs\App\Repositories;

use Illuminate\Http\Request;
use Illuminate\Pagination\LengthAwarePaginator;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Log;
use Modules\Jobs\App\Http\Requests\JobApplyRequest;
use Modules\Jobs\App\Interfaces\JobApplyRepositoryInterface;
use Modules\Jobs\App\Models\JobApply;
use Modules\Jobs\App\resources\JobApplyResource;

class JobApplyRepository implements JobApplyRepositoryInterface
{

    public function __construct(protected JobApply $jobApplyModel)
    {
    }

    public function apply(JobApplyRequest $request): JobApplyResource
    {
        $cvUrl = $this->handleCvUpload($request);

        $data = [
            'medical_job_id' => (int) $request->job_id,
            'user_id' => Auth::id(),
            'CV' => $cvUrl,
        ];

        try {
            $jobApply = $this->jobApplyModel->create($data);
            return new JobApplyResource($jobApply);
        } catch (\Exception $e) {
            Log::error(
                "Error while creating job application: {$e->getMessage()}",
                ['request' => $request->all(), 'trace' => $e->getTraceAsString()]
            );
            throw new \Exception('An unexpected error occurred. Please contact the support administrator.');
        }
    }

    private function handleCvUpload(JobApplyRequest $request): ?string
    {
        if ($request->hasFile('cv')) {
            return asset('jobs/CVs/' . upload($request, "cv", 'jobs/CVs'));
        }
        return null;
    }


    public function index(Request $request):LengthAwarePaginator
    {
        try{
            $applications = $this->jobApplyModel::where('medical_job_id','=',$request->job_id)->paginate(10);
            $applications->setCollection(collect(JobApplyResource::collection($applications)));
            return $applications;
        }catch(\Exception $e){
            Log::error(
                "Unexpected error while fetch job applications: {$e->getMessage()}",
                ['request' => $request->all()]
            );
            throw new \Exception('An unexpected error occurred, please contact your support administrator');
        }
    }
}
