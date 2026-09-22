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
    public function index()
    {
        $user = auth()->user();
        $data = Room::latest()->get();

        if (! $user->role == 'admin') {
            $data->where('compId', $user->compId);
        }

        return view();
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create()
    {
        $user = auth()->user();
        $company = Company::where('compId', $user->compId)->select(['compId', 'name'])->get();

        return view();
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(RoomStoreRequest $request)
    {
        $validatedData = $request->validated();

        Room::create($validatedData);

        return redirect()->route('')->with('success', 'Data berhasil dibuat');
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

        if(! $data) {
            return redirect()->back()->with('error', 'Data tidak ditemukan');
        }

        $company = Company::where('compId', $user->compId)->select(['compId', 'name'])->get();

        return view();
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(RoomUpdateRequest $request, string $id)
    {
        $data = Room::find($id);

        if (! $data) {
            return redirect()->route('')->with('error', 'Data tidak ditemukan');
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
        $data = Room::find($id);

        if (! $data) {
            return redirect()->back()->with('error', 'Data tidak ditemukan');
        }

        $data->delete();

        return redirect()->route('')->with('success', 'Data berhasil dihapus');
    }
}
