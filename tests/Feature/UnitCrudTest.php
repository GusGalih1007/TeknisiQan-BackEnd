<?php

namespace Tests\Feature;

use App\Enums\RoleOption;
use App\Models\Company;
use App\Models\Room;
use App\Models\Unit;
use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Http\UploadedFile;
use Illuminate\Support\Facades\Storage;
use Tests\TestCase;

class UnitCrudTest extends TestCase
{
    use RefreshDatabase;

    public function test_superadmin_can_create_unit_with_photo(): void
    {
        Storage::fake('public');
        [$user, $company, $room] = $this->createContext(RoleOption::SuperAdmin);

        $response = $this->actingAs($user)->post(route('units.store'), [
            'unitName' => 'Komputer Kasir',
            'roomId' => $room->roomId,
            'photo' => UploadedFile::fake()->image('unit.jpg'),
        ]);

        $response->assertRedirect(route('units.index'));
        $unit = Unit::where('unitName', 'Komputer Kasir')->firstOrFail();

        $this->assertSame($company->compId, $unit->compId);
        $this->assertSame($room->roomId, $unit->roomId);
        $this->assertSame('RU-001', $unit->unitNumber);
        Storage::disk('public')->assertExists($unit->photo);
    }

    public function test_admin_cannot_create_unit_in_another_company_room(): void
    {
        [$admin] = $this->createContext(RoleOption::Admin);
        [, , $otherRoom] = $this->createContext(RoleOption::Admin);

        $response = $this->actingAs($admin)->post(route('units.store'), [
            'unitName' => 'Unit Tidak Sah',
            'roomId' => $otherRoom->roomId,
        ]);

        $response->assertNotFound();
        $this->assertDatabaseMissing('units', ['unitName' => 'Unit Tidak Sah']);
    }

    public function test_technician_cannot_create_unit(): void
    {
        [$technician, , $room] = $this->createContext(RoleOption::Technician);

        $response = $this->actingAs($technician)->post(route('units.store'), [
            'unitName' => 'Unit Teknisi',
            'roomId' => $room->roomId,
        ]);

        $response->assertForbidden();
    }

    public function test_index_searches_unit_number_name_and_room(): void
    {
        [$admin, $company, $room] = $this->createContext(RoleOption::Admin);
        Unit::create([
            'unitNumber' => 'RU-001',
            'unitName' => 'Laptop Operasional',
            'compId' => $company->compId,
            'roomId' => $room->roomId,
        ]);

        $this->actingAs($admin)
            ->get(route('units.index', ['search' => 'Laptop']))
            ->assertOk()
            ->assertSee('Laptop Operasional');

        $this->actingAs($admin)
            ->get(route('units.index', ['search' => 'tidak-ada']))
            ->assertOk()
            ->assertDontSee('Laptop Operasional');
    }

    public function test_updating_room_regenerates_number_and_can_remove_photo(): void
    {
        Storage::fake('public');
        [$admin, $company, $room] = $this->createContext(RoleOption::Admin);
        $secondRoom = Room::create(['roomName' => 'Gudang', 'compId' => $company->compId]);
        Storage::disk('public')->put('units/old.jpg', 'old');

        $unit = Unit::create([
            'unitNumber' => 'RU-001',
            'unitName' => 'Printer',
            'compId' => $company->compId,
            'roomId' => $room->roomId,
            'photo' => 'units/old.jpg',
        ]);

        $response = $this->actingAs($admin)->put(route('units.update', $unit->unitId), [
            'unitName' => 'Printer Warna',
            'roomId' => $secondRoom->roomId,
            'removePhoto' => '1',
        ]);

        $response->assertRedirect(route('units.index'));
        $unit->refresh();

        $this->assertSame('Printer Warna', $unit->unitName);
        $this->assertSame($secondRoom->roomId, $unit->roomId);
        $this->assertSame('GU-001', $unit->unitNumber);
        $this->assertNull($unit->photo);
        Storage::disk('public')->assertMissing('units/old.jpg');
    }

    public function test_admin_cannot_view_unit_from_another_company(): void
    {
        [$admin] = $this->createContext(RoleOption::Admin);
        [, $otherCompany, $otherRoom] = $this->createContext(RoleOption::Admin);
        $unit = Unit::create([
            'unitNumber' => 'RU-001',
            'unitName' => 'Unit Perusahaan Lain',
            'compId' => $otherCompany->compId,
            'roomId' => $otherRoom->roomId,
        ]);

        $this->actingAs($admin)
            ->get(route('units.show', $unit->unitId))
            ->assertNotFound();
    }

    public function test_unit_without_reports_can_be_deleted_with_its_photo(): void
    {
        Storage::fake('public');
        [$admin, $company, $room] = $this->createContext(RoleOption::Admin);
        Storage::disk('public')->put('units/delete.jpg', 'photo');
        $unit = Unit::create([
            'unitNumber' => 'RU-001',
            'unitName' => 'Unit Dihapus',
            'compId' => $company->compId,
            'roomId' => $room->roomId,
            'photo' => 'units/delete.jpg',
        ]);

        $this->actingAs($admin)
            ->delete(route('units.destroy', $unit->unitId))
            ->assertRedirect(route('units.index'));

        $this->assertDatabaseMissing('units', ['unitId' => $unit->unitId]);
        Storage::disk('public')->assertMissing('units/delete.jpg');
    }

    private function createContext(RoleOption $role): array
    {
        $user = User::create([
            'name' => fake()->name(),
            'email' => fake()->unique()->safeEmail(),
            'phone' => fake()->numerify('08##########'),
            'password' => 'password',
            'role' => $role,
            'photo' => json_encode([]),
        ]);

        $company = Company::create([
            'name' => fake()->unique()->company(),
            'leaderId' => null,
            'address' => fake()->address(),
            'logo' => 'logo.png',
        ]);

        $user->update(['compId' => $company->compId]);
        $room = Room::create([
            'roomName' => 'Ruang Utama',
            'compId' => $company->compId,
        ]);

        return [$user->fresh(), $company, $room];
    }
}
