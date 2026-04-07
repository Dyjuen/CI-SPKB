<?php

namespace App\Controllers;

use CodeIgniter\Test\CIUnitTestCase;
use CodeIgniter\Test\FeatureTestTrait;
use CodeIgniter\Test\DatabaseTestTrait;

class DashboardTest extends CIUnitTestCase
{
    use FeatureTestTrait, DatabaseTestTrait;

    protected $migrate = true;
    protected $namespace = 'App';

    /**
     * Test index works when authenticated
     */
    public function testIndexAuthenticated()
    {
        // Seed some data for counters
        $this->db->table('mahasiswa')->insert([
            'nim'   => '777', 'nama' => 'Mhs X', 'prodi' => 'TI',
            'created_at' => date('Y-m-d H:i:s'), 'updated_at' => date('Y-m-d H:i:s')
        ]);
        
        $this->db->table('kriteria')->insert([
            'nama_kriteria' => 'Krit Y', 'bobot' => 0.5, 'tipe' => 'B',
            'created_at' => date('Y-m-d H:i:s'), 'updated_at' => date('Y-m-d H:i:s')
        ]);

        $result = $this->withSession(['login' => true])->get('/dashboard');

        $result->assertStatus(200);
        $result->assertSee('Dashboard');
        $result->assertSee('Total Mahasiswa');
        $result->assertSee('Total Kriteria');
    }

    /*
     Note: Unauthenticated test might fail or exit prematurely due to PHP exit() in constructor.
     Usually this should be handled by a filter.
    */
}
