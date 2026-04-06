<?php

namespace App\Database\Seeds;

use CodeIgniter\Database\Seeder;

class KriteriaSeeder extends Seeder
{
    public function run()
    {
        $data = [
            [
                'nama_kriteria' => 'IPK',
                'bobot'         => 0.40,
                'tipe'          => 'B',
            ],
            [
                'nama_kriteria' => 'Penghasilan Orang Tua',
                'bobot'         => 0.30,
                'tipe'          => 'C',
            ],
            [
                'nama_kriteria' => 'Jumlah Tanggungan',
                'bobot'         => 0.20,
                'tipe'          => 'B',
            ],
            [
                'nama_kriteria' => 'Prestasi Non-Akademik',
                'bobot'         => 0.10,
                'tipe'          => 'B',
            ],
        ];

        // Using Query Builder
        $this->db->table('kriteria')->insertBatch($data);
    }
}
