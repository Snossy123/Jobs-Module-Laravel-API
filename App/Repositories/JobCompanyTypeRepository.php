<?php

namespace Modules\Jobs\App\Repositories;

use Modules\Jobs\App\Interfaces\JobCompanyTypeRepositoryInterface;
use Modules\Jobs\App\Models\JobCompanyType;

class JobCompanyTypeRepository implements JobCompanyTypeRepositoryInterface
{

    public function __construct(protected JobCompanyType $jobCompanyTypeModel)
    {
    }

    public function all():array
    {
        return $this->jobCompanyTypeModel::all()->toArray();
    }
}
