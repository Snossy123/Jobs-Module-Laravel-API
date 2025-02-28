<?php

namespace Modules\Jobs\App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class JobListingRequest extends FormRequest
{
    /**
     * Get the validation rules that apply to the request.
     */
    public function rules(): array
    {
        return [
            'paginate' => ["nullable","integer","between:1,100"],
            'sort' => ["nullable","string","in:ascending,descending"]
        ];
    }

    /**
     * Determine if the user is authorized to make this request.
     */
    public function authorize(): bool
    {
        return true;
    }
}
