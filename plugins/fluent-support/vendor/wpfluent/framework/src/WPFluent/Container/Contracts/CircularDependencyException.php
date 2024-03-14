<?php

namespace FluentSupport\Framework\Container\Contracts;

use Exception;
use FluentSupport\Framework\Container\Contracts\Psr\ContainerExceptionInterface;

class CircularDependencyException extends Exception implements ContainerExceptionInterface
{
    //
}
