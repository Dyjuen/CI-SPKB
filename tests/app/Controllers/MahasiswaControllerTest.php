<?php

namespace App\Controllers;

use CodeIgniter\Test\CIUnitTestCase;
use CodeIgniter\Test\FeatureTestTrait;
use CodeIgniter\Test\DatabaseTestTrait;

class MahasiswaControllerTest extends CIUnitTestCase
{
    use FeatureTestTrait, DatabaseTestTrait;

    protected $migrate = true;
    protected $namespace = 'App';

    /**
     * Test index page shows students
     */
    public function testIndexShowsStudents()
    {
        $this->db->table('mahasiswa')->insert([
            'nim'   => '101', 'nama' => 'Student A', 'prodi' => 'TI',
            'created_at' => date('Y-m-d H:i:s'), 'updated_at' => date('Y-m-d H:i:s')
        ]);

        $result = $this->withSession(['login' => true])->get('/mahasiswa');

        $result->assertStatus(200);
        $result->assertSee('Data Mahasiswa');
        $result->assertSee('Student A');
    }

    /**
     * Test tambah renders form
     */
    public function testTambahRendersForm()
    {
        $result = $this->withSession(['login' => true])->get('/mahasiswa/tambah');

        $result->assertStatus(200);
        $result->assertSee('Tambah Mahasiswa');
    }

    /**
     * Test simpan saves data
     */
    public function testSimpanCreatesRecord()
    {
        $data = [
            'nim'  => '200202',
            'nama' => 'Student B',
            'prodi' => 'TI'
        ];

        $result = $this->withSession(['login' => true])->post('/mahasiswa/simpan', $data);

        $result->assertRedirectTo('/mahasiswa');
        $this->seeInDatabase('mahasiswa', ['nim' => '200202', 'nama' => 'Student B']);
    }

    /**
     * Test delete removes record (checks soft delete)
     */
    public function testDeleteRemovesRecord()
    {
        $id = $this->db->table('mahasiswa')->insert([
            'nim'   => '303', 'nama' => 'Student C', 'prodi' => 'TI',
            'created_at' => date('Y-m-d H:i:s'), 'updated_at' => date('Y-m-d H:i:s')
        ]);
        $id = $this->db->insertID();

        $result = $this->withSession(['login' => true])->get("/mahasiswa/delete/{$id}");

        $result->assertRedirectTo('/mahasiswa');
        $this->seeInDatabase('mahasiswa', ['id' => $id, 'deleted_at !=' => null]);
    }

    /**
     * Test pagination works when there are many students
     */
    public function testPaginationWorks()
    {
        // Insert 11 students to trigger pagination (limit 10)
        $data = [];
        for ($i = 1; $i <= 11; $i++) {
            $data[] = [
                'nim' => "NIM{$i}", 
                'nama' => "Student {$i}", 
                'prodi' => 'TI',
                'created_at' => date('Y-m-d H:i:s'), 
                'updated_at' => date('Y-m-d H:i:s')
            ];
        }
        $this->db->table('mahasiswa')->insertBatch($data);

        $result = $this->withSession(['login' => true])->get('/mahasiswa');
        $result->assertStatus(200);
        
        // Assert we see the pagination control
        $result->assertSee('nav aria-label="Page navigation"');
    }
}
