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

        // Create Master Data: Rekening
        $rekening1 = \App\Models\Rekening::create([
            'kode_rekening' => '5.2.02.03.01.0001',
            'nama_rekening' => 'Belanja Pengadaan Barang/Jasa'
        ]);
        $rekening2 = \App\Models\Rekening::create([
            'kode_rekening' => '5.2.02.03.02.0004',
            'nama_rekening' => 'Belanja Perjalanan Dinas Biasa'
        ]);

        // Create Master Data: Jenis SPJ
        $jenisSpj1 = \App\Models\JenisSpj::create([
            'nama_jenis' => 'Pengadaan Barang/Jasa'
        ]);
        
        $jenisSpj2 = \App\Models\JenisSpj::create([
            'nama_jenis' => 'Perjalanan Dinas'
        ]);

        // Create Master Data: Dokumen Pendukung
        \App\Models\DokumenPendukung::create([
            'jenis_spj_id' => $jenisSpj1->id,
            'nama_dokumen' => 'Surat Perintah Kerja (SPK)',
            'is_wajib' => true
        ]);
        \App\Models\DokumenPendukung::create([
            'jenis_spj_id' => $jenisSpj1->id,
            'nama_dokumen' => 'Kwitansi',
            'is_wajib' => true
        ]);
        \App\Models\DokumenPendukung::create([
            'jenis_spj_id' => $jenisSpj1->id,
            'nama_dokumen' => 'Faktur Pajak',
            'is_wajib' => false
        ]);

        // Create SPJ Data (The ones that went missing)
        $operator = User::where('username', 'operator_pi')->first();
        
        // 1. SPJ Selesai (Sesuai screenshot)
        $spj1 = \App\Models\Spj::create([
            'user_id' => $operator->id,
            'jenis_spj_id' => $jenisSpj1->id,
            'rekening_id' => $rekening1->id,
            'deskripsi' => 'Belanja modal pengadaan 3 (tiga) unit komputer PC dan 1 (satu) unit printer untuk menunjang kegiatan pelayanan publik, sesuai dengan Surat Perintah Kerja (SPK) Nomor: 800/123/SPK/2026 tanggal 5 Juni 2026 kepada CV. Maju Bersama.',
            'nominal' => 37185000,
            'filter_tipe' => 'TU',
            'status_level' => 5, // Selesai / Terverifikasi
            'is_rejected' => false,
            'submitted_at' => \Carbon\Carbon::create(2026, 6, 14, 15, 26, 0),
            'created_at' => \Carbon\Carbon::create(2026, 6, 14, 15, 26, 0),
            'updated_at' => \Carbon\Carbon::now()
        ]);

        // Seed some fake files so the UI doesn't break when looking for uploaded docs
        foreach ($jenisSpj1->dokumenPendukungs as $dokumen) {
            if ($dokumen->is_wajib) {
                \App\Models\SpjDokumen::create([
                    'spj_id' => $spj1->id,
                    'dokumen_pendukung_id' => $dokumen->id,
                    'file_path' => 'dummy_path.pdf'
                ]);
            }
        }

        // 2. SPJ Draft/Baru (Sebagai contoh antrian)
        \App\Models\Spj::create([
            'user_id' => $operator->id,
            'jenis_spj_id' => $jenisSpj2->id,
            'rekening_id' => $rekening2->id,
            'deskripsi' => 'Biaya perjalanan dinas ke Provinsi untuk koordinasi program kerja bulanan.',
            'nominal' => 2500000,
            'filter_tipe' => 'LS',
            'status_level' => 1, // Menunggu Kabid
            'is_rejected' => false,
            'submitted_at' => \Carbon\Carbon::now()->subHours(2),
            'created_at' => \Carbon\Carbon::now()->subHours(2),
            'updated_at' => \Carbon\Carbon::now()->subHours(2)
        ]);
    }
}
