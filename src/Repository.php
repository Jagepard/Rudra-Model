<?php declare(strict_types=1);

/**
 * This Source Code Form is subject to the terms of the Mozilla Public
 * License, v. 2.0. If a copy of the MPL was not distributed with this
 * file, You can obtain one at https://mozilla.org/MPL/2.0/.
 *
 * @author  Korotkov Danila (Jagepard) <jagepard@yandex.ru>
 * @license https://mozilla.org/MPL/2.0/  MPL-2.0
 */

namespace Rudra\Model;

use Rudra\Container\Rudra;
use Rudra\Model\Traits\CrudTrait;
use Rudra\Model\Traits\CacheTrait;
use Rudra\Model\Traits\SchemaTrait;
use Rudra\Exceptions\LogicException;

class Repository
{
    use CrudTrait;
    use CacheTrait;
    use SchemaTrait;

    public ?string $table;
    private Rudra $rudra;
    protected \PDO $connection;
    protected QB $qb;

    /**
     * Initializes the class with a table name, connection, and sets up dependencies.
     * The connection is either provided directly or retrieved from the Rudra container.
     * If the connection is not an instance of PDO, a LogicException is thrown.
     *
     * @throws LogicException
     */
    public function __construct(?string $table, ?\PDO $connection = null)
    {
        $this->table = $table;
        $this->rudra = Rudra::run();
        $this->connection = $connection ?? $this->rudra->get('connection');
        $this->qb = new QB($this->connection);

        if (!$this->connection instanceof \PDO) {
            throw new LogicException('connection must be an instance of PDO');
        }
    }

    /**
     * Handles calls to undefined methods by throwing a LogicException.
     * This method is invoked when an attempt is made to call a non-existent method on the object.
     *
     * @throws LogicException
     */
    public function __call(string $method, array $parameters = []): never
    {
        throw new LogicException(sprintf('Method %s does not exists', $method));
    }

    /**
     * Returns an instance of the Query Builder (QB).
     * If the QB instance is not yet initialized, it creates a new instance using the connection.
     * This method implements lazy initialization to ensure the QB instance is created only when needed.
     */
    public function qb(): QB
    {
        if ($this->qb === null) {
            $this->qb = new QB($this->connection);
        }

        return $this->qb;
    }

    /**
     * Returns the current PDO instance used by the repository.
     */
    public function connection(): \PDO
    {
        return $this->connection;
    }

    /**
     * Sets the connection for the database connection and resets the Query Builder instance.
     * This method allows changing the connection dynamically and ensures that the Query Builder is re-initialized.
     */
    public function onConnection(\PDO $connection): self
    {
        $this->connection = $connection;
        $this->qb  = null;

        return $this;
    }

    /**
     * Creates and returns a new instance of the class with the specified connection.
     * This method allows changing the connection while preserving the current table name.
     * It is useful for creating new instances with different database connections without modifying the original object.
     */
    public function withConnection(\PDO $connection): self
    {
        return new static($this->table, $connection);
    }

    /**
     * Executes a custom SQL query and returns the result as an associative array.
     * The method prepares the query, executes it with optional parameters, and fetches all results.
     */
    public function qBuilder(string $queryString, array $queryParams = []): array
    {
        $stmt = $this->connection->prepare($queryString);
        $stmt->execute($queryParams);

        return $stmt->fetchAll(\PDO::FETCH_ASSOC);
    }
}
