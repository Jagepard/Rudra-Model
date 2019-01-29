<?php

declare(strict_types=1);

/**
 * @author    : Korotkov Danila <dankorot@gmail.com>
 * @copyright Copyright (c) 2018, Korotkov Danila
 * @license   http://www.gnu.org/licenses/gpl.html GNU GPLv3.0
 *
 *  phpunit src/tests/ContainerTest --coverage-html src/tests/coverage-html
 */

namespace Rudra\Tests;

use Rudra\Model;
use Rudra\Container;
use Rudra\Interfaces\ContainerInterface;
use PHPUnit\Framework\TestCase as PHPUnit_Framework_TestCase;

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
        Container::app()->set('debugbar', 'DebugBar\StandardDebugBar');

        $this->model = new Model(Container::$app);
    }

    public function testContainer()
    {
        $this->assertInstanceOf(ContainerInterface::class, $this->model()->container());
    }

    /**
     * @return mixed
     */
    protected function model(): Model
    {
        return $this->model;
    }
}
