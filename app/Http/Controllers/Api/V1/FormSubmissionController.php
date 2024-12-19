<?php

namespace App\Http\Controllers\Api\V1;

use App\Formatters\ResponseFormatter;
use App\Http\Controllers\Controller;
use App\Http\Resources\FormSubmissionCollection;
use App\Http\Resources\FormSubmissionResource;
use App\Models\FormSubmission;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Log;

class FormSubmissionController extends Controller
{
    /**
     * Get all form submissions.
     *
     * @return \Illuminate\Http\Response
     */
    public function index(Request $request)
    {
        try {
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

            Log::warning("user get form submissions successfully", ['app_name' => $request->app_name, 'api_key' => $request->api_key_id]);
            return ResponseFormatter::success([
                'credentials' => $user,
                'form_submissions' => new FormSubmissionCollection($formSubmissions)],'Get form submissions successfully'
            );
        } catch (\Throwable $th) {
            Log::error(flattenError($th));
            return ResponseFormatter::error(null,'Internal server error', 500)->setStatusCode(500);
        }
    }
}
