<?php

namespace App\Http\Controllers\Web;

use App\Http\Controllers\Controller;
use App\Models\Report;
use Illuminate\Http\Request;

class TicketController extends Controller
{
    /**
     * API: Search for a ticket by ticket number (returns JSON)
     */
    public function apiSearch(Request $request)
    {
        $ticketNumber = $request->input('ticket');

        if (!$ticketNumber) {
            return response()->json(['report' => null, 'message' => 'Masukkan nomor tiket'], 400);
        }

        $report = Report::with('company', 'unit', 'unit.room', 'responses.technician')
            ->where('ticketNumber', $ticketNumber)
            ->first();

        if (!$report) {
            return response()->json(['report' => null, 'message' => 'Tiket tidak ditemukan'], 404);
        }

        return response()->json(['report' => $report, 'message' => 'Tiket ditemukan']);
    }
}
