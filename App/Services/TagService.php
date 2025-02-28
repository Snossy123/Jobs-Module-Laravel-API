<?php

namespace Modules\Jobs\App\Services;

use Illuminate\Support\Facades\Log;
use Modules\Jobs\App\Interfaces\TagRepositoryInterface;

class TagService
{
    public function __construct(protected TagRepositoryInterface $tagRepoInterface)
    {
    }

    public function all():array
    {
        try {
            $response= $this->tagRepoInterface->all();
            return $response;
        } catch (\Exception $e) {
            Log::error($e->getMessage());
            throw new \Exception( $e->getMessage());
        }
    }
}
