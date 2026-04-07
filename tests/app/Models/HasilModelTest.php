<?php

namespace App\Models;

use CodeIgniter\Test\CIUnitTestCase;
use CodeIgniter\Test\DatabaseTestTrait;

class HasilModelTest extends CIUnitTestCase
{
    use DatabaseTestTrait;

    protected $migrate = true;
    protected $namespace = 'App';

    /**
     * Test successful insertion with valid data
     */
    public function testInsertHasilSuccessful()
    {
        $model = new HasilModel();

        // Seed parent
        $mhsId = $this->db->table('mahasiswa')->insert([
            'nim'   => '33333',
            'nama'  => 'Mhs 3',
            'prodi' => 'TI',
            'created_at' => date('Y-m-d H:i:s'),
            'updated_at' => date('Y-m-d H:i:s'),
        ]);
        $mhsId = $this->db->insertID();

        $data = [
            'mahasiswa_id'     => $mhsId,
            'nilai_preferensi' => 0.85,
            'ranking'          => 1
        ];

        $id = $model->insert($data);

        $this->assertIsNumeric($id);
        $this->seeInDatabase('hasil', ['mahasiswa_id' => $mhsId]);
    }

    /**
     * Test getRankedResults: order and joins
     */
    public function testGetRankedResultsInOrder()
    {
        $model = new HasilModel();

        // Seed students
        $mhsId1 = $this->db->table('mahasiswa')->insert([
            'nim'   => '44444', 'nama' => 'Mhs B', 'prodi' => 'TI',
            'created_at' => date('Y-m-d H:i:s'), 'updated_at' => date('Y-m-d H:i:s')
        ]);
        $mhsId1 = $this->db->insertID();

        $mhsId2 = $this->db->table('mahasiswa')->insert([
            'nim'   => '55555', 'nama' => 'Mhs A', 'prodi' => 'TI',
            'created_at' => date('Y-m-d H:i:s'), 'updated_at' => date('Y-m-d H:i:s')
        ]);
        $mhsId2 = $this->db->insertID();

        // Seed results (out of order in DB)
        $model->insert(['mahasiswa_id' => $mhsId1, 'nilai_preferensi' => 0.7, 'ranking' => 2]);
        $model->insert(['mahasiswa_id' => $mhsId2, 'nilai_preferensi' => 0.9, 'ranking' => 1]);

        $results = $model->getRankedResults();

        $this->assertCount(2, $results);
        $this->assertEquals('Mhs A', $results[0]->nama);
        $this->assertEquals(1, $results[0]->ranking);
        $this->assertEquals('Mhs B', $results[1]->nama);
        $this->assertEquals(2, $results[1]->ranking);
    }

    /**
     * Test validation: ranking is integer
     */
    public function testValidationRankingIsInteger()
    {
        $model = new HasilModel();

        $data = [
            'mahasiswa_id'     => 1,
            'nilai_preferensi' => 0.85,
            'ranking'          => 'abc'
        ];

        $result = $model->insert($data);

        $this->assertFalse($result);
        $this->assertArrayHasKey('ranking', $model->errors());
    }
}
