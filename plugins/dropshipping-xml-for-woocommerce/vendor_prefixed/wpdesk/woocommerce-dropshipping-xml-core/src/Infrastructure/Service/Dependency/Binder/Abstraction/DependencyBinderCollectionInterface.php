<?php

namespace DropshippingXmlFreeVendor\WPDesk\Library\DropshippingXmlCore\Infrastructure\Service\Dependency\Binder\Abstraction;

/**
 * Interface DependencyBinderCollectionInterface abstraction for storing associated binded classnames/interfaces in a collection.
 * @package WPDesk\Library\DropshippingXmlCore\Infrastructure\Container
 */
interface DependencyBinderCollectionInterface
{
    /**
     * Add binders as associative array in below format
     * [
     * ClassName => ClassName,
     * ClassName => Object,
     * ClassName => callable(),
     * ClassName => array()
     * ]
     *
     * @param array $binders.
     *
     * @return void
     */
    public function add(array $binders);
    /**
     * Chceck if binded class already exists.
     *
     * @param string $class_name with namespaces.
     *
     * @return bool
     */
    public function has(string $class_name, string $parameter) : bool;
    /**
     * Get binded value.
     *
     * @param string $class_name or interface with namespaces.
     * @throws Exception if class not exists.
     * @return mixed value, class name, object, array or callable function.
     */
    public function get(string $class_name, string $parameter);
    /**
     * Get all binded array.
     * @return array
     */
    public function get_all() : array;
}
