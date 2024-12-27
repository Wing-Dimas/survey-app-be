<?php

namespace App\Http\Controllers\Api\V2;

use App\Formatters\ResponseFormatter;
use App\Http\Controllers\Controller;
use App\Models\SurveyToken;
use Carbon\Carbon;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Str;

class FormSubmissionController extends Controller
{
    public function index(Request $request)
    {
        Log::info("user attempt to get form submission", ['app_name' => $request->app_name, 'api_key' => $request->api_key_id, "api_version" => 2]);
        DB::beginTransaction();
        try{
            $token = Str::random(64);
            $api_key_id = $request->api_key_id;
            $expires_at = Carbon::now()->addMinutes(10);
            $redurect_url = env('APP_URL') . '/survey/' . $token;

            SurveyToken::create([
                'token' => $token,
                'api_key_id' => $api_key_id,
                'expires_at' => $expires_at
            ]);

            DB::commit();
            Log::info("user get form submission successfully", ['app_name' => $request->app_name, 'api_key' => $request->api_key_id, "api_version" => 2]);
            return ResponseFormatter::success(['redirect_url' => $redurect_url]);
        }catch(\Throwable $th){
            DB::rollBack();
            Log::error(flattenError($th));
            return ResponseFormatter::error(null, 'Internal server error', 500);
        }
    }
}
