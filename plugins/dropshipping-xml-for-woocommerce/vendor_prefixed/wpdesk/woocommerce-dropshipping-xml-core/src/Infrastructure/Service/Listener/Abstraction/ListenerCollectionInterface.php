<?php

namespace DropshippingXmlFreeVendor\WPDesk\Library\DropshippingXmlCore\Infrastructure\Service\Listener\Abstraction;

/**
 * Interface DependencyBinder allows to manage binders.
 */
interface ListenerCollectionInterface
{
    /**
     * Add binders as associative array in below format
     * [
     * ClassNameInterface => ClassName,
     * ParentClassName => ClassName,
     * ClassName => ClassName,
     * ObjectInterface => Object,
     * ParentObjectClassName => Object,
     * ObjectClassName => Object
     * ]
     *
     * @param array $binders
     *
     * @return void
     */
    public function add(array $listeners);
    /**
     * Chceck if binded class already exists.
     *
     * @param string $class_name with namespaces.
     *
     * @return bool
     */
    public function has(string $class_name) : bool;
    public function get(string $class_name);
    public function get_all() : array;
}
