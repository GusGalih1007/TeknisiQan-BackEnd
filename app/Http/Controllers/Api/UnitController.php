<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Models\Unit;
use Illuminate\Http\Request;

class UnitController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        $user = auth()->user();

        $data = Unit::where('compId', '=', $user->compId)->latest()->get();

        return response()->json([
            'status' => 'success',
            'data' => $data
        ], 200);
    }

    /**
     * Display the specified resource.
     */
    public function show(string $id)
    {
        $data = Unit::with('room', 'company')->find($id);

        return response()->json([
            'status' => 'success',
            'data' => $data
        ], 200);
    }

    /**
     * Get units by company
     */
    public function unitsByCompany(string $compId)
    {
        $data = Unit::with('room', 'company')
            ->where('compId', $compId)
            ->latest()
            ->get();

        return response()->json($data, 200);
    }
}
