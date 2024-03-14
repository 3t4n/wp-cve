<?php

namespace WpifyWooDeps\Wpify\Core\Abstracts;

use ReflectionClass;
use ReflectionException;
use WpifyWooDeps\Wpify\Core\Exceptions\PluginException;
/**
 * Class AbstractManager
 * @package Wpify\Core\Abstracts
 */
abstract class AbstractManager extends AbstractComponent
{
    /**
     */
    const MODULE_NAMESPACE = '';
    /**
     * @var array<string|int,AbstractComponent>
     */
    protected $modules = array();
    /**
     * @var array<string|int,mixed>
     */
    private $find_cache = array();
    /**
     * @return bool
     * @throws ReflectionException
     * @throws PluginException
     */
    public function load_components()
    {
        if (0 < \count(\array_filter($this->modules, 'is_object'))) {
            return \false;
        }
        $reflect = new ReflectionClass(\get_called_class());
        $class = \strtolower($reflect->getShortName());
        $namespace = static::MODULE_NAMESPACE;
        if (empty($namespace)) {
            $namespace = $reflect->getNamespaceName();
        }
        $component = \explode('\\', $namespace);
        $component = \strtolower(\end($component));
        $slug = $this->plugin->safe_slug;
        $filter = "{$slug}_{$component}_{$class}_modules";
        /*
         * Deprecated: Old filter, likely to be removed
         */
        $modules_list = apply_filters($filter, $this->modules);
        $modules_list = apply_filters("{$slug}_manager_modules", $this->modules, \get_class($this));
        $this->modules = [];
        foreach ($modules_list as $module) {
            $class = \trim($module, '\\');
            if (\false === \strpos($module, '\\')) {
                $class = $namespace . '\\' . $module;
            }
            $this->modules[$module] = $this->create_component($class);
        }
        return \true;
    }
    /**
     * @return array<string|int,mixed>
     */
    public function get_modules()
    {
        return $this->modules;
    }
    /**
     * @param $name
     *
     * @return bool|mixed
     */
    public function __get($name)
    {
        $module = $this->find($name);
        if (!$module) {
            return parent::__get($name);
        }
        return $module;
    }
    /**
     * @param $name
     *
     * @return bool|mixed
     */
    protected function find($name)
    {
        $module = $this->get_module($name);
        if (!$module) {
            $module = $this->get_module(\ucfirst($name));
        }
        if (!$module) {
            if (isset($this->find_cache[$name])) {
                $module = $this->get_module($this->find_cache[$name]);
            }
        }
        if (!$module) {
            $module_keys = \array_keys($this->modules);
            foreach ($module_keys as $module_key) {
                $module_key_converted = \ltrim($module_key, '\\');
                if (\preg_match('/[a-z]/', $module_key_converted)) {
                    $module_key_converted = \preg_replace('/[A-Z]/', 'WpifyWooDeps\\_', \lcfirst($module_key_converted));
                }
                $module_key_converted = \strtolower($module_key_converted);
                $lower_name = \strtolower($name);
                if ($module_key_converted === $lower_name || "\\{$module_key_converted}" === $lower_name) {
                    $module = $this->get_module($module_key);
                    $this->find_cache[$name] = $module_key;
                    break;
                }
            }
        }
        if (!$module) {
            $module = \false;
        }
        return $module;
    }
    /**
     * @param $name
     *
     * @return bool|mixed
     */
    public function get_module($name)
    {
        if (null === $name) {
            return \false;
        }
        if (isset($this->modules[$name])) {
            return $this->modules[$name];
        }
        $name = "\\{$name}";
        if (isset($this->modules[$name])) {
            return $this->modules[$name];
        }
        return \false;
    }
    /**
     * @param $name
     *
     * @return bool
     */
    public function __isset($name)
    {
        $module = $this->get_module($name);
        if (!$module) {
            return parent::__isset($name);
        }
        return \true;
    }
    public function add_module($module)
    {
        /**
         * @var AbstractComponent $module
         */
        if (!$this->is_component($module)) {
            return \false;
        }
        $this->modules[$module->get_class_name()] = $module;
        if (!$module->is_inited()) {
            if ($result = $this->try_init($module)) {
                throw $result;
            }
        }
        return \true;
    }
}
