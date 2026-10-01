<?php

namespace App\Http\Controllers\Web;

use App\Http\Controllers\Controller;
use App\Http\Requests\UnitStoreRequest;
use App\Http\Requests\UnitUpdateRequest;
use App\Models\Room;
use App\Models\Unit;
use App\Services\UnitNumberGeneratorService;
use App\Services\UnitQrCodeService;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Storage;
use Throwable;

class UnitController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index(Request $request)
    {
        $user = auth()->user();
        $query = Unit::with('company', 'room')->latest();

        if ($user->role->value !== 'superadmin') {
            $query->where('compId', $user->compId);
        }

        $query->when($request->filled('search'), function ($query) use ($request) {
            $search = trim((string) $request->input('search'));

            $query->where(function ($query) use ($search) {
                $query->where('unitNumber', 'like', "%{$search}%")
                    ->orWhere('unitName', 'like', "%{$search}%")
                    ->orWhereHas('room', fn ($room) => $room->where('roomName', 'like', "%{$search}%"))
                    ->orWhereHas('company', fn ($company) => $company->where('name', 'like', "%{$search}%"));
            });
        });

        $data = $query->paginate(10)->withQueryString();

        return view('units.index', compact('data'));
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create()
    {
        $this->ensureCanManageUnits();
        $rooms = $this->availableRooms();

        return view('units.create', compact('rooms'));
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(UnitStoreRequest $request)
    {
        $validatedData = $request->validated();
        $room = $this->findAvailableRoom($validatedData['roomId']);
        $photoPath = null;

        try {
            if ($request->hasFile('photo')) {
                $photoPath = $request->file('photo')->store('units', 'public');
            }

            $unit = DB::transaction(function () use ($validatedData, $room, $photoPath) {
                return Unit::create([
                    'unitName' => $validatedData['unitName'],
                    'unitNumber' => UnitNumberGeneratorService::generateByRoom($room->roomId, $room->roomName),
                    'roomId' => $room->roomId,
                    'compId' => $room->compId,
                    'photo' => $photoPath,
                ]);
            });
        } catch (Throwable $exception) {
            if ($photoPath) {
                Storage::disk('public')->delete($photoPath);
            }

            report($exception);

            return back()->withInput()->with('error', 'Unit gagal disimpan. Silakan coba kembali.');
        }

        return redirect()->route('units.index')->with('success', 'Unit berhasil dibuat dengan nomor: ' . $unit->unitNumber);
    }

    /**
     * Display the specified resource.
     */
    public function show(string $id)
    {
        $data = $this->findAvailableUnit($id, ['company', 'room']);

        return view('units.show', compact('data'));
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(string $id)
    {
        $this->ensureCanManageUnits();
        $data = $this->findAvailableUnit($id);
        $rooms = $this->availableRooms();

        return view('units.edit', compact('data', 'rooms'));
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(UnitUpdateRequest $request, string $id)
    {
        $data = $this->findAvailableUnit($id);
        $validatedData = $request->validated();
        $room = $this->findAvailableRoom($validatedData['roomId']);
        $oldPhoto = $data->photo;
        $newPhoto = null;

        try {
            if ($request->hasFile('photo')) {
                $newPhoto = $request->file('photo')->store('units', 'public');
            }

            DB::transaction(function () use ($data, $validatedData, $room, $newPhoto, $request) {
                $attributes = [
                    'unitName' => $validatedData['unitName'],
                    'roomId' => $room->roomId,
                    'compId' => $room->compId,
                ];

                if ($data->roomId !== $room->roomId) {
                    $attributes['unitNumber'] = UnitNumberGeneratorService::generateByRoom($room->roomId, $room->roomName);
                }

                if ($newPhoto) {
                    $attributes['photo'] = $newPhoto;
                } elseif ($request->boolean('removePhoto')) {
                    $attributes['photo'] = null;
                }

                $data->update($attributes);
            });
        } catch (Throwable $exception) {
            if ($newPhoto) {
                Storage::disk('public')->delete($newPhoto);
            }

            report($exception);

            return back()->withInput()->with('error', 'Unit gagal diperbarui. Silakan coba kembali.');
        }

        if ($oldPhoto && ($newPhoto || $request->boolean('removePhoto'))) {
            Storage::disk('public')->delete($oldPhoto);
        }

        return redirect()->route('units.index')->with('success', 'Unit berhasil diubah');
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(string $id)
    {
        $this->ensureCanManageUnits();
        $data = $this->findAvailableUnit($id);

        if ($data->reports()->exists()) {
            return back()->with('error', 'Unit tidak dapat dihapus karena sudah memiliki laporan kerusakan.');
        }

        try {
            $photo = $data->photo;
            $data->delete();

            if ($photo) {
                Storage::disk('public')->delete($photo);
            }
        } catch (Throwable $exception) {
            report($exception);

            return back()->with('error', 'Unit gagal dihapus karena masih digunakan oleh data lain.');
        }

        return redirect()->route('units.index')->with('success', 'Unit berhasil dihapus');
    }

    /**
     * Print QR Code
     */
    public function printPreview(string $id, string $format = 'label')
    {
        try {
            $unit = $this->findAvailableUnit($id, ['room', 'company']);

            $qrBase64 = UnitQrCodeService::generateQrCode($id, 300);

            return view('units.print-preview', [
                'unit' => $unit,
                'qrCode' => $qrBase64,
                'format' => $format,
            ]);
        } catch (\Exception $e) {
            return redirect()->back()->with('error', 'Error: ' . $e->getMessage());
        }
    }

    private function availableRooms()
    {
        $user = auth()->user();

        return Room::with('company')
            ->select(['roomId', 'roomName', 'compId'])
            ->when($user->role->value !== 'superadmin', fn ($query) => $query->where('compId', $user->compId))
            ->orderBy('roomName')
            ->get();
    }

    private function findAvailableRoom(string $roomId): Room
    {
        $user = auth()->user();

        return Room::query()
            ->when($user->role->value !== 'superadmin', fn ($query) => $query->where('compId', $user->compId))
            ->findOrFail($roomId);
    }

    private function findAvailableUnit(string $unitId, array $relations = []): Unit
    {
        $user = auth()->user();

        return Unit::with($relations)
            ->when($user->role->value !== 'superadmin', fn ($query) => $query->where('compId', $user->compId))
            ->findOrFail($unitId);
    }

    private function ensureCanManageUnits(): void
    {
        abort_unless(
            in_array(auth()->user()->role->value, ['superadmin', 'admin'], true),
            403,
            'Anda tidak memiliki akses untuk mengelola unit.'
        );
    }
}
