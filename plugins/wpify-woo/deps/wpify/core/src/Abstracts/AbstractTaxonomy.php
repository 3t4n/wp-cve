<?php

namespace WpifyWooDeps\Wpify\Core\Abstracts;

use WP_Taxonomy;
use WpifyWooDeps\Wpify\Core\Exceptions\PluginException;
use WpifyWooDeps\Wpify\Core\Interfaces\TaxonomyInterface;
use WpifyWooDeps\Wpify\Core\Traits\CustomFieldsTrait;
/**
 * @package Wpify\Core
 */
abstract class AbstractTaxonomy extends AbstractComponent implements TaxonomyInterface
{
    use CustomFieldsTrait;
    /** @var string */
    public $model;
    /** @var string */
    private $post_type;
    /** @var WP_Taxonomy */
    private $taxonomy;
    /** @var string */
    private $name;
    /** @var array */
    private $args = array();
    public function __construct()
    {
        $this->args = $this->taxonomy_args();
        $this->name = $this->taxonomy_name();
        $this->model = $this->model();
        $this->post_type = $this->post_type();
    }
    public abstract function taxonomy_args() : array;
    public abstract function taxonomy_name() : string;
    public abstract function model() : string;
    /**
     * Get the post types for taxonomy - can be either string or array of the post type names
     *
     * @return string | array
     */
    public abstract function post_type();
    public function init()
    {
        add_action('init', array($this, 'register'));
        $this->init_custom_fields('taxonomy', $this->name);
        parent::init();
    }
    /**
     * Registers the post type
     */
    public function register()
    {
        if (\is_array($this->post_type)) {
            foreach ($this->post_type as $post_type) {
                $this->register_taxonomy_for_post_type($post_type);
            }
        } else {
            $this->register_taxonomy_for_post_type($this->post_type);
        }
    }
    /**
     * Register taxonomy for post type
     *
     * @param $post_type
     *
     * @throws PluginException
     */
    private function register_taxonomy_for_post_type($post_type)
    {
        $post_type = $this->plugin->create_component($post_type);
        if (!taxonomy_exists($this->name)) {
            $this->taxonomy = register_taxonomy($this->name, $post_type->name, $this->args);
        }
        register_taxonomy_for_object_type($this->name, $post_type->name);
    }
    /**
     * Gets post type object
     */
    public function get_taxonomy()
    {
        return $this->taxonomy;
    }
    public function get_model()
    {
        return $this->model;
    }
    public function set_model(string $model)
    {
        $this->model = $model;
    }
    /**
     * @return string
     */
    public function get_name() : string
    {
        return $this->name;
    }
    /**
     * @param string $name
     */
    public function set_name(string $name) : void
    {
        $this->name = $name;
    }
    /**
     * @param array $args
     */
    public function get_args() : array
    {
        return $this->args;
    }
    /**
     * @param array $args
     */
    public function set_args(array $args) : void
    {
        $this->args = $args;
    }
    /**
     * @param string $singular Singular name of the taxonomy
     * @param string $plural Plural name of the taxonomy
     */
    protected function get_generic_labels(string $singular, string $plural) : array
    {
        $labels = array('name' => \sprintf(_x('%s', 'Taxonomy General Name', 'wpify'), $plural), 'singular_name' => \sprintf(_x('%s', 'Taxonomy Singular Name', 'wpify'), $singular), 'menu_name' => \sprintf(__('%s', 'wpify'), $singular), 'all_items' => \sprintf(__('All %s', 'wpify'), $plural), 'parent_item' => \sprintf(__('Parent %s', 'wpify'), $singular), 'parent_item_colon' => \sprintf(__('Parent %s:', 'wpify'), $singular), 'new_item_name' => \sprintf(__('New %s Name', 'wpify'), $singular), 'add_new_item' => \sprintf(__('Add New %s', 'wpify'), $singular), 'edit_item' => \sprintf(__('Edit %s', 'wpify'), $singular), 'update_item' => \sprintf(__('Update %s', 'wpify'), $singular), 'view_item' => \sprintf(__('View %s', 'wpify'), $singular), 'separate_items_with_commas' => \sprintf(__('Separate %s with commas', 'wpify'), $plural), 'add_or_remove_items' => \sprintf(__('Add or remove %s', 'wpify'), $plural), 'choose_from_most_used' => __('Choose from the most used', 'wpify'), 'popular_items' => \sprintf(__('Popular %s', 'wpify'), $plural), 'search_items' => \sprintf(__('Search %s', 'wpify'), $plural), 'not_found' => __('Not Found', 'wpify'), 'no_terms' => \sprintf(__('No %s', 'wpify'), $plural), 'items_list' => \sprintf(__('%s list', 'wpify'), $plural), 'items_list_navigation' => \sprintf(__('%s list navigation', 'wpify'), $plural));
        return $labels;
    }
}
