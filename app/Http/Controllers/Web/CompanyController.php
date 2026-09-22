<?php

namespace App\Http\Controllers\Web;

use App\Http\Controllers\Controller;
use App\Http\Requests\CompanyStoreRequest;
use App\Http\Requests\CompanyUpdateRequest;
use App\Models\Company;
use App\Models\User;
use Illuminate\Http\Request;

class CompanyController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        $data = Company::latest()->get();
        $user = auth()->user();

        if ($user->role == "admin") {
            $data->where('compId', $user->compId);
        }

        return view();
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create()
    {
        $user = User::latest()->select(['userId', 'name'])->get();
        return view();
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(CompanyStoreRequest $request)
    {
        $validatedData = $request->validated();

        $company = Company::create($validatedData);

        return redirect()->route('');
    }

    /**
     * Display the specified resource.
     */
    public function show(string $id)
    {
        $data = Company::find($id);

        if (!$data) {
            return redirect()->back()->with('error', '');
        }

        return view();
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(string $id)
    {
        $data = Company::latest()->get();

        if (! $data) {
            return redirect()->back()->with('error', 'Data tidak ditemukan');
        }

        return view();
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(CompanyUpdateRequest $request, string $id)
    {
        $data = Company::find($id);

        if (! $data) {
            return redirect()->back()->with('error', 'Data tidak ditemukan');
        }

        $validatedData = $request->validated();

        $data->update($validatedData);

        return redirect()->route('');
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(string $id)
    {
        $data = Company::findOrFail($id);

        if (! $data) {
            return redirect()->back()->with('error', 'Data tidak ditemukan');
        }

        $data->delete();

        return redirect()->back()->with('success', 'Data berhasil dihapus');
    }
}
