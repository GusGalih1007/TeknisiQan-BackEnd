<?php

namespace App\Http\Controllers\Web;

use App\Http\Controllers\Controller;
use App\Http\Requests\RoomStoreRequest;
use App\Http\Requests\RoomUpdateRequest;
use App\Models\Room;
use App\Models\Company;
use Illuminate\Http\Request;

class RoomController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index(Request $request)
    {
        $user = auth()->user();
        $query = Room::with('company')->latest();

        // Superadmin dapat melihat semua, admin hanya miliknya
        if ($user->role->value == 'admin') {
            $query->where('compId', $user->compId);
        }

        if ($request->has('search') && !empty($request->search)) {
            $search = $request->search;
            $query->where('roomName', 'like', "%$search%");
        }

        $data = $query->paginate(10);

        return view('room.index', compact('data'));
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create()
    {
        $user = auth()->user();
        
        if ($user->role->value != 'admin' && $user->role->value != 'superadmin') {
            return redirect()->route('rooms.index')->with('error', 'Hanya admin yang dapat membuat ruangan');
        }

        if ($user->role->value == 'superadmin') {
            $companies = Company::select(['compId', 'name'])->get();
        } else {
            $companies = Company::where('compId', $user->compId)->select(['compId', 'name'])->get();
        }

        return view('room.create', compact('companies'));
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(RoomStoreRequest $request)
    {
        try {
            $user = auth()->user();

            if ($user->role->value != 'admin' && $user->role->value != 'superadmin') {
                return redirect()->route('rooms.index')->with('error', 'Hanya admin yang dapat membuat ruangan');
            }

            $validatedData = $request->validated();
            
            // Jika superadmin, gunakan compId dari request, jika admin gunakan miliknya
            if ($user->role->value == 'admin') {
                $validatedData['compId'] = $user->compId;
            }

            Room::create($validatedData);

            return redirect()->route('rooms.index')->with('success', 'Ruangan berhasil ditambahkan');
        } catch (\Exception $e) {
            return redirect()->back()->with('error', 'Terjadi kesalahan: ' . $e->getMessage());
        }
    }

    /**
     * Display the specified resource.
     */
    public function show(string $id)
    {
        $data = Room::find($id);

        if(! $data) {
            return redirect()->back()->with('error', 'Data tidak ditemukan');
        }

        return view('', compact('data'));
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(string $id)
    {
        $user = auth()->user();
        $data = Room::find($id);

        if (! $data) {
            return redirect()->route('rooms.index')->with('error', 'Data tidak ditemukan');
        }

        if ($user->role->value != 'admin' && $user->role->value != 'superadmin') {
            return redirect()->route('rooms.index')->with('error', 'Hanya admin yang dapat mengedit ruangan');
        }

        if ($user->role->value == 'superadmin') {
            $companies = Company::select(['compId', 'name'])->get();
        } else {
            $companies = Company::where('compId', $user->compId)->select(['compId', 'name'])->get();
        }

        return view('room.edit', compact('data', 'companies'));
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(RoomUpdateRequest $request, string $id)
    {
        try {
            $user = auth()->user();
            $data = Room::find($id);

            if (! $data) {
                return redirect()->route('rooms.index')->with('error', 'Data tidak ditemukan');
            }

            if ($user->role->value != 'admin' && $user->role->value != 'superadmin') {
                return redirect()->route('rooms.index')->with('error', 'Hanya admin yang dapat mengedit ruangan');
            }

            $validatedData = $request->validated();
            
            // Jika admin, pastikan compId tidak berubah
            if ($user->role->value == 'admin') {
                $validatedData['compId'] = $data->compId;
            }

            $data->update($validatedData);

            return redirect()->route('rooms.index')->with('success', 'Ruangan berhasil diperbarui');
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
            $user = auth()->user();
            $data = Room::find($id);

            if (! $data) {
                return redirect()->back()->with('error', 'Data tidak ditemukan');
            }

            if ($user->role->value != 'admin' && $user->role->value != 'superadmin') {
                return redirect()->route('rooms.index')->with('error', 'Hanya admin yang dapat menghapus ruangan');
            }

            $data->delete();

            return redirect()->back()->with('success', 'Ruangan berhasil dihapus');
        } catch (\Exception $e) {
            return redirect()->back()->with('error', 'Terjadi kesalahan: ' . $e->getMessage());
        }
    }
}
