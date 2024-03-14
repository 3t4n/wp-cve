<?php

namespace DropshippingXmlFreeVendor\WPDesk\Library\DropshippingXmlCore\Infrastructure\Config\Exception;

use RuntimeException;
/**
 * Class Config, stores all configurations and shares data access methods.
 * @package WPDesk\Library\DropshippingXmlCore\Infrastructure\Config
 */
class ConfigException extends \RuntimeException
{
    public function __construct(string $message)
    {
        parent::__construct(\sprintf('Config error: %1$s!', $message));
    }
}
