<?php

declare (strict_types=1);
namespace WpifyWooDeps\DI;

use WpifyWooDeps\Psr\Container\ContainerExceptionInterface;
/**
 * Exception for the Container.
 */
class DependencyException extends \Exception implements ContainerExceptionInterface
{
}
