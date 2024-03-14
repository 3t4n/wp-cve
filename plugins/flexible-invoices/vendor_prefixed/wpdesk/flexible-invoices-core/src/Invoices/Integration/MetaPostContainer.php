<?php

namespace WPDeskFIVendor\WPDesk\Library\FlexibleInvoicesCore\Integration;

use WPDeskFIVendor\WPDesk\Library\FlexibleInvoicesAbstracts\Containers\MetaContainer;
/**
 * Simple post meta container.
 *
 * @package WPDesk\Library\FlexibleInvoicesCore\Integration
 */
class MetaPostContainer implements \WPDeskFIVendor\WPDesk\Library\FlexibleInvoicesAbstracts\Containers\MetaContainer
{
    /**
     * @var int
     */
    private $post_id;
    /**
     * @param int $post_id
     */
    public function __construct(int $post_id)
    {
        $this->post_id = $post_id;
    }
    /**
     * @param string $id Meta key.
     *
     * @return mixed
     */
    public function get($id)
    {
        return \get_post_meta($this->post_id, $id, \true);
    }
    /**
     * @param string $name
     * @param mixed  $default Default value.
     *
     * @return mixed
     */
    public function get_fallback($name, $default = null)
    {
        $value = $this->get($name);
        if (empty($value) && $default !== null) {
            return $default;
        }
        return $value;
    }
    /**
     * @param string $id    Meta key.
     * @param mixed  $value Value.
     * @param bool   $add   Should use add.
     *
     * @return void
     */
    public function set($id, $value, bool $add = \false)
    {
        if ($add) {
            \add_post_meta($this->post_id, $id, $value);
        }
        \update_post_meta($this->post_id, $id, $value);
    }
    /**
     * @param string $id Meta key.
     *
     * @return bool
     */
    public function has($id) : bool
    {
        $value = \get_post_meta($this->post_id, $id, \true);
        return !empty($value);
    }
    /**
     * @param string $id Meta key.
     *
     * @return bool
     */
    public function delete($id) : bool
    {
        return \delete_post_meta($this->post_id, $id);
    }
}
