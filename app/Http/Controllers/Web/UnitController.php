<?php

namespace App\Http\Controllers\Web;

use App\Http\Controllers\Controller;
use App\Http\Requests\UnitStoreRequest;
use App\Http\Requests\UnitUpdateRequest;
use App\Models\Unit;
use App\Models\Company;
use App\Models\Room;
use App\Services\UnitNumberGeneratorService;
use App\Services\UnitQrCodeService;
use App\Services\UnitEmailService;
use Illuminate\Http\Request;

class UnitController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        $user = auth()->user();
        $query = Unit::with('company', 'room')->latest();

        // Filter berdasarkan role
        if ($user->role->value === 'admin') {
            $query->where('compId', $user->compId);
        }

        $data = $query->paginate(10);

        return view('units.index', compact('data'));
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create()
    {
        $user = auth()->user();
        $rooms = Room::with('company')->select(['roomId', 'roomName', 'compId'])->get();

        return view('units.create', compact('rooms'));
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(UnitStoreRequest $request)
    {
        $validatedData = $request->validated();

        // Get room untuk ambil nama ruangan
        $room = Room::find($validatedData['roomId']);

        // Generate unit number otomatis berdasarkan ruangan
        $validatedData['unitNumber'] = UnitNumberGeneratorService::generateByRoom(
            $validatedData['roomId'],
            $room->roomName
        );

        $unit = Unit::create($validatedData);

        return redirect()->route('units.index')->with('success', 'Unit berhasil dibuat dengan nomor: ' . $unit->unitNumber);
    }

    /**
     * Display the specified resource.
     */
    public function show(string $id)
    {
        $data = Unit::with('company', 'room')->find($id);

        if (!$data) {
            return redirect()->back()->with('error', 'Data unit tidak ditemukan');
        }

        return view('units.show', compact('data'));
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(string $id)
    {
        $data = Unit::find($id);

        if (!$data) {
            return redirect()->back()->with('error', 'Data unit tidak ditemukan');
        }

        $rooms = Room::with('company')->select(['roomId', 'roomName', 'compId'])->get();

        return view('units.edit', compact('data', 'rooms'));
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(UnitUpdateRequest $request, string $id)
    {
        $data = Unit::find($id);

        if (!$data) {
            return redirect()->back()->with('error', 'Data unit tidak ditemukan');
        }

        $validatedData = $request->validated();
        $data->update($validatedData);

        return redirect()->route('units.index')->with('success', 'Unit berhasil diubah');
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(string $id)
    {
        $data = Unit::find($id);

        if (!$data) {
            return redirect()->back()->with('error', 'Data unit tidak ditemukan');
        }

        $data->delete();

        return redirect()->route('units.index')->with('success', 'Unit berhasil dihapus');
    }

    /**
     * Download QR Code PDF
     */
    public function downloadQrCode(string $id, string $format = 'label')
    {
        try {
            return UnitQrCodeService::downloadPdf($id, $format);
        } catch (\Exception $e) {
            return redirect()->back()->with('error', 'Gagal download PDF: ' . $e->getMessage());
        }
    }

    /**
     * Send QR Code via email
     */
    public function sendQrCodeEmail(string $id, Request $request)
    {
        try {
            $validated = $request->validate([
                'email' => 'required|email',
                'format' => 'required|in:label,certificate,tag',
                'message' => 'nullable|string|max:500',
            ]);

            $success = UnitEmailService::sendQrCodeEmail(
                $id,
                $validated['email'],
                $validated['format'],
                $validated['message'] ?? null
            );

            if ($success) {
                return redirect()->back()->with('success', 'QR Code berhasil dikirim ke ' . $validated['email']);
            } else {
                return redirect()->back()->with('error', 'Gagal mengirim QR Code');
            }
        } catch (\Exception $e) {
            return redirect()->back()->with('error', 'Error: ' . $e->getMessage());
        }
    }

    /**
     * Bulk download QR Code PDF untuk multiple units
     */
    public function bulkDownloadQrCode(Request $request)
    {
        try {
            $validated = $request->validate([
                'unit_ids' => 'required|array',
                'unit_ids.*' => 'string|exists:units,unitId',
                'format' => 'required|in:label,certificate,tag',
            ]);

            $pdf = UnitQrCodeService::generatePdf(
                $validated['unit_ids'][0],
                $validated['format']
            );

            // Jika multiple, generate bulk
            if (count($validated['unit_ids']) > 1) {
                $pdfPath = UnitQrCodeService::generateBulkPdf(
                    $validated['unit_ids'],
                    $validated['format']
                );
                return response()->download(storage_path("app/public/{$pdfPath}"));
            }

            return $pdf->download("Units-{$validated['format']}.pdf");
        } catch (\Exception $e) {
            return redirect()->back()->with('error', 'Gagal download: ' . $e->getMessage());
        }
    }

    /**
     * Send bulk QR Code via email
     */
    public function bulkSendQrCodeEmail(Request $request)
    {
        try {
            $validated = $request->validate([
                'unit_ids' => 'required|array',
                'unit_ids.*' => 'string|exists:units,unitId',
                'email' => 'required|email',
                'format' => 'required|in:label,certificate,tag',
                'message' => 'nullable|string|max:500',
            ]);

            $success = UnitEmailService::sendBulkQrCodeEmail(
                $validated['unit_ids'],
                $validated['email'],
                $validated['format'],
                $validated['message'] ?? null
            );

            if ($success) {
                return redirect()->back()->with('success', 'QR Code untuk ' . count($validated['unit_ids']) . ' units berhasil dikirim');
            } else {
                return redirect()->back()->with('error', 'Gagal mengirim QR Code');
            }
        } catch (\Exception $e) {
            return redirect()->back()->with('error', 'Error: ' . $e->getMessage());
        }
    }

    /**
     * Print preview QR Code
     */
    public function printPreview(string $id, string $format = 'label')
    {
        try {
            $unit = Unit::with('room', 'company')->find($id);

            if (!$unit) {
                return redirect()->back()->with('error', 'Unit tidak ditemukan');
            }

            $qrBase64 = UnitQrCodeService::generateQrCode($id, 300);

            return view('units.print-preview', [
                'unit' => $unit,
                'qrCode' => $qrBase64,
                'format' => $format,
            ]);
        } catch (\Exception $e) {
            return redirect()->back()->with('error', 'Error: ' . $e->getMessage());
        }
    }
}
