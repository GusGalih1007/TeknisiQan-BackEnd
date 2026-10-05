<?php

namespace App\Http\Controllers\Web;

use App\Enums\ResponseStatusOption;
use App\Http\Controllers\Controller;
use App\Http\Requests\NonUserReportStoreRequest;
use App\Models\Company;
use App\Models\Report;
use App\Models\Response;
use App\Models\Unit;
use App\Services\TicketNumberGeneratorService;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Log;

class ReportController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index(Request $request)
    {
        $auth = auth()->user();

        // Admin/superadmin: lihat laporan dari perusahaan mereka
        // Technician: read-only
        $query = Report::with(['company', 'unit', 'responses']);

        // Role-based filtering
        if ($auth->role->value === 'admin') {
            $query->whereHas('company', function ($q) use ($auth) {
                $q->where('compId', $auth->compId);
            });
        } elseif ($auth->role->value === 'technician') {
            // Technician bisa lihat semua laporan
        }

        // Search by ticket number atau unit name
        if ($request->has('search') && $request->search) {
            $search = $request->search;
            $query->where('ticketNumber', 'like', "%{$search}%")
                  ->orWhereHas('unit', function ($q) use ($search) {
                      $q->where('unitName', 'like', "%{$search}%");
                  });
        }

        // Filter by status
        if ($request->has('status') && $request->status) {
            $status = $request->status;
            if ($status === 'pending') {
                $query->whereDoesntHave('responses');
            } else {
                $query->whereHas('responses', function ($q) use ($status) {
                    $q->where('status', $status)->latest('responseDate')->limit(1);
                });
            }
        }

        $reports = $query->latest('reportDate')->paginate(10);

        return view('reports.index', compact('reports'));
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
        $report = Report::with(['company', 'unit.room', 'responses.technician'])->findOrFail($id);

        // Authorization check
        $auth = auth()->user();
        if ($auth->role->value === 'admin' && $report->compId !== $auth->compId) {
            abort(403, 'Anda tidak memiliki akses ke laporan ini');
        }

        return view('reports.show', compact('report'));
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

    /**
     * Reject a report by creating a response with rejected status
     */
    public function rejectReport(Request $request, string $id)
    {
        $report = Report::findOrFail($id);

        // Authorization: only admin (same company) or superadmin
        $auth = auth()->user();
        if ($auth->role->value === 'admin' && $report->compId !== $auth->compId) {
            abort(403, 'Anda tidak memiliki akses untuk menolak laporan ini');
        }

        // Validate
        $validated = $request->validate([
            'reason' => 'required|string|max:500',
        ]);

        try {
            DB::transaction(function () use ($report, $validated, $auth) {
                // Create response with rejected status
                Response::create([
                    'reportId' => $report->reportId,
                    'solution' => $validated['reason'],
                    'photo' => [],
                    'responseDate' => now(),
                    'status' => ResponseStatusOption::Rejected,
                    'technicianId' => $auth->userId,
                ]);
            });

            return back()->with('success', 'Laporan berhasil ditolak');
        } catch (\Exception $e) {
            Log::error('Report rejection error: ' . $e->getMessage());
            return back()->with('error', 'Terjadi kesalahan saat menolak laporan: ' . $e->getMessage());
        }
    }
}
