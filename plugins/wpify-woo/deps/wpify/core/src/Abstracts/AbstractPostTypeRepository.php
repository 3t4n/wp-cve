<?php

namespace WpifyWooDeps\Wpify\Core\Abstracts;

use WpifyWooDeps\Doctrine\Common\Collections\ArrayCollection;
use WP_Query;
use WpifyWooDeps\Wpify\Core\Exceptions\PluginException;
use WpifyWooDeps\Wpify\Core\Interfaces\PostTypeModelInterface;
use WpifyWooDeps\Wpify\Core\Interfaces\RepositoryInterface;
/**
 * @package Wpify\Core
 */
abstract class AbstractPostTypeRepository extends AbstractComponent implements RepositoryInterface
{
    /** @var AbstractPostType */
    private $post_type;
    private $query;
    public function init()
    {
        $this->post_type = $this->post_type();
        parent::init();
    }
    public abstract function post_type();
    /**
     * @param array $args
     *
     * @return ArrayCollection
     */
    public function all($args = array()) : ArrayCollection
    {
        $defaults = array('posts_per_page' => -1);
        $args = wp_parse_args($args, $defaults);
        return $this->find($args);
    }
    public function find($args = array())
    {
        $defaults = array('post_type' => $this->post_type->get_name());
        $args = wp_parse_args($args, $defaults);
        $collection = new ArrayCollection();
        $this->query = new WP_Query($args);
        // The Loop
        while ($this->query->have_posts()) {
            $this->query->the_post();
            global $post;
            $collection->add($this->get($post));
        }
        wp_reset_postdata();
        return $collection;
    }
    /**
     * @param $post
     *
     * @return AbstractPostTypeModel
     *
     * @throws PluginException
     */
    public function get($post) : ?PostTypeModelInterface
    {
        $model = $this->plugin->create_component($this->post_type->model, ['post' => $post, 'post_type' => $this->post_type]);
        $model->init();
        return $model;
    }
    /**
     * @return AbstractPostType
     */
    public function get_post_type()
    {
        return $this->post_type;
    }
    /**
     * @param AbstractPostType $post_type
     */
    public function set_post_type(AbstractPostType $post_type) : void
    {
        $this->post_type = $post_type;
    }
    /**
     * @return string
     */
    public function get_model()
    {
        return $this->post_type->get_model();
    }
    public function get_paginate_links($args = array())
    {
        $pagination = $this->get_pagination();
        $default_args = array('total' => $pagination['total_pages'], 'current' => $pagination['current_page']);
        $args = wp_parse_args($args, $default_args);
        return paginate_links($args);
    }
    public function get_pagination()
    {
        return array('found_posts' => $this->get_query()->found_posts, 'current_page' => $this->get_query()->query_vars['paged'] ?: 1, 'total_pages' => $this->get_query()->max_num_pages, 'per_page' => $this->get_query()->query_vars['posts_per_page']);
    }
    /**
     * @return mixed
     */
    public function get_query()
    {
        return $this->query;
    }
}
