<?php

/*
 * This file is part of the Symfony package.
 *
 * (c) Fabien Potencier <fabien@symfony.com>
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 *
 * Modified by Atanas Angelov on 13-January-2024 using Strauss.
 * @see https://github.com/BrianHenryIE/strauss
 */

namespace CoffeeCode\Symfony\Contracts\Service\Test;

use PHPUnit\Framework\TestCase;
use CoffeeCode\Psr\Container\ContainerInterface;
use CoffeeCode\Symfony\Contracts\Service\ServiceLocatorTrait;

class ServiceLocatorTest extends TestCase
{
    public function getServiceLocator(array $factories)
    {
        return new class($factories) implements ContainerInterface {
            use ServiceLocatorTrait;
        };
    }

    public function testHas()
    {
        $locator = $this->getServiceLocator([
            'foo' => function () { return 'bar'; },
            'bar' => function () { return 'baz'; },
            function () { return 'dummy'; },
        ]);

        $this->assertTrue($locator->has('foo'));
        $this->assertTrue($locator->has('bar'));
        $this->assertFalse($locator->has('dummy'));
    }

    public function testGet()
    {
        $locator = $this->getServiceLocator([
            'foo' => function () { return 'bar'; },
            'bar' => function () { return 'baz'; },
        ]);

        $this->assertSame('bar', $locator->get('foo'));
        $this->assertSame('baz', $locator->get('bar'));
    }

    public function testGetDoesNotMemoize()
    {
        $i = 0;
        $locator = $this->getServiceLocator([
            'foo' => function () use (&$i) {
                ++$i;

                return 'bar';
            },
        ]);

        $this->assertSame('bar', $locator->get('foo'));
        $this->assertSame('bar', $locator->get('foo'));
        $this->assertSame(2, $i);
    }

    /**
     * @expectedException        \CoffeeCode\Psr\Container\NotFoundExceptionInterface
     * @expectedExceptionMessage The service "foo" has a dependency on a non-existent service "bar". This locator only knows about the "foo" service.
     */
    public function testThrowsOnUndefinedInternalService()
    {
        $locator = $this->getServiceLocator([
            'foo' => function () use (&$locator) { return $locator->get('bar'); },
        ]);

        $locator->get('foo');
    }

    /**
     * @expectedException        \CoffeeCode\Psr\Container\ContainerExceptionInterface
     * @expectedExceptionMessage Circular reference detected for service "bar", path: "bar -> baz -> bar".
     */
    public function testThrowsOnCircularReference()
    {
        $locator = $this->getServiceLocator([
            'foo' => function () use (&$locator) { return $locator->get('bar'); },
            'bar' => function () use (&$locator) { return $locator->get('baz'); },
            'baz' => function () use (&$locator) { return $locator->get('bar'); },
        ]);

        $locator->get('foo');
    }
}
