<?php

declare (strict_types = 1);

/**
 * @author  : Jagepard <jagepard@yandex.ru">
 * @license https://mit-license.org/ MIT
 */

namespace Rudra\Model;

class Entity
{
    public static string $table;

    public static function __callStatic($method, array $parameters = [])
    {
        return self::callMethod($method, $parameters);
    }

    public function __call($method, array $parameters = [])
    {
        return self::callMethod($method, $parameters);
    }

    protected static function callMethod($method, array $parameters)
    {
        $className = str_replace("Entity", "Model", get_called_class());

        // If there is no Model, then call the Repository
        if (!class_exists($className)) {
            $className = str_replace("Entity", "Repository", get_called_class() . "Repository");
        }

        // If there is no Repository, then call the parent Repository
        if (!class_exists($className)) {
            $className = Repository::class;
        }

        $newInstance = new $className(static::$table);

        return $newInstance->$method(...$parameters);
    }
}
