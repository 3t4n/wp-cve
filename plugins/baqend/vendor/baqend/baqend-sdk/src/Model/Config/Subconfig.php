<?php

namespace Baqend\SDK\Model\Config;

/**
 * Class Subconfig created on 22.01.2018.
 *
 * @author  Florian Bücklers
 * @package Baqend\SDK\Model\Config
 */
class Subconfig
{

    /**
     * @var Config
     */
    private $config;

    /**
     * @var string
     */
    private $propertyOffset;

    /**
     * Subconfig constructor.
     *
     * @param Config $config The parsed configuration
     * @param string $propertyOffset The propertyOffset of this config
     */
    public function __construct(Config $config, $propertyOffset) {
        $this->config = $config;
        $this->propertyOffset = $propertyOffset;
    }

    /**
     * @param string $property
     * @param mixed $default
     * @return mixed
     */
    public function get($property, $default = null) {
        return $this->config->get($this->propertyOffset.$property, $default);
    }

    /**
     * @param string $property
     * @param mixed $value
     * @throws \TypeError
     */
    public function set($property, $value) {
        $this->config->set($this->propertyOffset.$property, $value);
    }
}
