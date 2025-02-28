<?php

namespace Modules\Jobs\App\Services;

use Illuminate\Http\Request;
use Illuminate\Pagination\LengthAwarePaginator;
use Illuminate\Support\Facades\Log;
use Modules\Jobs\App\Http\Requests\JobApplyRequest;
use Modules\Jobs\App\Interfaces\JobApplyRepositoryInterface;
use Modules\Jobs\App\resources\JobApplyResource;

class JobApplyService
{
    public function __construct(protected JobApplyRepositoryInterface $jobApplyRepoInterface)
    {
    }

    public function apply(JobApplyRequest $request):JobApplyResource
    {
        try {
            $response= $this->jobApplyRepoInterface->apply($request);
            return $response;
        } catch (\Exception $e) {
            Log::error($e->getMessage());
            throw new \Exception( $e->getMessage());
        }
    }

    public function getApplications(Request $request):LengthAwarePaginator
    {
        try{
            $response = $this->jobApplyRepoInterface->index($request);
            return $response;
        }catch(\Exception $e){
            throw new \Exception($e->getMessage());
        }
    }
    public function checkApply(Request $request):bool
    {
        try{
            $response = $this->jobApplyRepoInterface->checkApply($request);
            return $response;
        }catch(\Exception $e){
            throw new \Exception($e->getMessage());
        }
    }
}
