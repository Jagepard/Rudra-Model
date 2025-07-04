<?php

declare(strict_types = 1);

/**
 * @author  : Jagepard <jagepard@yandex.ru">
 * @license https://mit-license.org/ MIT
 */

namespace Rudra\Model\Driver;

class SQLite
{
    /**
     * @param  string $fieldName
     * @param  string $alias
     * @param  string $orderBy
     * @return string
     */
    public function concat(string $fieldName, string $alias, string $orderBy): string
    {
        return ", GROUP_CONCAT($fieldName,';') $alias  ";  
    }

    /**
     * @return string
     */
    public function close(): string
    {
        return ")";
    }

    /**
     * @param  string  $field
     * @param  string  $default
     * @param  boolean $pk
     * @param  string  $null
     * @return string
     */
    public function integer(string $field, string $default = "", bool $pk = false, string $null = "NOT NULL"): string
    {
        if ($pk) {
            return "$field INTEGER PRIMARY KEY";
        }

        return ", $field INTEGER $null $default";
    }

    /**
     * @param  string $field
     * @param  string $default
     * @param  string $null
     * @return string
     */
    public function string(string $field, string $default = "", string $null = "NOT NULL"): string
    {
        return ", $field TEXT $null $default";
    }

    /**
     * @param  string $field
     * @param  string $null
     * @return string
     */
    public function text(string $field, string $null = "NOT NULL"): string
    {
        return ", $field TEXT $null";
    }

    /**
     * @return string
     */
    public function created_at(): string
    {
        return ", created_at TEXT DEFAULT CURRENT_TIMESTAMP";
    }

    /**
     * @return string
     */
    public function updated_at(): string
    {
        return ", updated_at TEXT DEFAULT CURRENT_TIMESTAMP";
    }

    /**
     * @param  string $field
     * @return string
     */
    public function pk(string $field): string
    {
        return "";
    }
}
