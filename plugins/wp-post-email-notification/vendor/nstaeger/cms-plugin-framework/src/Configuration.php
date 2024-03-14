<?php

namespace Nstaeger\CmsPluginFramework;

class Configuration
{
    const CONTROLLER_NAMESPACE = 'controller_namespace';
    const OPTION_PREFIX = 'option_prefix';
    const PLUGIN_DIR = 'plugin_dir';
    const PLUGIN_MAIN_FILE = 'plugin_main_file';
    const PLUGIN_URL = 'plugin_url';
    const REST_PREFIX = 'rest_prefix';
    const VIEW_DIR = 'view_directory';

    /**
     * @var array
     */
    private $config;

    /**
     * Create a new configuration
     *
     * @param array $config
     */
    public function __construct($config)
    {
        $this->config = $config;
        $this->validate();
    }

    /**
     * Get the namespace for controllers
     *
     * @return string
     */
    public function getControllerNamespace()
    {
        return $this->getValue(self::CONTROLLER_NAMESPACE);
    }

    /**
     * Get the main directory of the plugin.
     *
     * @return string
     */
    public function getDirectory()
    {
        return $this->getValue(self::PLUGIN_DIR);
    }

    /**
     * @return string
     */
    public function getMainPluginFile()
    {
        return $this->getValue(self::PLUGIN_MAIN_FILE);
    }

    /**
     * Get the prefix to be used when storing options.
     *
     * @return string
     */
    public function getOptionPrefix()
    {
        return $this->getValue(self::OPTION_PREFIX);
    }

    /**
     * Get the rest prefix url/string
     *
     * @return string
     */
    public function getRestPrefix()
    {
        return $this->getValue(self::REST_PREFIX);
    }

    /**
     * Get the main url of the plugin e.g. for assets.
     *
     * @return string
     */
    public function getUrl()
    {
        return $this->getValue(self::PLUGIN_URL);
    }

    /**
     * Get the directory for views
     *
     * @return string
     */
    public function getViewDirectory()
    {
        return $this->getValue(self::VIEW_DIR, $this->getDirectory() . DIRECTORY_SEPARATOR . 'views');
    }

    /**
     * Get the value of a key within the configuration. If it is not set get the default.
     *
     * @param string $key
     * @param mixed  $default
     * @return mixed
     */
    private function getValue($key, $default = null)
    {
        return isset($this->config[$key])
            ? $this->config[$key]
            : $default;
    }

    /**
     * Validate the configuration
     */
    private function validate()
    {
        if (!isset($this->config[self::CONTROLLER_NAMESPACE])) {
            throw new \InvalidArgumentException("Configuration must contain a value for CONTROLLER_NAMESPACE");
        }
        if (!isset($this->config[self::PLUGIN_DIR])) {
            throw new \InvalidArgumentException("Configuration must contain a value for PLUGIN_DIR");
        }
        if (!isset($this->config[self::PLUGIN_MAIN_FILE])) {
            throw new \InvalidArgumentException("Configuration must contain a value for PLUGIN_MAIN_FILE");
        }
        if (!isset($this->config[self::PLUGIN_URL])) {
            throw new \InvalidArgumentException("Configuration must contain a value for PLUGIN_URL");
        }
    }
}
