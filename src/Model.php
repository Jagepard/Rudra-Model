<?php

declare(strict_types=1);

/**
 * @author    : Korotkov Danila <dankorot@gmail.com>
 * @copyright Copyright (c) 2018, Korotkov Danila
 * @license   http://www.gnu.org/licenses/gpl.html GNU GPLv3.0
 */

namespace Rudra;

use Rudra\Interfaces\ContainerInterface;
use Rudra\ExternalTraits\ContainerTrait;
use Rudra\ExternalTraits\ControllerTrait;
use Rudra\ExternalTraits\SetContainerTrait;

/**
 * Class Model
 * @package Rudra
 */
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
