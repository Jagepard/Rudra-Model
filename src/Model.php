<?php

declare(strict_types = 1);

namespace Rudra;

/**
 * Class Model
 *
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
