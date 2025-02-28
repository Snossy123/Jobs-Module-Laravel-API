<?php

namespace Modules\Jobs\App\Http\Requests;

use App\Enums\EmploymentType;
use App\Enums\SalaryType;
use App\Enums\WorkPlaceType;
use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Validation\Rule;

class JobRequest extends FormRequest
{
    /**
     * Get the validation rules that apply to the request.
     */
    public function rules(): array
    {
        return [
            'job_title' => 'required|string|min:3|max:100|regex:/^[A-Za-z0-9\s,.-()&]+$/',
            'job_role' => 'required|string|min:3|max:100|regex:/^[A-Za-z0-9\s,.-()&]+$/',
            'tags' => 'nullable|array|min:0|max:5',
            'tags.*' => 'string|min:3|max:50|regex:/^[A-Z]+(-[A-Z]+)*$/',
            'salary' => ['required', 'array', $this->validateSalary()],
            'vacancies' => 'required|integer|min:1|max:45',
            'years_experience_from' => 'required|integer|min:0|max:50',
            'years_experience_to' => 'nullable|integer|gte:years_experience_from|max:50',
            'work_place_type' => ['required', Rule::in(array_column(WorkPlaceType::cases(), 'value'))],
            'description' => 'required|string|min:50|max:2000|regex:/^[A-Za-z0-9\s,.-()&]+$/',
            'key_responsibilities' => 'required|string|min:50|max:2000|regex:/^[A-Za-z0-9\s,.-()&]+$/',
            'qualifications' => 'required|string|min:50|max:2000|regex:/^[A-Za-z0-9\s,.-()&]+$/',
            'city_id' => 'required|integer|exists:cities,id',
            'country_id' => 'required|integer|exists:countries,id',
            'job_company_industry' => 'required|string|max:255',
            'job_company_type' => 'required|string|max:255',
            'job_seniority_level' => 'required|string|max:255',
            'job_employment' => ['required', 'array', $this->employment_check()]
        ];
    }

    public function messages():array
    {
        return [
            'job_title.required' => 'The job title is required.',
            'job_role.required' => 'The job role is required.',
            'tags.max' => 'Max number of tags is 5 tags.',
            'salary.required' => 'Please provide a salary or choose ' . SalaryType::Sentence->value,
            'vacancies.required' => 'Please provide number of vacancies.',
            'years_experience_to.gte' => 'The "to" experience must be greater than or equal to the "from" experience.',
            'work_place_type.required' => 'Please choose valid work place type.',
            'city_id.exists' => 'The selected city does not exist in our records.',
            'country_id.exists' => 'The selected country does not exist in our records.',
            'job_company_industry.required' => 'The job company industry required.',
            'job_company_type.required' => 'The job company type required.',
            'job_seniority_level.required' => 'The job seniority level required.',
            'job_employment.required' => 'The job employment type required, if choose working hours please enter a number of hours.',
            'job_employment.type.in' => "The selected employment type is invalid. Valid types are: " . implode(', ', array_column(EmploymentType::cases(), 'value')),
            'created_by_user_id.exists' => 'The user ID must exist.'
        ];
    }

    /**
     * Determine if the user is authorized to make this request.
     */
    public function authorize(): bool
    {
        return true;
    }

    protected function prepareForValidation():void
    {
        if($this->has('tags')){
            $this->merge(['tags'=> $this->prepareTags($this->tags)]);
        }
    }


    private function prepareTags(array $tags):array
    {
        $tags = array_map(fn($tag)=>strtoupper(trim($tag)), $tags);
        if(count($tags) !== count(array_unique($tags))){
            throw new \Exception("Invalid tags provided redundant");
        }
        return $tags;
    }

    private function validateSalary()
    {
        return function ($attribute, $value, $fail) {
            $allowedValues = array_column(SalaryType::cases(), 'value');
            // validate salary type
            if(!isset($value['type']) || !in_array($value['type'], $allowedValues)) {
                return $fail("The {$attribute}.type must be one of the following: " . implode(',', $allowedValues));
            }
            // Validate based on type
            switch ($value['type']){
                case SalaryType::Static->value:
                    $cleanValue = isset($value['value']) ? str_replace(',', '', $value['value']) : null;

                    if (!is_numeric($cleanValue) || $cleanValue <= 1000 || $cleanValue > 100000) {
                        return $fail("The {$attribute}.type must be a decimal between 1,000 and 100,000.");
                    }
                    break;
                case SalaryType::Range->value:
                    $cleanFrom = isset($value['from']) ? str_replace(',', '', $value['from']) : null;
                    $cleanTo = isset($value['to']) ? str_replace(',', '', $value['to']) : null;
                    if (!is_numeric($cleanFrom) || $cleanFrom <= 1000 || $cleanFrom > 100_000) {
                        return $fail("The {$attribute}.from must be a decimal between 0 and 1,000,000");
                    }
                    if (!is_numeric($cleanTo) || $cleanTo < $cleanFrom || $cleanTo > 100_000) {
                        return $fail("The {$attribute}.to must be a decimal greater than or equal to {$cleanFrom} and less than or equal to 1,000,000.");
                    }
                    break;
                case SalaryType::Sentence->value:
                    break;
                default:
                    return $fail("The {$attribute}.type is invalid.");
            }
        };
    }

    public function employment_check(): callable
    {
        return function ($attribute, $value, $fail) {
            $allowedKeys = ["type", "working_hours"];

            if (array_diff(array_keys($value), $allowedKeys)) {
                return $fail("The {$attribute} attribute contains invalid keys. Only 'type' and 'working_hours' .");
            }
            if (count(array_keys($value)) > 2) {
                return $fail("The {$attribute} attribute must only contain 'type' and/or 'working_hours' .");
            }

            if (isset($value['type'])) {
                $employmentType = array_column(EmploymentType::cases(), 'value');
                if (!is_string($value['type'])) {
                    return $fail("The {$attribute} attribute 'type' must be a string.");
                }
                if (!in_array($value['type'], $employmentType)) {
                    return $fail("The {$attribute} attribute type must only contain: " . implode(',', $employmentType));
                }
                if ($value['type'] === EmploymentType::WorkingHours->value) {
                    if (!isset($value['working_hours'])) {
                        return $fail("The {$attribute} attribute 'working_hours' required if type is " . EmploymentType::WorkingHours->value);
                    }
                    if (!is_numeric($value['working_hours'])) {
                        return $fail("The {$attribute} attribute 'working_hours' must be a numeric value.");
                    }
                    if (isset($value['working_hours']) && ($value['working_hours'] > 168 || $value['working_hours'] < 1)) {
                        return $fail("The {$attribute} attribute 'working_hours' must be between 1 and 168.");
                    }

                }
            }
        };
    }
}
