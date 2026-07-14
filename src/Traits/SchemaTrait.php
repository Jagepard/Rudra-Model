<?php declare(strict_types=1);

/**
 * This Source Code Form is subject to the terms of the Mozilla Public
 * License, v. 2.0. If a copy of the MPL was not distributed with this
 * file, You can obtain one at https://mozilla.org/MPL/2.0/.
 *
 * @author  Korotkov Danila (Jagepard) <jagepard@yandex.ru>
 * @license https://mozilla.org/MPL/2.0/  MPL-2.0
 */

namespace Rudra\Model\Traits;

trait SchemaTrait
{
    /**
     * Retrieves the column information for the current table based on the database driver.
     * The method executes a query specific to the database type (MySQL, PostgreSQL, or SQLite) 
     * to fetch the column details of the table.
     *
     * @throws \PDOException
     */
    public function getColumns(): array
    {
        $table = $this->table;

        if ($this->connection->getAttribute(\PDO::ATTR_DRIVER_NAME) === "mysql") {
            $query = $this->connection->query("SHOW COLUMNS FROM {$table}");
        } elseif ($this->connection->getAttribute(\PDO::ATTR_DRIVER_NAME) === "pgsql") {
            $query = $this->connection->query("SELECT column_name, data_type
                FROM information_schema.columns 
                WHERE table_name = '{$table}'"
            );
        } elseif ($this->connection->getAttribute(\PDO::ATTR_DRIVER_NAME) === "sqlite") {
                $query = $this->connection->query("PRAGMA table_info('{$table}')"
            );
        }

        return $query->fetchAll(\PDO::FETCH_ASSOC);
    }

    /**
     * Retrieves the list of fields (columns) for the current table.
     * If no specific fields are provided, the method fetches all column names based on the database driver.
     * Otherwise, it splits the provided comma-separated string of fields into an array.
     */
    public function getFields(?string $fields = null): array
    {
        if ($fields !== null) {
            // Split by comma and remove spaces around each field
            return array_map('trim', explode(',', $fields));
        }

        // Initialize as an empty array — protection against null
        $fieldList = [];

        if ($this->connection->getAttribute(\PDO::ATTR_DRIVER_NAME) === "mysql") {
            foreach ($this->getColumns() as $column) {
                $fieldList[] = $column['Field'];
            }
        } elseif ($this->connection->getAttribute(\PDO::ATTR_DRIVER_NAME) === "pgsql") {
            foreach ($this->getColumns() as $column) {
                $fieldList[] = $column['column_name'];
            }
        } elseif ($this->connection->getAttribute(\PDO::ATTR_DRIVER_NAME) === "sqlite") {
            foreach ($this->getColumns() as $column) {
                $fieldList[] = $column['name'];
            }
        }

        return $fieldList;
    }
}
