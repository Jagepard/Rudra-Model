<?php declare(strict_types=1);

/**
 * This Source Code Form is subject to the terms of the Mozilla Public
 * License, v. 2.0. If a copy of the MPL was not distributed with this
 * file, You can obtain one at https://mozilla.org/MPL/2.0/.
 *
 * @author  Korotkov Danila (JageDord) <jagepard@yandex.ru>
 * @license https://mozilla.org/MPL/2.0/  MPL-2.0
 */

namespace Rudra\Model\Drivers;

use Rudra\Model\Interfaces\SqlDialectInterface;

class PgSQL implements SqlDialectInterface
{
    #[\Override]
    public function groupConcat(string $fieldName, string $alias, ?string $orderBy, bool $distinct = true): string
    {
        $distinctStr = $distinct ? 'DISTINCT ' : '';
        $orderField  = $distinct ? $fieldName : ($orderBy ?? $fieldName);
        return ", array_to_string(array_agg({$distinctStr}$fieldName ORDER BY $orderField), ';') $alias";
    }

    #[\Override]
    public function close(): string
    {
        return ");";
    }

    #[\Override]
    public function integer(string $field, string $default = "", bool $autoincrement = false, string $null = "NOT NULL"): string
    {
        if ($autoincrement) {
            return "$field SERIAL PRIMARY KEY";
        }

        return ", $field INTEGER $null $default";
    }

    #[\Override]
    public function string(string $field, string $default = "", string $null = "NOT NULL"): string
    {
        return ", $field VARCHAR(255) $null $default";
    }

    #[\Override]
    public function text(string $field, string $null = "NOT NULL"): string
    {
        return ", $field TEXT $null";
    }

    #[\Override]
    public function createdAt(): string
    {
        return ", created_at TIMESTAMP without time zone";
    }

    #[\Override]
    public function updatedAt(): string
    {
        return ", updated_at TIMESTAMP without time zone";
    }

    #[\Override]
    public function primaryKey(string $field): string
    {
        return "";
    }
}
