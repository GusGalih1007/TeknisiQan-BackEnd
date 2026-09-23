<?php

namespace App\Http\Controllers\Web;

use App\Enums\RoleOption;
use App\Http\Controllers\Controller;
use App\Http\Requests\UserStoreRequest;
use App\Http\Requests\UserUpdateRequest;
use App\Models\User;
use Exception;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Log;

class UserController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index(Request $request)
    {
        $query = User::with('company');
        
        if ($request->has('search') && !empty($request->search)) {
            $search = $request->search;
            $query->where('name', 'like', "%$search%")
                  ->orWhere('email', 'like', "%$search%");
        }
        
        $data = $query->paginate(10);
        
        return view('user.index', compact('data'));
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create()
    {
        return view();
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(UserStoreRequest $request)
    {
        try {

            $user = auth()->user();
            $validatedData = $request->validated();

            if ($user->role == 'admin') {
                $validatedData['compId'] = $user->compId;
                $validatedData['role'] = RoleOption::Technician->value;
            }

            User::create($validatedData);

            // return response()->json($user);
            return redirect()->route('')->with('success', '');

        } catch (Exception $e) {
            Log::error($e);

            return redirect()->back()->with('error', $e);
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
            return redirect()->route('')->with('error', '');
        }

        return view();
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(UserUpdateRequest $request, string $id)
    {
        $user = auth()->user();

        $data = User::find($id);

        if (! $data) {
            return redirect()->route('')->with('error', '');
        }

        $validatedData = $request->validated();

        if ($user->role == 'admin') {
            $validatedData['compId'] = $user->compId;
            $validatedData['role'] = RoleOption::Technician->value;
        }

        $data->update($validatedData);

        return redirect()->route('')->with('success', '');
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(string $id)
    {
        $user = User::find($id);

        if (! $user) {
            return back()->with('error', '');
        }

        $user->delete();

        return redirect()->back()->with('success', '');
    }
}
