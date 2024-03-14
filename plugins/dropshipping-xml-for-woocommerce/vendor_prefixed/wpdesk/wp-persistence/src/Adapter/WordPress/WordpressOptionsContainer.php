<?php

namespace DropshippingXmlFreeVendor\WPDesk\Persistence\Adapter\WordPress;

use DropshippingXmlFreeVendor\WPDesk\Persistence\ElementNotExistsException;
use DropshippingXmlFreeVendor\WPDesk\Persistence\PersistentContainer;
/**
 * Can store data using WordPress options.
 *
 * @package WPDesk\Persistence\Wordpress
 */
final class WordpressOptionsContainer implements \DropshippingXmlFreeVendor\WPDesk\Persistence\PersistentContainer
{
    /** @var string */
    private $namespace;
    /**
     * @param string $namespace Namespace so options in different containers would not conflict.
     */
    public function __construct($namespace = '')
    {
        $this->namespace = $namespace;
    }
    public function set($id, $value)
    {
        \update_option($this->prepare_key_name($id), $value);
    }
    public function delete($id)
    {
        \delete_option($this->prepare_key_name($id));
    }
    public function has($key)
    {
        $fake_default = \uniqid();
        return $fake_default !== \get_option($this->prepare_key_name($key), $fake_default);
    }
    /**
     * Prepare name for key.
     *
     * @param string $key Key.
     *
     * @return string
     */
    private function prepare_key_name($key)
    {
        return \sanitize_key($this->namespace . $key);
    }
    public function get($id)
    {
        $fake_default = \uniqid();
        $value = \get_option($this->prepare_key_name($id), $fake_default);
        if ($fake_default === $value) {
            throw new \DropshippingXmlFreeVendor\WPDesk\Persistence\ElementNotExistsException(\sprintf('Element %s not exists!', $id));
        }
        return $value;
    }
}
