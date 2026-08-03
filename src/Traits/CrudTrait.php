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

use Rudra\Pagination;

trait CrudTrait
{
    public function getAllPerPage(Pagination $pagination, ?string $fields = null): array
    {
        $fields  = $fields ?? implode(',', $this->getFields());
        $qString = $this->qb()
            ->select($fields)
            ->from($this->table)
            ->orderBy("id DESC")
            ->limit($pagination->getPerPage())
            ->offset($pagination->getOffset())
            ->get();

        return $this->qBuilder($qString);
    }

    public function getAll(string $sort = 'id ASC', ?string $fields = null): array
    {
        $fields  = $fields ?? implode(',', $this->getFields());
        $qString = $this->qb()
            ->select($fields)
            ->from($this->table)
            ->orderBy($sort)
            ->get();

        return $this->qBuilder($qString);
    }

    public function numRows(): int
    {
        $qString = $this->qb()
            ->select('COUNT(*) as count')
            ->from($this->table)
            ->get();

        $result = $this->qBuilder($qString);
        return (int)($result[0]['count'] ?? 0);
    }

    /**
     * Finds a single record by a specified field and value.
     * 
     * WARNING: The $field parameter is inserted directly into the SQL query. 
     * It is the developer's responsibility to ensure the field name is valid 
     * and sanitized to prevent SQL injection. Do not pass raw user input here.
     */
    public function findBy(string $field, mixed $value): array|false
    {
        $qString = $this->qb()
            ->select('*')
            ->from($this->table)
            ->where("{$field} = :val")
            ->get();

        $result = $this->qBuilder($qString, [':val' => $value]);
        return $result ? $result[0] : false;
    }

    public function lastInsertId(): string
    {
        return $this->connection->lastInsertId();
    }

    /**
     * Searches for records in the database based on a search term and column.
     * 
     * WARNING: The $column parameter is inserted directly into the SQL query. 
     * Ensure it is sanitized to prevent SQL injection.
     * Results are ordered by ID in descending order and limited to 10 records.
     */
    public function search(string $search, string $column, ?string $fields = null): array
    {
        $fields = $fields ?: implode(',', $this->getFields());
        $driver = $this->connection->getAttribute(\PDO::ATTR_DRIVER_NAME);

        // Form an expression for casting to string
        $searchExpr = match ($driver) {
            'pgsql'  => "$column::TEXT",          // PostgreSQL
            'mysql'  => "CAST($column AS CHAR)",  // MySQL
            'sqlite' => "CAST($column AS TEXT)",  // SQLite
            default  => "$column",                // fallback (If suddenly another DBMS)
        };

        $qString = $this->qb()
            ->select($fields)
            ->from($this->table)
            ->where("{$searchExpr} LIKE :search")
            ->orderBy('id DESC')
            ->limit(10)
            ->get();

        return $this->qBuilder($qString, [':search' => "%{$search}%"]);
    }

    public function find(int|string $id): array|false
    {
        $qString = $this->qb()
            ->select('*')
            ->from($this->table)
            ->where('id = :id')
            ->get();

        $result = $this->qBuilder($qString, [':id' => $id]);
        return $result ? $result[0] : false;
    }

    public function update(array $fields): void
    {
        $id = $fields['id'];
        unset($fields['id']);
        $stmtString   = $this->updateStmtString($fields);
        $fields['id'] = $id;

        $qString = $this->qb()
            ->update($this->table, $stmtString)
            ->where('id = :id')
            ->get();

        $this->qBuilder($qString, $fields);
        $this->clearCache();
    }

    public function create(array $fields): void
    {
        $stmtString = $this->createStmtString($fields);

        $qString = $this->qb()
            ->insert($this->table, $stmtString[0])
            ->values($stmtString[1])
            ->get();

        $this->qBuilder($qString, $fields);
        $this->clearCache();
    }

    public function delete(int|string $id): void
    {
        $qString = $this->qb()
            ->delete($this->table)
            ->where('id = :id')
            ->get();

        $this->qBuilder($qString, [':id' => $id]);
        $this->clearCache();
    }

    /**
     * Generates a string of fields and placeholders for an SQL UPDATE statement.
     * The method takes an array of fields and constructs a comma-separated list of "key=:key" pairs.
     * This string can be directly used in the SET clause of an SQL UPDATE query.
     */
    protected static function updateStmtString(array $fields): string
    {
        $stmtFields = [];

        foreach (array_keys($fields) as $key) {
            $stmtFields[] = "{$key}=:{$key}";
        }

        return implode(",", $stmtFields);
    }

    /**
     * Generates two strings for an SQL INSERT statement: one for column names and one for placeholders.
     * The method takes an array of fields and constructs two comma-separated lists:
     * - A list of column names.
     * - A list of placeholders (prefixed with colons) for parameter binding.
     * These strings can be directly used in the SQL INSERT query.
     */
    protected static function createStmtString(array $fields): array
    {
        $insert  = [];
        $execute = [];

        foreach (array_keys($fields) as $key) {
            $insert[]  = $key;
            $execute[] = ":{$key}";
        }

        return [implode(",", $insert), implode(",", $execute)];
    }
}
