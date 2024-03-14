<?php

namespace DropshippingXmlFreeVendor\WPDesk\Library\DropshippingXmlCore\Infrastructure\Config\Abstraction;

/**
 * Class AbstractSingleConfig, abstraction layer for single configuration. It allows to store of individual specific configurations in ConfigInterface.
 * @package WPDesk\Library\DropshippingXmlCore\Infrastructure\Config
 */
abstract class AbstractSingleConfig
{
    /**
     * @var ConfigInterface
     */
    private $config;
    /**
     * @return void
     */
    public function set_config(\DropshippingXmlFreeVendor\WPDesk\Library\DropshippingXmlCore\Infrastructure\Config\Abstraction\ConfigInterface $config)
    {
        $this->config = $config;
    }
    public function get_config() : \DropshippingXmlFreeVendor\WPDesk\Library\DropshippingXmlCore\Infrastructure\Config\Abstraction\ConfigInterface
    {
        return $this->config;
    }
    public abstract function get() : array;
    public abstract function get_id() : string;
}
