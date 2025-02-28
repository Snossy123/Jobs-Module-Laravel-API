<?php

namespace Modules\Jobs\App\Repositories;

use Modules\Jobs\App\Interfaces\JobCompanyIndustryRepositoryInterface;
use Modules\Jobs\App\Models\JobCompanyIndustry;

class JobCompanyIndustryRepository implements JobCompanyIndustryRepositoryInterface
{

    public function __construct(protected JobCompanyIndustry $jobCompanyIndustryModel)
    {
    }

    public function all():array
    {
        return $this->jobCompanyIndustryModel::all()->toArray();
    }
}
