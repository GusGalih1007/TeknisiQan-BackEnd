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
    public function index(Request $request)
    {
        $user = auth()->user();
        $query = Company::with('leader')->latest();

        if ($user->role->value == "admin") {
            $query->where('compId', $user->compId);
        }

        if ($request->has('search') && !empty($request->search)) {
            $search = $request->search;
            $query->where('name', 'like', "%$search%")
                  ->orWhere('address', 'like', "%$search%");
        }

        $data = $query->paginate(10);

        return view('company.index', compact('data'));
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create()
    {
        $leaders = User::where('role', 'admin')->select(['userId', 'name', 'email'])->get();
        return view('company.create', compact('leaders'));
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(CompanyStoreRequest $request)
    {
        try {
            $validatedData = $request->validated();

            // Handle logo upload
            if ($request->hasFile('logo')) {
                $file = $request->file('logo');
                $filename = time() . '_' . uniqid() . '.' . $file->getClientOriginalExtension();
                $file->storeAs('logos', $filename, 'public');
                $validatedData['logo'] = 'logos/' . $filename;
            }

            Company::create($validatedData);

            return redirect()->route('companies.index')->with('success', 'Instansi berhasil ditambahkan');
        } catch (\Exception $e) {
            return redirect()->back()->with('error', 'Terjadi kesalahan: ' . $e->getMessage());
        }
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
        $data = Company::find($id);

        if (! $data) {
            return redirect()->route('companies.index')->with('error', 'Data tidak ditemukan');
        }

        $leaders = User::where('role', 'admin')->select(['userId', 'name', 'email'])->get();

        return view('company.edit', compact('data', 'leaders'));
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(CompanyUpdateRequest $request, string $id)
    {
        try {
            $data = Company::find($id);

            if (! $data) {
                return redirect()->route('companies.index')->with('error', 'Data tidak ditemukan');
            }

            $validatedData = $request->validated();

            // Handle logo upload
            if ($request->hasFile('logo')) {
                // Delete old logo if exists
                if ($data->logo && file_exists(public_path('storage/' . $data->logo))) {
                    unlink(public_path('storage/' . $data->logo));
                }

                $file = $request->file('logo');
                $filename = time() . '_' . uniqid() . '.' . $file->getClientOriginalExtension();
                $file->storeAs('logos', $filename, 'public');
                $validatedData['logo'] = 'logos/' . $filename;
            }

            $data->update($validatedData);

            return redirect()->route('companies.index')->with('success', 'Instansi berhasil diperbarui');
        } catch (\Exception $e) {
            return redirect()->back()->with('error', 'Terjadi kesalahan: ' . $e->getMessage());
        }
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(string $id)
    {
        try {
            $data = Company::find($id);

            if (! $data) {
                return redirect()->back()->with('error', 'Data tidak ditemukan');
            }

            // Delete logo if exists
            if ($data->logo && file_exists(public_path('storage/' . $data->logo))) {
                unlink(public_path('storage/' . $data->logo));
            }

            $data->delete();

            return redirect()->back()->with('success', 'Instansi berhasil dihapus');
        } catch (\Exception $e) {
            return redirect()->back()->with('error', 'Terjadi kesalahan: ' . $e->getMessage());
        }
    }
}
