<?php declare(strict_types=1);

/**
 * This Source Code Form is subject to the terms of the Mozilla Public
 * License, v. 2.0. If a copy of the MPL was not distributed with this
 * file, You can obtain one at https://mozilla.org/MPL/2.0/.
 *
 * @author  Korotkov Danila (Jagepard) <jagepard@yandex.ru>
 * @license https://mozilla.org/MPL/2.0/  MPL-2.0
 */

/**
 * @author  : Jagepard <jagepard@yandex.ru">
 * @license https://mit-license.org/ MIT
 */

namespace Rudra\Model;

use Rudra\Model\Driver\MySQL;
use Rudra\Model\Driver\PgSQL;
use Rudra\Model\Driver\SQLite;
use Rudra\Container\Facades\Rudra;
use Rudra\Exceptions\LogicException;

class QB
{
    private $driver;
    private string $query = '';

    /**
     * Initializes the database driver based on the provided connection or a default connection from the container.
     * If no connection is provided and none is available in the container, a LogicException is thrown.
     * The driver is selected based on the database type specified in the connection's driver attribute.
     * -------------------------
     * Инициализирует драйвер базы данных на основе предоставленного connection или connection по умолчанию из контейнера.
     * Если connection не предоставлен и отсутствует в контейнере, выбрасывается исключение LogicException.
     * Драйвер выбирается на основе типа базы данных, указанного в атрибуте драйвера connection.
     * 
     * @param $connection
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

    /**
     * @param  string $fields
     * @return self
     */
    public function select(string $fields = '*'): self
    {
        $this->query .= "SELECT {$fields} ";
        return $this;
    }

    /**
     * @param  string      $fieldName
     * @param  string      $alias
     * @param  string|null $orderBy
     * @return self
     */
    public function concat(string $fieldName, string $alias, ?string $orderBy = null): self
    {
        $this->query .= $this->driver->concat($fieldName, $alias, $orderBy);
        return $this;
    }

    /**
     * @param  string $table
     * @return self
     */
    public function from(string $table): self
    {
        $this->query .= "FROM {$table} ";
        return $this;
    }

    /**
     * @param  string $param
     * @return self
     */
    public function where(string $param): self
    {
        $this->query .= "WHERE $param ";
        return $this;
    }

    /**
     * @param  string $param
     * @return self
     */
    public function and(string $param): self
    {
        $this->query .= "AND $param ";
        return $this;
    }

    /**
     * @param  string $param
     * @return self
     */
    public function or(string $param): self
    {
        $this->query .= "OR $param ";
        return $this;
    }

    /**
     * @param $param
     * @return self
     */
    public function limit($param): self
    {
        $this->query .= "LIMIT $param ";
        return $this;
    }

    /**
     * @param  $param
     * @return self
     */
    public function offset($param): self
    {
        $this->query .= "OFFSET $param ";
        return $this;
    }

    /**
     * @param  string $param
     * @return self
     */
    public function orderBy(string $param): self
    {
        $this->query .= "ORDER BY $param ";
        return $this;
    }

    /**
     * @param  string $param
     * @return self
     */
    public function groupBy(string $param): self
    {
        $this->query .= "GROUP BY $param ";
        return $this;
    }

    /**
     * @param  string $param
     * @param  string $type
     * @return self
     */
    public function join(string $param, string $type = "LEFT"): self
    {
        $this->query .= "$type JOIN $param ";
        return $this;
    }

    /**
     * @param  string $param
     * @return self
     */
    public function on(string $param): self
    {
        $this->query .= "ON $param ";
        return $this;
    }

    /**
     * @return string
     */
    public function get(): string
    {
        $result      = $this->query . ';';
        $this->query = '';

        return $result;
    }

    /**
     * @param  string $table
     * @return self
     */
    public function create(string $table): self
    {
        $this->query .= "CREATE TABLE {$table} (";
        return $this;
    }

    /**
     * @return self
     */
    public function close(): self
    {
        $this->query .= $this->driver->close();
        return $this;
    }

    /**
     * @param  string  $field
     * @param  string  $default
     * @param  boolean $autoincrement
     * @param  string  $null
     * @return self
     */
    public function integer(string $field, string $default = "", bool $autoincrement = false, string $null = "NOT NULL"): self
    {
        $this->query .= $this->driver->integer($field, $default, $autoincrement, $null);
        return $this;
    }

    /**
     * @param  string $field
     * @param  string $default
     * @param  string $null
     * @return self
     */
    public function string(string $field, string $default = "", string $null = "NOT NULL"): self
    {
        $this->query .= $this->driver->string($field, $default, $null);
        return $this;
    }

    /**
     * @param  string $field
     * @param  string $null
     * @return self
     */
    public function text(string $field, string $null = "NOT NULL"): self
    {
        $this->query .= $this->driver->text($field, $null);
        return $this;
    }

    /**
     * @return self
     */
    public function created_at(): self
    {
        $this->query .= $this->driver->created_at();
        return $this;
    }

    /**
     * @return self
     */
    public function updated_at(): self
    {
        $this->query .= $this->driver->updated_at();
        return $this;
    }

    /**
     * @param  string|null $field
     * @return self
     */
    public function pk(?string $field = null): self
    {
        $this->query .= $this->driver->pk($field);
        return $this;
    }
}
