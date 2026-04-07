<?php

namespace App\Database\Seeds;

use CodeIgniter\Database\Seeder;
use Faker\Factory;

/**
 * ThirtyMahasiswaSeeder
 * 
 * Generates 30 sample students to test the "passing 10" criteria.
 * Each student will also have associated penilaian (evaluations) 
 * so they appear in the SAW ranking results.
 */
class ThirtyMahasiswaSeeder extends Seeder
{
    public function run()
    {
        $faker = Factory::create('id_ID'); // Use Indonesian locale
        $prodis = ['Informatika', 'Sistem Informasi', 'Teknik Elektro', 'Teknik Industri', 'Manajemen Informatika'];

        // Get existing student IDs to avoid duplication if run multiple times
        // However, for testing purposes, we start NIM from 20210004
        
        $batchSize = 30;
        
        for ($i = 0; $i < $batchSize; $i++) {
            $nim = '2021' . str_pad($i + 4, 4, '0', STR_PAD_LEFT); 
            
            $mahasiswaData = [
                'nim'        => $nim,
                'nama'       => $faker->name,
                'prodi'      => $faker->randomElement($prodis),
                'created_at' => date('Y-m-d H:i:s'),
                'updated_at' => date('Y-m-d H:i:s'),
            ];

            // Insert Mahasiswa
            $this->db->table('mahasiswa')->insert($mahasiswaData);
            $mahasiswaId = $this->db->insertID();

            // Seed Penilaian for this student
            // Criteria IDs from KriteriaSeeder: 
            // 1: IPK (B), 2: Penghasilan (C), 3: Tanggungan (B), 4: Prestasi (B)
            $penilaianData = [
                [
                    'mahasiswa_id' => $mahasiswaId,
                    'kriteria_id'  => 1, // IPK
                    'nilai'         => $faker->randomFloat(2, 3.0, 4.0),
                    'created_at'   => date('Y-m-d H:i:s'),
                ],
                [
                    'mahasiswa_id' => $mahasiswaId,
                    'kriteria_id'  => 2, // Penghasilan Orang Tua
                    'nilai'         => $faker->numberBetween(1000000, 10000000),
                    'created_at'   => date('Y-m-d H:i:s'),
                ],
                [
                    'mahasiswa_id' => $mahasiswaId,
                    'kriteria_id'  => 3, // Jumlah Tanggungan
                    'nilai'         => $faker->numberBetween(1, 5),
                    'created_at'   => date('Y-m-d H:i:s'),
                ],
                [
                    'mahasiswa_id' => $mahasiswaId,
                    'kriteria_id'  => 4, // Prestasi Non-Akademik
                    'nilai'         => $faker->numberBetween(0, 5),
                    'created_at'   => date('Y-m-d H:i:s'),
                ],
            ];

            $this->db->table('penilaian')->insertBatch($penilaianData);
        }

        echo "Successfully seeded {$batchSize} Mahasiswa and their assessments.\n";
    }
}
