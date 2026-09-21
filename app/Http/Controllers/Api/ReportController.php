<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Http\Requests\ReportStoreRequest;
use App\Services\TicketNumberGeneratorService;
use App\Models\Company;
use App\Models\Report;
use Illuminate\Http\Request;

class ReportController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        $data = Report::latest()->get();

        return response()->json([
            'status' => 'success',
            'data' => $data
        ]);
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(ReportStoreRequest $request, TicketNumberGeneratorService $ticketGenerator)
    {
        $validatedData = $request->validated();

        $company = Company::find($validatedData->compId);

        $validatedData['ticketNumber'] = $ticketGenerator->generate($company);

        $report = Report::create($validatedData);

        return response()->json([
            'status' => 'success',
            'message' => 'Data has been created',
            'data' => $report
        ]);
    }

    /**
     * Display the specified resource.
     */
    public function show(string $id)
    {
        $data = Report::find($id);

        return response()->json([
            'status' => 'message',
            'data' => $data
        ]);
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, string $id)
    {
        //
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(string $id)
    {
        //
    }
}
