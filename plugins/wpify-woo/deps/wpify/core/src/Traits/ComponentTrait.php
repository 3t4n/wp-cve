<?php

namespace WpifyWooDeps\Wpify\Core\Traits;

use Exception;
use ReflectionClass;
use ReflectionException;
use ReflectionMethod;
use ReflectionProperty;
use stdClass;
use WP_Error;
use WpifyWooDeps\Wpify\Core\Abstracts\AbstractComponent;
use WpifyWooDeps\Wpify\Core\Abstracts\AbstractPlugin;
use WpifyWooDeps\Wpify\Core\Exceptions\ComponentInitFailureException;
use WpifyWooDeps\Wpify\Core\Exceptions\ComponentMissingException;
use WpifyWooDeps\Wpify\Core\Exceptions\PluginException;
/**
 * Trait Component
 * @package Traits
 */
trait ComponentTrait
{
    use BaseObjectTrait;
    /**
     * @var bool
     */
    protected $inited = \false;
    protected $auto_init = \true;
    protected $auto_init_list = [];
    /**
     * @var AbstractPlugin
     */
    private $plugin;
    /**
     * @var ComponentTrait
     */
    private $parent;
    /**
     */
    public function __destruct()
    {
        $this->plugin = null;
        $this->parent = null;
    }
    /**
     * jQuery inspired method to get the first parent component that is an instance of the given class
     *
     * @param $class
     *
     * @return bool|ComponentTrait
     */
    public function get_closest($class)
    {
        $parent = $this;
        while ($parent->has_parent() && !\is_a($parent, $class)) {
            $parent = $parent->get_parent();
        }
        if ($parent === $this || !\is_a($parent, $class)) {
            return \false;
        }
        return $parent;
    }
    /**
     * Return if the current component has a parent or not
     * @return bool
     */
    public function has_parent()
    {
        return null !== $this->parent;
    }
    /**
     * @return ComponentTrait
     */
    public function get_parent()
    {
        return $this->parent;
    }
    /**
     * @param ComponentTrait $parent
     */
    public function set_parent($parent)
    {
        $this->parent = $parent;
    }
    /**
     * @param string $class
     * @param mixed  $args,...
     *
     * @return mixed
     * @throws PluginException
     */
    public function create_component($class, array $args = array())
    {
        $component = $this->create_object($class, $args);
        if (\method_exists($component, 'set_parent')) {
            $component->set_parent($this);
        }
        return $component;
    }
    /**
     * @param                         $class
     * @param array<string|int,mixed> $args
     *
     * @return mixed
     */
    public function create_object($class, array $args)
    {
        if (empty($args)) {
            $object = $this->get_plugin()->container->get($class);
        } else {
            $object = $this->get_plugin()->container->make($class, $args);
        }
        return $object;
    }
    /**
     * Magical utility method that will walk up the reference chain to get the master Plugin instance and cache it in $plugin
     * @return AbstractPlugin
     * @throws PluginException
     */
    public function get_plugin()
    {
        if (null === $this->plugin) {
            $parent = $this;
            while ($parent->has_parent()) {
                $parent = $parent->get_parent();
            }
            $this->plugin = $parent;
        }
        if ($this->plugin === $this && !$this instanceof AbstractPlugin) {
            throw new PluginException(\sprintf('Plugin property on %s is equal to self. Did you forget to set the parent or create a getter?', $this->get_full_class_name()));
        }
        if (!$this->plugin instanceof AbstractPlugin) {
            throw new PluginException(\sprintf('Parent property on %s not set. Did you forget to set the parent?', $this->get_full_class_name()));
        }
        return $this->plugin;
    }
    /**
     * The super init method magic happens
     * @return bool|Exception
     * @throws PluginException
     * @throws ReflectionException
     */
    public function init()
    {
        try {
            if ($this->is_error($result = $this->link_components())) {
                return $result;
            }
        } catch (Exception $e) {
            return $e;
        }
        if ($this->is_error($result = $this->init_components())) {
            return $result;
        }
        try {
            new ReflectionProperty($this, 'plugin');
            $this->get_plugin();
        } catch (ReflectionException $e) {
        }
        /**
         * @noinspection DynamicInvocationViaScopeResolutionInspection
         */
        $setup = static::setup();
        if (!$this->is_error($setup)) {
            $this->inited = \true;
        }
        return $setup;
    }
    protected function is_error($value)
    {
        return \false === $value || is_wp_error($value) || $value instanceof Exception;
    }
    /**
     * Setup components
     * @return bool
     * @throws ReflectionException
     */
    protected function link_components()
    {
        /**
         * @noinspection DynamicInvocationViaScopeResolutionInspection
         */
        if ($this->is_error($result = static::load_components())) {
            return $result;
        }
        $components = $this->get_components();
        $this->set_component_parents($components);
        return \true;
    }
    /**
     * Lazy load components possibly conditionally
     * @return bool
     */
    protected function load_components()
    {
        return \true;
    }
    /**
     * Get all components with a getter and that uses the Component trait
     * @return array<string|int,mixed>|array<string|int,ReflectionProperty>
     */
    protected function get_components()
    {
        if (!$this->is_auto_init()) {
            return [];
        }
        static $cache = array();
        $hash = \spl_object_hash($this);
        if (isset($cache[$hash])) {
            return $cache[$hash];
        }
        $components = (new ReflectionClass($this))->getProperties();
        $components = \array_map(
            /**
             * @param ReflectionProperty $property
             *
             * @return string
             */
            static function ($property) {
                return $property->name;
            },
            $components
        );
        $components = \array_diff($components, ['plugin', 'parent', 'auto_init', 'auto_init_list', 'inited']);
        if (!empty($this->auto_init_list) && \is_array($this->auto_init_list)) {
            $components = \array_intersect($components, $this->auto_init_list);
        }
        $components = \array_filter($components, [$this, 'is_component']);
        $components = \array_map(
            /**
             * @param ReflectionProperty $component
             *
             * @return ComponentTrait
             */
            function ($component) {
                $getter = "get_{$component}";
                if (\method_exists($this, $getter)) {
                    return $this->{$getter}();
                }
                $getter = 'get' . \ucfirst($component);
                if (\method_exists($this, $getter)) {
                    return $this->{$getter}();
                }
                return $this->get_private_property($this, $component);
            },
            $components
        );
        if (!empty($components)) {
            $components = \array_map(static function ($component) {
                if (!\is_array($component)) {
                    return [$component];
                }
                return $component;
            }, $components);
            $components = \call_user_func_array('array_merge', $components);
            $components = \array_filter($components, [$this, 'is_component']);
        }
        $cache[$hash] = $components;
        return $components;
    }
    /**
     * @return bool
     */
    public function is_auto_init()
    {
        return $this->auto_init;
    }
    /**
     * @param bool $auto_init
     */
    public function set_auto_init($auto_init)
    {
        $this->auto_init = $auto_init;
    }
    private function get_private_property($component, $name)
    {
        static $cache = [];
        $class = \get_class($component);
        if (!isset($cache[$class])) {
            $cache[$class] = new ReflectionClass($component);
        }
        $reflectObject = $cache[$class];
        if ($reflectObject->hasProperty($name)) {
            $property = $reflectObject->getProperty($name);
            $property->setAccessible(\true);
            return $property->getValue($component);
        }
        return null;
    }
    /**
     * Set the parent reference for the given components to the current component
     *
     * @param $components
     */
    protected function set_component_parents($components)
    {
        /**
         * @var ComponentTrait $component
         */
        foreach ($components as $component) {
            $component->set_parent($this);
        }
    }
    /**
     * Run init
     * @return bool|ComponentInitFailureException
     * @throws ReflectionException
     */
    protected function init_components()
    {
        /**
         * @var array<string|int,AbstractComponent> $components
         */
        $components = $this->get_components();
        foreach ($components as $component) {
            if ($result = $this->try_init($component)) {
                return $result;
            }
        }
        return \true;
    }
    /**
     * @param                                $component
     * @param null|WP_Error|bool|Exception   $error
     *
     * @return bool|ComponentInitFailureException|Exception|void
     */
    protected function try_init($component, $error = null)
    {
        $result = null;
        if (null !== $error) {
            $result = $error;
        } else {
            if ($this !== $component) {
                $result = $component->init();
            }
        }
        if ($this->is_error($result)) {
            if ($result instanceof Exception) {
                return $result;
            }
            $message = 'Component %s for parent %s failed to initialize!';
            $args = [$component->get_full_class_name(), $this->get_full_class_name()];
            if ($result) {
                $message .= ' Error: %s';
                /**
                 * @var WP_Error $result
                 */
                $args[] = $result->get_error_message();
            }
            return new ComponentInitFailureException(\vsprintf($message, $args));
        }
    }
    /**
     * Method to overload to put in component code
     * @return bool
     */
    public function setup()
    {
        return \true;
    }
    /**
     * @return bool
     */
    public function is_inited()
    {
        return $this->inited;
    }
    /**
     * @param string|ComponentTrait $component
     * @param bool                  $use_cache
     *
     * @return bool|mixed
     * @throws ReflectionException
     */
    protected function is_component($component, $use_cache = \true)
    {
        static $cache = [];
        if (!\is_object($component)) {
            if (!\is_string($component)) {
                return \false;
            }
            $found = \false;
            foreach (['get_' . $component, 'get' . \ucfirst($component)] as $getter) {
                if (\method_exists($this, $getter) && (new ReflectionMethod($this, $getter))->isPublic()) {
                    $found = \true;
                    break;
                }
            }
            if (!$found) {
                $property_component = $this->get_private_property($this, $component);
            }
            if (isset($property_component)) {
                $component = $property_component;
            } elseif ($found) {
                $component = $this->{$getter}();
            }
        }
        /**
         * @noinspection CallableParameterUseCaseInTypeContextInspection
         */
        if (\is_array($component)) {
            $count = \count(\array_filter($component, function ($component) {
                return $this->is_component($component);
            }));
            return $count > 0 && $count === \count($component);
        }
        if (!\is_object($component)) {
            return \false;
        }
        if ($component instanceof stdClass) {
            return \false;
        }
        $hash = \spl_object_hash($component);
        if ($use_cache && isset($cache[$hash])) {
            return $cache[$hash];
        }
        $trait = __TRAIT__;
        $used = \class_uses($component);
        if (!isset($used[$trait])) {
            $parents = \class_parents($component);
            while (!isset($used[$trait]) && $parents) {
                //get trait used by parents
                $used = \class_uses(\array_pop($parents));
            }
        }
        $cache[$hash] = \in_array($trait, $used, \true);
        return $cache[$hash];
    }
    /**
     * Load any property on the current component based on its string value as the class via the container
     *
     * @param string                  $component
     * @param array<string|int,mixed> $args
     *
     * @return bool
     * @throws ComponentMissingException
     */
    protected function load($component, ...$args)
    {
        if (!\property_exists($this, $component)) {
            return \false;
        }
        $class = $this->{$component};
        if (!\is_string($class) && !\is_array($class)) {
            return \false;
        }
        $class = (array) $class;
        foreach ($class as $index => $class_element) {
            if (!\is_string($class_element)) {
                return \false;
            }
            if (!\class_exists($class_element)) {
                throw new ComponentMissingException(\sprintf('Can not find class "%s" for Component "%s" in parent Component "%s"', $class_element, $component, __CLASS__));
            }
            $class[$index] = $this->create_object($class_element, $args);
        }
        if (1 === \count($class)) {
            $class = \array_pop($class);
        }
        $this->{$component} = $class;
        return \true;
    }
    /**
     * Utility method to see if a component property is loaded
     *
     * @param string $component
     *
     * @return bool
     */
    protected function is_loaded($component)
    {
        if (!\property_exists($this, $component)) {
            return \false;
        }
        $property = $this->{$component};
        $property = \is_array($property) ? $property : [$property];
        if (0 === \count($property)) {
            return \false;
        }
        foreach ($property as $item) {
            if (!\is_object($item)) {
                return \false;
            }
            if ($item instanceof stdClass) {
                return \false;
            }
        }
        return \true;
    }
}
