<?php

namespace App\Http\Controllers\Api\V1;

use App\Formatters\ResponseFormatter;
use App\Http\Controllers\Controller;
use App\Http\Resources\FormSubmissionCollection;
use App\Http\Resources\FormSubmissionResource;
use App\Models\FormSubmission;
use Illuminate\Http\Request;

class FormSubmissionController extends Controller
{
    /**
     * Get all form submissions.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        try {
            $formSubmissions = FormSubmission::with('options')->get();
            return ResponseFormatter::success(new FormSubmissionCollection($formSubmissions),'Get form submissions successfully');
        } catch (\Exception $e) {
            return ResponseFormatter::error(null,'Internal server error', 500)->setStatusCode(500);
        }
    }
}
