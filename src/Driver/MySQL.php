<?php

declare(strict_types = 1);

/**
 * @author  : Jagepard <jagepard@yandex.ru">
 * @license https://mit-license.org/ MIT
 */

namespace Rudra\Model\Driver;

class MySQL
{
    public function concat(string $fieldName, string $alias, string $orderBy): string
    {
        return ", GROUP_CONCAT($fieldName ORDER BY $orderBy SEPARATOR ';') as $alias  ";  
    }

    public function close(): string
    {
        return ") ENGINE = InnoDB";
    }

    public function integer(string $field, string $default = "", bool $autoincrement = false, string $null = "NOT NULL"): string
    {
        if ($autoincrement) {
            return "`$field` INT $null AUTO_INCREMENT $default";
        }

        return ", `$field` INT $null $default";
    }

    public function string(string $field, string $default = "", string $null = "NOT NULL"): string
    {
        return ", `$field` VARCHAR(255) $null $default";
    }

    public function text(string $field, string $null = "NOT NULL"): string
    {
        return ", `$field` text $null";
    }

    public function created_at(): string
    {
        return ", `created_at` TIMESTAMP NOT NULL DEFAULT CURRENT_TIMESTAMP";
    }

    public function updated_at(): string
    {
        return ", `updated_at` TIMESTAMP NOT NULL DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP";
    }

    public function pk($field): string
    {
        return ", PRIMARY KEY (`$field`)";
    }
}
