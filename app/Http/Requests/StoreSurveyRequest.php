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
        $formSubmissions = FormSubmission::with('options')->get();
        $rules = [
            'email' => 'required|email', // required and must be valid email
            'name' => 'required|string', // required and must be string
        ];

        // generate rules for each field
        foreach($formSubmissions as $field){
            if($field->type == 'number'){
                // required|numeric|min:<min>|max:<max>
                $rules[$field->field_name] = [
                    $field->required ? 'required' : '',
                    "min:" . ($field->options[0]->min ?? 0),
                    "max:" . ($field->options[0]->max ?? 0),
                    "numeric"
                ];
            }else if(in_array($field->type, ['checkbox', 'radio', 'select'])){
                // required|in:<values>
                $rules[$field->field_name] = [
                    $field->required ? 'required' : '',
                    "in:" . implode(',', $field->options->pluck('value')->toArray())
                ];
            }else{
                // required
                $rules[$field->field_name] = $field->required ? 'required' : '';
            }
        }

        return $rules;
    }

}
