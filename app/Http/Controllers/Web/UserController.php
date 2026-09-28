<?php

namespace App\Http\Controllers\Web;

use App\Enums\RoleOption;
use App\Http\Controllers\Controller;
use App\Http\Requests\UserStoreRequest;
use App\Http\Requests\UserUpdateRequest;
use App\Models\Company;
use App\Models\User;
use Carbon\Carbon;
use Exception;
use Illuminate\Http\Request;
use Log;

class UserController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index(Request $request)
    {
        $query = User::with('company');

        if ($request->has('search') && ! empty($request->search)) {
            $search = $request->search;
            $query->where('name', 'like', "%$search%")
                ->orWhere('email', 'like', "%$search%");
        }

        $data = $query->paginate(10);

        // Statistik Total Users
        $totalUsers = User::count();

        // Statistik Users Bulan Ini
        $startOfMonth = Carbon::now()->startOfMonth();
        $endOfMonth = Carbon::now()->endOfMonth();
        $usersThisMonth = User::whereBetween('created_at', [$startOfMonth, $endOfMonth])->count();

        return view('user.index', compact('data', 'totalUsers', 'usersThisMonth'));
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create()
    {
        $user = auth()->user();
        $companies = [];
        $roles = [];
        $pageTitle = 'Tambah User';

        if ($user->role->value == 'superadmin') {
            // Super Admin dapat menambah semua role dengan dropdown company
            $companies = Company::select(['compId', 'name'])->get();
            $roles = RoleOption::cases();
            $pageTitle = 'Tambah User';
        } elseif ($user->role->value == 'admin') {
            // Admin hanya dapat menambah technician dan hanya untuk perusahaannya
            $companies = Company::where('compId', $user->compId)->select(['compId', 'name'])->get();
            $pageTitle = 'Tambah Teknisi';
        }

        return view('user.create', compact('companies', 'roles', 'pageTitle'));
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(UserStoreRequest $request)
    {
        try {
            $user = auth()->user();
            $validatedData = $request->validated();

            if ($user->role->value == 'admin') {
                $validatedData['compId'] = $user->compId;
                $validatedData['role'] = RoleOption::Technician->value;
            }

            // Handle photo upload
            if ($request->hasFile('photo')) {
                $file = $request->file('photo');
                $filename = time().'_'.uniqid().'.'.$file->getClientOriginalExtension();
                $file->storeAs('photos', $filename, 'public');
                $validatedData['photo'] = 'photos/'.$filename;
            }

            User::create($validatedData);

            return redirect()->route('users.index')->with('success', 'User berhasil ditambahkan');

        } catch (Exception $e) {
            Log::error($e);

            return redirect()->back()->with('error', 'Terjadi kesalahan: '.$e->getMessage());
        }
    }

    /**
     * Display the specified resource.
     */
    public function show(string $id)
    {
        $data = User::find($id);

        if (! $data) {
            return redirect()->back()->with('error', 'Data tidak ditemukan');
        }

        return view();
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(string $id)
    {
        $data = User::find($id);

        if (! $data) {
            return redirect()->route('users.index')->with('error', 'Data tidak ditemukan');
        }

        $user = auth()->user();
        $companies = [];
        $roles = [];
        $pageTitle = 'Edit User';

        if ($user->role->value == 'superadmin') {
            // Super Admin dapat edit semua user dengan dropdown company
            $companies = Company::select(['compId', 'name'])->get();
            $roles = RoleOption::cases();
            $pageTitle = 'Edit User';
        } elseif ($user->role->value == 'admin') {
            // Admin hanya dapat edit technician di perusahaannya
            $companies = Company::where('compId', $user->compId)->select(['compId', 'name'])->get();
            $pageTitle = 'Edit Teknisi';
        }

        return view('user.edit', compact('data', 'companies', 'roles', 'pageTitle'));
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(UserUpdateRequest $request, string $id)
    {
        try {
            $user = auth()->user();
            $data = User::find($id);

            if (! $data) {
                return redirect()->route('users.index')->with('error', 'Data tidak ditemukan');
            }

            $validatedData = $request->validated();

            if ($user->role->value == 'admin') {
                $validatedData['compId'] = $user->compId;
                $validatedData['role'] = RoleOption::Technician->value;
            }

            // Handle photo upload
            if ($request->hasFile('photo')) {
                // Delete old photo if exists
                if ($data->photo && file_exists(public_path('storage/'.$data->photo))) {
                    unlink(public_path('storage/'.$data->photo));
                }

                $file = $request->file('photo');
                $filename = time().'_'.uniqid().'.'.$file->getClientOriginalExtension();
                $file->storeAs('photos', $filename, 'public');
                $validatedData['photo'] = 'photos/'.$filename;
            }

            $data->update($validatedData);

            return redirect()->route('users.index')->with('success', 'User berhasil diperbarui');

        } catch (Exception $e) {
            Log::error($e);

            return redirect()->back()->with('error', 'Terjadi kesalahan: '.$e->getMessage());
        }
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(string $id)
    {
        try {
            $user = User::find($id);

            if (! $user) {
                return back()->with('error', 'Data tidak ditemukan');
            }

            // Delete photo if exists
            if ($user->photo && file_exists(public_path('storage/'.$user->photo))) {
                unlink(public_path('storage/'.$user->photo));
            }

            $user->delete();

            return redirect()->back()->with('success', 'User berhasil dihapus');
        } catch (Exception $e) {
            Log::error($e);

            return back()->with('error', 'Terjadi kesalahan: '.$e->getMessage());
        }
    }
}
