<?php

namespace DropshippingXmlFreeVendor\WPDesk\Persistence;

use Psr\Container\NotFoundExceptionInterface;
/**
 * @package WPDesk\Persistence
 */
class ElementNotExistsException extends \RuntimeException implements \Psr\Container\NotFoundExceptionInterface
{
}
