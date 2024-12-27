<?php

namespace App\Http\Controllers;

use App\Http\Controllers\Controller;
use App\Models\FormSubmission;
use App\Models\SurveyToken;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Log;

class SurveyController extends Controller
{
    public function index(Request $request)
    {
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
            // 'survey_token' => $survey_token->token,
            'formSubmissions' => $formSubmissions,
            'credentials' => $user
        ]);
    }
}
