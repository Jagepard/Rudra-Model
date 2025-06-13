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

    public static function create(string $table, callable $callback): self
    {
        $qb = Entity::qb()->create($table);
        $callback($qb);
        $self = new self();
        $self->qb = $qb;
        return $self;
    }

    public function execute(): bool
    {
        $sql = $this->qb->close()->get();
        return Rudra::get("DSN")->prepare($sql)->execute();
    }
}
