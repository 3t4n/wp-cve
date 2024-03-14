<?php

namespace DropshippingXmlFreeVendor\WPDesk\Library\DropshippingXmlCore\Infrastructure\DAO\Exception;

use RuntimeException;
/**
 * Class NotFoundException, exception if entity is not found.
 * @package WPDesk\Library\DropshippingXmlCore\Infrastructure\DAO\Exception
 */
class NotFoundException extends \RuntimeException
{
    public function __construct(string $message)
    {
        parent::__construct(\sprintf('Not found: %1$s!', $message));
    }
}
