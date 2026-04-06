<?php

namespace App\Database\Seeds;

use CodeIgniter\Database\Seeder;

class MahasiswaSeeder extends Seeder
{
    public function run()
    {
        $data = [
            [
                'nim'   => '20210001',
                'nama'  => 'Andi Wijaya',
                'prodi' => 'Informatika',
                'created_at' => date('Y-m-d H:i:s'),
            ],
            [
                'nim'   => '20210002',
                'nama'  => 'Siti Aminah',
                'prodi' => 'Sistem Informasi',
                'created_at' => date('Y-m-d H:i:s'),
            ],
            [
                'nim'   => '20210003',
                'nama'  => 'Budi Santoso',
                'prodi' => 'Informatika',
                'created_at' => date('Y-m-d H:i:s'),
            ],
        ];

        $this->db->table('mahasiswa')->insertBatch($data);
    }
}
