<?php

namespace App\Database\Seeds;

use CodeIgniter\Database\Seeder;

class PenilaianSeeder extends Seeder
{
    public function run()
    {
        // Criteria IDs from seeder: 1 (IPK-B), 2 (Income-C), 3 (Tanggungan-B), 4 (Prestasi-B)
        // Mahasiswa IDs: 1, 2, 3
        
        $data = [
            // Andi (Complete)
            ['mahasiswa_id' => 1, 'kriteria_id' => 1, 'nilai' => 3.8],
            ['mahasiswa_id' => 1, 'kriteria_id' => 2, 'nilai' => 5000000],
            ['mahasiswa_id' => 1, 'kriteria_id' => 3, 'nilai' => 3],
            ['mahasiswa_id' => 1, 'kriteria_id' => 4, 'nilai' => 1],
            
            // Siti (Complete)
            ['mahasiswa_id' => 2, 'kriteria_id' => 1, 'nilai' => 3.5],
            ['mahasiswa_id' => 2, 'kriteria_id' => 2, 'nilai' => 3000000],
            ['mahasiswa_id' => 2, 'kriteria_id' => 3, 'nilai' => 2],
            ['mahasiswa_id' => 2, 'kriteria_id' => 4, 'nilai' => 5],
            
            // Budi (Complete)
            ['mahasiswa_id' => 3, 'kriteria_id' => 1, 'nilai' => 3.9],
            ['mahasiswa_id' => 3, 'kriteria_id' => 2, 'nilai' => 2000000],
            ['mahasiswa_id' => 3, 'kriteria_id' => 3, 'nilai' => 1],
            ['mahasiswa_id' => 3, 'kriteria_id' => 4, 'nilai' => 0],
        ];

        foreach ($data as $item) {
            $this->db->table('penilaian')->insert(array_merge($item, ['created_at' => date('Y-m-d H:i:s')]));
        }
    }
}
