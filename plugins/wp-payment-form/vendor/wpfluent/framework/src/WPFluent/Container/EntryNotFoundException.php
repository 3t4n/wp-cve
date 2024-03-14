<?php

namespace WPPayForm\Framework\Container;

use Exception;
use WPPayForm\Framework\Container\Contracts\Psr\NotFoundExceptionInterface;

class EntryNotFoundException extends Exception implements NotFoundExceptionInterface
{
    //
}
