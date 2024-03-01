<?php

declare (strict_types = 1);

/**
 * @author    : Jagepard <jagepard@yandex.ru">
 * @license   https://mit-license.org/ MIT
 */

namespace Rudra\Model;

use Rudra\Exceptions\RudraException;

class Model
{
    public string $table;

    public function __construct(string $table)
    {
        $this->table = $table;
    }

    /**
     * Calls unavailable methods in the Repository namespace
     * -------------------------------------------------------------------------
     * Вызывает недоступные методы в пространстве имен репозитория.
     *
     * @param  $method
     * @param  array  $parameters
     * @return void
     */
    public function __call($method, $parameters = [])
    {      
        $className   = str_replace("Model", "Repository", get_called_class()) . "Repository";
        $newInstance = new $className($this->table);

        if (method_exists($newInstance, $method)) {
            return $newInstance->$method(...$parameters);
        }

        throw new RudraException('method does not exists');
    }
}
