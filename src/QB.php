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

    public function select(?string $fields = '*')
    {
        $this->query .= "SELECT {$fields} ";
        return $this;
    }

    public function from($table)
    {
        $this->query .= "FROM {$table} ";
        return $this;
    }

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

    public function join($param)
    {
        $this->query .= "LEFT JOIN $param ";
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
