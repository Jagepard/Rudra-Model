<?php

declare(strict_types=1);

/**
 * @author  : Jagepard <jagepard@yandex.ru">
 * @license https://mit-license.org/ MIT
 *
 *  phpunit src/tests/ContainerTest --coverage-html src/tests/coverage-html
 */

namespace Rudra\Tests;

use PDO;
use PDOStatement;
use Rudra\Model\Repository;
use PHPUnit\Framework\TestCase;

class RepositoryTest extends TestCase
{
    protected function setUp(): void
    {

    }

    public function testGetAllReturnsExpectedData()
    {
        $mockStmt = $this->createMock(PDOStatement::class);
        $mockStmt->method('execute')->willReturn(true);
        $mockStmt->method('fetchAll')->willReturn([
            ['id' => 1, 'name' => 'John Doe']
        ]);

        $mockPdo = $this->createMock(PDO::class);
        $mockPdo->method('prepare')->willReturn($mockStmt);

        $repository = new Repository('users', $mockPdo);
        $result = $repository->qBuilder("SELECT * FROM users");

        $this->assertSame([
            ['id' => 1, 'name' => 'John Doe']
        ], $result);
    }

    public function testFindReturnsRecordWhenFound()
    {
        $table = 'users';
        $id = 1;
        $expectedResult = ['id' => 1, 'name' => 'John Doe'];

        $mockStmt = $this->createMock(PDOStatement::class);
        $mockStmt->method('execute')->willReturn(true);
        $mockStmt->method('fetch')->willReturn($expectedResult);
        $mockPdo = $this->createMock(PDO::class);
        $mockPdo->method('prepare')->willReturn($mockStmt);

        $repository = new Repository($table, $mockPdo);
        $result     = $repository->find($id);

        $this->assertSame($expectedResult, $result);
    }

    public function testFindReturnsFalseWhenNotFound()
    {
        $table = 'users';
        $id = 999;

        $mockStmt = $this->createMock(PDOStatement::class);
        $mockStmt->method('execute')->willReturn(true);
        $mockStmt->method('fetch')->willReturn(false);
        $mockPdo = $this->createMock(PDO::class);
        $mockPdo->method('prepare')->willReturn($mockStmt);

        $repository = new Repository($table, $mockPdo);
        $result     = $repository->find($id);

        $this->assertFalse($result);
    }
}
