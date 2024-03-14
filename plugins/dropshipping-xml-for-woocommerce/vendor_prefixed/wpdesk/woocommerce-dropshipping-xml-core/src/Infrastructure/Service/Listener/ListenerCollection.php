<?php

namespace DropshippingXmlFreeVendor\WPDesk\Library\DropshippingXmlCore\Infrastructure\Service\Listener;

use DropshippingXmlFreeVendor\WPDesk\Library\DropshippingXmlCore\Infrastructure\Service\Listener\Abstraction\AbstractServiceListener;
use OutOfBoundsException;
use RuntimeException;
use DropshippingXmlFreeVendor\WPDesk\Library\DropshippingXmlCore\Infrastructure\Service\Listener\Abstraction\ListenerCollectionInterface;
/**
 * Class ListenerCollection
 * @package WPDesk\Library\DropshippingXmlCore\Infrastructure\Service\Listener
 */
class ListenerCollection implements \DropshippingXmlFreeVendor\WPDesk\Library\DropshippingXmlCore\Infrastructure\Service\Listener\Abstraction\ListenerCollectionInterface
{
    /**
     * @var AbstractServiceListener[]
     */
    private $listeners = [];
    public function add(array $listeners)
    {
        foreach ($listeners as $class_name) {
            $this->add_listener($class_name);
        }
    }
    public function has(string $class_name) : bool
    {
        return isset($this->listeners[$class_name]);
    }
    public function get(string $class_name)
    {
        if ($this->has($class_name)) {
            return $this->listeners[$class_name];
        }
        throw new \OutOfBoundsException('Error, element ' . $class_name . ' not exists in the collection');
    }
    public function get_all() : array
    {
        return $this->listeners;
    }
    private function add_listener($class_name)
    {
        if (\is_string($class_name) && \class_exists($class_name) && \is_subclass_of($class_name, \DropshippingXmlFreeVendor\WPDesk\Library\DropshippingXmlCore\Infrastructure\Service\Listener\Abstraction\AbstractServiceListener::class)) {
            $this->listeners[$class_name] = new $class_name();
        } else {
            throw new \RuntimeException('Listener class should implements ' . \DropshippingXmlFreeVendor\WPDesk\Library\DropshippingXmlCore\Infrastructure\Service\Listener\Abstraction\AbstractServiceListener::class);
        }
    }
}
