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

class MySQL
{
    /**
     * @param  string $fieldName
     * @param  string $alias
     * @param  string $orderBy
     * @return string
     */
    public function concat(string $fieldName, string $alias, string $orderBy): string
    {
        return ", GROUP_CONCAT($fieldName ORDER BY $orderBy SEPARATOR ';') as $alias  ";  
    }

    /**
     * @return string
     */
    public function close(): string
    {
        return ") ENGINE = InnoDB";
    }

    /**
     * @param  string  $field
     * @param  string  $default
     * @param  boolean $autoincrement
     * @param  string  $null
     * @return string
     */
    public function integer(string $field, string $default = "", bool $autoincrement = false, string $null = "NOT NULL"): string
    {
        if ($autoincrement) {
            return "`$field` INT $null AUTO_INCREMENT $default";
        }

        return ", `$field` INT $null $default";
    }

    /**
     * @param  string $field
     * @param  string $default
     * @param  string $null
     * @return string
     */
    public function string(string $field, string $default = "", string $null = "NOT NULL"): string
    {
        return ", `$field` VARCHAR(255) $null $default";
    }

    /**
     * @param  string $field
     * @param  string $null
     * @return string
     */
    public function text(string $field, string $null = "NOT NULL"): string
    {
        return ", `$field` text $null";
    }

    /**
     * @return string
     */
    public function created_at(): string
    {
        return ", `created_at` TIMESTAMP NOT NULL DEFAULT CURRENT_TIMESTAMP";
    }

    /**
     * @return string
     */
    public function updated_at(): string
    {
        return ", `updated_at` TIMESTAMP NOT NULL DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP";
    }

    /**
     * @param $field
     * @return string
     */
    public function pk($field): string
    {
        return ", PRIMARY KEY (`$field`)";
    }
}
