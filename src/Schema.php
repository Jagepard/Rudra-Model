<?php

declare (strict_types = 1);

/**
 * @author  : Jagepard <jagepard@yandex.ru">
 * @license https://mit-license.org/ MIT
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
        return Rudra::get("DSN")->prepare($sql)->execute();
    }
}
