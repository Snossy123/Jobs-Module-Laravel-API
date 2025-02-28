<?php

namespace Modules\Jobs\App\Services;

use Illuminate\Support\Facades\Log;
use Modules\Jobs\App\Interfaces\JobEmploymentTypeRepositoryInterface;

class JobEmploymentTypeService
{
    public function __construct(protected JobEmploymentTypeRepositoryInterface $jobEmployTypeRepoInterface)
    {
    }

    public function all():array
    {
        try {
            $response= $this->jobEmployTypeRepoInterface->all();
            return $response;
        } catch (\Exception $e) {
            Log::error($e->getMessage());
            throw new \Exception( $e->getMessage());
        }
    }
}
