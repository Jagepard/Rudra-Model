<?php

namespace Rudra\Model;

use Rudra\Exceptions\RudraException;

class Entity
{
    /**
     * Calls unavailable methods in a static context in the Model namespace
     * -------------------------------------------------------------------------
     * Вызывает недоступные методы в статическом контексте в пространстве имен модели.
     *
     * @param  $method
     * @param  array  $parameters
     * @return void
     */
    public static function __callStatic($method, $parameters = [])
    {       
        $className   = str_replace("Entity", "Model", get_called_class());
        $newInstance = new $className(static::$table);

        return $newInstance->$method(...$parameters);
    }
}
