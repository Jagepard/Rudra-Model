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
        $className  = str_replace("Entity", "Model", get_called_class());

        /**
         * If there is no Model, then call the Repository
         * ----------------------------------------------
         * Если нет Модели, то вызываем Репозиторий
         */
        if (!class_exists($className)) {
            $className  = str_replace("Entity", "Repository", get_called_class() . "Repository");
        }

        $newInstance = new $className(static::$table);

        return $newInstance->$method(...$parameters);
    }
}
