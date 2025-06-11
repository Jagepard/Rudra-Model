<?php

declare (strict_types = 1);

/**
 * @author  : Jagepard <jagepard@yandex.ru">
 * @license https://mit-license.org/ MIT
 */

namespace Rudra\Model;

use Rudra\Container\Traits\FacadeTrait;

/**
 * @deprecated
 * @method static select(?string $fields = '*')
 *
 * @see QB
 */
class QBFacade
{
    use FacadeTrait;
}
