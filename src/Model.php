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

    /**
     * @param  string|null $table
     */
    public function __construct(?string $table = null)
    {
        $this->table = $table;
    }

    /**
     * Handles calls to undefined methods by delegating them to the corresponding Repository class.
     * The method dynamically resolves the Repository class associated with the Model.
     * If the Repository does not exist, it falls back to the parent Repository class.
     * If the method exists in the resolved Repository, it is invoked with the provided parameters.
     * Otherwise, an exception is thrown.
     * -------------------------
     * Обрабатывает вызовы неопределённых методов, делегируя их соответствующему классу Repository.
     * Метод динамически определяет класс Repository, связанный с Model.
     * Если Repository не существует, используется родительский класс Repository.
     * Если метод существует в разрешённом Repository, он вызывается с предоставленными параметрами.
     * В противном случае выбрасывается исключение.
     *
     * @param  $method
     * @param  array  $parameters
     * @return void
     * @throws RudraException 
     */
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
