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

    /**
     * Creates a new Schema instance and defines the table structure using a callback function.
     * The callback function is used to configure the table schema via the Query Builder.
     * -------------------------
     * Создает новый экземпляр Schema и определяет структуру таблицы с помощью callback-функции.
     * Callback-функция используется для настройки схемы таблицы через Query Builder.
     *
     * @param  string $table
     * @param  callable $callback
     * @return self
     */
    public static function create(string $table, callable $callback): self
    {
        $qb = Entity::qb()->create($table);
        $callback($qb);
        $self = new self();
        $self->qb = $qb;
        return $self;
    }

    /**
     * Executes the schema creation by preparing and running the SQL query.
     * The SQL query is generated using the Query Builder and executed on the database connection.
     * -------------------------
     * Выполняет создание схемы путем подготовки и выполнения SQL-запроса.
     * SQL-запрос генерируется с использованием Query Builder и выполняется на подключении к базе данных.
     *
     * @return bool
     */
    public function execute(): bool
    {
        $sql = $this->qb->close()->get();
        return Rudra::get("connection")->prepare($sql)->execute();
    }
}
