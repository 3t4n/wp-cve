<?php

namespace FluentSupport\Framework\Container;

use Exception;
use FluentSupport\Framework\Container\Contracts\Psr\NotFoundExceptionInterface;

class EntryNotFoundException extends Exception implements NotFoundExceptionInterface
{
    //
}
