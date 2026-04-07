<?php

namespace App\Models;

use CodeIgniter\Test\CIUnitTestCase;
use CodeIgniter\Test\DatabaseTestTrait;

class MahasiswaModelTest extends CIUnitTestCase
{
    use DatabaseTestTrait;

    protected $migrate = true;
    protected $namespace = 'App';

    /**
     * Test successful insertion with valid data
     */
    public function testInsertMahasiswaSuccessful()
    {
        $model = new MahasiswaModel();

        $data = [
            'nim'   => '12345678',
            'nama'  => 'Test Mahasiswa',
            'prodi' => 'Informatika',
        ];

        $id = $model->insert($data);

        $this->assertIsNumeric($id);
        $this->seeInDatabase('mahasiswa', ['nim' => '12345678']);
    }

    /**
     * Test validation: Duplicate NIM
     */
    public function testValidationDuplicateNim()
    {
        $model = new MahasiswaModel();

        $data = [
            'nim'   => '11111',
            'nama'  => 'Mhs 1',
            'prodi' => 'Prodi A',
        ];

        $model->insert($data);

        $data2 = [
            'nim'   => '11111',
            'nama'  => 'Mhs 2',
            'prodi' => 'Prodi B',
        ];

        $result = $model->insert($data2);

        $this->assertFalse($result);
        $this->assertArrayHasKey('nim', $model->errors());
    }

    /**
     * Test validation: Non-numeric NIM
     */
    public function testValidationNonNumericNim()
    {
        $model = new MahasiswaModel();

        $data = [
            'nim'   => 'ABCDE',
            'nama'  => 'Test Mhs',
            'prodi' => 'Prodi A',
        ];

        $result = $model->insert($data);

        $this->assertFalse($result);
        $this->assertArrayHasKey('nim', $model->errors());
    }

    /**
     * Test validation: NIM too short
     */
    public function testValidationNimTooShort()
    {
        $model = new MahasiswaModel();

        $data = [
            'nim'   => '1234',
            'nama'  => 'Test Mhs',
            'prodi' => 'Prodi A',
        ];

        $result = $model->insert($data);

        $this->assertFalse($result);
        $this->assertArrayHasKey('nim', $model->errors());
    }

    /**
     * Test validation: Name too short
     */
    public function testValidationNameTooShort()
    {
        $model = new MahasiswaModel();

        $data = [
            'nim'   => '12345',
            'nama'  => 'Te',
            'prodi' => 'Prodi A',
        ];

        $result = $model->insert($data);

        $this->assertFalse($result);
        $this->assertArrayHasKey('nama', $model->errors());
    }

    /**
     * Test validation: Missing prodi
     */
    public function testValidationMissingProdi()
    {
        $model = new MahasiswaModel();

        $data = [
            'nim'   => '12345',
            'nama'  => 'Valid Name',
        ];

        $result = $model->insert($data);

        $this->assertFalse($result);
        $this->assertArrayHasKey('prodi', $model->errors());
    }
}
