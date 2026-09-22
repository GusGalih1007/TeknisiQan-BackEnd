<?php

namespace Database\Seeders;

use App\Enums\RoleOption;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use App\Models\User;
use Illuminate\Database\Seeder;

class SuperAdminSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        User::create([
            'name' => 'Super Admin',
            'email' => 'superadmin@teknisiqan.com',
            'phone' => '088299223303',
            'password' => bcrypt('1234567890'),
            'role' => RoleOption::SuperAdmin->value,
            'photo' => null,
            'compId' => null
        ]);
    }
}
