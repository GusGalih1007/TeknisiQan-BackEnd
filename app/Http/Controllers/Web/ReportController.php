<?php

namespace App\Http\Controllers\Web;

use App\Http\Controllers\Controller;
use App\Http\Requests\NonUserReportStoreRequest;
use App\Models\Company;
use App\Models\Report;
use App\Models\Unit;
use App\Services\TicketNumberGeneratorService;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Log;

class ReportController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        //
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create()
    {
        $companies = Company::select(['compId', 'name'])->get();
        return view('non-user.create', compact('companies'));
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(NonUserReportStoreRequest $request)
    {
        try {
            $validatedData = $request->validated();

            // Get unit and company data
            $unit = Unit::find($validatedData['unitId']);
            $company = Company::find($validatedData['compId']);

            if (!$unit || !$company || $unit->compId !== $company->compId) {
                return redirect()->back()->with('error', 'Unit atau perusahaan tidak ditemukan');
            }

            // Generate ticket number
            $ticketGeneratorService = new TicketNumberGeneratorService();
            $ticketNumber = $ticketGeneratorService->generate($company);

            // Handle multiple photo uploads
            $photoPaths = [];
            if ($request->hasFile('photos')) {
                try {
                    foreach ($request->file('photos') as $photo) {
                        $path = $photo->store('reports/' . date('Y/m/d'), 'public');
                        $photoPaths[] = $path;
                    }
                } catch (\Exception $e) {
                    Log::error('Photo upload error: ' . $e->getMessage());
                    // Continue without photos if upload fails
                }
            }

            // Create reporter metadata
            $reporterMetadata = [
                'name' => $validatedData['reportByName'],
            ];

            // Create report
            $report = Report::create([
                'ticketNumber' => $ticketNumber,
                'unitId' => $validatedData['unitId'],
                'compId' => $validatedData['compId'],
                'problem' => $validatedData['problem'],
                'title' => $validatedData['title'],
                'reportBy' => $reporterMetadata,
                'reportDate' => $validatedData['reportDate'],
                'photo' => $photoPaths ?: null,
            ]);

            // Store additional info in session for confirmation
            session()->put('last_report', [
                'ticketNumber' => $ticketNumber,
                'unitName' => $unit->unitName,
                'companyName' => $company->name,
                'reporterName' => $validatedData['reportByName'],
            ]);

            return redirect()->route('non-user-reports.create')
                ->with('success', 'Laporan kerusakan berhasil dikirim dengan nomor tiket: ' . $ticketNumber);

        } catch (\Exception $e) {
            Log::error('Report creation error: ' . $e->getMessage());
            return redirect()->back()
                ->with('error', 'Terjadi kesalahan saat menyimpan laporan: ' . $e->getMessage())
                ->withInput();
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
    public function edit(string $id)
    {
        //
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
