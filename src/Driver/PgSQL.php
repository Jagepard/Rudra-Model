<?php

declare(strict_types = 1);

/**
 * @author  : Jagepard <jagepard@yandex.ru">
 * @license https://mit-license.org/ MIT
 */

namespace Rudra\Model\Driver;

class PgSQL
{
    public function concat(string $fieldName, string $alias, string $orderBy): string
    {
        return ", array_to_string(array_agg($fieldName ORDER BY $orderBy), ';') $alias  ";     
    }

    public function close(): string
    {
        return ");";
    }

    public function integer(string $field, string $default = "", bool $pk = false, string $null = "NOT NULL"): string
    {
        if ($pk) {
            return "$field SERIAL PRIMARY KEY";
        }

        return ", $field INTEGER $null $default";
    }

    public function string(string $field, string $default = "", string $null = "NOT NULL"): string
    {
        return ", $field VARCHAR(255) $null $default";
    }

    public function text(string $field, string $null = "NOT NULL"): string
    {
        return ", $field TEXT $null";
    }

    public function created_at(): string
    {
        return ", created_at TIMESTAMP without time zone";
    }

    public function updated_at(): string
    {
        return ", updated_at TIMESTAMP without time zone";
    }

    public function pk(string $field): string
    {
        return "";
    }
}
