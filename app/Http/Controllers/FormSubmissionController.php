<?php

namespace App\Http\Controllers;

use App\Http\Requests\StoreFieldRequest;
use App\Http\Requests\UpdateFieldRequest;
use App\Models\FormSubmission;
use App\Models\FormSubmissionOption;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

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
        $data = $request->validated();

        DB::beginTransaction();

        try {
            $formSubmission = FormSubmission::create($data);

            // check if type is select, checkbox, or radio
            if(in_array($data['type'], ['select', 'checkbox', 'radio'])) {
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
                // set min and max to null if not set
                $data['min'] = $data['min'] ?? null;
                $data['max'] = $data['max'] ?? null;

                FormSubmissionOption::create([
                    'form_submission_id' => $formSubmission->id,
                    'min' => $data['min'],
                    'max' => $data['max'],
                ]);
            }

            DB::commit();
            return redirect()->route('form-submission.index')->with('success', 'Form submission created successfully');
        } catch (\Throwable $th) {
            DB::rollBack();
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
        $data = $request->validated();

        DB::beginTransaction();

        try {
            $formSubmission->update($data);

            // check if type is select, checkbox, or radio
            FormSubmissionOption::where('form_submission_id', $formSubmission->id)->delete();
            if(in_array($data['type'], ['select', 'checkbox', 'radio'])) {
                // delete old options
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
                // set min and max to null if not set
                $data['min'] = $data['min'] ?? null;
                $data['max'] = $data['max'] ?? null;

                FormSubmissionOption::create([
                    'form_submission_id' => $formSubmission->id,
                    'min' => $data['min'],
                    'max' => $data['max'],
                ]);
            }

            DB::commit();
            return redirect()->route('form-submission.index')->with('success', 'Form submission updated successfully');
        } catch (\Throwable $th) {
            DB::rollBack();
            return redirect()->route('form-submission.index')->with('error', 'Form submission updated failed');
        }
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(FormSubmission $formSubmission)
    {
        DB::beginTransaction();
        try {
            $formSubmission->delete();
            DB::commit();
            return redirect()->route('form-submission.index')->with('success', 'Form submission deleted successfully');
        } catch (\Throwable $th) {
            DB::rollBack();
            return redirect()->route('form-submission.index')->with('error', 'Form submission deleted failed');
        }
    }
}