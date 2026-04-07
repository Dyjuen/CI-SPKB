<?php

namespace App\Controllers;

use CodeIgniter\Test\CIUnitTestCase;
use CodeIgniter\Test\FeatureTestTrait;
use CodeIgniter\Test\DatabaseTestTrait;

class HasilControllerTest extends CIUnitTestCase
{
    use FeatureTestTrait, DatabaseTestTrait;

    protected $migrate = true;
    protected $namespace = 'App';

    /**
     * Test index page shows results
     */
    public function testIndexShowsResults()
    {
        // Seed student and result
        $mhsId = $this->db->table('mahasiswa')->insert([
            'nim'   => '900901', 'nama' => 'Student Hasil', 'prodi' => 'TI',
            'created_at' => date('Y-m-d H:i:s'), 'updated_at' => date('Y-m-d H:i:s')
        ]);
        $mhsId = $this->db->insertID();

        $this->db->table('hasil')->insert([
            'mahasiswa_id'     => $mhsId,
            'nilai_preferensi' => 0.95,
            'ranking'          => 1,
            'created_at'       => date('Y-m-d H:i:s'),
            'updated_at'       => date('Y-m-d H:i:s')
        ]);

        $result = $this->withSession(['login' => true])->get('/hasil');

        $result->assertStatus(200);
        $result->assertSee('Hasil Perhitungan SAW');
        $result->assertSee('Student Hasil');
    }

    /**
     * Test hitung calculation logic
     */
    public function testHitungSuccessfullyCalculates()
    {
        // Setup students
        $m1 = $this->db->table('mahasiswa')->insert([
            'nim' => '101', 'nama' => 'Mhs 1', 'prodi' => 'TI',
            'created_at' => date('Y-m-d H:i:s'), 'updated_at' => date('Y-m-d H:i:s')
        ]);
        $m1 = $this->db->insertID();
        
        // Setup criteria
        $k1 = $this->db->table('kriteria')->insert([
            'nama_kriteria' => 'IPK', 'bobot' => 0.6, 'tipe' => 'B',
            'created_at' => date('Y-m-d H:i:s'), 'updated_at' => date('Y-m-d H:i:s')
        ]);
        $k1 = $this->db->insertID();
        
        $k2 = $this->db->table('kriteria')->insert([
            'nama_kriteria' => 'Penghasilan Ortu', 'bobot' => 0.4, 'tipe' => 'C',
            'created_at' => date('Y-m-d H:i:s'), 'updated_at' => date('Y-m-d H:i:s')
        ]);
        $k2 = $this->db->insertID();

        // Setup assessment
        $this->db->table('penilaian')->insertBatch([
            ['mahasiswa_id' => $m1, 'kriteria_id' => $k1, 'nilai' => 3.5, 'created_at' => date('Y-m-d H:i:s'), 'updated_at' => date('Y-m-d H:i:s')],
            ['mahasiswa_id' => $m1, 'kriteria_id' => $k2, 'nilai' => 5000000, 'created_at' => date('Y-m-d H:i:s'), 'updated_at' => date('Y-m-d H:i:s')]
        ]);

        $result = $this->withSession(['login' => true])->post('/hasil/hitung');

        $result->assertRedirectTo('/hasil');
        $result->assertSessionHas('success', 'Perhitungan SAW berhasil diselesaikan.');
        
        $this->seeInDatabase('hasil', ['mahasiswa_id' => $m1, 'ranking' => 1]);
    }

    /**
     * Test hitung fails if data missing
     */
    public function testHitungFailsIfEmptyData()
    {
        $result = $this->withSession(['login' => true])->post('/hasil/hitung');

        $result->assertSessionHas('error', 'Data mahasiswa atau kriteria masih kosong.');
    }

    /**
     * Test hitung fails if criteria not fully filled
     */
    public function testHitungFailsIfIncompleteCriteria()
    {
        $m1 = $this->db->table('mahasiswa')->insert([
            'nim' => '101', 'nama' => 'Mhs 1', 'prodi' => 'TI',
            'created_at' => date('Y-m-d H:i:s'), 'updated_at' => date('Y-m-d H:i:s')
        ]);
        $m1 = $this->db->insertID();
        
        $k1 = $this->db->table('kriteria')->insert([
            'nama_kriteria' => 'IPK', 'bobot' => 0.6, 'tipe' => 'B',
            'created_at' => date('Y-m-d H:i:s'), 'updated_at' => date('Y-m-d H:i:s')
        ]);
        $k1 = $this->db->insertID();

        // Criteria k1 filled, but no criteria exists yet in penilaian? 
        // Wait, loop checks all students have counts >= total criteria.
        
        $result = $this->withSession(['login' => true])->post('/hasil/hitung');

        $result->assertSessionHas('error', 'kriteria belum diisi sepenuhnya');
    }
}
