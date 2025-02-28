<?php

namespace Modules\Jobs\App\Http\Controllers;


use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Modules\Jobs\App\Http\Requests\JobListingRequest;
use Modules\Jobs\App\Services\JobService;
use Modules\Jobs\App\Http\Requests\JobRequest;
use Modules\Jobs\App\Http\Requests\JobSearchRequest;
use Modules\Jobs\App\Http\Requests\JobStatusRequest;

class JobController extends Controller
{
    private const SERVER_ERROR = "server_error";
    private const OK = "ok";
    private const CREATED = "created";
    public function __construct(protected JobService $jobService)
    {
    }

    public function index(JobListingRequest $request)
    {
        try {
            $result = $this->jobService->index($request);
            return apiResponse(true, $result['message'], $result["data"], self::OK);
        } catch (\Exception $e) {
            return apiResponse(false, $e->getMessage(), null, self::SERVER_ERROR);
        }
    }

    public function show(Request $request)
    {
        try{
            $result = $this->jobService->show($request);
            return apiResponse(true, $result['message'], $result['data'], self::OK);
        }catch (\Exception $e){
            return apiResponse(false, $e->getMessage(), null, self::SERVER_ERROR);
        }
    }
    public function store(JobRequest $request)
    {
        try {
            $result = $this->jobService->store($request);
            return apiResponse(true,$result['message'], $result['data'], self::CREATED);
        } catch (\Exception $e) {
            return apiResponse(false,$e->getMessage(), null, self::SERVER_ERROR);
        }

    }

    public function search(JobSearchRequest $request)
    {
        try{
            $result = $this->jobService->search($request);
            return apiResponse(true, $result['message'], $result['data'], self::OK);
        }catch (\Exception $e){
            return apiResponse(false,$e->getMessage(), null, self::SERVER_ERROR);
        }
    }

    public function edit(JobRequest $request)
    {
        try{
            $result = $this->jobService->edit($request);
            return apiResponse(true, $result['message'], $result['data'], self::OK);
        }catch (\Exception $e){
            return apiResponse(false,$e->getMessage(), null, self::SERVER_ERROR);
        }
    }

    public function destroy(Request $request)
    {
        try{
            $result = $this->jobService->destroy($request);
            return apiResponse(true, $result['message'], $result['data'], self::OK);
        }catch (\Exception $e){
            return apiResponse(false,$e->getMessage(), null, self::SERVER_ERROR);
        }
    }

    public function changeStatus(JobStatusRequest $request)
    {
        try{
            $result = $this->jobService->changeStatus($request);
            return apiResponse(true, $result['message'], $result['data'], self::OK);
        }catch (\Exception $e){
            return apiResponse(false,$e->getMessage(), null, self::SERVER_ERROR);
        }
    }
}
