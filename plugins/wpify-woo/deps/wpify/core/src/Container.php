<?php

namespace WpifyWooDeps\Wpify\Core;

use WpifyWooDeps\DI\ContainerBuilder;
use Exception;
use WpifyWooDeps\Wpify\Core\Abstracts\AbstractComponent;
/**
 * Class Container
 * @package Wpify\Core
 */
class Container extends AbstractComponent
{
    /**
     * Instance of self
     * @var self
     */
    private static $instance;
    /**
     * Auto init the container
     * @var bool
     */
    protected $auto_init = \false;
    /**
     * List of containers
     * @var array
     */
    private $containers = array();
    /**
     * Returns the current instance
     * @return self
     */
    public static function getInstance()
    {
        if (null === self::$instance) {
            self::$instance = new self();
        }
        return self::$instance;
    }
    /**
     * Adds a container
     *
     * @param string $name Container name.
     * @param array $rules Rules.
     *
     * @return mixed
     * @throws Exception Container exists.
     */
    public function add_container($name, $rules = array())
    {
        if (isset($this->containers[$name])) {
            throw new Exception('Container with this name already exists');
        }
        $builder = new ContainerBuilder();
        $builder->addDefinitions($rules);
        $this->containers[$name] = $builder->build();
        return $this->containers[$name];
    }
    /**
     * Get a single \DI\Container container
     *
     * @param $name
     *
     * @return bool|\DI\Container
     */
    public function get_container($name)
    {
        return empty($this->containers[$name]) ? null : $this->containers[$name];
    }
    /**
     * Get all registered containers
     * @return mixed
     */
    public function get_containers()
    {
        return $this->containers;
    }
}
