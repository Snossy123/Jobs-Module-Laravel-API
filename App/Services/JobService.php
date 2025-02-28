<?php

namespace Modules\Jobs\App\Services;

use Illuminate\Http\Request;
use Illuminate\Validation\Rule;
use Modules\Jobs\App\Http\Requests\JobListingRequest;
use Modules\Jobs\App\Http\Requests\JobRequest;
use Modules\Jobs\App\Http\Requests\JobSearchRequest;
use Modules\Jobs\App\Http\Requests\JobStatusRequest;
use Modules\Jobs\App\Interfaces\JobRepositoryInterface;



class JobService
{
    public function __construct(protected JobRepositoryInterface $jobRepository)
    {
    }

    public function index(JobListingRequest $request): array
    {

        try {
            $result = $this->jobRepository->index($request);
            return [
                'error' => false,
                'message' => 'jobs fetched successfully',
                'data' => $result,
            ];
        } catch (\Exception $e) {
            throw new \Exception($e->getMessage());
        }
    }

    public function show(Request $request):array
    {
        $request->validate([
            'id' => 'required|integer|exists:medical_jobs,id',
        ]);
        try {
            $result = $this->jobRepository->find($request->id);
            return [
                'error' => false,
                'message' => 'job fetched successfully',
                'data' => $result,
            ];
        } catch (\Exception $e) {
            throw new \Exception($e->getMessage());
        }
    }

    public function store(JobRequest $request): array
    {
        try {
            $this->jobRepository->checkCityInCountry($request->country_id, $request->city_id);
            $result = $this->jobRepository->create($request);
            return [
                'error' => false,
                'message' => 'Job created successfully',
                'data' => $result
            ];
        } catch (\Exception $e) {
            throw new \Exception($e->getMessage());
        }
    }

    public function search(JobSearchRequest $request): array
    {
        try{
            if(isset($request->job_location['city_id'], $request->job_location['country_id']))
                $this->jobRepository->checkCityInCountry($request->job_location['country_id'], $request->job_location['city_id']);

            $result = $this->jobRepository->search($request);
            return [
              "error" => false,
              "message" => "Job search retrieved successfully",
              "data" => $result,
            ];
        }catch(\Exception $e){
            throw new \Exception($e->getMessage());
        }
    }

    public function edit(Request $request): array
    {
        try{
            if(isset($request->job_location['city_id'], $request->job_location['country_id']))
                $this->jobRepository->checkCityInCountry($request->job_location['country_id'], $request->job_location['city_id']);

            $result = $this->jobRepository->update($request);
            return [
              "error" => false,
              "message" => "Job updated successfully",
              "data" => $result,
            ];
        }catch(\Exception $e){
            throw new \Exception($e->getMessage());
        }
    }

    public function destroy(Request $request): array
    {
        try{
            $request->validate([
                'id' => ['required','integer', Rule::exists('medical_jobs','id')],
            ]);
            $result = $this->jobRepository->delete( $request->id);
            return [
                'error' => false,
                'message' => "Job deleted successfully",
                'data' => $result
            ];
        }catch(\Exception $e){
            throw new \Exception($e->getMessage());
        }
    }

    public function changeStatus(JobStatusRequest $request): array
    {
        try{
            $result = $this->jobRepository->updateStatus($request);
            return [
              "error" => false,
              "message" => "Job status updated successfully",
              "data" => $result,
            ];
        }catch(\Exception $e){
            throw new \Exception($e->getMessage());
        }
    }
}
