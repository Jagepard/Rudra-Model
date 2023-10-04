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

    /**
     * Logical operator AND
     * --------------------
     * Логический оператор И
     *
     * @param $param
     * @return void
     */
    public function and($param)
    {
        $this->query .= "AND $param ";
        return $this;
    }

    /**
     * Logical operator OR
     * --------------------
     * Логический оператор ИЛИ
     *
     * @param $param
     * @return void
     */
    public function or($param)
    {
        $this->query .= "OR $param ";
        return $this;
    }

    /**
     * LIMIT is an optional clause of the SELECT statement 
     * ---------------------------------------------------
     * LIMIT — необязательное предложение оператора SELECT.
     *
     * @param  $param
     * @return void
     */
    public function limit($param)
    {
        $this->query .= "LIMIT $param ";
        return $this;
    }

    /**
     * OFFSET clause
     * -------------
     * Предложение OFFSET
     *
     * @param  [type] $param
     * @return void
     */
    public function offset($param)
    {
        $this->query .= "OFFSET $param ";
        return $this;
    }

    /**
     * To sort the rows of the result set, use the ORDER BY
     * ----------------------------------------------------
     * Чтобы отсортировать строки результирующего набора, используйте ORDER BY
     *
     * @param  $param
     * @return void
     */
    public function orderBy($param)
    {
        $this->query .= "ORDER BY $param ";
        return $this;
    }

    /**
     * The GROUP BY clause divides the rows returned from the SELECT statement into groups
     * -----------------------------------------------------------------------------------
     * Предложение GROUP BY делит строки, возвращаемые инструкцией SELECT, на группы
     *
     * @param  $param
     * @return void
     */
    public function groupBy($param)
    {
        $this->query .= "GROUP BY $param ";
        return $this;
    }

    /**
     * PostgreSQL join is used to combine columns from one (self-join) or more tables
     * ------------------------------------------------------------------------------
     * Соединение PostgreSQL используется для объединения столбцов из одной (самообъединение) или нескольких таблиц
     *
     * @param  $param
     * @param  string $type
     * @return void
     */
    public function join($param, $type = "LEFT")
    {
        $this->query .= "$type JOIN $param ";
        return $this;
    }

    /**
     * Matching the values
     * -------------------
     * Соответствие значений
     *
     * @param  $param
     * @return void
     */
    public function on($param)
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
}
