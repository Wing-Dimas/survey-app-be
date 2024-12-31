<?php

namespace App\Http\Controllers;

use App\Http\Requests\StoreFieldRequest;
use App\Http\Requests\UpdateFieldRequest;
use App\Models\FormSubmission;
use App\Models\FormSubmissionOption;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Log;

class FormSubmissionController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        $formSubmissions = FormSubmission::filter(request(['search', 'type']))
                                        ->latest()
                                        ->paginate(5)
                                        ->withQueryString();

        return view('pages.form-submission.index', [
            'formSubmissions' => $formSubmissions
        ]);
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create()
    {
        return view('pages.form-submission.create');
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(StoreFieldRequest $request)
    {
        Log::info("user attempt to create form submission", ['user' => Auth::user()->username ,'data' => $request->all()]);
        DB::beginTransaction();
        try {
            $data = $request->validated();
            $data['rule'] = isset($data['required']) ? 'required|' : '';

            // check if type is select, checkbox, or radio
            if(in_array($data['type'], ['select', 'checkbox', 'radio'])) {
                $rule = [
                    "string",
                    "in:" . implode(',', $data['value'])
                ];

                $data['rule'] .= implode('|', $rule);
                $formSubmission = FormSubmission::create($data);
                // merge key and value from data to array as key => value
                foreach (array_combine($data['key'], $data['value']) as $key => $value) {
                    FormSubmissionOption::create([
                        'form_submission_id' => $formSubmission->id,
                        'key' => $key,
                        'value' => $value,
                    ]);
                }
            }
            // check if type is number
            else if($data['type'] == 'number') {
                $rule = [
                    "min:" . ($data['min'] ?? 0),
                    "max:" . ($data['max'] ?? 0),
                    "numeric",
                ];

                $data['rule'] .= implode('|', $rule);
                $formSubmission = FormSubmission::create($data);

                // set min and max to null if not set
                $data['min'] = $data['min'] ?? null;
                $data['max'] = $data['max'] ?? null;

                FormSubmissionOption::create([
                    'form_submission_id' => $formSubmission->id,
                    'min' => $data['min'],
                    'max' => $data['max'],
                ]);
            }else{
                $rule = ["string"];

                $data['rule'] .= implode('|', $rule);
                $formSubmission = FormSubmission::create($data);
            }

            DB::commit();
            Log::info("user create a new form submission successfully", ['user' => Auth::user()->username, 'form_submission' => $formSubmission->id]);
            return redirect()->route('form-submission.index')->with('success', 'Form submission created successfully');
        } catch (\Throwable $th) {
            DB::rollBack();
            Log::warning("form submission could not be created", ['user' => Auth::user()->username, 'data' => $request->all()]);
            Log::error(flattenError($th), ['user' => Auth::user()->username, 'data' => $request->all()]);
            return redirect()->route('form-submission.index')->with('error', 'Form submission created failed');
        }
    }

    /**
     * Display the specified resource.
     */
    public function show(string $id)
    {
        //
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(FormSubmission $formSubmission)
    {
        return view('pages.form-submission.edit', [
            'formSubmission' => $formSubmission
        ]);
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(UpdateFieldRequest $request, FormSubmission $formSubmission)
    {
        Log::info("user attempt to update form submission", ['user' => Auth::user()->username, 'form_submission' => $formSubmission->id]);
        DB::beginTransaction();
        try {
            $data = $request->validated();

            $data['rule'] = isset($data['required']) ? 'required|' : '';

            // delete old options
            FormSubmissionOption::where('form_submission_id', $formSubmission->id)->delete();
            // check if type is select, checkbox, or radio
            if(in_array($data['type'], ['select', 'checkbox', 'radio'])) {
                $rule = [
                    "string",
                    "in:" . implode(',', $data['value'])
                ];

                $data['rule'] .= implode('|', $rule);
                $formSubmission->update($data);
                // merge key and value from data to array as key => value
                foreach (array_combine($data['key'], $data['value']) as $key => $value) {
                    FormSubmissionOption::create([
                        'form_submission_id' => $formSubmission->id,
                        'key' => $key,
                        'value' => $value,
                    ]);
                }
            }
            // check if type is number
            else if($data['type'] == 'number') {
                $rule = [
                    "min:" . ($data['min'] ?? 0),
                    "max:" . ($data['max'] ?? 0),
                    "numeric",
                ];

                $data['rule'] .= implode('|', $rule);
                $formSubmission->update($data);
                // set min and max to null if not set
                $data['min'] = $data['min'] ?? null;
                $data['max'] = $data['max'] ?? null;

                FormSubmissionOption::create([
                    'form_submission_id' => $formSubmission->id,
                    'min' => $data['min'],
                    'max' => $data['max'],
                ]);
            }else{
                $rule = ["string"];

                $data['rule'] .= implode('|', $rule);
                $formSubmission->update($data);
            }

            DB::commit();
            Log::info("user update form submission successfully", ['user' => Auth::user()->username, 'form_submission' => $formSubmission->id]);
            return redirect()->route('form-submission.index')->with('success', 'Form submission updated successfully');
        } catch (\Throwable $th) {
            DB::rollBack();
            Log::warning("user update form submission failed", ['user' => Auth::user()->username, 'form_submission' => $formSubmission->id]);
            Log::error(flattenError($th), ['user' => Auth::user()->username, 'form_submission' => $formSubmission->id]);
            return redirect()->route('form-submission.index')->with('error', 'Form submission updated failed');
        }
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(FormSubmission $formSubmission)
    {
        Log::info("user attempt to delete form submission", ['user' => Auth::user()->username, 'form_submission' => $formSubmission->id]);
        DB::beginTransaction();
        try {
            $formSubmission->delete();
            DB::commit();
            Log::info("user delete form submission successfully", ['user' => Auth::user()->username, 'form_submission' => $formSubmission->id]);
            return redirect()->route('form-submission.index')->with('success', 'Form submission deleted successfully');
        } catch (\Throwable $th) {
            DB::rollBack();
            Log::warning("user delete form submission failed", ['user' => Auth::user()->username, 'form_submission' => $formSubmission->id]);
            Log::error(flattenError($th), ['user' => Auth::user()->username, 'form_submission' => $formSubmission->id]);
            return redirect()->route('form-submission.index')->with('error', 'Form submission deleted failed');
        }
    }
}
