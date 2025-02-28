<?php

namespace Modules\Jobs\App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class JobApplyRequest extends FormRequest
{
    /**
     * Get the validation rules that apply to the request.
     */
    public function rules(): array
    {

        return [
            'job_id' => 'required|integer|exists:medical_jobs,id',
            'cv' => 'nullable|mimes:pdf,txt,doc,docx|max:10240'
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
