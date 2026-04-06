<?php

namespace App\Controllers;

use CodeIgniter\Test\CIUnitTestCase;
use CodeIgniter\Test\FeatureTestTrait;
use CodeIgniter\Test\DatabaseTestTrait;

class KriteriaControllerTest extends CIUnitTestCase
{
    use FeatureTestTrait, DatabaseTestTrait;

    protected $migrate = true;
    protected $namespace = 'App';
    protected $seed    = ''; 

    protected function setUp(): void
    {
        parent::setUp();
    }

    /**
     * 1. Happy Path: Index page shows list of criteria.
     */
    public function testIndexShowsKriteriaList()
    {
        $result = $this->get('/kriteria');
        $result->assertStatus(200);
        $result->assertSee('Manajemen Kriteria');
    }

    /**
     * 2. Happy Path: Store valid record.
     */
    public function testStoreCreatesValidRecord()
    {
        $data = [
            'nama_kriteria' => 'Test Kriteria',
            'bobot'         => 0.5,
            'tipe'          => 'B',
        ];

        $result = $this->post('/kriteria', $data);
        $result->assertStatus(302);
        $result->assertSessionHas('success', 'Kriteria berhasil disimpan');

        $this->seeInDatabase('kriteria', ['nama_kriteria' => 'Test Kriteria']);
    }

    /**
     * 3. Happy Path: Update valid record.
     */
    public function testUpdateChangesRecord()
    {
        // Insert record first
        $id = $this->db->table('kriteria')->insert([
            'nama_kriteria' => 'Old Name',
            'bobot'         => 0.1,
            'tipe'          => 'C',
            'created_at'    => date('Y-m-d H:i:s'),
            'updated_at'    => date('Y-m-d H:i:s'),
        ]);
        $id = $this->db->insertID();

        $data = [
            'nama_kriteria' => 'New Name',
            'bobot'         => 0.9,
            'tipe'          => 'B',
        ];

        $result = $this->withBodyFormat('json')->put("/kriteria/{$id}", $data);

        $result->assertStatus(302);
        $result->assertSessionHas('success', 'Kriteria berhasil diperbarui');
        $this->seeInDatabase('kriteria', ['id' => $id, 'nama_kriteria' => 'New Name']);
    }

    /**
     * 4. Happy Path: Delete removes record (soft delete check).
     */
    public function testDeleteRemovesRecord()
    {
        $id = $this->db->table('kriteria')->insert([
            'nama_kriteria' => 'ToDelete',
            'bobot'         => 0.2,
            'tipe'          => 'B',
            'created_at'    => date('Y-m-d H:i:s'),
            'updated_at'    => date('Y-m-d H:i:s'),
        ]);
        $id = $this->db->insertID();

        $result = $this->call('DELETE', "/kriteria/{$id}");

        $result->assertStatus(302);
        $this->seeInDatabase('kriteria', ['id' => $id, 'deleted_at !=' => null]);
    }

    /**
     * 5. Happy Path: Show returns JSON data.
     */
    public function testShowReturnsJson()
    {
        $id = $this->db->table('kriteria')->insert([
            'nama_kriteria' => 'JsonTest',
            'bobot'         => 0.3,
            'tipe'          => 'C',
            'created_at'    => date('Y-m-d H:i:s'),
            'updated_at'    => date('Y-m-d H:i:s'),
        ]);
        $id = $this->db->insertID();

        $result = $this->get("/kriteria/{$id}/json");
        $result->assertStatus(200);
        $result->assertHeader('Content-Type', 'application/json; charset=UTF-8');
        
        $body = json_decode($result->getJSON(), true);
        $this->assertEquals('JsonTest', $body['nama_kriteria']);
    }

    /**
     * 6-8. Edge Case: Nama Kriteria validation (empty, too short, too long).
     */
    public function testStoreRejectsNamaValidation()
    {
        // 6. Empty
        $result = $this->post('/kriteria', ['nama_kriteria' => '', 'bobot' => 0.5, 'tipe' => 'B']);
        $result->assertSessionHas('error');

        // 7. Too short (min 3)
        $result = $this->post('/kriteria', ['nama_kriteria' => 'AB', 'bobot' => 0.5, 'tipe' => 'B']);
        $result->assertSessionHas('error');

        // 8. Too long (max 100)
        $result = $this->post('/kriteria', ['nama_kriteria' => str_repeat('A', 101), 'bobot' => 0.5, 'tipe' => 'B']);
        $result->assertSessionHas('error');
    }

