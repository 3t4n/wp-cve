<?php

namespace luckywp\tableOfContents\core;

use Exception;
use luckywp\tableOfContents\core\base\BasePlugin;
use luckywp\tableOfContents\core\base\Container;

class Core
{

    /**
     * @var Container
     */
    public static $container;

    /**
     * @var \luckywp\tableOfContents\plugin\Plugin
     */
    public static $plugin;

    public static function initialize(BasePlugin $plugin)
    {
        static::$container = new Container();
        static::$plugin = $plugin;
    }

    /**
     * @param object $object
     * @param array $properties
     * @return object
     */
    public static function configure($object, $properties)
    {
        foreach ($properties as $name => $value) {
            $object->$name = $value;
        }
        return $object;
    }

    /**
     * @param string|array $type
     * @param array $params
     * @return object
     */
    public static function createObject($type, array $params = [])
    {
        if (is_string($type)) {
            return static::$container->get($type, $params);
        } elseif (is_array($type) && isset($type['class'])) {
            $class = $type['class'];
            unset($type['class']);
            return static::$container->get($class, $params, $type);
        }
        throw new Exception('Unsupported configuration type: ' . gettype($type));
    }

    /**
     * @return bool
     */
    public static function isFront()
    {
        return !is_admin() && !wp_doing_ajax();
    }
}
