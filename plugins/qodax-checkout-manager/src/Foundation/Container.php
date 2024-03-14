<?php

namespace Qodax\CheckoutManager\Foundation;

use Qodax\CheckoutManager\Exceptions\ContainerException;

if ( ! defined('ABSPATH')) {
    exit;
}

class Container
{
    /**
     * @var Container
     */
    private static $instance;

    /**
     * List of container bindings.
     *
     * @var array
     */
    private $bindings = [];

    /**
     * List of container singleton instances.
     *
     * @var array
     */
    private $singletons = [];

    /**
     * Container constructor.
     * @param array $bindings
     */
    public function __construct($bindings = [])
    {
        $this->bindings = $bindings;
    }

    public static function instance($bindings = [])
    {
        if (null === self::$instance) {
            self::$instance = new self($bindings);
        }

        return self::$instance;
    }

    /**
     * @param string $key
     * @return mixed
     */
    public function __get($key)
    {
        return $this->$key;
    }

    /**
     * @param string $key
     * @param mixed $value
     * @return mixed
     */
    public function __set($key, $value)
    {
        return $this->$key = $value;
    }

    /**
     * PSR 11 implementation
     *
     * @param string $id
     */
    public function has($id)
    {
        return isset($this->bindings[$id]);
    }

    /**
     * @param string $id
     * @return mixed
     *
     * @throws ContainerException
     */
    public function get($id)
    {
        if ($this->has($id)) {
            return $this->resolve($this->bindings[$id]);
        }
        else {
            throw new ContainerException('Dependency ' . $id . ' not found.');
        }
    }

    /**
     * Bind concrete implementation.
     *
     * @param string $abstract
     * @param callable $concrete
     *
     * @throws ContainerException
     */
    public function bind($abstract, $concrete)
    {
        if (is_callable($concrete)) {
            $this->bindings[$abstract] = $concrete;
        }
        else {
            throw new ContainerException('Bind method expects second parameter to be a callable');
        }
    }

    /**
     * Make concrete instance. Trying to create current type instance if no exists.
     *
     * @param string $abstract
     * @return mixed
     */
    public function make($abstract)
    {
        try {
            return $this->get($abstract);
        }
        catch (ContainerException $e) {
            return $this->resolveWithWiring($abstract);
        }
    }

    /**
     * @param string $dependency
     * @return mixed
     */
    private function resolve($dependency)
    {
        return call_user_func($dependency, $this);
    }

    /**
     * @param string $abstract
     * @return mixed
     */
    private function resolveWithWiring($abstract)
    {
        $reflectionClass = new \ReflectionClass($abstract);
        $constructor = $reflectionClass->getConstructor();

        if ( ! $constructor) {
            return new $abstract();
        }

        $params = $constructor->getParameters();
        $args = [];

        foreach ($params as $param) {
            $paramType = $param->getType();

            if (version_compare(PHP_VERSION, '7.1.0') >= 0) {
                if ($paramType instanceof \ReflectionNamedType) {
                    $args[] = $this->resolveWithWiring($paramType->getName());
                }
            }
            else {
                $args[] = $this->resolveWithWiring((string)$paramType);
            }
        }

        return $reflectionClass->newInstanceArgs($args);
    }
}