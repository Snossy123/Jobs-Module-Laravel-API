<?php

namespace Modules\Jobs\App\Repositories;

use Modules\Jobs\App\Interfaces\JobSeniorityLevelRepositoryInterface;
use Modules\Jobs\App\Models\JobSeniorityLevel;

class JobSeniorityLevelRepository implements JobSeniorityLevelRepositoryInterface
{

    public function __construct(protected JobSeniorityLevel $jobSenLevelModel)
    {
    }

    public function all():array
    {
        return $this->jobSenLevelModel::all()->toArray();
    }
}
