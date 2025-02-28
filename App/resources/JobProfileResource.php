<?php

namespace Modules\Jobs\App\resources;

use App\Enums\EmploymentType;
use App\Enums\SalaryType;
use App\Models\UserInfo;
use Illuminate\Http\Resources\Json\JsonResource;
use Illuminate\Support\Facades\Auth;
use Modules\Jobs\App\Models\JobApply;

class JobProfileResource extends JsonResource
{
    /**
     * Transform the resource into an array.
     */
    public function toArray($request): array
    {
        return [
            'id' => $this->id,
            'creator' => [
                'id' => $this->user->id ?? null,
                'name' => $this->user->name ?? "Anonymous User",
                'avatar' => $this->getAvatar(),
                'type' => $this->user->type_id===2?'center':($this->user->type_id===3? 'company':'')
            ],
            'job_title' => $this->job_title ?? 'No job title provided',
            'location' => $this->getLocation(),
            'created_from' => $this->created_at->diffForHumans() ?? 'Unknown creation time',
            'tags' => $this->getTags(),
            'job_employment' => $this->getJobEmployment(),
            'description' => $this->description ?? 'No description available',
            'key_responsibilities' => $this->key_responsibilities ?? 'No responsibilities specified',
            'qualifications' => $this->qualifications ?? 'No qualifications listed',
            'work_place_type' => $this->work_place_type ?? 'Workplace type not specified',
            'job_company_industry' => $this->company_industry->name ?? 'Industry not provided',
            'job_company_type' => $this->company_type->name ?? 'Company type not provided',
            'job_seniority_level' => $this->seniority_level->name ?? 'Seniority level not specified',
            'job_role' => $this->job_role ?? 'No job role defined',
            'salary' => $this->getSalary(),
            'vacancies' => $this->vacancies ?? 'No vacancies available',
            'years_experience' => $this->getYearsExperience(),
            'job_status' => $this->is_open ? true : false,
            'applied_job_status' => $this->checkApply(),
            'total_appllications' => $this->getTotalApplied()
        ];
    }

    private function getTotalApplied(): int
    {
        $total = JobApply::where('medical_job_id','=',$this->id)->count();
        return $total ?? 0;
    }
    private function checkApply():bool
    {
        $application = JobApply::where('medical_job_id','=',$this->id, 'and', 'user_id', '=', Auth::id())->first();
        return $application ? true : false;
    }

    private function getAvatar(): string
    {
        $avatar = UserInfo::where('user_id', $this->user->id)
            ->where('attribute_name', 'avatar')
            ->value('value');

        if(!$avatar){
            return match($this->user->type_id){
                1 => 'Not valid user type creator',
                2 => asset('jobs/images/center.png'),
                3 => asset('jobs/images/company.png')
            };
        }
        return $avatar;
    }

    private function getLocation(): array
    {
        return [
            'city' => [
                "id" => $this->city->id??null,
                "name" => $this->city->name??null,
            ],
            'country' => [
                "id" => $this->country->id??null,
                "name" => $this->country->name??null,
            ]
        ];
    }

    private function getTags(): array
    {
        return $this->tags->map(function ($tag) {
            return [
                'id' => $tag->id,
                'name' => $tag->name,
            ];
        })->toArray() ?? ['No tags available'];
    }

    private function getJobEmployment(): array
    {
        return match($this->employment_type->type){
            EmploymentType::WorkingHours->value => [
                'type' => $this->employment_type->type ?? 'Employment type not specified',
                'working_hours' => $this->employment_type->value ?? 'Working hours not defined'
            ],
            default => [
                'type' => $this->employment_type->type ?? 'Employment type not specified',
            ]
        };
    }

    private function getSalary(): array
    {
        return match($this->salary->type){
            SalaryType::Static->value => [
                'type' => $this->salary->type ?? 'Salary type not specified',
                'value' => $this->salary->value ?? 'Salary value not provided'
            ],
            SalaryType::Range->value => [
                'type' => $this->salary->type ?? 'Salary type not specified',
                'from' => $this->salary->from ?? 'Salary range start not defined',
                'to' => $this->salary->to ?? 'Salary range end not defined'
            ],
            SalaryType::Sentence->value => [
                'type' => $this->salary->type ?? 'Salary type not specified',
            ],
        };
    }

    private function getYearsExperience(): array
    {
        return [
            'from' => $this->years_experience_from ?? 'No minimum experience required',
            'to' => $this->years_experience_to ?? null,
        ];
    }
}
