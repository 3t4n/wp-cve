<?php
/**
 * Created by PhpStorm.
 * Date: 6/4/18
 * Time: 4:57 PM
 */
namespace Hfd\Woocommerce;

class Registry
{
    protected $registry = array();

    protected static $instance;

    /**
     * @return Registry
     */
    public static function getInstance()
    {
        if (!self::$instance) {
            self::$instance = new Registry();
        }

        return self::$instance;
    }

    /**
     * @param $key
     * @return mixed|null
     */
    public function get($key)
    {
        if (isset($this->registry[$key])) {
            return $this->registry[$key];
        }

        return null;
    }

    /**
     * @param string $key
     * @param mixed $value
     * @param bool $force
     * @return $this
     */
    public function set($key, $value, $force = false)
    {
        if (!isset($this->registry[$key]) || $force) {
            $this->registry[$key] = $value;
        }

        return $this;
    }
}