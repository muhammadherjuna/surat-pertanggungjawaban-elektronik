<?php
namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\Hash;
use App\Models\User;
use App\Models\Role;
use App\Models\Bidang;

class DatabaseSeeder extends Seeder
{
    public function run(): void
    {
        // Create Roles
        $roleOperator = Role::create(['name' => 'Operator', 'level' => 0]);
        $roleKabid = Role::create(['name' => 'Kabid', 'level' => 1]);
        $roleSekdin = Role::create(['name' => 'Sekdin', 'level' => 2]);
        $roleKadin = Role::create(['name' => 'Kadin', 'level' => 3]);
        $roleBendahara = Role::create(['name' => 'Bendahara / Admin Dinas', 'level' => 4]);
        $roleSuperAdmin = Role::create(['name' => 'Super Admin', 'level' => 5]);

        // Create Bidang
        $bidangPI = Bidang::create(['nama_bidang' => 'Pengembangan Informatika', 'unit_kerja' => 'Dinkominfo']);
        $bidangIKP = Bidang::create(['nama_bidang' => 'Informasi dan Komunikasi Publik', 'unit_kerja' => 'Dinkominfo']);

        // Create Users
        User::create([
            'name' => 'Budi Santoso, S.Kom.',
            'username' => 'superadmin',
            'email' => 'budisantoso@kebumen.go.id',
            'password' => Hash::make('password'),
            'role_id' => $roleSuperAdmin->id,
            'bidang_id' => null,
        ]);

        User::create([
            'name' => 'Rina Amelia, S.E.',
            'username' => 'bendahara',
            'email' => 'rina.bendahara@kebumen.go.id',
            'password' => Hash::make('password'),
            'role_id' => $roleBendahara->id,
            'bidang_id' => null,
        ]);

        User::create([
            'name' => 'Drs. H. Ahmad Fauzi, M.Si.',
            'username' => 'kadin',
            'email' => 'ahmad.fauzi@kebumen.go.id',
            'password' => Hash::make('password'),
            'role_id' => $roleKadin->id,
            'bidang_id' => null,
        ]);

        User::create([
            'name' => 'Ir. Budi Hartono, M.T.',
            'username' => 'sekdin',
            'email' => 'budi.hartono@kebumen.go.id',
            'password' => Hash::make('password'),
            'role_id' => $roleSekdin->id,
            'bidang_id' => null,
        ]);

        User::create([
            'name' => 'Siti Rahmawati, S.Kom., M.Eng.',
            'username' => 'kabid_pi',
            'email' => 'siti.rahma@kebumen.go.id',
            'password' => Hash::make('password'),
            'role_id' => $roleKabid->id,
            'bidang_id' => $bidangPI->id,
        ]);

        User::create([
            'name' => 'Dwi Cahyono',
            'username' => 'operator_pi',
            'email' => 'dwi.operator@kebumen.go.id',
            'password' => Hash::make('password'),
            'role_id' => $roleOperator->id,
            'bidang_id' => $bidangPI->id,
        ]);
    }
}
