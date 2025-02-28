<?php

namespace Modules\Jobs\App\Repositories;

use Modules\Jobs\App\Interfaces\JobEmploymentTypeRepositoryInterface;
use Modules\Jobs\App\Models\JobEmploymentType;

class JobEmploymentTypeRepository implements JobEmploymentTypeRepositoryInterface
{

    public function __construct(protected JobEmploymentType $jobEmpTypeModel)
    {
    }

    public function all():array
    {
        return $this->jobEmpTypeModel::query()->select('id','type')->limit(9)->get()->toArray();
    }
}
