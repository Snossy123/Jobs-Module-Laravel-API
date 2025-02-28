<?php

namespace Modules\Jobs\App\Interfaces;

use Illuminate\Http\Request;
use Illuminate\Pagination\LengthAwarePaginator;
use Modules\Jobs\App\Http\Requests\JobApplyRequest;
use Modules\Jobs\App\resources\JobApplyResource;

interface JobApplyRepositoryInterface
{
    public function apply(JobApplyRequest $request):JobApplyResource;
    public function index(Request $request):LengthAwarePaginator;
}
