<?php

namespace DropshippingXmlFreeVendor\WPDesk\Library\DropshippingXmlCore\Infrastructure\Config\Abstraction;

use DropshippingXmlFreeVendor\WPDesk\Library\DropshippingXmlCore\Infrastructure\Data\Abstraction\DataTypeInterface;
/**
 * Interface ConfigInterface, abstraction layer for configuration managment class.
 * @package WPDesk\Library\DropshippingXmlCore\Infrastructure\Config
 */
interface ConfigInterface
{
    public function get_param(string $key) : \DropshippingXmlFreeVendor\WPDesk\Library\DropshippingXmlCore\Infrastructure\Data\Abstraction\DataTypeInterface;
    /**
     * @param array $config
     *
     * @return void
     */
    public function register_config(array $config);
}
