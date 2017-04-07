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

    use ContainerTrait, DataTrait;

    /**
     * @var
     */
    protected $container;

    /**
     * Model constructor.
     *
     * @param \Rudra\IContainer $container
     */
    public function __construct(IContainer $container)
    {
        $this->container = $container;
    }

    /**
     * @return mixed
     */
    public function container()
    {
        return $this->container;
    }
}
