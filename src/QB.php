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

use Rudra\Model\Driver\MySQL;
use Rudra\Model\Driver\PgSQL;
use Rudra\Model\Driver\SQLite;
use Rudra\Container\Facades\Rudra;
use Rudra\Exceptions\LogicException;

class QB
{
    private MySQL|PgSQL|SQLite $driver;
    private string $query = '';

    /**
     * Initializes the database driver based on the provided connection or a default connection from the container.
     * If no connection is provided and none is available in the container, a LogicException is thrown.
     * The driver is selected based on the database type specified in the connection's driver attribute.
     * 
     * @param mixed $connection DO NOT TYPE HINT - causes premature PDO resolution by IoC container
     * @throws LogicException
     */
    public function __construct($connection = null)
    {
        $connection = $connection ?? Rudra::get('connection') ?? throw new LogicException("connection is not installed");

        if ($connection->getAttribute(\PDO::ATTR_DRIVER_NAME) === "mysql") {
            $this->driver = new MySQL;
        } elseif ($connection->getAttribute(\PDO::ATTR_DRIVER_NAME) === "pgsql") {
            $this->driver = new PgSQL;
        } elseif ($connection->getAttribute(\PDO::ATTR_DRIVER_NAME) === "sqlite") {
            $this->driver = new SQLite;
        }
    }

    public function select(string $fields = '*'): self
    {
        $this->query .= "SELECT {$fields} ";
        return $this;
    }

    public function concat(string $fieldName, string $alias, ?string $orderBy = null): self
    {
        $this->query .= $this->driver->concat($fieldName, $alias, $orderBy);
        return $this;
    }

    public function from(string $table): self
    {
        $this->query .= "FROM {$table} ";
        return $this;
    }

    public function where(string $param): self
    {
        $this->query .= "WHERE $param ";
        return $this;
    }

    public function and(string $param): self
    {
        $this->query .= "AND $param ";
        return $this;
    }

    public function or(string $param): self
    {
        $this->query .= "OR $param ";
        return $this;
    }

    public function limit(int|string $param): self
    {
        $this->query .= "LIMIT $param ";
        return $this;
    }

    public function offset(int|string $param): self
    {
        $this->query .= "OFFSET $param ";
        return $this;
    }

    public function orderBy(string $param): self
    {
        $this->query .= "ORDER BY $param ";
        return $this;
    }

    public function groupBy(string $param): self
    {
        $this->query .= "GROUP BY $param ";
        return $this;
    }

    public function join(string $param, string $type = "LEFT"): self
    {
        $this->query .= "$type JOIN $param ";
        return $this;
    }

    public function on(string $param): self
    {
        $this->query .= "ON $param ";
        return $this;
    }

    public function get(): string
    {
        $result      = $this->query . ';';
        $this->query = '';

        return $result;
    }

    public function create(string $table): self
    {
        $this->query .= "CREATE TABLE {$table} (";
        return $this;
    }

    public function close(): self
    {
        $this->query .= $this->driver->close();
        return $this;
    }

    public function integer(string $field, string $default = "", bool $autoincrement = false, string $null = "NOT NULL"): self
    {
        $this->query .= $this->driver->integer($field, $default, $autoincrement, $null);
        return $this;
    }

    public function string(string $field, string $default = "", string $null = "NOT NULL"): self
    {
        $this->query .= $this->driver->string($field, $default, $null);
        return $this;
    }

    public function text(string $field, string $null = "NOT NULL"): self
    {
        $this->query .= $this->driver->text($field, $null);
        return $this;
    }

    public function created_at(): self
    {
        $this->query .= $this->driver->created_at();
        return $this;
    }

    public function updated_at(): self
    {
        $this->query .= $this->driver->updated_at();
        return $this;
    }

    public function pk(?string $field = null): self
    {
        $this->query .= $this->driver->pk($field);
        return $this;
    }
}