    /**
     * 9-12. Edge Case: Bobot validation (missing, > 1, < 0, non-numeric).
     */
    public function testStoreRejectsBobotValidation()
    {
        // 9. Missing
        $result = $this->post('/kriteria', ['nama_kriteria' => 'Valid Name', 'tipe' => 'B']);
        $result->assertSessionHas('error');

        // 10. > 1
        $result = $this->post('/kriteria', ['nama_kriteria' => 'Valid Name', 'bobot' => 1.5, 'tipe' => 'B']);
        $result->assertSessionHas('error');

        // 11. < 0
        $result = $this->post('/kriteria', ['nama_kriteria' => 'Valid Name', 'bobot' => -0.1, 'tipe' => 'B']);
        $result->assertSessionHas('error');

        // 12. Non-numeric
        $result = $this->post('/kriteria', ['nama_kriteria' => 'Valid Name', 'bobot' => 'abc', 'tipe' => 'B']);
        $result->assertSessionHas('error');
    }

    /**
     * 13-14. Edge Case: Tipe validation (invalid, missing).
     */
    public function testStoreRejectsTipeValidation()
    {
        // 13. Invalid
        $result = $this->post('/kriteria', ['nama_kriteria' => 'Valid Name', 'bobot' => 0.5, 'tipe' => 'X']);
        $result->assertSessionHas('error');

        // 14. Missing
        $result = $this->post('/kriteria', ['nama_kriteria' => 'Valid Name', 'bobot' => 0.5]);
        $result->assertSessionHas('error');
    }

    /**
     * 15. Edge Case: Update non-existent record.
     */
    public function testUpdateNonExistentRecord()
    {
        $result = $this->call('PUT', "/kriteria/9999", ['nama_kriteria' => 'Test', 'bobot' => 0.5, 'tipe' => 'B']);
        
        $result->assertSessionHas('error');
    }

    /**
     * 16. Edge Case: Delete non-existent record.
     */
    public function testDeleteNonExistentRecord()
    {
        $result = $this->call('DELETE', "/kriteria/9999");
        
        $result->assertStatus(302);
    }

    /**
     * 17. Edge Case: Show non-existent JSON returns 404.
     */
    public function testShowNonExistentRecordReturns404()
    {
        $result = $this->get('/kriteria/9999/json');
        $result->assertStatus(404);
    }

    /**
     * 18. Edge Case: Bobot boundary zero is valid.
     */
    public function testBobotBoundaryZeroIsValid()
    {
        $data = ['nama_kriteria' => 'Zero Bobot', 'bobot' => 0.00, 'tipe' => 'B'];
        $result = $this->post('/kriteria', $data);
        $result->assertStatus(302);
        $this->seeInDatabase('kriteria', ['nama_kriteria' => 'Zero Bobot', 'bobot' => 0]);
    }

    /**
     * 19. Edge Case: Bobot boundary one is valid.
     */
    public function testBobotBoundaryOneIsValid()
    {
        $data = ['nama_kriteria' => 'One Bobot', 'bobot' => 1.00, 'tipe' => 'B'];
        $result = $this->post('/kriteria', $data);
        $result->assertStatus(302);
        $this->seeInDatabase('kriteria', ['nama_kriteria' => 'One Bobot', 'bobot' => 1]);
    }

    /**
     * 20. Edge Case: Duplicate Nama allowed.
     */
    public function testDuplicateNamaCriteriaAllowed()
    {
        $this->post('/kriteria', ['nama_kriteria' => 'Dua', 'bobot' => 0.5, 'tipe' => 'B']);
        $this->post('/kriteria', ['nama_kriteria' => 'Dua', 'bobot' => 0.3, 'tipe' => 'C']);
        
        $count = $this->db->table('kriteria')->where('nama_kriteria', 'Dua')->countAllResults();
        $this->assertEquals(2, $count);
    }
}
