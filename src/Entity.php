<?php

namespace Rudra\Model;

class Entity
{
    /**
     * Calls unavailable methods in a static context in the Model namespace
     * -------------------------------------------------------------------------
     * Вызывает недоступные методы в статическом контексте в пространстве имен модели.
     *
     * @param  $method
     * @param array $parameters
     * @return void
     */
    public static function __callStatic($method, array $parameters = [])
    {
        return self::callMethod($method, $parameters);
    }

    /**
     * Calls unavailable methods in the Model namespace
     * -------------------------------------------------------
     * Вызывает недоступные методы в пространстве имен модели.
     *
     * @param  $method
     * @param array $parameters
     * @return void
     */
    public function __call($method, array $parameters = [])
    {
        return self::callMethod($method, $parameters);
    }

    /**
     * @param $method
     * @param array $parameters
     * @return mixed
     */
    protected static function callMethod($method, array $parameters)
    {
        $className = str_replace("Entity", "Repository", get_called_class() . "Repository");

        /**
         * If there is no Repository, then call the parent Repository
         * ----------------------------------------------------------
         * Если нет Репозитория, то вызываем родительский Репозиторий
         */
        if (!class_exists($className)) {
            $className = Repository::class;
        }

        $newInstance = new $className(static::$table, static::$directory);

        return $newInstance->$method(...$parameters);
    }
}
