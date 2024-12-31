<?php

namespace App\Http\Controllers;

use App\Http\Controllers\Controller;
use App\Http\Requests\StoreSurveyRequest;
use App\Models\ApiKey;
use App\Models\FormSubmission;
use App\Models\Response;
use App\Models\ResponseAnswer;
use App\Models\SurveyToken;
use Carbon\Carbon;
use Illuminate\Http\Request;
use Illuminate\Support\Arr;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Http;
use Illuminate\Support\Facades\Log;
use Symfony\Component\HttpKernel\Exception\HttpException;

class SurveyController extends Controller
{
    /**
     * Get survey page
     *
     * @param Request $request
     * @param SurveyToken $survey_token
     * @return \Illuminate\Contracts\View\View
     */
    public function index(Request $request, SurveyToken $survey_token)
    {
        try{
            Log::info('survey token', ["ip_address" => $request->ip(), "survey_token" => $survey_token->token]);
            // cek survey token
            if (!$survey_token) {
               Log::warning("survey token not found", ['survey_token' => $survey_token->token]);
               return abort(401, 'token invalid, please try again');
           }

           // cek apakah token sudah expired
           if (Carbon::now()->greaterThan($survey_token->expires_at)) {
               Log::warning("survey token expired", ['survey_token' => $survey_token->token]);
               $survey_token->delete();
               return abort(401, 'token expired, please try again');
           }

           $formSubmissions = FormSubmission::with('options')->get();
           $user = [
               [
                   'id' => null,
                   'field_name' => 'email',
                   'type' => 'email',
                   'required' => true,
                   'options' => null
               ],
               [
                   'id' => null,
                   'field_name' => 'name',
                   'type' => 'text',
                   'required' => true,
                   'options' => null,
               ]
           ];

           return view('pages.survey.index', [
               'survey_token' => $survey_token->token,
               'formSubmissions' => $formSubmissions,
               'credentials' => $user,
               'app_name' => $survey_token->api_key->name
           ]);

        }
        catch(HttpException $e){
            throw new HttpException($e->getStatusCode(), $e->getMessage());
        }
        catch(\Throwable $th){
            Log::error(flattenError($th));
            abort(500);
        }
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(StoreSurveyRequest $request, SurveyToken $survey_token)
    {
        DB::beginTransaction();
        try {
            // cek survey token
            if (!$survey_token) {
                Log::warning("survey token not found", ['survey_token' => $survey_token->token]);
                return abort(401, 'token invalid, please try again');
            }

            // cek apakah token sudah expired
            if (Carbon::now()->greaterThan($survey_token->expires_at)) {
                Log::warning("survey token expired", ['survey_token' => $survey_token->token]);
                $survey_token->delete();
                return abort(401, 'token expired, please try again');
            }

            // cek api key
            $apiKey = ApiKey::where('id', $survey_token->api_key_id)->first();
            if (!$apiKey) {
                Log::warning("api key not found", ['survey_token' => $survey_token->token, 'api_key' => $apiKey->token]);
                return abort(401, 'Api key not found');
            }

            Log::info("user attempt to submit form", ['app_name' => $apiKey->name, 'api_key' => $apiKey->id, 'email' => $request->email]);

            $data = $request->validated();

            $user = Response::where('email', $data['email'])->where('api_key_id', $apiKey->id)->first();

            $to_form_submission_id = FormSubmission::all()->pluck('id', 'field_name')->toArray();

            if($user){
                $user->update($data);
                foreach($data as $field_name => $response){
                    if(!Arr::exists($to_form_submission_id, $field_name)) continue;
                    $form_submission_id = $to_form_submission_id[$field_name];
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
                $data['api_key_id'] = $apiKey->id;
                $user = Response::create($data);
                foreach($data as $field_name => $response){
                    if(!Arr::exists($to_form_submission_id, $field_name)) continue;
                    $form_submission_id = $to_form_submission_id[$field_name];
                    ResponseAnswer::create([
                        'response_id' => $user['id'],
                        'form_submission_id' => $form_submission_id,
                        'answer' => $response,
                    ]);
                }
            }

            $survey_token->delete();
            DB::commit();
            Log::info("user submit form successfully", ['app_name' => $apiKey->name, 'api_key' => $apiKey->id, 'email' => $request->email]);
            return view('pages.survey.success');
        }
        catch (\Throwable $th) {
            DB::rollBack();
            Log::error(flattenError($th));
            abort(500);
        }
    }
}
