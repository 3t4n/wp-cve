<?php

namespace OctolizeShippingNoticesVendor\WPDesk\Persistence;

use OctolizeShippingNoticesVendor\Psr\Container\NotFoundExceptionInterface;
/**
 * @package WPDesk\Persistence
 */
class ElementNotExistsException extends \RuntimeException implements \OctolizeShippingNoticesVendor\Psr\Container\NotFoundExceptionInterface
{
}
