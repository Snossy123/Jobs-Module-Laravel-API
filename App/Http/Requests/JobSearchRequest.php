<?php

namespace Modules\Jobs\App\Http\Requests;

use App\Enums\EmploymentType;
use App\Enums\WorkPlaceType;
use Illuminate\Foundation\Http\FormRequest;

class JobSearchRequest extends FormRequest
{
    /**
     * Get the validation rules that apply to the request.
     */
    public function rules(): array
    {

        return [
            'job_title' => 'nullable|string|min:3|max:255',
            'job_location' => ['nullable', $this->check_location()],
            'job_location.city_id' => 'nullable|integer|min:1|exists:cities,id',
            'job_location.country_id' => 'nullable|integer|min:1|exists:countries,id',
            'job_employment' => ['nullable', 'array', $this->employment_check()],
            'years_experience' => ['nullable', 'array', $this->years_of_experience_check()],
            'job_company_industry' => 'nullable|array',
            'job_company_industry.*' => 'nullable|integer|exists:job_company_industries,id',
            'job_seniority_level' => 'nullable|array',
            'job_seniority_level.*' => 'nullable|integer|exists:job_seniority_levels,id',
            'work_place_type' => ['nullable', 'array', $this->work_place_check()],
            'paginate' => ["nullable","integer","between:1,100"],
            'sort' => ["nullable","string","in:ascending,descending"]
        ];
    }

    protected function prepareForValidation():void
    {
        if($this->has('job_location') && is_string($this->job_location)){
            $this->merge(['job_location'=> json_decode($this->job_location, true)??null]);
        }
        if($this->has('job_employment') && is_string($this->job_employment)){
            $this->merge(['job_employment'=> json_decode($this->job_employment)??null]);
        }
        if($this->has('years_experience') && is_string($this->years_experience)){
            $this->merge(['years_experience'=> json_decode($this->years_experience, true)??null]);
        }
        if($this->has('job_company_industry') && is_string($this->job_company_industry)){
            $this->merge(['job_company_industry'=> json_decode($this->job_company_industry)??null]);
        }
        if($this->has('job_seniority_level') && is_string($this->job_seniority_level)){
            $this->merge(['job_seniority_level'=> json_decode($this->job_seniority_level)??null]);
        }
        if($this->has('work_place_type') && is_string($this->work_place_type)){
            $this->merge(['work_place_type'=> json_decode($this->work_place_type)??null]);
        }
    }

    /**
     * Determine if the user is authorized to make this request.
     */
    public function authorize(): bool
    {
        return true;
    }

    public function messages()
    {
        return [
            "work_place_type.in" => "The selected work place type is invalid. Valid types are: " . implode(', ', array_column(WorkPlaceType::cases(), 'value'))
        ];
    }

    private function check_location(){
        return function ($attribute, $value, $fail) {
            if(!isset($value['country_id']) && !isset($value['city_id'])){
                return $fail('The ' . $attribute . ' field must contain only one/both country_id and city_id.');
            }
        };
    }

    private function years_of_experience_check(): callable
    {
        return function ($attribute, $value, $fail) {
            $allowedKeys = ["from", "to"];
            $keys = array_keys($value);

            // Validate allowed keys and count
            if (array_diff($keys, $allowedKeys)) {
                return $fail("The {$attribute} attribute contains invalid keys. Only 'from' and 'to' are allowed.");
            }

            if (count($keys) > 2) {
                return $fail("The {$attribute} attribute must only contain 'from' and/or 'to'.");
            }

            // Validate numeric values
            foreach ($allowedKeys as $key) {
                if (isset($value[$key]) && !is_numeric($value[$key])) {
                    return $fail("The {$attribute} attribute '{$key}' must be a numeric value.");
                }
            }

            // Validate ranges
            if (isset($value['from']) && ($value['from'] < 0 || $value['from'] > 50)) {
                return $fail("The {$attribute} attribute 'from' must be between 0 and 50.");
            }

            if (isset($value['to']) && ($value['to'] < 0 || $value['to'] > 50)) {
                return $fail("The {$attribute} attribute 'to' must be between 0 and 50.");
            }

            // Validate logical relationship between 'from' and 'to'
            if (isset($value['from'], $value['to']) && $value['from'] > $value['to']) {
                return $fail("The {$attribute} attribute 'to' must be greater than or equal to 'from'.");
            }
        };
    }

    public function employment_check():callable
    {
        return function ($attribute, $value, $fail) {
            $employmentType = array_column(EmploymentType::cases(), 'value');
            foreach ($value as $type){
                if(!in_array($type, $employmentType)){
                    return $fail("The {$attribute} attribute must be array of one or more of the following: " . implode(',',$employmentType));
                }
                if(!is_string($type)){
                    return $fail("The {$attribute} attribute must be a array of string.");
                }
            }
        };
    }

    public function work_place_check():callable
    {
        return function ($attribute, $value, $fail) {
            $workPlaceType = array_column(WorkPlaceType::cases(), 'value');
            foreach ($value as $type){
                if(!in_array($type, $workPlaceType)){
                    return $fail("The {$attribute} attribute must be array of one or more of the following: " . implode(',',$workPlaceType));
                }
                if(!is_string($type)){
                    return $fail("The {$attribute} attribute must be a array of string.");
                }
            }
        };
    }
}
