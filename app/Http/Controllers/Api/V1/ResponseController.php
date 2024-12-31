<?php

namespace App\Http\Controllers\Api\V1;

use App\Formatters\ResponseFormatter;
use App\Http\Controllers\Controller;
use App\Http\Requests\UserResponseRequest;
use App\Models\FormSubmission;
use App\Models\Response;
use App\Models\ResponseAnswer;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Log;
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
        // cek application json
        if ($request->header('Content-Type') !== 'application/json') {
            Log::warning("user attempt to submit form with invalid content type", ['app_name' => $request->app_name, 'api_key' => $request->api_key_id]);
            return ResponseFormatter::error(null,"Content-Type must be application/json", 400);
        }

        // cek apakah ada credential dan resposes uuser
        if (!$request->has('email') || !$request->has('name') || !$request->has('responses')) {
            Log::warning("user attempt to submit form with invalid request", ['app_name' => $request->app_name, 'api_key' => $request->api_key_id]);
            return ResponseFormatter::error(null,"email, name and credentials is required", 400);
        }

        // cek jika ada data pada responses
        if($request->has('responses') && count($request->responses) == 0){
            Log::warning("user attempt to submit form with empty responses", ['app_name' => $request->app_name, 'api_key' => $request->api_key_id]);
            return ResponseFormatter::error(null,"responses is required", 400);
        }

        Log::info("user attempt to submit form", ['app_name' => $request->app_name, 'api_key' => $request->api_key_id,'email' => $request->email]);
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
            Log::info("user submit form successfully", ['app_name' => $request->app_name, 'api_key' => $request->api_key_id,'email' => $request->email]);
            return ResponseFormatter::success(null,'Submit form successfully', 201);
        }catch(\Throwable $th){
            DB::rollBack();
            Log::warning("user submit form failed", ['app_name' => $request->app_name, 'api_key' => $request->api_key_id,'email' => $request->email]);
            Log::error(flattenError($th), ['app_name' => $request->app_name, 'api_key' => $request->api_key_id,'email' => $request->email]);
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
        $formSubmissionsRule = FormSubmission::all()->pluck('rule', 'field_name')->toArray();
        $rules = [
            'email' => 'required|email', // required and must be valid email
            'name' => 'required|string', // required and must be string
        ];

        return array_merge($rules, $formSubmissionsRule);
    }
}
