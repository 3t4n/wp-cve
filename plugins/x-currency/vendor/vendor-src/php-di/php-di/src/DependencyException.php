<?php

declare (strict_types=1);
namespace XCurrency\DI;

use XCurrency\Psr\Container\ContainerExceptionInterface;
/**
 * Exception for the Container.
 */
class DependencyException extends \Exception implements ContainerExceptionInterface
{
}
