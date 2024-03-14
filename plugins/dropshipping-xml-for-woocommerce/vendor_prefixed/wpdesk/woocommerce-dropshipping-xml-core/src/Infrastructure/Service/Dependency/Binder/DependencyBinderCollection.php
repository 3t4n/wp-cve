<?php

namespace DropshippingXmlFreeVendor\WPDesk\Library\DropshippingXmlCore\Infrastructure\Service\Dependency\Binder;

use Exception;
use DropshippingXmlFreeVendor\WPDesk\Library\DropshippingXmlCore\Infrastructure\Service\Dependency\Binder\Abstraction\DependencyBinderCollectionInterface;
/**
 * Class DependencyBinderCollection
 * @package WPDesk\Library\DropshippingXmlCore\Infrastructure\Container
 */
class DependencyBinderCollection implements \DropshippingXmlFreeVendor\WPDesk\Library\DropshippingXmlCore\Infrastructure\Service\Dependency\Binder\Abstraction\DependencyBinderCollectionInterface
{
    /**
     * Binded class names.
     * @var array
     */
    private $binded = [];
    /**
     * @see DependencyBinderCollectionInterface::add()
     */
    public function add(array $binders)
    {
        foreach ($binders as $parent_class_name => $dependency) {
            $this->add_class($dependency, $parent_class_name);
        }
    }
    /**
     * @see DependencyBinderCollectionInterface::has()
     */
    public function has(string $class_name, string $parameter = '') : bool
    {
        return empty($parameter) ? isset($this->binded[$class_name]) : isset($this->binded[$class_name][$parameter]);
    }
    /**
     * @see DependencyBinderCollectionInterface::get()
     */
    public function get(string $class_name, string $parameter = '')
    {
        if ($this->has($class_name, $parameter)) {
            return empty($parameter) ? $this->binded[$class_name] : $this->binded[$class_name][$parameter];
        }
        throw new \Exception('Error, element ' . $class_name . ' not exists in collection');
    }
    /**
     * @see DependencyBinderCollectionInterface::get_all()
     */
    public function get_all() : array
    {
        return $this->binded;
    }
    /**
     * @param mixed $dependency value, class name, object, array (with mapped fields) or callable function.
     * @param string $parent_class_name or interface with namesapce.
     */
    private function add_class($dependency, string $parent_class_name)
    {
        $this->binded[$parent_class_name] = $dependency;
    }
}
