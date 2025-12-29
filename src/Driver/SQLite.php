<?php declare(strict_types=1);

/**
 * This Source Code Form is subject to the terms of the Mozilla Public
 * License, v. 2.0. If a copy of the MPL was not distributed with this
 * file, You can obtain one at https://mozilla.org/MPL/2.0/.
 *
 * @author  Korotkov Danila (Jagepard) <jagepard@yandex.ru>
 * @license https://mozilla.org/MPL/2.0/  MPL-2.0
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
