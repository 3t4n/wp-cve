<?php
declare( strict_types=1 );

namespace WPDesk\ShopMagic\Components\Persistence;

use ShopMagicVendor\WPDesk\Persistence\ElementNotExistsException;
use ShopMagicVendor\WPDesk\Persistence\FallbackFromGetTrait;
use ShopMagicVendor\WPDesk\Persistence\PersistentContainer;

class JsonSerializedOptionsContainer implements PersistentContainer {

    use FallbackFromGetTrait;
    /** @var string */
    private $option_name;
    /** @var array */
    private $option_value;

    public function __construct($option_name)
    {
        $this->option_name = $option_name;
    }
    /**
     * @return void
     */
    private function refresh_value()
    {
        $this->option_value = json_decode(get_option($this->option_name, '[]'), true);
    }
    public function set(string $id, $value)
    {
        $this->refresh_value();
        $this->option_value[$id] = $value;
        update_option($this->option_name, json_encode($this->option_value));
    }
    public function delete(string $id)
    {
        $this->refresh_value();
        unset($this->option_value[$id]);
        update_option($this->option_name, json_encode($this->option_value));
    }
    public function has($key) : bool
    {
        $this->refresh_value();
        return isset($this->option_value[$key]);
    }
    public function get($id)
    {
        $this->refresh_value();
        if ($this->has($id)) {
	        return $this->option_value[$id];
        }

	    throw new ElementNotExistsException(\sprintf('Element %s not exists in site\'s options!', $id));
    }
}
