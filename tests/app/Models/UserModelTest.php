<?php

namespace App\Models;

use CodeIgniter\Test\CIUnitTestCase;
use CodeIgniter\Test\DatabaseTestTrait;

class UserModelTest extends CIUnitTestCase
{
    use DatabaseTestTrait;

    protected $migrate = true;
    protected $namespace = 'App';

    protected function setUp(): void
    {
        parent::setUp();
    }

    /**
     * Test successful insertion with valid data
     */
    public function testInsertUserSuccessful()
    {
        $model = new UserModel();

        $data = [
            'username' => 'testuser',
            'password' => password_hash('password123', PASSWORD_DEFAULT),
        ];

        $id = $model->insert($data);

        $this->assertIsNumeric($id);
        $this->seeInDatabase('users', ['username' => 'testuser']);
    }

    /**
     * Test findByUsername method
     */
    public function testFindByUsername()
    {
        $model = new UserModel();
        $password = password_hash('password123', PASSWORD_DEFAULT);

        $model->insert([
            'username' => 'findme',
            'password' => $password,
        ]);

        $user = $model->findByUsername('findme');

        $this->assertNotNull($user);
        $this->assertEquals('findme', $user->username);
        $this->assertTrue(password_verify('password123', $user->password));
    }

    /**
     * Test findByUsername returns null for non-existent user
     */
    public function testFindByUsernameReturnsNullForMissingUser()
    {
        $model = new UserModel();
        $user = $model->findByUsername('nonexistent');
        $this->assertNull($user);
    }

    /**
     * Test validation: Username too short
     */
    public function testValidationUsernameTooShort()
    {
        $model = new UserModel();

        $data = [
            'username' => 'te',
            'password' => 'password123',
        ];

        $result = $model->insert($data);

        $this->assertFalse($result);
        $this->assertArrayHasKey('username', $model->errors());
    }

    /**
     * Test validation: Duplicate username
     */
    public function testValidationDuplicateUsername()
    {
        $model = new UserModel();

        $data = [
            'username' => 'duplicate',
            'password' => 'password123',
        ];

        $model->insert($data);
        $result = $model->insert($data);

        $this->assertFalse($result);
        $this->assertArrayHasKey('username', $model->errors());
    }

    /**
     * Test validation: Password too short
     */
    public function testValidationPasswordTooShort()
    {
        $model = new UserModel();

        $data = [
            'username' => 'validuser',
            'password' => '12345',
        ];

        $result = $model->insert($data);

        $this->assertFalse($result);
        $this->assertArrayHasKey('password', $model->errors());
    }
}
