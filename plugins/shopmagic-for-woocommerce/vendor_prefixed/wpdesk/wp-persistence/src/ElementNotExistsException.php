<?php

namespace ShopMagicVendor\WPDesk\Persistence;

use ShopMagicVendor\Psr\Container\NotFoundExceptionInterface;
/**
 * @package WPDesk\Persistence
 */
class ElementNotExistsException extends \RuntimeException implements NotFoundExceptionInterface
{
}
