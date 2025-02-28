<?php

namespace Modules\Jobs\App\Interfaces;

use Illuminate\Contracts\Pagination\LengthAwarePaginator;
use Modules\Jobs\App\Http\Requests\JobListingRequest;
use Modules\Jobs\App\Http\Requests\JobRequest;
use Modules\Jobs\App\Http\Requests\JobSearchRequest;
use Modules\Jobs\App\Http\Requests\JobStatusRequest;
use Modules\Jobs\App\resources\JobProfileResource;
use Modules\Jobs\App\resources\JobResource;
use Modules\Jobs\App\resources\JobStatusResource;

interface JobRepositoryInterface
{
    public function index(JobListingRequest $request):LengthAwarePaginator;
    public function create(JobRequest $request):JobResource;
    public function checkCityInCountry(int $countryId, int $cityId):void;
    public function search(JobSearchRequest $request):LengthAwarePaginator;
    public function find(int $id):JobProfileResource;
    public function update(JobRequest $request):JobProfileResource;
    public function delete(int $id):JobProfileResource;
    public function updateStatus(JobStatusRequest $request):JobStatusResource;
}
