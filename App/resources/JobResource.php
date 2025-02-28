<?php

namespace Modules\Jobs\App\resources;

use Illuminate\Http\Resources\Json\JsonResource;

class JobResource extends JsonResource
{
    /**
     * Transform the resource into an array.
     */
    public function toArray($request): array
    {
        return [
            'id' => $this->id,
            'job_title' => $this->job_title ?? null,
        ];
    }


}
