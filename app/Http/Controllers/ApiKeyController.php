<?php

namespace App\Http\Controllers;

use App\Exports\ResponsesExport;
use App\Http\Requests\StoreApiKeyRequest;
use App\Http\Requests\UpdateApiKeyRequest;
use App\Models\ApiKey;
use App\Models\FormSubmission;
use Carbon\Carbon;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Log;
use Maatwebsite\Excel\Facades\Excel;
use Ramsey\Uuid\Uuid;

class ApiKeyController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        $apiKeys = ApiKey::filter(request(['search']))
                        ->latest()
                        ->paginate(5)
                        ->withQueryString();

        return view('pages.api-key.index', [
            'apiKeys' => $apiKeys
        ]);
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create()
    {
        return view('pages.api-key.create');
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(StoreApiKeyRequest $request)
    {
        Log::info("user attempt to create api key", $request->all());
        $data = $request->validated();
        $data['token'] = Uuid::uuid4();
        $data['user_id'] = $request->user()->id;

        DB::beginTransaction();

        try {
            $apikey = ApiKey::create($data);
            DB::commit();
            Log::info("user create a new api key successfully", ['user' => Auth::user()->username, 'id' => $apikey->id]);
            return redirect()->route('api-key.index')->with('success', 'API Key created successfully');
        } catch (\Throwable $th) {
            DB::rollBack();
            Log::warning("Api key could not be created", ['user' => Auth::user()->username, 'data' => $request->all()]);
            Log::error(flattenError($th), $request->all());
            return redirect()->route('api-key.index')->with('error', 'API Key created failed');
        }
    }

    public function export(ApiKey $apiKey) {
        Log::info("user attempt to export responses to excel", ['user' => Auth::user()->username, 'id' => $apiKey->id]);
        try {
            $formSubmissions = FormSubmission::all();

            $headers = ['nomor','email', 'name'];
            $headers = array_merge($headers, $formSubmissions->pluck('field_name')->toArray());

            $responded = $apiKey->responses;
            $data = [];
            foreach($responded as $key => $response) {
                $respondedAnswers = $response->answers;
                $answers = [
                    'nomor' => $key + 1,
                    'email' => $response->email,
                    'name' => $response->name
                ];
                foreach($respondedAnswers as $respondedAnswer) {
                    $answers[$respondedAnswer->form_submission->field_name] = $respondedAnswer->answer;
                }

                $data[] = $answers;
            }

            // it should be the same with headers
            foreach($data as $key => $row) {
                $missing_columns = array_diff($headers, array_keys($row));
                foreach($missing_columns as $column) {
                    $data[$key][$column] = null;
                }
            }

            $filename = "responses-". Carbon::now()->format('Y-m-d') . ".xlsx";
            Log::info("user export responses to excel successfully", ['user' => Auth::user()->username, 'api_key' => $apiKey->id]);
            return Excel::download(new ResponsesExport($data, $headers), $filename);
        } catch (\Throwable $th) {
            Log::warning("user export responses to excel failed", ['user' => Auth::user()->username, 'api_key' => $apiKey->id]);
            Log::error(flattenError($th), ['user' => Auth::user()->username, 'api_key' => $apiKey->id]);
            return redirect()->route('api-key.index')->with('error', 'Excel export failed');
        }
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(ApiKey $apiKey)
    {
        return view('pages.api-key.edit', [
            'apiKey' => $apiKey
        ]);
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(UpdateApiKeyRequest $request, ApiKey $apiKey)
    {
        Log::info("user attempt to update api key", ['user' => Auth::user()->username, 'api_key' => $apiKey->id]);
        $data = $request->validated();
        $data['active'] = $request->has('active');

        DB::beginTransaction();

        try {
            $apiKey->update($data);
            DB::commit();
            Log::info("user update api key successfully", ['user' => Auth::user()->username, 'api_key' => $apiKey->id]);
            return redirect()->route('api-key.index')->with('success', 'API Key updated successfully');
        }catch (\Throwable $th) {
            DB::rollBack();
            Log::warning("user update api key failed", ['user' => Auth::user()->username, 'api_key' => $apiKey->id]);
            Log::error(flattenError($th), ['user' => Auth::user()->username, 'api_key' => $apiKey->id]);
            return redirect()->route('api-key.index')->with('error', 'API Key updated failed');
        }
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(ApiKey $apiKey)
    {
        Log::info("user attempt to delete api key", ['user' => Auth::user()->username, 'api_key' => $apiKey->id]);
        DB::beginTransaction();
        try {
            $apiKey->delete();
            DB::commit();
            Log::info("user delete api key successfully", ['user' => Auth::user()->username, 'api_key' => $apiKey->id]);
            return redirect()->route('api-key.index')->with('success', 'API Key deleted successfully');
        } catch (\Throwable $th) {
            DB::rollBack();
            Log::warning("user delete api key failed", ['user' => Auth::user()->username, 'api_key' => $apiKey->id]);
            Log::error(flattenError($th), ['user' => Auth::user()->username, 'api_key' => $apiKey->id]);
            return redirect()->route('api-key.index')->with('error', 'API Key deleted failed');
        }
    }
}
