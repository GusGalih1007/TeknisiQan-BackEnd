<?php

namespace App\Http\Controllers\Web;

use App\Http\Controllers\Controller;
use App\Http\Requests\UnitStoreRequest;
use App\Http\Requests\UnitUpdateRequest;
use App\Models\Unit;
use App\Models\Company;
use Illuminate\Http\Request;

class UnitController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        $data = Unit::latest()->get();
        $user = auth()->user();

        if ($user->role == 'admin') {
            $data->where('compId', $user->compId);
        }

        return view();
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create()
    {
        $company = Company::all();
        
        return view();
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(UnitStoreRequest $request)
    {
        $user = auth()->user();
        $validatedData = $request->validated();
        $unit = Unit::create($validatedData);

        return redirect()-route('');
    }

    /**
     * Display the specified resource.
     */
    public function show(string $id)
    {
        $data = Unit::find($id);

        if (! $data) {
            return redirect()->back()->with('error', '');
        }

        return view('', compact('data'));
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(string $id)
    {
        $data = Unit::find($id);

        return view();
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(UnitUpdateRequest $request, string $id)
    {
        $data = Unit::find($id);

        if (! $data) {
            return redirect()->back()->with('error', 'Data tidak ditemukan');
        }

        $validatedData = $request->validated();

        $data->update($validatedData);

        return redirect()->route('')->with('success', 'Data berhasil diubah');
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(string $id)
    {
        $data = Unit::findOrFail($id);

        if (! $data) {
            return redirect()->back()->with('error', 'Data tidak ditemukan');
        }

        $data->delete();

        return redirect()->route('')->with('success', 'Data berhasil dihapus');
    }
}
