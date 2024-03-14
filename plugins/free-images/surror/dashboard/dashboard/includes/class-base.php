<?php

/**
 * Base class.
 *
 * @since      1.0.0
 * @package    Surror
 * @author     Surror <support@surror.com>
 */
namespace FAL\Surror\Dashboard;

\defined('ABSPATH') || exit;
/**
 * Base class.
 */
class Base
{
    /**
     * Path
     */
    private $path = '';
    /**
     * URI
     */
    private $uri = '';
    /**
     * Set constructor.
     */
    public function __construct()
    {
        $this->path = plugin_dir_path(__FILE__);
        $this->uri = plugins_url('/', $this->path);
    }
    /**
     * Handle function exist with magic method.
     */
    public function __call($name, $arguments)
    {
        if (\method_exists($this, $name)) {
            return \call_user_func_array([$this, $name], $arguments);
        }
    }
    /**
     * Handle static function exist with magic method.
     */
    public static function __callStatic($name, $arguments)
    {
        if (\method_exists(self::class, $name)) {
            return \call_user_func_array([self::class, $name], $arguments);
        }
    }
    /**
     * Handle isset function exist with magic method.
     */
    public function __isset($name)
    {
        return isset($this->{$name});
    }
    /**
     * Handle unset function exist with magic method.
     */
    public function __unset($name)
    {
        unset($this->{$name});
    }
    /**
     * Handle set function exist with magic method.
     */
    public function __set($name, $value)
    {
        $this->{$name} = $value;
    }
    /**
     * Handle get function exist with magic method.
     */
    public function __get($name)
    {
        return $this->{$name};
    }
}
