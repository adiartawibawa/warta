<?php

namespace Tests\Unit\Repositories;

use App\Repositories\BaseRepository;
use Illuminate\Database\Eloquent\Model;
use Mockery;
use PHPUnit\Framework\TestCase;

class BaseRepositoryTest extends TestCase
{
    protected $modelMock;
    protected $repository;

    /**
     * Set up the test
     */
    protected function setUp(): void
    {
        parent::setUp();
        $this->modelMock = Mockery::mock(Model::class);
        $this->repository = new BaseRepository($this->modelMock);
    }

    /**
     * Test all
     */
    public function testAll()
    {
        $this->modelMock->shouldReceive('all')->once()->andReturn(collect(['item1', 'item2']));
        $result = $this->repository->all();
        $this->assertCount(2, $result);
    }

    /**
     * Test find
     */
    public function testFind()
    {
        $this->modelMock->shouldReceive('find')->with(1)->once()->andReturn((object) ['id' => 1]);
        $result = $this->repository->find(1);
        $this->assertEquals(1, $result->id);
    }

    /**
     * Test find where
     */
    public function testFindWhere()
    {
        $this->modelMock->shouldReceive('where')->with('email', 'test@example.com')->andReturnSelf();
        $this->modelMock->shouldReceive('get')->once()->andReturn(collect(['user1']));

        $result = $this->repository->findWhere('email', 'test@example.com');
        $this->assertCount(1, $result);
    }

    /**
     * Test create
     */
    public function testCreate()
    {
        $this->modelMock->shouldReceive('create')->with(['name' => 'John'])->once()->andReturn((object) ['id' => 1, 'name' => 'John']);
        $result = $this->repository->create(['name' => 'John']);
        $this->assertEquals('John', $result->name);
    }

    /**
     * Test update
     */
    public function testUpdate()
    {
        $recordMock = Mockery::mock(Model::class);
        $recordMock->shouldReceive('update')->with(['name' => 'Updated'])->once()->andReturn(true);

        $this->modelMock->shouldReceive('find')->with(1)->once()->andReturn($recordMock);

        $result = $this->repository->update(['name' => 'Updated'], 1);
        $this->assertTrue($result);
    }

    /**
     * Test delete
     */
    public function testDelete()
    {
        $recordMock = Mockery::mock(Model::class);
        $recordMock->shouldReceive('delete')->once()->andReturn(true);

        $this->modelMock->shouldReceive('find')->with(1)->once()->andReturn($recordMock);

        $result = $this->repository->delete(1);
        $this->assertTrue($result);
    }
}
