<?php

namespace DropshippingXmlFreeVendor\WPDesk\Library\DropshippingXmlCore\Infrastructure\Service\Dependency\Resolver;

use InvalidArgumentException;
use ReflectionClass;
use ReflectionParameter;
use DropshippingXmlFreeVendor\WPDesk\Library\DropshippingXmlCore\Infrastructure\Service\Container\Abstraction\ServiceContainerAwareInterface;
use DropshippingXmlFreeVendor\WPDesk\Library\DropshippingXmlCore\Infrastructure\Service\Container\Abstraction\ServiceContainerInterface;
use DropshippingXmlFreeVendor\WPDesk\Library\DropshippingXmlCore\Infrastructure\Service\Dependency\Binder\Abstraction\DependencyBinderCollectionInterface;
use DropshippingXmlFreeVendor\WPDesk\Library\DropshippingXmlCore\Infrastructure\Service\Dependency\Resolver\Abstraction\DependencyResolverInterface;
/**
 * Class DependencyResolver, resolves instances from class names and register required dependencies to service container (if set).
 * @package WPDesk\Library\DropshippingXmlCore\Infrastructure\Container
 */
class DependencyResolver implements \DropshippingXmlFreeVendor\WPDesk\Library\DropshippingXmlCore\Infrastructure\Service\Dependency\Resolver\Abstraction\DependencyResolverInterface, \DropshippingXmlFreeVendor\WPDesk\Library\DropshippingXmlCore\Infrastructure\Service\Container\Abstraction\ServiceContainerAwareInterface
{
    /**
     * @var DependencyBinderCollectionInterface
     */
    private $binder;
    /**
     * @var ServiceContainerInterface
     */
    private $service_container;
    /**
     * DependencyResolver constructor.
     *
     * @param DependencyBinderCollectionInterface $binder
     */
    public function __construct(\DropshippingXmlFreeVendor\WPDesk\Library\DropshippingXmlCore\Infrastructure\Service\Dependency\Binder\Abstraction\DependencyBinderCollectionInterface $binder)
    {
        $this->binder = $binder;
    }
    /**
     * @see ServiceContainerAwareInterface::set_service_container()
     */
    public function set_service_container(\DropshippingXmlFreeVendor\WPDesk\Library\DropshippingXmlCore\Infrastructure\Service\Container\Abstraction\ServiceContainerInterface $service_container)
    {
        if (isset($this->service_container)) {
            throw new \InvalidArgumentException('Service container is already set.');
        }
        $this->service_container = $service_container;
    }
    /**
     * @see DependencyResolverInterface::resolve()
     */
    public function resolve(string $class_name, array $arguments = array())
    {
        if ($this->binder->has($class_name)) {
            $bound = $this->binder->get($class_name);
            $dependency = $this->resolve_object($bound, $class_name, $arguments);
        } else {
            $dependency = $this->create_reflected_object($class_name, $arguments);
        }
        if (!$dependency instanceof $class_name) {
            throw new \InvalidArgumentException('Object of class ' . \get_class($dependency) . ' is not instance of ' . $class_name . '.');
        }
        return $dependency;
    }
    public function create_reflected_object(string $class_name, array $arguments = array())
    {
        if (\interface_exists($class_name)) {
            throw new \InvalidArgumentException('Interface ' . $class_name . ' can\'t be resolved.');
        }
        $obj = new \ReflectionClass($class_name);
        return $obj->newInstanceArgs($this->get_constructor_params($obj, $arguments));
    }
    /**
     * Get object from class name.
     *
     * @param ReflectionClass     $reflection_class
     * @param ReflectionParameter $reflection_parameter
     *
     * @return object
     * @throws \ReflectionException
     */
    private function get_object(\ReflectionClass $reflection_class, \ReflectionParameter $reflection_parameter)
    {
        $class_name = $reflection_class->getName();
        $dependency_class_name = $reflection_parameter->getType()->getName();
        $should_register_service = isset($this->service_container) && !$this->binder->has($class_name, $dependency_class_name);
        if ($should_register_service && $this->service_container->has($dependency_class_name)) {
            return $this->service_container->get($dependency_class_name);
        }
        if ($this->binder->has($class_name, $dependency_class_name) || $this->binder->has($dependency_class_name)) {
            $bound = $this->binder->has($class_name, $dependency_class_name) ? $this->binder->get($class_name, $dependency_class_name) : $this->binder->get($dependency_class_name);
            $dependency = $this->resolve_object($bound, $dependency_class_name);
        } else {
            $dependency = $this->create_reflected_object($dependency_class_name);
        }
        return $should_register_service ? $this->service_container->register($dependency, $dependency_class_name) : $dependency;
    }
    /**
     * Resolve object.
     *
     * @param mixed  $bind                  array, object, class name or function.
     * @param string $dependency_class_name class name.
     * @param array  $arguments class name.
     *
     * @return object
     * @throws \ReflectionException
     */
    private function resolve_object($bind, $dependency_class_name, array $arguments = [])
    {
        if (\is_string($bind)) {
            return $this->create_reflected_object($bind, $arguments);
        }
        if (\is_array($bind)) {
            return $this->create_reflected_object($dependency_class_name, \array_merge($bind, $arguments));
        }
        if (\is_callable($bind)) {
            $obj = $bind();
            if (!\is_object($obj)) {
                throw new \InvalidArgumentException('Callable method should return instance of the object - ' . \gettype($obj) . ' given.');
            }
            return $obj;
        }
        if (\is_object($bind)) {
            return $bind;
        }
        throw new \InvalidArgumentException('Service can bind only class name, instance of the object, array or callable method');
    }
    /**
     * Get default parameter or binded value from class constructor.
     *
     * @param ReflectionClass     $reflection_class
     * @param ReflectionParameter $reflection_parameter
     *
     * @return mixed
     * @throws \ReflectionException
     * @throws InvalidArgumentException
     */
    private function get_value(\ReflectionClass $reflection_class, \ReflectionParameter $reflection_parameter)
    {
        $reflection_class_name = $reflection_class->getName();
        $reflection_parameter_name = $reflection_parameter->getName();
        if ($this->binder->has($reflection_class_name, $reflection_parameter_name)) {
            return $this->binder->get($reflection_class_name, $reflection_parameter_name);
        }
        if ($reflection_parameter->isDefaultValueAvailable()) {
            return $reflection_parameter->getDefaultValue();
        }
        throw new \InvalidArgumentException('Class ' . $reflection_class_name . ' used for dependency injection needs parameter ' . $reflection_parameter->getName() . ' value that does not exists');
    }
    /**
     * Get constructor parameters, including dependencies from class name.
     *
     * @param ReflectionClass $reflection_class
     * @param array           $arguments
     *
     * @return array
     * @throws \ReflectionException
     */
    private function get_constructor_params(\ReflectionClass $reflection_class, array $arguments)
    {
        $result = array();
        $constructor = $reflection_class->getConstructor();
        if (null !== $constructor) {
            foreach ($constructor->getParameters() as $param) {
                if (isset($arguments[$param->getName()])) {
                    $result[] = $arguments[$param->getName()];
                } else {
                    $result[] = $param->getType() && !$param->getType()->isBuiltin() ? $this->get_object($reflection_class, $param) : $this->get_value($reflection_class, $param);
                }
            }
        }
        return $result;
    }
}
