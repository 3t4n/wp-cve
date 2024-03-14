<?php

namespace DropshippingXmlFreeVendor\WPDesk\Library\DropshippingXmlCore\Infrastructure\DAO\Exception;

use RuntimeException;
/**
 * Class NotSavedException, exception if entity is not saved.
 * @package WPDesk\Library\DropshippingXmlCore\Infrastructure\DAO\Exception
 */
class NotSavedException extends \RuntimeException
{
    public function __construct(string $message)
    {
        parent::__construct(\sprintf('Not saved: %1$s!', $message));
    }
}
