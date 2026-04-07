<?php

namespace App\Controllers;

use CodeIgniter\Test\CIUnitTestCase;
use CodeIgniter\Test\FeatureTestTrait;
use CodeIgniter\Test\DatabaseTestTrait;

class PenilaianControllerTest extends CIUnitTestCase
{
    use FeatureTestTrait, DatabaseTestTrait;

    protected $migrate = true;
    protected $namespace = 'App';

    protected function setUp(): void
    {
        parent::setUp();
    }

    /**
     * Test index page returns success and see basic text.
     */
    public function testIndexReturnsViewWithData()
    {
        // Insert dummy kriteria and mahasiswa
        $this->db->table('kriteria')->insert([
            'nama_kriteria' => 'IPK',
            'bobot'         => 0.4,
            'tipe'          => 'B',
            'created_at'    => date('Y-m-d H:i:s'),
            'updated_at'    => date('Y-m-d H:i:s'),
        ]);

        $this->db->table('mahasiswa')->insert([
            'nim'   => '12345',
            'nama'  => 'Agus',
            'prodi' => 'Informatika',
            'created_at'    => date('Y-m-d H:i:s'),
            'updated_at'    => date('Y-m-d H:i:s'),
        ]);

        $result = $this->withSession(['login' => true])->get('/penilaian');

        $result->assertStatus(200);
        $result->assertSee('Input Nilai Mahasiswa');
        $result->assertSee('Agus');
        $result->assertSee('IPK');
    }

    /**
     * Test storing scores for a specific candidate.
     */
    public function testStoreSavesScoresForSingleCandidateAndRedirects()
    {
        $this->db->table('kriteria')->insert([
            'nama_kriteria' => 'IPK',
            'bobot'         => 0.4,
            'tipe'          => 'B',
            'created_at'    => date('Y-m-d H:i:s'),
            'updated_at'    => date('Y-m-d H:i:s'),
        ]);
        $krtId = $this->db->insertID();

        $this->db->table('mahasiswa')->insert([
            'nim'   => '12345',
            'nama'  => 'Agus',
            'prodi' => 'Informatika',
            'created_at'    => date('Y-m-d H:i:s'),
            'updated_at'    => date('Y-m-d H:i:s'),
        ]);
        $mhsId = $this->db->insertID();

        $data = [
            'nilai' => [
                $krtId => 3.5
            ]
        ];

        $result = $this->post("/penilaian/{$mhsId}", $data);

        $result->assertStatus(302);
        $result->assertSessionHas('success', 'Nilai berhasil disimpan');

        $this->seeInDatabase('penilaian', [
            'mahasiswa_id' => $mhsId,
            'kriteria_id'  => $krtId,
            'nilai'        => 3.5
        ]);
    }

    /**
     * Test storing scores via AJAX.
     */
    public function testStoreSavesScoresViaAJAX()
    {
        $this->db->table('kriteria')->insert([
            'nama_kriteria' => 'IPK',
            'bobot'         => 0.4,
            'tipe'          => 'B',
            'created_at'    => date('Y-m-d H:i:s'),
            'updated_at'    => date('Y-m-d H:i:s'),
        ]);
        $krtId = $this->db->insertID();

        $this->db->table('mahasiswa')->insert([
            'nim'   => '12345',
            'nama'  => 'Agus',
            'prodi' => 'Informatika',
            'created_at'    => date('Y-m-d H:i:s'),
            'updated_at'    => date('Y-m-d H:i:s'),
        ]);
        $mhsId = $this->db->insertID();

        $data = [
            'nilai' => [
                $krtId => 3.5
            ]
        ];

        $result = $this->withHeaders([
            'X-Requested-With' => 'XMLHttpRequest'
        ])->post("/penilaian/{$mhsId}", $data);

        $result->assertStatus(200);
        $result->assertHeader('Content-Type', 'application/json; charset=UTF-8');
        
        $responseBody = json_decode($result->getJSON(), true);
        $this->assertTrue($responseBody['success']);
        $this->assertEquals('Nilai berhasil disimpan.', $responseBody['message']);

        $this->seeInDatabase('penilaian', [
            'mahasiswa_id' => $mhsId,
            'kriteria_id'  => $krtId,
            'nilai'        => 3.5
        ]);
    }

    /**
     * Test validation failure when criteria missing.
     */
    public function testStoreFailsWhenSomeCriteriaAreMissing()
    {
        $this->db->table('kriteria')->insert([
            'nama_kriteria' => 'C1',
            'bobot'         => 0.5,
            'tipe'          => 'B',
            'created_at'    => date('Y-m-d H:i:s'),
            'updated_at'    => date('Y-m-d H:i:s'),
        ]);
        $krtId1 = $this->db->insertID();

        $this->db->table('kriteria')->insert([
            'nama_kriteria' => 'C2',
            'bobot'         => 0.5,
            'tipe'          => 'B',
            'created_at'    => date('Y-m-d H:i:s'),
            'updated_at'    => date('Y-m-d H:i:s'),
        ]);
        $krtId2 = $this->db->insertID();

        $this->db->table('mahasiswa')->insert([
            'nim'   => '12345',
            'nama'  => 'Agus',
            'prodi' => 'Informatika',
            'created_at'    => date('Y-m-d H:i:s'),
            'updated_at'    => date('Y-m-d H:i:s'),
        ]);
        $mhsId = $this->db->insertID();

        // Only submit C1, C2 is missing
        $data = [
            'nilai' => [
                $krtId1 => 3.5
            ]
        ];

        $result = $this->post("/penilaian/{$mhsId}", $data);

        $result->assertStatus(302);
        $result->assertSessionHas('errors');
    }

    /**
     * Test validation failure via AJAX.
     */
    public function testStoreFailsViaAJAX()
    {
        $this->db->table('kriteria')->insert([
            'nama_kriteria' => 'C1',
            'bobot'         => 1.0,
            'tipe'          => 'B',
            'created_at'    => date('Y-m-d H:i:s'),
            'updated_at'    => date('Y-m-d H:i:s'),
        ]);
        $krtId1 = $this->db->insertID();

        $this->db->table('mahasiswa')->insert([
            'nim'   => '12345',
            'nama'  => 'Agus',
            'prodi' => 'Informatika',
            'created_at'    => date('Y-m-d H:i:s'),
            'updated_at'    => date('Y-m-d H:i:s'),
        ]);
        $mhsId = $this->db->insertID();

        // Submit non-numeric value
        $data = [
            'nilai' => [
                $krtId1 => 'invalid'
            ]
        ];

        $result = $this->withHeaders([
            'X-Requested-With' => 'XMLHttpRequest'
        ])->post("/penilaian/{$mhsId}", $data);

        $result->assertStatus(200);
        $responseBody = json_decode($result->getJSON(), true);
        $this->assertFalse($responseBody['success']);
        $this->assertArrayHasKey('nilai.' . $krtId1, $responseBody['errors']);
    }
}
