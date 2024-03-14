<?php

namespace DropshippingXmlFreeVendor\WPDesk\Library\DropshippingXmlCore\Infrastructure\Service\Container;

use OutOfBoundsException;
use InvalidArgumentException;
use DropshippingXmlFreeVendor\WPDesk\Library\DropshippingXmlCore\Infrastructure\Service\Dependency\Resolver\Abstraction\DependencyResolverInterface;
use DropshippingXmlFreeVendor\WPDesk\Library\DropshippingXmlCore\Infrastructure\Service\Listener\Abstraction\ListenerCollectionInterface;
use DropshippingXmlFreeVendor\WPDesk\Library\DropshippingXmlCore\Infrastructure\Service\Container\Abstraction\ServiceContainerInterface;
/**
 * Class ServiceContainer manage services. It use DependencyResolver to gets instances from class names.
 * @package WPDesk\Library\DropshippingXmlCore\Infrastructure\Container
 */
class ServiceContainer implements \DropshippingXmlFreeVendor\WPDesk\Library\DropshippingXmlCore\Infrastructure\Service\Container\Abstraction\ServiceContainerInterface
{
    /**
     * @var array
     */
    private $services = array();
    /**
     * Forbidden services class names.
     * @var array
     */
    private $forbidden = array();
    /**
     * @var DependencyResolverInterface
     */
    private $resolver;
    /**
     * @var ListenerCollectionInterface
     */
    private $listener;
    /**
     * ServiceContainer constructor. Init dependencies and register itself as service.
     *
     * @param DependencyResolverInterface $resolver
     * @param ListenerCollectionInterface $listener
     */
    public function __construct(\DropshippingXmlFreeVendor\WPDesk\Library\DropshippingXmlCore\Infrastructure\Service\Dependency\Resolver\Abstraction\DependencyResolverInterface $resolver, \DropshippingXmlFreeVendor\WPDesk\Library\DropshippingXmlCore\Infrastructure\Service\Listener\Abstraction\ListenerCollectionInterface $listener)
    {
        $this->resolver = $resolver;
        $this->listener = $listener;
        $this->register_from_object($this, \DropshippingXmlFreeVendor\WPDesk\Library\DropshippingXmlCore\Infrastructure\Service\Container\Abstraction\ServiceContainerInterface::class);
        $this->register_from_object($resolver, \DropshippingXmlFreeVendor\WPDesk\Library\DropshippingXmlCore\Infrastructure\Service\Dependency\Resolver\Abstraction\DependencyResolverInterface::class);
    }
    /**
     * @see ServiceContainerInterface::has()
     */
    public function has($class_name)
    {
        return isset($this->services[$class_name]);
    }
    /**
     * @see ServiceContainerInterface::get()
     */
    public function get($class_name)
    {
        if (!$this->has($class_name)) {
            throw new \OutOfBoundsException('Service not exists');
        }
        return $this->services[$class_name];
    }
    /**
     * @see ServiceContainerInterface::register()
     */
    public function register($dependency, string $class_name = '')
    {
        if (\is_string($dependency) || \is_object($dependency)) {
            return \is_string($dependency) ? $this->register_from_class_name($dependency, $class_name) : $this->register_from_object($dependency, $class_name);
        }
        throw new \InvalidArgumentException('Service can be registered only from class name or instance of the object');
    }
    /**
     * @see ServiceContainerInterface::register_from_array()
     */
    public function register_from_array(array $services)
    {
        foreach ($services as $key => $class_name) {
            \is_numeric($key) ? $this->register($class_name) : $this->register($class_name, $key);
        }
    }
    /**
     * @see ServiceContainerInterface::replace()
     */
    public function replace($object, string $class_name)
    {
        if (!\is_object($object)) {
            throw InvalidArgumentException('First parameter in method should be an object');
        }
        $this->register_service($object, $class_name);
        return $object;
    }
    /**
     * @see ServiceContainerInterface::add_forbidden()
     */
    public function add_forbidden(array $forbidden)
    {
        foreach ($forbidden as $class_name) {
            $this->add_forbidden_service($class_name);
        }
    }
    /**
     * Register service from class name.
     *
     * @param string $dependency_class_name
     * @param string $parent_class_name
     *
     * @return object
     * @throws InvalidArgumentException
     */
    private function register_from_class_name(string $dependency_class_name, string $parent_class_name)
    {
        if (!\class_exists($dependency_class_name) && !\interface_exists($dependency_class_name)) {
            throw new \InvalidArgumentException('Class ' . $dependency_class_name . ' not exists');
        }
        $parent_class_name = $parent_class_name === '' ? $dependency_class_name : $parent_class_name;
        if ($this->has($parent_class_name)) {
            return $this->get($parent_class_name);
        }
        $resolved = $this->resolver->resolve($dependency_class_name);
        $this->register_service($resolved, $parent_class_name);
        return $resolved;
    }
    /**
     * Register service from object.
     *
     * @param object $dependency_object
     * @param string $parent_class_name
     *
     * @return object
     * @throws InvalidArgumentException
     */
    private function register_from_object($dependency_object, string $parent_class_name)
    {
        $parent_class_name = $parent_class_name === '' ? \get_class($dependency_object) : $parent_class_name;
        if ($this->has($parent_class_name)) {
            return $this->get($parent_class_name);
        }
        $this->register_service($dependency_object, $parent_class_name);
        return $dependency_object;
    }
    /**
     * Register service.
     *
     * @param object $service
     * @param string $parent_class_name class name or interface
     *
     * @throws InvalidArgumentException
     */
    private function register_service($service, string $parent_class_name)
    {
        if (!$this->has_forbidden($parent_class_name)) {
            if ($service instanceof $parent_class_name) {
                $this->services[$parent_class_name] = $service;
                $this->notify_listeners($service);
            } else {
                throw new \InvalidArgumentException('Error, object should be instance of ' . $parent_class_name);
            }
        }
    }
    /**
     * Add forbidden service that will not be available from service container.
     *
     * @param string $class_name class name or interface.
     *
     * @throws InvalidArgumentException
     */
    private function add_forbidden_service(string $class_name)
    {
        if (\class_exists($class_name)) {
            $this->forbidden[$class_name] = $class_name;
        } else {
            throw new \InvalidArgumentException('Class ' . $class_name . ' not exists');
        }
    }
    /**
     * Notify listeners about registered service.
     *
     * @param object $service
     *
     */
    private function notify_listeners($service)
    {
        foreach ($this->listener->get_all() as $listener) {
            $listener->update($service, $this);
            if ($listener->stop_propagation()) {
                break;
            }
        }
    }
    private function has_forbidden(string $parent_class_name) : bool
    {
        return isset($this->forbidden[$parent_class_name]);
    }
}
