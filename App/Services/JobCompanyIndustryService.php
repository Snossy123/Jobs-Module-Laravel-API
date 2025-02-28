<?php

namespace Modules\Jobs\App\Services;

use Illuminate\Support\Facades\Log;
use Modules\Jobs\App\Interfaces\JobCompanyIndustryRepositoryInterface;



class JobCompanyIndustryService
{
    public function __construct(protected JobCompanyIndustryRepositoryInterface $jobCompIndRepoInterface)
    {
    }

    public function all():array
    {
        try {
            $response= $this->jobCompIndRepoInterface->all();
            return $response;
        } catch (\Exception $e) {
            Log::error($e->getMessage());
            throw new \Exception( $e->getMessage());
        }
    }
}
