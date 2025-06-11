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
    public ?string $table;

    public function __construct(?string $table = null)
    {
        $this->table = $table;
    }

    public function __call($method, array $parameters = [])
    {      
        $className = str_replace("Model", "Repository", get_called_class()) . "Repository";

        // If there is no Repository, then call the parent Repository
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
