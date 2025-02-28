<?php

namespace Modules\Jobs\App\Services;

use Illuminate\Support\Facades\Log; 
use Modules\Jobs\App\Interfaces\JobCompanyTypeRepositoryInterface;

class JobCompanyTypeService
{
    public function __construct(protected JobCompanyTypeRepositoryInterface $jobCompTypeRepoInterface)
    {
    }

    public function all():array
    {
        try {
            $response= $this->jobCompTypeRepoInterface->all();
            return $response;
        } catch (\Exception $e) {
            Log::error($e->getMessage());
            throw new \Exception( $e->getMessage());
        }
    }
}
