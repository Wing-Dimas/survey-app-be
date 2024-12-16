<?php

namespace App\Http\Controllers;

use App\Models\Response;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class ResponseController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        $responses = Response::filter(request(['search']))
                            ->with('api_key')
                            ->latest()
                            ->paginate(5)
                            ->withQueryString();

        return view('pages.response.index', [
            'responses' => $responses
        ]);
    }

    /**
     * Display the specified resource.
     */
    public function show(Response $response)
    {
        return view('pages.response.show', [
            'response' => $response
        ]);
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(Response $response)
    {
        DB::beginTransaction();
        try {
            $response->delete();
            DB::commit();
            return redirect()->route('response.index')->with('success', 'Response deleted successfully');
        } catch (\Throwable $th) {
            DB::rollBack();
            return redirect()->route('response.index')->with('error', 'Response deleted failed');
        }
    }
}
