<?php

namespace Modules\Jobs\App\Repositories;

use Modules\Jobs\App\Interfaces\TagRepositoryInterface;
use Modules\Jobs\App\Models\Tag;

class TagRepository implements TagRepositoryInterface
{

    public function __construct(protected Tag $tagModel)
    {
    }

    public function all():array
    {
        return $this->tagModel::all()->toArray();
    }
}
