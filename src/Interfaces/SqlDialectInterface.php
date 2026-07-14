<?php

/**
 * This Source Code Form is subject to the terms of the Mozilla Public
 * License, v. 2.0. If a copy of the MPL was not distributed with this
 * file, You can obtain one at https://mozilla.org/MPL/2.0/.
 *
 * @author  Korotkov Danila (Jagepard) <jagepard@yandex.ru>
 * @license https://mozilla.org/MPL/2.0/  MPL-2.0
 */

namespace Rudra\Model\Interfaces;

interface SqlDialectInterface
{
    public function groupConcat(string $fieldName, string $alias, ?string $orderBy): string;
    public function close(): string;
    public function integer(string $field, string $default = "", bool $autoincrement = false, string $null = "NOT NULL"): string;
    public function string(string $field, string $default = "", string $null = "NOT NULL"): string;
    public function text(string $field, string $null = "NOT NULL"): string;
    public function createdAt(): string;
    public function updatedAt(): string;
    public function primaryKey(string $field): string;
}
