<?php

namespace App\Http\Requests;

use App\Models\FormSubmission;
use Illuminate\Foundation\Http\FormRequest;

class StoreSurveyRequest extends FormRequest
{
    /**
     * Determine if the user is authorized to make this request.
     */
    public function authorize(): bool
    {
        return true;
    }

    /**
     * Get the validation rules that apply to the request.
     *
     * @return array<string, \Illuminate\Contracts\Validation\ValidationRule|array<mixed>|string>
     */
    public function rules(): array
    {
        $formSubmissions = FormSubmission::all()->pluck('rule', 'field_name')->toArray();
        $rules = [
            'email' => 'required|email', // required and must be valid email
            'name' => 'required|string', // required and must be string
        ];

        $rules = array_merge($rules, $formSubmissions);

        return $rules;
    }

}
