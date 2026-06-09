<?php declare(strict_types=1);

/**
 * This Source Code Form is subject to the terms of the Mozilla Public
 * License, v. 2.0. If a copy of the MPL was not distributed with this
 * file, You can obtain one at https://mozilla.org/MPL/2.0/.
 *
 * @author  Korotkov Danila (JageDord) <jagepard@yandex.ru>
 * @license https://mozilla.org/MPL/2.0/  MPL-2.0
 */

namespace Rudra\Model\Driver;

class PgSQL
{
    public function concat(string $fieldName, string $alias, ?string $orderBy): string
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
