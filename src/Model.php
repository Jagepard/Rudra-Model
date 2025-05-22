<?php

declare (strict_types = 1);

/**
 * @author  : Jagepard <jagepard@yandex.ru">
 * @license https://mit-license.org/ MIT
 */

namespace Rudra\Model;

use Rudra\Exceptions\RudraException;

class Model
{
    public string $table;
    public string $directory;

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
     * @param array $parameters
     * @return void
     * @throws RudraException
     */
    public function __call($method, array $parameters = [])
    {      
        $className = str_replace("Model", "Repository", get_called_class()) . "Repository";

        /**
         * If there is no Repository, then call the parent Repository
         * ----------------------------------------------------------
         * Если нет Репозитория, то вызываем родительский Репозиторий
         */
        if (!class_exists($className)) {
            $className = Repository::class;
        }

        $newInstance = new $className($this->table);

        if (method_exists($newInstance, $method)) {
            return $newInstance->$method(...$parameters);
        }

        throw new RudraException(sprintf('Method %s does not exists', $method));
    }
}
