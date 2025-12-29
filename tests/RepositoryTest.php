<?php declare(strict_types=1);

/**
 * This Source Code Form is subject to the terms of the Mozilla Public
 * License, v. 2.0. If a copy of the MPL was not distributed with this
 * file, You can obtain one at https://mozilla.org/MPL/2.0/.
 *
 * @author  Korotkov Danila (Jagepard) <jagepard@yandex.ru>
 * @license https://mozilla.org/MPL/2.0/  MPL-2.0
 * 
 * phpunit src/tests/ContainerTest --coverage-html src/tests/coverage-html
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
