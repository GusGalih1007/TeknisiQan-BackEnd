<?php

namespace App\Http\Controllers\Web;

use App\Http\Controllers\Controller;
use App\Models\Report;
use Illuminate\Http\Request;

class TicketController extends Controller
{
    /**
     * Search for a ticket by ticket number
     */
    public function search(Request $request)
    {
        $ticketNumber = $request->input('ticket');
        $report = null;

        if ($ticketNumber) {
            $report = Report::with('company', 'unit', 'unit.room', 'responses.technician')
                ->where('ticketNumber', $ticketNumber)
                ->first();
        }

        return view('tickets.search', compact('report'));
    }
}
