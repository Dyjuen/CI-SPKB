<?php

namespace App\Models;

use CodeIgniter\Test\CIUnitTestCase;
use CodeIgniter\Test\DatabaseTestTrait;

class PenilaianModelTest extends CIUnitTestCase
{
    use DatabaseTestTrait;

    protected $migrate = true;
    protected $namespace = 'App';

    /**
     * Test upsertScore: Insert new record
     */
    public function testUpsertScoreInsertsNewRecord()
    {
        $model = new PenilaianModel();

        // Need student and criteria to avoid FK validation errors (if enforced by DB or Model)
        $mhsId = $this->db->table('mahasiswa')->insert([
            'nim'   => '11111',
            'nama'  => 'Mhs 1',
            'prodi' => 'TI',
            'created_at' => date('Y-m-d H:i:s'),
            'updated_at' => date('Y-m-d H:i:s'),
        ]);
        $mhsId = $this->db->insertID();

        $kritId = $this->db->table('kriteria')->insert([
            'nama_kriteria' => 'Krit 1',
            'bobot'         => 0.5,
            'tipe'          => 'B',
            'created_at'    => date('Y-m-d H:i:s'),
            'updated_at'    => date('Y-m-d H:i:s'),
        ]);
        $kritId = $this->db->insertID();

        $result = $model->upsertScore($mhsId, $kritId, 85.5);

        $this->assertTrue($result !== false);
        $this->seeInDatabase('penilaian', [
            'mahasiswa_id' => $mhsId,
            'kriteria_id'  => $kritId,
            'nilai'        => 85.5
        ]);
    }

    /**
     * Test upsertScore: Update existing record
     */
    public function testUpsertScoreUpdatesExistingRecord()
    {
        $model = new PenilaianModel();

        // Setup data
        $mhsId = $this->db->table('mahasiswa')->insert([
            'nim'   => '22222',
            'nama'  => 'Mhs 2',
            'prodi' => 'TI',
            'created_at' => date('Y-m-d H:i:s'),
            'updated_at' => date('Y-m-d H:i:s'),
        ]);
        $mhsId = $this->db->insertID();

        $kritId = $this->db->table('kriteria')->insert([
            'nama_kriteria' => 'Krit 2',
            'bobot'         => 0.4,
            'tipe'          => 'C',
            'created_at'    => date('Y-m-d H:i:s'),
            'updated_at'    => date('Y-m-d H:i:s'),
        ]);
        $kritId = $this->db->insertID();

        // First insert
        $model->upsertScore($mhsId, $kritId, 70.0);
        
        // Second insert (should be update)
        $result = $model->upsertScore($mhsId, $kritId, 90.0);

        $this->assertTrue($result !== false);
        $this->seeInDatabase('penilaian', [
            'mahasiswa_id' => $mhsId,
            'kriteria_id'  => $kritId,
            'nilai'        => 90.0
        ]);
        
        // Check count (should still be 1)
        $count = $this->db->table('penilaian')
                          ->where(['mahasiswa_id' => $mhsId, 'kriteria_id' => $kritId])
                          ->countAllResults();
        $this->assertEquals(1, $count);
    }

    /**
     * Test validation: Nilai is numeric
     */
    public function testValidationNilaiIsNumeric()
    {
        $model = new PenilaianModel();

        $data = [
            'mahasiswa_id' => 1,
            'kriteria_id'  => 1,
            'nilai'        => 'abc'
        ];

        $result = $model->insert($data);

        $this->assertFalse($result);
        $this->assertArrayHasKey('nilai', $model->errors());
    }
}
