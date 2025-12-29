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

class Entity
{
    public static ?string $table = null;

    /**
     * @param  $method
     * @param  array  $parameters
     * @return void
     */
    public static function __callStatic($method, array $parameters = [])
    {
        return self::callMethod($method, $parameters);
    }

    /**
     * @param  $method
     * @param  array  $parameters
     * @return void
     */
    public function __call($method, array $parameters = [])
    {
        return self::callMethod($method, $parameters);
    }

    /**
     * Dynamically calls a method on the corresponding Model, Repository, or parent Repository class.
     * The method first attempts to call the method on the Model class associated with the Entity.
     * If the Model does not exist, it falls back to the Repository class.
     * If the Repository does not exist, it defaults to the parent Repository class.
     * -------------------------
     * Динамически вызывает метод в соответствующем классе Model, Repository или родительском Repository.
     * Метод сначала пытается вызвать метод в классе Model, связанном с Entity.
     * Если Model не существует, используется класс Repository.
     * Если Repository не существует, используется родительский класс Repository.
     *
     * @param  $method
     * @param  array  $parameters
     * @return void
     */
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
