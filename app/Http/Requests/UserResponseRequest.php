<?php

namespace App\Http\Requests;

use App\Models\FormSubmission;
use Illuminate\Foundation\Http\FormRequest;

class UserResponseRequest extends FormRequest
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
        return [
            //
        ];
    }

    public function parsingRequest(): array
    {
        $formSubmissions = FormSubmission::with('options')->get();
        $ids = $formSubmissions->pluck('id')->toArray();

        // only email and name
        $data = request()->except('responses');
        $dataResponse = [];

        // parsing responses
        foreach(request()->responses as $response){
            if(in_array($response['id'], $ids)){
                $form = $formSubmissions->where('id', $response['id'])->first();
                $data[$form->field_name] = $response['answer'];
                $dataResponse[$response['id']] = $response['answer'];
            }
        }

        return [$data, $dataResponse];
    }
}
