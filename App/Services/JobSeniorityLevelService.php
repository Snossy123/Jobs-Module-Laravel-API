<?php

namespace Modules\Jobs\App\Services;

use Illuminate\Support\Facades\Log;
use Modules\Jobs\App\Interfaces\JobSeniorityLevelRepositoryInterface;

class JobSeniorityLevelService
{
    public function __construct(protected JobSeniorityLevelRepositoryInterface $jobSenLevelRepoInterface)
    {
    }

    public function all():array
    {
        try {
            $response= $this->jobSenLevelRepoInterface->all();
            return $response;
        } catch (\Exception $e) {
            Log::error($e->getMessage());
            throw new \Exception( $e->getMessage());
        }
    }
}
