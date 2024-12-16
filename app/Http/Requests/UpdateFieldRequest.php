<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Validation\Rule;

class UpdateFieldRequest extends FormRequest
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
        $rules = [
            'field_name' => 'required|string',
            'question' => 'required|string',
            'type' => 'required|in:text,email,number,checkbox,radio,select,textarea',
            'required' => 'boolean',
        ];

        if(in_array($this->type, ['checkbox', 'radio', 'select'])) {
            $rules = array_merge($rules, [
                'key' => 'required|array',
                'value' => 'required|array',
                'key.*' => 'required|string',
                'value.*' => 'required|string',
            ]);
        }

        if($this->type == 'number') {
            $rules = array_merge($rules, [
                'min' => array_merge(['nullable','numeric', "max:" . ($this->max ?? 0), Rule::requiredIf(fn () => $this->max == null)], $this->max == null ? [] : ['lt:max']),
                'max' => array_merge(['nullable','numeric', "min:" . ($this->min ?? 0 ),Rule::requiredIf(fn () => $this->min != null || $this->max == null)], $this->min == null ? [] : ['gt:min']),
            ]);
        }

        return $rules;
    }
}
