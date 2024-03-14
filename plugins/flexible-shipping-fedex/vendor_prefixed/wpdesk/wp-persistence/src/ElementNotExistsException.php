<?php

namespace FedExVendor\WPDesk\Persistence;

use FedExVendor\Psr\Container\NotFoundExceptionInterface;
/**
 * @package WPDesk\Persistence
 */
class ElementNotExistsException extends \RuntimeException implements \FedExVendor\Psr\Container\NotFoundExceptionInterface
{
}
