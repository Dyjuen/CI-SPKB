<?php

namespace App\Models;

use CodeIgniter\Test\CIUnitTestCase;
use CodeIgniter\Test\DatabaseTestTrait;

class KriteriaModelTest extends CIUnitTestCase
{
    use DatabaseTestTrait;

    protected $migrate = true;
    protected $namespace = 'App';

    /**
     * Test successful insertion with valid data
     */
    public function testInsertKriteriaSuccessful()
    {
        $model = new KriteriaModel();

        $data = [
            'nama_kriteria' => 'Test Kriteria',
            'bobot'         => 0.25,
            'tipe'          => 'B',
        ];

        $id = $model->insert($data);

        $this->assertIsNumeric($id);
        $this->seeInDatabase('kriteria', ['nama_kriteria' => 'Test Kriteria']);
    }

    /**
     * Test validation: Bobot > 1
     */
    public function testValidationBobotTooLarge()
    {
        $model = new KriteriaModel();

        $data = [
            'nama_kriteria' => 'Test Kriteria',
            'bobot'         => 1.5,
            'tipe'          => 'B',
        ];

        $result = $model->insert($data);

        $this->assertFalse($result);
        $this->assertArrayHasKey('bobot', $model->errors());
    }

    /**
     * Test validation: Bobot < 0
     */
    public function testValidationBobotTooSmall()
    {
        $model = new KriteriaModel();

        $data = [
            'nama_kriteria' => 'Test Kriteria',
            'bobot'         => -0.1,
            'tipe'          => 'B',
        ];

        $result = $model->insert($data);

        $this->assertFalse($result);
        $this->assertArrayHasKey('bobot', $model->errors());
    }

    /**
     * Test validation: Tipe Invalid
     */
    public function testValidationInvalidType()
    {
        $model = new KriteriaModel();

        $data = [
            'nama_kriteria' => 'Test Kriteria',
            'bobot'         => 0.5,
            'tipe'          => 'Z',
        ];

        $result = $model->insert($data);

        $this->assertFalse($result);
        $this->assertArrayHasKey('tipe', $model->errors());
    }

    /**
     * Test validation: Name too short
     */
    public function testValidationNameTooShort()
    {
        $model = new KriteriaModel();

        $data = [
            'nama_kriteria' => 'Te',
            'bobot'         => 0.5,
            'tipe'          => 'B',
        ];

        $result = $model->insert($data);

        $this->assertFalse($result);
        $this->assertArrayHasKey('nama_kriteria', $model->errors());
    }
}
