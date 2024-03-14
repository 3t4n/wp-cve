<?php

namespace FRFreeVendor\WPDesk\Persistence;

use FRFreeVendor\Psr\Container\NotFoundExceptionInterface;
/**
 * @package WPDesk\Persistence
 */
class ElementNotExistsException extends \RuntimeException implements \FRFreeVendor\Psr\Container\NotFoundExceptionInterface
{
}
