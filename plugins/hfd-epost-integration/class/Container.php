<?php
/**
 * Created by PhpStorm.
 * Date: 6/6/18
 * Time: 1:47 PM
 */
namespace Hfd\Woocommerce;

class Container
{
    protected static $instance;

    protected $registry;

    public function __construct()
    {
        $this->registry = Registry::getInstance();
    }

    /**
     * @return Container
     */
    public static function getInstance()
    {
        if (!self::$instance) {
            self::$instance = new Container();
        }

        return self::$instance;
    }

    /**
     * @param string $className
     * @return mixed
     */
    public static function get($className)
    {
        $instance = self::getInstance();

        $alias = $instance->getAlias($className);

        if (!$alias) {
            $alias = $className;
        }

        $object = $instance->getRegistry()->get($alias);

        if (!$object) {
            $object = self::create($className);
        }

        $instance->getRegistry()->set($alias, $object);

        return $object;
    }

    /**
     * @param string $className
     * @param array $args
     * @return mixed
     */
    public static function create($className, $args = array())
    {
        return new $className($args);
    }

    /**
     * @return Registry
     */
    public function getRegistry()
    {
        return $this->registry;
    }

    /**
     * @param string $className
     * @return string|null
     */
    public function getAlias($className)
    {
        $alias = array(
            'Hfd\Woocommerce\Setting' => 'setting',
            'Hfd\Woocommerce\Template' => 'template',
            'Hfd\Woocommerce\AutoLoad' => 'autoload',
            'Hfd\Woocommerce\Cart\Pickup' => 'cart_pickup',
        );

        return isset($alias[$className]) ? $alias[$className] : null;
    }
}