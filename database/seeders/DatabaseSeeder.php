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
            'name' => 'Super Admin Pusat',
            'username' => 'superadmin',
            'email' => 'superadmin@kebumen.go.id',
            'password' => Hash::make('password'),
            'role_id' => $roleSuperAdmin->id,
            'bidang_id' => null,
        ]);

        User::create([
            'name' => 'Bendahara Dinas',
            'username' => 'bendahara',
            'email' => 'bendahara@kebumen.go.id',
            'password' => Hash::make('password'),
            'role_id' => $roleBendahara->id,
            'bidang_id' => null,
        ]);

        User::create([
            'name' => 'Kepala Dinas',
            'username' => 'kadin',
            'email' => 'kadin@kebumen.go.id',
            'password' => Hash::make('password'),
            'role_id' => $roleKadin->id,
            'bidang_id' => null,
        ]);

        User::create([
            'name' => 'Sekretaris Dinas',
            'username' => 'sekdin',
            'email' => 'sekdin@kebumen.go.id',
            'password' => Hash::make('password'),
            'role_id' => $roleSekdin->id,
            'bidang_id' => null,
        ]);

        User::create([
            'name' => 'Kabid PI',
            'username' => 'kabid_pi',
            'email' => 'kabid.pi@kebumen.go.id',
            'password' => Hash::make('password'),
            'role_id' => $roleKabid->id,
            'bidang_id' => $bidangPI->id,
        ]);

        User::create([
            'name' => 'Operator PI',
            'username' => 'operator_pi',
            'email' => 'operator.pi@kebumen.go.id',
            'password' => Hash::make('password'),
            'role_id' => $roleOperator->id,
            'bidang_id' => $bidangPI->id,
        ]);
    }
}
