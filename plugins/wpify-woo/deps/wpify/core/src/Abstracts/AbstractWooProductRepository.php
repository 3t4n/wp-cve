<?php

namespace WpifyWooDeps\Wpify\Core\Abstracts;

use WpifyWooDeps\Doctrine\Common\Collections\ArrayCollection;
use WpifyWooDeps\Wpify\Core\Exceptions\PluginException;
use WpifyWooDeps\Wpify\Core\Interfaces\PostTypeModelInterface;
use WpifyWooDeps\Wpify\Core\Interfaces\RepositoryInterface;
/**
 * @package Wpify\Core
 */
abstract class AbstractWooProductRepository extends AbstractPostTypeRepository implements RepositoryInterface
{
    /**
     * @param array $args
     *
     * @return ArrayCollection
     */
    public function all($args = array()) : ArrayCollection
    {
        $defaults = array('limit' => -1);
        $args = wp_parse_args($args, $defaults);
        return $this->find($args);
    }
    public function find($args = array())
    {
        $collection = new ArrayCollection();
        // The Loop
        foreach (wc_get_products($args) as $order) {
            $collection->add($this->get($order));
        }
        wp_reset_postdata();
        return $collection;
    }
    /**
     * @param $post
     *
     * @return AbstractPostTypeModel
     * @throws PluginException
     */
    public function get($post) : ?PostTypeModelInterface
    {
        $model = $this->plugin->create_component($this->get_post_type()->model, array('product' => $post, 'post_type' => $this->get_post_type()));
        $model->init();
        return $model;
    }
}
