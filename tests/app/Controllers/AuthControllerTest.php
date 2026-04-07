<?php

namespace App\Controllers;

use CodeIgniter\Test\CIUnitTestCase;
use CodeIgniter\Test\FeatureTestTrait;
use CodeIgniter\Test\DatabaseTestTrait;

class AuthControllerTest extends CIUnitTestCase
{
    use FeatureTestTrait, DatabaseTestTrait;

    protected $migrate = true;
    protected $namespace = 'App';

    /**
     * Test index redirects to login
     */
    public function testIndexRedirectsToLogin()
    {
        $result = $this->get('/');
        $result->assertRedirectTo('/login');
    }

    /**
     * Test login view renders
     */
    public function testLoginViewRenders()
    {
        $result = $this->get('/login');
        $result->assertStatus(200);
        $result->assertSee('Login');
    }

    /**
     * Test login while already authenticated redirects to dashboard
     */
    public function testLoginWhileAuthenticatedRedirects()
    {
        $result = $this->withSession(['login' => true])->get('/login');
        $result->assertRedirectTo('/dashboard');
    }

    /**
     * Test authentication success
     */
    public function testAuthenticateSuccess()
    {
        // Seed user
        $this->db->table('users')->insert([
            'username' => 'admin',
            'password' => password_hash('password123', PASSWORD_DEFAULT),
            'created_at' => date('Y-m-d H:i:s'),
            'updated_at' => date('Y-m-d H:i:s'),
        ]);

        $result = $this->post('/login', [
            'username' => 'admin',
            'password' => 'password123'
        ]);

        $result->assertRedirectTo('/dashboard');
        $result->assertSessionHas('login', true);
        $result->assertSessionHas('username', 'admin');
    }

    /**
     * Test authentication failure: user not found
     */
    public function testAuthenticateUserNotFound()
    {
        $result = $this->post('/login', [
            'username' => 'nobody',
            'password' => 'password123'
        ]);

        $result->assertRedirectTo('/login');
        $result->assertSessionHas('error', 'User not found');
    }

    /**
     * Test authentication failure: wrong password
     */
    public function testAuthenticateWrongPassword()
    {
        // Seed user
        $this->db->table('users')->insert([
            'username' => 'admin2',
            'password' => password_hash('password123', PASSWORD_DEFAULT),
            'created_at' => date('Y-m-d H:i:s'),
            'updated_at' => date('Y-m-d H:i:s'),
        ]);

        $result = $this->post('/login', [
            'username' => 'admin2',
            'password' => 'wrongpass'
        ]);

        $result->assertRedirectTo('/login');
        $result->assertSessionHas('error', 'Wrong password');
    }

    /**
     * Test logout redirects to login
     */
    public function testLogout()
    {
        $result = $this->withSession(['login' => true])->get('/logout');
        $result->assertRedirectTo('/login');
    }
}
