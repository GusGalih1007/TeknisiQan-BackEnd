<?php

namespace Database\Seeders;

use App\Models\User;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use App\Models\Company;

class InitCompanySeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $superadmin = User::where('role', 'superadmin')->oldest()->first();;

        $company = Company::create([
            'compId' => null,
            'name' => 'PT. Teknisi Qan',
            'address' => 'Jl. Contoh Alamat No. 123, Jakarta',
            'phone' => '081234567890',
            'email' => 'info@teknisiquan.com'
        ]);

        $superadmin->compId = $company->compId;
        $superadmin->save();
    }
}
