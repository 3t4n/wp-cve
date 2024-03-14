<?php

namespace WPPayForm\Framework\Container\Contracts;

use Exception;
use WPPayForm\Framework\Container\Contracts\Psr\ContainerExceptionInterface;

class CircularDependencyException extends Exception implements ContainerExceptionInterface
{
    //
}
