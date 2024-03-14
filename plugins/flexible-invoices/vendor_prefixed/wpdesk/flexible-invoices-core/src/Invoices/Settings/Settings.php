<?php

namespace WPDeskFIVendor\WPDesk\Library\FlexibleInvoicesCore\Settings;

use WPDeskFIVendor\WPDesk\Persistence\PersistentContainer;
/**
 * WordPress setting container.
 *
 * @package WPDesk\Library\FlexibleInvoicesCore\Settings
 */
class Settings implements \WPDeskFIVendor\WPDesk\Persistence\PersistentContainer
{
    /**
     * @var string
     */
    private $prefix = 'inspire_invoices_';
    /**
     * @param $prefix
     */
    public function set_prefix($prefix)
    {
        $this->prefix = $prefix;
    }
    /**
     * @param string $id      Setting name.
     * @param null   $default Default value.
     *
     * @return string|null
     */
    public function get($id, $default = null)
    {
        $value = \get_option($this->prefix . $id, $default);
        return $this->get_real_value($value);
    }
    /**
     *
     * For backward compatibility, it returns the checkbox values for the new schema.
     *
     * @param mixed $value
     *
     * @return mixed
     */
    private function get_real_value($value)
    {
        if (\is_string($value)) {
            if ($value === 'on') {
                return 'yes';
            }
            if ($value === 'off') {
                return 'no';
            }
        }
        return $value;
    }
    /**
     * @param string $id    Setting name.
     * @param null   $value Value.
     *
     * @return bool
     */
    public function set($id, $value) : bool
    {
        return \update_option($this->prefix . $id, $value);
    }
    /**
     * @param string $id Setting name.
     *
     * @return bool
     */
    public function has($id) : bool
    {
        $option = \get_option($this->prefix . $id);
        return !empty($option);
    }
    /**
     * @param string $id Setting name.
     */
    public function delete($id)
    {
        \delete_option($this->prefix . $id);
    }
    /**
     * @param string $id
     * @param null   $fallback
     *
     * @return mixed
     */
    public function get_fallback(string $id, $fallback = null)
    {
        return $this->has($id) ? $this->get($id) : $fallback;
    }
}
