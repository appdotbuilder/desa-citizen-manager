<?php

namespace Database\Seeders;

use App\Models\Desa;
use App\Models\Dusun;
use App\Models\Rw;
use App\Models\Rt;
use App\Models\User;
use App\Models\LetterType;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\Hash;

class DesaSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        // Create sample village
        $desa = Desa::create([
            'name' => 'Desa Sukamaju',
            'code' => '3201122001',
            'kecamatan' => 'Sukamaju',
            'kabupaten' => 'Bogor',
            'provinsi' => 'Jawa Barat',
            'address' => 'Jl. Raya Sukamaju No. 123',
            'postal_code' => '16710',
            'phone' => '021-12345678',
            'email' => 'admin@sukamaju.desa.id',
            'status' => 'active',
            'subscription_data' => [
                'package' => 'premium',
                'features' => ['citizen_management', 'letter_service', 'news', 'gallery', 'reports']
            ],
            'subscription_expires_at' => now()->addYear(),
        ]);

        // Create hamlets (Dusun)
        $dusuns = [
            ['name' => 'Dusun Mawar', 'code' => 'DUS01'],
            ['name' => 'Dusun Melati', 'code' => 'DUS02'],
            ['name' => 'Dusun Kenanga', 'code' => 'DUS03'],
        ];

        foreach ($dusuns as $dusunData) {
            $dusun = Dusun::create([
                'desa_id' => $desa->id,
                'name' => $dusunData['name'],
                'code' => $dusunData['code'],
                'description' => 'Deskripsi ' . $dusunData['name'],
            ]);

            // Create RWs for each hamlet
            for ($rwNum = 1; $rwNum <= 3; $rwNum++) {
                $rw = Rw::create([
                    'desa_id' => $desa->id,
                    'dusun_id' => $dusun->id,
                    'number' => sprintf('%03d', $rwNum),
                    'name' => 'RW ' . sprintf('%03d', $rwNum),
                    'description' => 'RW ' . sprintf('%03d', $rwNum) . ' ' . $dusunData['name'],
                ]);

                // Create RTs for each RW
                for ($rtNum = 1; $rtNum <= 4; $rtNum++) {
                    Rt::create([
                        'desa_id' => $desa->id,
                        'rw_id' => $rw->id,
                        'number' => sprintf('%03d', $rtNum),
                        'name' => 'RT ' . sprintf('%03d', $rtNum),
                        'description' => 'RT ' . sprintf('%03d', $rtNum) . '/RW ' . sprintf('%03d', $rwNum),
                    ]);
                }
            }
        }

        // Create super admin
        $superAdmin = User::create([
            'name' => 'Super Admin',
            'email' => 'super@desadigital.com',
            'password' => Hash::make('password'),
            'role' => 'super_admin',
            'email_verified_at' => now(),
        ]);

        // Create village officials
        $kepala = User::create([
            'desa_id' => $desa->id,
            'name' => 'Budi Santoso',
            'email' => 'kepala@sukamaju.desa.id',
            'password' => Hash::make('password'),
            'role' => 'kepala_desa',
            'nik' => '3201122001010001',
            'birth_date' => '1970-05-15',
            'birth_place' => 'Bogor',
            'gender' => 'L',
            'religion' => 'islam',
            'marital_status' => 'kawin',
            'occupation' => 'Kepala Desa',
            'education' => 'sarjana',
            'address' => 'Jl. Kepala Desa No. 1',
            'phone' => '081234567890',
            'citizen_status' => 'active',
            'email_verified_at' => now(),
        ]);

        $admin = User::create([
            'desa_id' => $desa->id,
            'name' => 'Siti Nurhaliza',
            'email' => 'admin@sukamaju.desa.id',
            'password' => Hash::make('password'),
            'role' => 'admin_desa',
            'nik' => '3201122001010002',
            'birth_date' => '1985-08-20',
            'birth_place' => 'Bogor',
            'gender' => 'P',
            'religion' => 'islam',
            'marital_status' => 'kawin',
            'occupation' => 'Admin Desa',
            'education' => 'diploma',
            'address' => 'Jl. Admin No. 2',
            'phone' => '081234567891',
            'citizen_status' => 'active',
            'email_verified_at' => now(),
        ]);

        // Create some RW and RT heads
        $rw001 = Rw::where('desa_id', $desa->id)->where('number', '001')->first();
        $rt001 = Rt::where('desa_id', $desa->id)->where('rw_id', $rw001->id)->where('number', '001')->first();

        $ketuaRw = User::create([
            'desa_id' => $desa->id,
            'name' => 'Ahmad Wijaya',
            'email' => 'ketua.rw001@sukamaju.desa.id',
            'password' => Hash::make('password'),
            'role' => 'ketua_rw',
            'nik' => '3201122001010003',
            'birth_date' => '1975-12-10',
            'birth_place' => 'Bogor',
            'gender' => 'L',
            'religion' => 'islam',
            'marital_status' => 'kawin',
            'occupation' => 'Ketua RW',
            'education' => 'sma',
            'address' => 'Jl. RW 001 No. 3',
            'rw_id' => $rw001->id,
            'phone' => '081234567892',
            'citizen_status' => 'active',
            'email_verified_at' => now(),
        ]);

        // Assign RW head
        $rw001->update(['ketua_user_id' => $ketuaRw->id]);

        $ketuaRt = User::create([
            'desa_id' => $desa->id,
            'name' => 'Dewi Sartika',
            'email' => 'ketua.rt001@sukamaju.desa.id',
            'password' => Hash::make('password'),
            'role' => 'ketua_rt',
            'nik' => '3201122001010004',
            'birth_date' => '1980-03-25',
            'birth_place' => 'Bogor',
            'gender' => 'P',
            'religion' => 'islam',
            'marital_status' => 'kawin',
            'occupation' => 'Ketua RT',
            'education' => 'sma',
            'address' => 'Jl. RT 001 No. 4',
            'rt_id' => $rt001->id,
            'rw_id' => $rw001->id,
            'phone' => '081234567893',
            'citizen_status' => 'active',
            'email_verified_at' => now(),
        ]);

        // Assign RT head
        $rt001->update(['ketua_user_id' => $ketuaRt->id]);

        // Create some citizens
        for ($i = 1; $i <= 20; $i++) {
            $rt = Rt::where('desa_id', $desa->id)->inRandomOrder()->first();
            
            User::create([
                'desa_id' => $desa->id,
                'name' => $this->generateIndonesianName(),
                'email' => "warga{$i}@sukamaju.desa.id",
                'password' => Hash::make('password'),
                'role' => 'warga',
                'nik' => '32011220010100' . sprintf('%02d', $i + 4),
                'birth_date' => now()->subYears(random_int(18, 65))->subDays(random_int(1, 365)),
                'birth_place' => $this->generateIndonesianCity(),
                'gender' => random_int(0, 1) ? 'L' : 'P',
                'religion' => ['islam', 'kristen', 'katolik', 'hindu'][random_int(0, 3)],
                'marital_status' => ['belum_kawin', 'kawin', 'cerai_hidup', 'cerai_mati'][random_int(0, 3)],
                'occupation' => $this->generateOccupation(),
                'education' => ['sd', 'smp', 'sma', 'diploma', 'sarjana'][random_int(0, 4)],
                'address' => "Jl. {$rt->full_name} No. " . random_int(1, 50),
                'rt_id' => $rt->id,
                'rw_id' => $rt->rw_id,
                'phone' => '0812' . random_int(10000000, 99999999),
                'citizen_status' => 'active',
                'email_verified_at' => now(),
            ]);
        }

        // Create letter types
        $letterTypes = [
            [
                'name' => 'Surat Keterangan Domisili',
                'code' => 'SKD',
                'description' => 'Surat keterangan tempat tinggal',
                'fee' => 0,
            ],
            [
                'name' => 'Surat Keterangan Tidak Mampu',
                'code' => 'SKTM',
                'description' => 'Surat keterangan tidak mampu untuk kebutuhan bantuan',
                'fee' => 0,
            ],
            [
                'name' => 'Surat Keterangan Usaha',
                'code' => 'SKU',
                'description' => 'Surat keterangan menjalankan usaha',
                'fee' => 5000,
            ],
            [
                'name' => 'Surat Pengantar SKCK',
                'code' => 'SP-SKCK',
                'description' => 'Surat pengantar untuk membuat SKCK',
                'fee' => 0,
            ],
        ];

        foreach ($letterTypes as $letterTypeData) {
            LetterType::create(array_merge($letterTypeData, [
                'desa_id' => $desa->id,
                'is_active' => true,
                'required_fields' => [
                    'keperluan' => 'text',
                    'keterangan_tambahan' => 'textarea'
                ],
                'template' => 'Template surat untuk ' . $letterTypeData['name'],
            ]));
        }
    }

    protected function generateIndonesianName(): string
    {
        $firstNames = [
            'Budi', 'Siti', 'Ahmad', 'Dewi', 'Andi', 'Sri', 'Dedi', 'Rina', 'Hadi', 'Lina',
            'Agus', 'Nina', 'Eko', 'Maya', 'Rudi', 'Sari', 'Joko', 'Ita', 'Bambang', 'Wati'
        ];
        
        $lastNames = [
            'Santoso', 'Wijaya', 'Sari', 'Pratama', 'Wibowo', 'Kusuma', 'Putra', 'Indah',
            'Cahaya', 'Permana', 'Saputra', 'Lestari', 'Handoko', 'Maharani', 'Setiawan'
        ];

        return $firstNames[array_rand($firstNames)] . ' ' . $lastNames[array_rand($lastNames)];
    }

    protected function generateIndonesianCity(): string
    {
        $cities = [
            'Jakarta', 'Surabaya', 'Bandung', 'Medan', 'Semarang', 'Makassar', 'Palembang',
            'Bogor', 'Tangerang', 'Depok', 'Bekasi', 'Yogyakarta', 'Solo', 'Malang'
        ];

        return $cities[array_rand($cities)];
    }

    protected function generateOccupation(): string
    {
        $occupations = [
            'Petani', 'Pedagang', 'PNS', 'Guru', 'Buruh', 'Wiraswasta', 'Ibu Rumah Tangga',
            'Pensiunan', 'Mahasiswa', 'Karyawan Swasta', 'Tukang', 'Sopir', 'Nelayan'
        ];

        return $occupations[array_rand($occupations)];
    }
}