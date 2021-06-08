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

    public function where($qp)
    {
        $this->query .= "WHERE $qp ";
        return $this;
    }

    public function and($qp)
    {
        $this->query .= "AND $qp ";
        return $this;
    }

    public function or($qp)
    {
        $this->query .= "OR $qp ";
        return $this;
    }

    public function limit($qp)
    {
        $this->query .= "LIMIT $qp ";
        return $this;
    }

    public function offset($qp)
    {
        $this->query .= "OFFSET $qp ";
        return $this;
    }

    public function orderBy($qp)
    {
        $this->query .= "ORDER BY $qp ";
        return $this;
    }

    public function join($qp)
    {
        $this->query .= "LEFT JOIN $qp ";
        return $this;
    }

    public function on($qp)
    {
        $this->query .= "ON $qp ";
        return $this;
    }

    public function get(): string
    {
        $result      = $this->query . ';';
        $this->query = '';

        return $result;
    }
}
