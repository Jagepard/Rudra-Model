<?php

declare(strict_types = 1);

/**
 * Date: 17.02.17
 * Time: 13:23
 *
 * @author    : Korotkov Danila <dankorot@gmail.com>
 * @copyright Copyright (c) 2016, Korotkov Danila
 * @license   http://www.gnu.org/licenses/gpl.html GNU GPLv3.0
 *
 *  phpunit src/tests/ContainerTest --coverage-html src/tests/coverage-html
 */


use PHPUnit\Framework\TestCase as PHPUnit_Framework_TestCase;
use Rudra\IContainer;
use Rudra\Container;
use Rudra\Model;


/**
 * Class ModelTest
 */
class ModelTest extends PHPUnit_Framework_TestCase
{

    /**
     * @var Model
     */
    protected $model;

    protected function setUp(): void
    {
        $this->model = new Model(Container::app());
    }

    public function testContainer()
    {
        $this->assertInstanceOf(IContainer::class,$this->model()->container());
    }

    /**
     * @return mixed
     */
    protected function model(): Model
    {
        return $this->model;
    }
}
