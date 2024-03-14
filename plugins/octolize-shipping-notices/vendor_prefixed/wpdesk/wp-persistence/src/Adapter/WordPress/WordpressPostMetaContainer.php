<?php

namespace OctolizeShippingNoticesVendor\WPDesk\Persistence\Adapter\WordPress;

use OctolizeShippingNoticesVendor\WPDesk\Persistence\ElementNotExistsException;
use OctolizeShippingNoticesVendor\WPDesk\Persistence\FallbackFromGetTrait;
use OctolizeShippingNoticesVendor\WPDesk\Persistence\PersistentContainer;
/**
 * Can store data using WordPress Post metadata.
 * Warning: stored string '' is considered unset.
 *
 * @package WPDesk\Persistence\Wordpress
 */
final class WordpressPostMetaContainer implements \OctolizeShippingNoticesVendor\WPDesk\Persistence\PersistentContainer
{
    use FallbackFromGetTrait;
    /** @var int */
    private $post_id;
    /**
     * @param int $post_id Id of the WordPress post.
     */
    public function __construct(int $post_id)
    {
        $this->post_id = $post_id;
    }
    public function set(string $key, $value)
    {
        if ($value !== null) {
            \update_post_meta($this->post_id, $key, $value);
        } else {
            $this->delete($key);
        }
    }
    public function get($key)
    {
        $meta = \get_post_meta($this->post_id, $key);
        if (\count($meta) === 0) {
            throw new \OctolizeShippingNoticesVendor\WPDesk\Persistence\ElementNotExistsException(\sprintf('Element %s not exists!', $key));
        }
        return $meta[0];
    }
    /**
     * @param string $id
     *
     * @return bool
     */
    public function has($id) : bool
    {
        return \metadata_exists('post', $this->post_id, $id);
    }
    public function delete(string $key)
    {
        \delete_post_meta($this->post_id, $key);
    }
}
