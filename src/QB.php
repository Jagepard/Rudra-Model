<?php

declare(strict_types = 1);

/**
 * @author    : Jagepard <jagepard@yandex.ru">
 * @license   https://mit-license.org/ MIT
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

    public function __construct()
    {
        if (Rudra::get("DSN")->getAttribute(\PDO::ATTR_DRIVER_NAME) === "mysql") {
            $this->driver = new MySQL;
        } elseif (Rudra::get("DSN")->getAttribute(\PDO::ATTR_DRIVER_NAME) === "pgsql") {
            $this->driver = new PgSQL;
        } elseif (Rudra::get("DSN")->getAttribute(\PDO::ATTR_DRIVER_NAME) === "sqlite") {
            $this->driver = new SQLite;
        }
    }

    /**
     * Selects data from the database
     * ------------------------------
     * Выбирает данные из базы данных
     *
     * @param string $fields
     * @return $this
     */
    public function select(string $fields = '*'): self
    {
        $this->query .= "SELECT {$fields} ";
        return $this;
    }

    /**
     * Accepts a set of values
     * -----------------------
     * Принимает набор значений
     *
     * @param string $fieldName
     * @param string $alias
     * @param string|null $orderBy
     * @return $this
     */
    public function concat(string $fieldName, string $alias, ?string $orderBy = null): self
    {
        $this->query .= $this->driver->concat($fieldName, $alias, $orderBy);
        return $this;
    }

    /**
     * Specifies the table name
     * ------------------------
     * Указывает название таблицы
     *
     * @param string $table
     * @return $this
     */
    public function from(string $table): self
    {
        $this->query .= "FROM {$table} ";
        return $this;
    }

    /**
     * WHERE clause to filter rows returned by a SELECT statement
     * ----------------------------------------------------------
     * Предложение WHERE для фильтрации строк, возвращаемых инструкцией SELECT.
     *
     * @param string $param
     * @return $this
     */
    public function where(string $param): self
    {
        $this->query .= "WHERE $param ";
        return $this;
    }

    /**
     * Logical operator AND
     * --------------------
     * Логический оператор И
     *
     * @param string $param
     * @return $this
     */
    public function and(string $param): self
    {
        $this->query .= "AND $param ";
        return $this;
    }

    /**
     * Logical operator OR
     * --------------------
     * Логический оператор ИЛИ
     *
     * @param string $param
     * @return $this
     */
    public function or(string $param): self
    {
        $this->query .= "OR $param ";
        return $this;
    }

    /**
     * LIMIT is an optional clause of the SELECT statement
     * ---------------------------------------------------
     * LIMIT — необязательное предложение оператора SELECT.
     *
     * @param string $param
     * @return $this
     */
    public function limit(string $param): self
    {
        $this->query .= "LIMIT $param ";
        return $this;
    }

    /**
     * OFFSET clause
     * -------------
     * Предложение OFFSET
     *
     * @param string $param
     * @return $this
     */
    public function offset(string $param): self
    {
        $this->query .= "OFFSET $param ";
        return $this;
    }

    /**
     * To sort the rows of the result set, use the ORDER BY
     * ----------------------------------------------------
     * Чтобы отсортировать строки результирующего набора, используйте ORDER BY
     *
     * @param string $param
     * @return $this
     */
    public function orderBy(string $param): self
    {
        $this->query .= "ORDER BY $param ";
        return $this;
    }

    /**
     * The GROUP BY clause divides the rows returned from the SELECT statement into groups
     * -----------------------------------------------------------------------------------
     * Предложение GROUP BY делит строки, возвращаемые инструкцией SELECT, на группы
     *
     * @param string $param
     * @return $this
     */
    public function groupBy(string $param): self
    {
        $this->query .= "GROUP BY $param ";
        return $this;
    }

    /**
     * PostgresSQL join is used to combine columns from one (self-join) or more tables
     * * ------------------------------------------------------------------------------
     * * Соединение PostgresSQL используется для объединения столбцов из одной (самообъединение) или нескольких таблиц
     *
     * @param string $param
     * @param string $type
     * @return $this
     */
    public function join(string $param, string $type = "LEFT"): self
    {
        $this->query .= "$type JOIN $param ";
        return $this;
    }

    /**
     * Matching the values
     * -------------------
     * Соответствие значений
     *
     * @param string $param
     * @return $this
     */
    public function on(string $param): self
    {
        $this->query .= "ON $param ";
        return $this;
    }

    /**
     * Gets query string
     * -----------------
     * Получает строку запроса
     *
     * @return string
     */
    public function get(): string
    {
        $result      = $this->query . ';';
        $this->query = '';

        return $result;
    }

    /**
     * @param string $table
     * @return $this
     */
    public function create(string $table): self
    {
        $this->query .= "CREATE TABLE {$table} (";
        return $this;
    }

    /**
     * @return $this
     */
    public function close(): self
    {
        $this->query .= $this->driver->close();
        return $this;
    }

    /**
     * @param string $field
     * @param string $default
     * @param bool $autoincrement
     * @param string $null
     * @return $this
     */
    public function integer(string $field, string $default = "", bool $autoincrement = false, string $null = "NOT NULL"): self
    {
        $this->query .= $this->driver->integer($field, $default, $autoincrement, $null);
        return $this;
    }

    /**
     * @param string $field
     * @param string $default
     * @param string $null
     * @return $this
     */
    public function string(string $field, string $default = "", string $null = "NOT NULL"): self
    {
        $this->query .= $this->driver->string($field, $default, $null);
        return $this;
    }

    /**
     * @param string $field
     * @param string $null
     * @return $this
     */
    public function text(string $field, string $null = "NOT NULL"): self
    {
        $this->query .= $this->driver->text($field, $null);
        return $this;
    }

    /**
     * @return $this
     */
    public function created_at(): self
    {
        $this->query .= $this->driver->created_at();
        return $this;
    }

    /**
     * @return $this
     */
    public function updated_at(): self
    {
        $this->query .= $this->driver->updated_at();
        return $this;
    }

    /**
     * @param string|null $field
     * @return $this
     */
    public function pk(?string $field = null): self
    {
        $this->query .= $this->driver->pk($field);
        return $this;
    }
}
