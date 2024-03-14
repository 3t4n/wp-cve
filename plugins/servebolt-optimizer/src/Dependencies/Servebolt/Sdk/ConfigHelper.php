<?php

namespace Servebolt\Optimizer\Dependencies\Servebolt\Sdk;

/**
 * Class ConfigHelper
 * @package Servebolt\Optimizer\Dependencies\Servebolt\Sdk
 */
class ConfigHelper
{

    /**
     * An array containing the configuration items.
     *
     * @var array
     */
    private $configArray = [];

    /**
     * Set the configuration using an associative array.
     *
     * @param array $array
     * @param false $append
     */
    public function setWithArray(array $array, $append = false) : void
    {
        if ($append) {
            $this->configArray = $this->configArray + $array;
        } else {
            $this->configArray = $array;
        }
    }

    /**
     * Set a configuration item.
     *
     * @param string $key
     * @param mixed $value
     */
    public function set(string $key, $value) : void
    {
        $this->configArray[$key] = $value;
    }

    /**
     * Unset a configuration item.
     *
     * @param string $key
     */
    public function unset(string $key) : void
    {
        if (array_key_exists($key, $this->configArray)) {
            unset($this->configArray[$key]);
        }
    }

    /**
     * Get a configuration item.
     *
     * @param string $key
     * @param null|mixed $default
     * @return mixed|null
     */
    public function get(string $key, $default = null)
    {
        if (array_key_exists($key, $this->configArray)) {
            return $this->configArray[$key];
        }
        return $default;
    }
}
