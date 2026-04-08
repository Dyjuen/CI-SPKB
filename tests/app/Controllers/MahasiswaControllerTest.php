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
     * Test store saves data
     */
    public function testStoreCreatesRecord()
    {
        $data = [
            'nim'  => '200202',
            'nama' => 'Student B',
            'prodi' => 'TI'
        ];

        $result = $this->withSession(['login' => true])->post('/mahasiswa', $data);

        $result->assertRedirectTo('/mahasiswa');
        $this->seeInDatabase('mahasiswa', ['nim' => '200202', 'nama' => 'Student B']);
    }

    /**
     * Test update modifies record
     */
    public function testUpdateChangesRecord()
    {
        $this->db->table('mahasiswa')->insert([
            'nim'   => '404', 'nama' => 'Old Name', 'prodi' => 'TI',
            'created_at' => date('Y-m-d H:i:s'), 'updated_at' => date('Y-m-d H:i:s')
        ]);
        $row = $this->db->table('mahasiswa')->where('nim', '404')->get()->getRow();
        $id = $row->id;

        $data = [
            'nim'  => '404', // test uniqueness ignore
            'nama' => 'New Name',
            'prodi' => 'SI'
        ];

        $result = $this->withSession(['login' => true])->put("/mahasiswa/{$id}", $data);

        $result->assertRedirectTo('/mahasiswa');
        $result->assertSessionHas('success', 'Mahasiswa berhasil diperbarui');
        $this->seeInDatabase('mahasiswa', ['id' => $id, 'nama' => 'New Name', 'prodi' => 'SI']);
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

        // Setup dummy kriteria to create an associated penilaian
        $kriteriaId = $this->db->table('kriteria')->insert([
            'nama_kriteria' => 'Test', 'bobot' => 0.5, 'tipe' => 'B'
        ]);
        $kriteriaId = $this->db->insertID();

        // Create a penilaian linked to the mahasiswa
        $this->db->table('penilaian')->insert([
            'mahasiswa_id' => $id,
            'kriteria_id' => $kriteriaId,
            'nilai' => 80
        ]);

        $result = $this->withSession(['login' => true])->call('DELETE', "/mahasiswa/{$id}");

        $result->assertRedirectTo('/mahasiswa');
        $this->seeInDatabase('mahasiswa', ['id' => $id, 'deleted_at !=' => null]);
        
        // Assert Penilaian is hard deleted
        $this->dontSeeInDatabase('penilaian', ['mahasiswa_id' => $id]);
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
