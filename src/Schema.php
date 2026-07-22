<?php declare(strict_types=1);

/**
 * This Source Code Form is subject to the terms of the Mozilla Public
 * License, v. 2.0. If a copy of the MPL was not distributed with this
 * file, You can obtain one at https://mozilla.org/MPL/2.0/.
 *
 * @author  Korotkov Danila (Jagepard) <jagepard@yandex.ru>
 * @license https://mozilla.org/MPL/2.0/  MPL-2.0
 */

namespace Rudra\Model;

use Rudra\Container\Facades\Rudra;

class Schema
{
    private QB $qb;
    private string $table;

    /**
     * Creates a new Schema instance and defines the table structure using a callback function.
     */
    public static function create(string $table, callable $callback): self
    {
        $qb = Entity::qb()->create($table);
        $callback($qb);
        $self = new self();
        $self->qb = $qb;
        $self->table = $table;
        return $self;
    }

    /**
     * Checks if a table exists in the database.
     */
    public static function hasTable(string $table): bool
    {
        try {
            Rudra::get("connection")->query("SELECT 1 FROM `$table` LIMIT 1");
            return true;
        } catch (\PDOException $e) {
            return false;
        }
    }

    /**
     * Executes the schema creation.
     * Throws an exception if the table already exists to prevent silent masking of DB state.
     */
    public function execute(): bool
    {
        $connection = Rudra::get("connection");

        if (isset($this->table) && self::hasTable($this->table)) {
            throw new \RuntimeException("Table '{$this->table}' already exists in DB");
        }

        $sql = $this->qb->close()->get();
        return $connection->prepare($sql)->execute();
    }
}
