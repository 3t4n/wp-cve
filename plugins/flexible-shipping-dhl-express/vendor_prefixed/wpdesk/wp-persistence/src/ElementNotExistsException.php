<?php

namespace DhlVendor\WPDesk\Persistence;

use DhlVendor\Psr\Container\NotFoundExceptionInterface;
/**
 * @package WPDesk\Persistence
 */
class ElementNotExistsException extends \RuntimeException implements \DhlVendor\Psr\Container\NotFoundExceptionInterface
{
}
