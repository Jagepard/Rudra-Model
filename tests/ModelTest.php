<?php

declare(strict_types=1);

/**
 * @author    : Jagepard <jagepard@yandex.ru">
 * @copyright Copyright (c) 2019, Jagepard
 * @license   https://mit-license.org/ MIT
 *
 *  phpunit src/tests/ContainerTest --coverage-html src/tests/coverage-html
 */

namespace Rudra\Tests;

use Rudra\Model;
use Rudra\Container;
use Rudra\Interfaces\ContainerInterface;
use PHPUnit\Framework\TestCase as PHPUnit_Framework_TestCase;

class ModelTest extends PHPUnit_Framework_TestCase
{
    protected function setUp(): void
    {
        rudra()->set('debugbar', 'DebugBar\StandardDebugBar');
    }

    public function testContainer()
    {
        $this->assertInstanceOf(ContainerInterface::class, (new Model(rudra()))->container());
    }
}
