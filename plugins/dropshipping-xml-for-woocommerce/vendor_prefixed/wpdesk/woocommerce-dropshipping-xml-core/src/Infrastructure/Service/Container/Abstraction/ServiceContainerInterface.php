<?php

namespace DropshippingXmlFreeVendor\WPDesk\Library\DropshippingXmlCore\Infrastructure\Service\Container\Abstraction;

use Psr\Container\ContainerInterface;
use OutOfBoundsException;
/**
 * Interface ServiceContainerInterface abstraction class for service container.
 * @package WPDesk\Library\DropshippingXmlCore\Infrastructure\Container
 */
interface ServiceContainerInterface extends \Psr\Container\ContainerInterface
{
    /**
     * @see ContainerInterface::has()
     */
    public function has($class_name);
    /**
     * @see ContainerInterface::get()
     */
    public function get($class_name);
    /**
     * Register dependency by class name or interface name.
     *
     * @param string|object $dependency
     * @param string        $class_name
     *
     * @return object registered.
     */
    public function register($dependency, string $class_name = '');
    /**
     * Register multiple services
     *
     * @param array $services in below format.
     * [
     *  'class_name' => 'class_name',
     *  'class_name' => instance_of_class_name_object,
     *  'class_name',
     *   object,
     *   ...
     * ]
     *
     * @return void
     */
    public function register_from_array(array $services);
    /**
     * Replace service in container.
     * @param object $object instance that should be placed inside the container.
     * @param string $class_name parent class or interface of $object parameter.
     *
     * @return object
     *
     */
    public function replace($object, string $class_name);
    /**
     * Add forbidden services that will not be available from service container.
     * @param array $forbidden in below format.
     * 	[
     *  'class_name',
     *   ...
     * ]
     *
     * @return void
     */
    public function add_forbidden(array $forbidden);
}
