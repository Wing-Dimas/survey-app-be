<?php

namespace App\Http\Controllers\Api\V1;

use App\Formatters\ResponseFormatter;
use App\Http\Controllers\Controller;
use App\Http\Requests\UserResponseRequest;
use App\Models\FormSubmission;
use App\Models\Response;
use App\Models\ResponseAnswer;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Validator;

class ResponseController extends Controller
{
    /**
     * Store a newly created resource in storage.
     *
     * @param  \App\Http\Requests\UserResponseRequest  $request
     * @return \Illuminate\Http\Response
     */
    public function store(UserResponseRequest $request)
    {
        DB::beginTransaction();
        try{
            // Parsing request
            [$data, $dataResponse] = $request->parsingRequest();
            // get rules
            $rules = $this->getRules();
            // validate data
            $validator = Validator::make($data, $rules);

            if ($validator->fails()) {
                return ResponseFormatter::error($validator->errors(),"Error in your request", 400);
            }

            $user = Response::where('email', $data['email'])->where('api_key_id', $data['api_key_id'])->first();

            if($user){
                $user->update($data);
                foreach($dataResponse as $form_submission_id => $response){
                    $responseAnswer = ResponseAnswer::where('response_id', $user['id'])->where('form_submission_id', $form_submission_id)->first();
                    if($responseAnswer){
                        $responseAnswer->update([
                            'answer' => $response
                        ]);
                    }else{
                        ResponseAnswer::create([
                            'form_submission_id' => $form_submission_id,
                            'response_id' => $user['id'],
                            'answer' => $response,
                        ]);
                    }
                }
            }else{
                $user = Response::create($data);
                foreach($dataResponse as $form_submission_id => $response){
                    ResponseAnswer::create([
                        'response_id' => $user['id'],
                        'form_submission_id' => $form_submission_id,
                        'answer' => $response,
                    ]);
                }
            }

            DB::commit();
            return ResponseFormatter::success(null,'Submit form successfully', 201);
        }catch(\Exception $e){
            DB::rollBack();
            dd($e->getMessage());
            return ResponseFormatter::error(null,"Internal server error", 500);
        }
    }

    /**
     * Generate rules for validator based on FormSubmission model.
     *
     * @param  array  $data
     * @return array
     */
    protected function getRules()
    {
        $formSubmissions = FormSubmission::with('options')->get();
        $rules = [
            'email' => 'required|email', // required and must be valid email
            'name' => 'required|string', // required and must be string
        ];

        // generate rules for each form
        foreach($formSubmissions as $form){
            if($form->type == 'number'){
                // required|numeric|min:<min>|max:<max>
                $rules[$form->field_name] = [
                    $form->required ? 'required' : '',
                    "min:" . ($form->options[0]->min ?? 0),
                    "max:" . ($form->options[0]->max ?? 0),
                    "numeric"
                ];
            }else if(in_array($form->type, ['checkbox', 'radio', 'select'])){
                // required|in:<values>
                $rules[$form->field_name] = [
                    $form->required ? 'required' : '',
                    "in:" . implode(',', $form->options->pluck('value')->toArray())
                ];
            }else{
                // required
                $rules[$form->field_name] = $form->required ? 'required' : '';
            }
        }

        return $rules;
    }
}
