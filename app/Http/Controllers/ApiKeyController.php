<?php

namespace App\Http\Controllers;

use App\Http\Requests\StoreApiKeyRequest;
use App\Http\Requests\UpdateApiKeyRequest;
use App\Models\ApiKey;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
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
        $data = $request->validated();
        $data['token'] = Uuid::uuid4();
        $data['user_id'] = $request->user()->id;

        DB::beginTransaction();

        try {
            ApiKey::create($data);
            DB::commit();
            return redirect()->route('api-key.index')->with('success', 'API Key created successfully');
        } catch (\Throwable $th) {
            DB::rollBack();
            return redirect()->route('api-key.index')->with('error', 'API Key created failed');
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
        $data = $request->validated();
        $data['active'] = $request->has('active');

        DB::beginTransaction();

        try {
            $apiKey->update($data);
            DB::commit();
            return redirect()->route('api-key.index')->with('success', 'API Key updated successfully');
        }catch (\Throwable $th) {
            DB::rollBack();
            return redirect()->route('api-key.index')->with('error', 'API Key updated failed');
        }
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(ApiKey $apiKey)
    {
        DB::beginTransaction();
        try {
            $apiKey->delete();
            DB::commit();
            return redirect()->route('api-key.index')->with('success', 'API Key deleted successfully');
        } catch (\Throwable $th) {
            DB::rollBack();
            return redirect()->route('api-key.index')->with('error', 'API Key deleted failed');
        }
    }
}
