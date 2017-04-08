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
     * @param \Rudra\ContainerInterface $container
     */
    public function __construct(ContainerInterface $container)
    {
        $this->container = $container;
    }

    /**
     * @return mixed
     */
    public function container(): ContainerInterface
    {
        return $this->container;
    }
}
