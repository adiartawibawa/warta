<?php

namespace Tests\Unit\Repositories;

use App\Models\User;
use App\Repositories\UserRepository;
use Mockery;
use PHPUnit\Framework\TestCase;

class UserRepositoryTest extends TestCase
{
    protected $userMock;
    protected $repository;

    /**
     * Set up the test
     */
    protected function setUp(): void
    {
        parent::setUp();
        $this->userMock = Mockery::mock(User::class);
        $this->repository = new UserRepository($this->userMock);
    }

    /**
     * Test find by email
     */
    public function testFindByEmail()
    {
        $this->userMock->shouldReceive('where')->with('email', 'test@example.com')->once()->andReturnSelf();
        $this->userMock->shouldReceive('first')->once()->andReturn((object) ['email' => 'test@example.com']);

        $result = $this->repository->findByEmail('test@example.com');
        $this->assertEquals('test@example.com', $result->email);
    }

    /**
     * Test find by username
     */
    public function testFindByUsername()
    {
        $this->userMock->shouldReceive('where')->with('username', 'testuser')->once()->andReturnSelf();
        $this->userMock->shouldReceive('first')->once()->andReturn((object) ['username' => 'testuser']);

        $result = $this->repository->findByUsername('testuser');
        $this->assertEquals('testuser', $result->username);
    }

    /**
     * Test find by email or username
     */
    public function testFindByEmailOrUsername()
    {
        $this->userMock->shouldReceive('where')->with('email', 'test@example.com')->once()->andReturnSelf();
        $this->userMock->shouldReceive('orWhere')->with('username', 'test@example.com')->once()->andReturnSelf();
        $this->userMock->shouldReceive('first')->once()->andReturn((object) ['email' => 'test@example.com', 'username' => 'testuser']);

        $result = $this->repository->findByEmailOrUsername('test@example.com');
        $this->assertEquals('test@example.com', $result->email);
        $this->assertEquals('testuser', $result->username);
    }
}
