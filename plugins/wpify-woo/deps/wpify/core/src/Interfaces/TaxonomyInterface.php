<?php

namespace WpifyWooDeps\Wpify\Core\Interfaces;

/**
 * @package Wpify\Core
 */
interface TaxonomyInterface
{
    public function taxonomy_args() : array;
    public function taxonomy_name() : string;
    public function model() : string;
    /**
     * Get the post types for taxonomy - can be either string or array of the post type names
     *
     * @return string | array
     */
    public function post_type();
    public function set_args(array $args) : void;
    public function get_args() : array;
    public function set_name(string $name) : void;
    public function get_name() : string;
    public function set_model(string $model);
    public function get_model();
    public function get_taxonomy();
    public function register();
    public function setup();
}
