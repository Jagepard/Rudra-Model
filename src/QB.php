<?php

declare(strict_types = 1);

/**
 * @author  : Jagepard <jagepard@yandex.ru">
 * @license https://mit-license.org/ MIT
 */

namespace Rudra\Model;

use Rudra\Model\Driver\MySQL;
use Rudra\Model\Driver\PgSQL;
use Rudra\Model\Driver\SQLite;
use Rudra\Container\Facades\Rudra;

class QB
{
    private $driver;
    private string $query = '';

    public function __construct(\PDO $dsn)
    {
        if ($dsn->getAttribute(\PDO::ATTR_DRIVER_NAME) === "mysql") {
            $this->driver = new MySQL;
        } elseif ($dsn->getAttribute(\PDO::ATTR_DRIVER_NAME) === "pgsql") {
            $this->driver = new PgSQL;
        } elseif ($dsn->getAttribute(\PDO::ATTR_DRIVER_NAME) === "sqlite") {
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

    public function limit($param): self
    {
        $this->query .= "LIMIT $param ";
        return $this;
    }

    public function offset($param): self
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
