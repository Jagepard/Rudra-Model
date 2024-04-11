<?php

declare(strict_types = 1);

/**
 * @author    : Jagepard <jagepard@yandex.ru">
 * @license   https://mit-license.org/ MIT
 */

namespace Rudra\Model\Driver;

class MySQL
{
    public function select(?string $fields = '*')
    {
        return "SELECT {$fields} ";
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
        return "FROM {$table} ";
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

    #######################################

    public function close()
    {
        return ") ENGINE = InnoDB DEFAULT CHARSET = utf8mb4 COLLATE = utf8mb4_0900_ai_ci";
    }

    public function integer($field, $default = "", $autoincrement = false, $null = "NOT NULL")
    {
        if ($autoincrement) {
            return "`$field` INT $null AUTO_INCREMENT $default,";
        }

        return "`$field` INT $null $default,";
    }

    public function string($field, $default = "", $null = "NOT NULL")
    {
        return "`$field` VARCHAR(255) $null $default,";
    }

    public function text($field, $null = "NOT NULL")
    {
        return "`$field` text $null,";
    }

    public function created_at()
    {
        return "`created_at` TIMESTAMP NOT NULL DEFAULT CURRENT_TIMESTAMP,";
    }

    public function updated_at()
    {
        return "`updated_at` TIMESTAMP NOT NULL DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,";
    }

    public function pk($field)
    {
        return "PRIMARY KEY (`$field`)";
    }
}
