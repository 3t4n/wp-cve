<?php

namespace WPDeskFIVendor\WPDesk\Persistence;

use WPDeskFIVendor\Psr\Container\NotFoundExceptionInterface;
/**
 * @package WPDesk\Persistence
 */
class ElementNotExistsException extends \RuntimeException implements \WPDeskFIVendor\Psr\Container\NotFoundExceptionInterface
{
}
