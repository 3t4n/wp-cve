<?php

namespace UpsFreeVendor\WPDesk\Persistence\Decorator;

use UpsFreeVendor\WPDesk\Persistence\ElementNotExistsException;
use UpsFreeVendor\WPDesk\Persistence\FallbackFromGetTrait;
use UpsFreeVendor\WPDesk\Persistence\PersistentContainer;
/**
 * Store values as serialized. Thanks to this the strict typing can be used.
 *
 * @package WPDesk\Persistence\Decorator
 */
class SerializedPersistentContainer implements \UpsFreeVendor\WPDesk\Persistence\PersistentContainer
{
    use FallbackFromGetTrait;
    private $container;
    public function __construct(\UpsFreeVendor\WPDesk\Persistence\PersistentContainer $container)
    {
        $this->container = $container;
    }
    public function get($id)
    {
        if ($this->container->has($id)) {
            return \unserialize($this->container->get($id));
        }
        throw new \UpsFreeVendor\WPDesk\Persistence\ElementNotExistsException(\sprintf('Element %s not exists!', $id));
    }
    public function set(string $id, $value)
    {
        if ($value === null) {
            $this->delete($id);
        } else {
            $this->container->set($id, \serialize($value));
        }
    }
    public function delete(string $id)
    {
        $this->container->delete($id);
    }
    public function has($id) : bool
    {
        return $this->container->has($id);
    }
}
