<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Http\Requests\CompanyStoreRequest;
use App\Http\Requests\CompanyUpdateRequest;
use App\Models\Company;
use Illuminate\Http\Request;

class CompanyController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        $data = Company::latest()->get();

        return response()->json([
            'message' => 'success',
            'data' => $data
        ], 200);
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(CompanyStoreRequest $request)
    {
        $validatedData = $request->validated();

        $company = Company::create($validatedData);

        return response()->json([
            'status' => 'success',
            'message' => 'Data has been created',
            'data' => $company
        ], 200);
    }

    /**
     * Display the specified resource.
     */
    public function show(string $id)
    {
        $data = Company::find($id);

        return response()->json([
            'status' => 'success',
            'data' => $data
        ]);
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(CompanyUpdateRequest $request, string $id)
    {
        $existedData = Company::find($id);

        if (!$existedData) {
            return response()->json([
                'status' => 'error',
                'message' => 'Data does not exist'
            ]);
        }

        $validatedData = $request->validated();

        $newData = $existedData->update($validatedData);

        return response()->json([
            'status' => 'success',
            'message' => 'Data has been updated',
            'data' => $newData
        ]);
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(string $id)
    {
        $data = Company::findOrFail($id);

        $data->delete();

        return response()->json([
            'status' => 'success',
            'message' => 'Data has been deleted'
        ]);
    }
}
