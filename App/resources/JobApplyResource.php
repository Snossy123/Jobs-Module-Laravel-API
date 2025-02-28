<?php

namespace Modules\Jobs\App\resources;

use Illuminate\Http\Resources\Json\JsonResource;

class JobApplyResource extends JsonResource
{
    /**
     * Transform the resource into an array.
     */
    public function toArray($request): array
    {
        return [
            'job_id' => $this->medical_job_id,
            'user_id' => $this->user_id,
            'CV' => $this->CV ?? "Unattached CV",
        ];
    }


}
