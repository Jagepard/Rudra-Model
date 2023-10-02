<?php

declare(strict_types = 1);

/**
 * @author    : Jagepard <jagepard@yandex.ru">
 * @license   https://mit-license.org/ MIT
 */

namespace Rudra\Model;

class QB
{
    private string $query = '';

    /**
     * Selects data from the database
     * ------------------------------
     * Выбирает данные из базы данных
     *
     * @param  string|null $fields
     * @return void
     */
    public function select(?string $fields = '*')
    {
        $this->query .= "SELECT {$fields} ";
        return $this;
    }

    /**
     * Accepts a set of values
     * -----------------------
     * Принимает набор значений
     *
     * @param  string $fieldName
     * @param  string $alias
     * @param  string $orderBy
     * @return void
     */
    public function array_agg(string $fieldName, string $alias, string $orderBy)
    {
        $this->query .= ", array_to_json(array_agg($fieldName ORDER BY $orderBy)) $alias  ";        
        return $this;
    }

    /**
     * Specifies the table name
     * ------------------------
     * Указывает название таблицы
     *
     * @param  $table
     * @return void
     */
    public function from($table)
    {
        $this->query .= "FROM {$table} ";
        return $this;
    }

    /**
     * WHERE clause to filter rows returned by a SELECT statement
     * ----------------------------------------------------------
     * Предложение WHERE для фильтрации строк, возвращаемых инструкцией SELECT.
     *
     * @param  $param
     * @return void
     */
    public function where($param)
    {
        $this->query .= "WHERE $param ";
        return $this;
    }

    public function and($param)
    {
        $this->query .= "AND $param ";
        return $this;
    }

    public function or($param)
    {
        $this->query .= "OR $param ";
        return $this;
    }

    public function limit($param)
    {
        $this->query .= "LIMIT $param ";
        return $this;
    }

    public function offset($param)
    {
        $this->query .= "OFFSET $param ";
        return $this;
    }

    public function orderBy($param)
    {
        $this->query .= "ORDER BY $param ";
        return $this;
    }

    public function groupBy($param)
    {
        $this->query .= "GROUP BY $param ";
        return $this;
    }

    public function join($param, $type = "LEFT")
    {
        $this->query .= "$type JOIN $param ";
        return $this;
    }

    public function on($param)
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
}
