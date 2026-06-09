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
    public function concat(string $fieldName, string $alias, ?string $orderBy): string
    {
        return ", GROUP_CONCAT($fieldName,';') $alias  ";  
    }

    public function close(): string
    {
        return ")";
    }

    public function integer(string $field, string $default = "", bool $pk = false, string $null = "NOT NULL"): string
    {
        if ($pk) {
            return "$field INTEGER PRIMARY KEY";
        }

        return ", $field INTEGER $null $default";
    }

    public function string(string $field, string $default = "", string $null = "NOT NULL"): string
    {
        return ", $field TEXT $null $default";
    }

    public function text(string $field, string $null = "NOT NULL"): string
    {
        return ", $field TEXT $null";
    }

    public function created_at(): string
    {
        return ", created_at TEXT DEFAULT CURRENT_TIMESTAMP";
    }

    public function updated_at(): string
    {
        return ", updated_at TEXT DEFAULT CURRENT_TIMESTAMP";
    }

    public function pk(string $field): string
    {
        return "";
    }
}
