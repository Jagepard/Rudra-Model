<?php

declare(strict_types=1);

/**
 * @author    : Jagepard <jagepard@yandex.ru">
 * @copyright Copyright (c) 2019, Jagepard
 * @license   https://mit-license.org/ MIT
 */

namespace Rudra;

use Rudra\Interfaces\ContainerInterface;
use Rudra\ExternalTraits\ContainerTrait;
use Rudra\ExternalTraits\ControllerTrait;
use Rudra\ExternalTraits\SetContainerTrait;

class Model
{
    use ContainerTrait;
    use ControllerTrait;
    use SetContainerTrait {
        SetContainerTrait::__construct as public __setContainerTraitConstruct;
    }

    public function __construct(ContainerInterface $container)
    {
        $this->__setContainerTraitConstruct($container);
        $this->container()->get('debugbar')['time']->startMeasure('Model', 'Model');
    }
}
