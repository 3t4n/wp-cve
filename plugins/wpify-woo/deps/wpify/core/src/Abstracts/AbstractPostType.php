<?php

namespace WpifyWooDeps\Wpify\Core\Abstracts;

use Exception;
use WP_Post_Type;
use WpifyWooDeps\Wpify\Core\Traits\CustomFieldsTrait;
/**
 * @package Wpify\Core
 */
abstract class AbstractPostType extends AbstractComponent
{
    use CustomFieldsTrait;
    /** @var string */
    public $model;
    /**
     * If true, the PostType will be registered
     *
     * @var bool
     */
    protected $register_cpt = \true;
    /** @var WP_Post_Type */
    private $post_type;
    /** @var string */
    private $name;
    /** @var array */
    private $args = array();
    /**
     * PostType constructor.
     */
    public function __construct()
    {
        $this->args = $this->post_type_args();
        $this->name = $this->post_type_name();
        $this->model = $this->model();
    }
    /**
     * Get arguments for registering the custom post type
     * The args match register_post_type arguments: https://developer.wordpress.org/reference/functions/register_post_type/
     *
     * @return array
     */
    public abstract function post_type_args() : array;
    /**
     * Set post type name
     *
     * @return string
     */
    public abstract function post_type_name() : string;
    /**
     * Set post type model
     *
     * @return string
     */
    public abstract function model() : string;
    public function init()
    {
        add_action('init', array($this, 'register'));
        $this->init_custom_fields('cpt', $this->name);
        parent::init();
    }
    /**
     * Registers the post type
     *
     * @throws Exception
     */
    public function register()
    {
        if ($this->register_cpt) {
            $this->post_type = register_post_type($this->name, $this->args);
        } else {
            $post_types = get_post_types(array('name' => $this->name), 'objects');
            if (empty($post_types)) {
                throw new Exception(__('Post type is not registered yet and register_post_type is set to false', 'wpify'));
            }
            $this->post_type = $post_types[$this->name];
        }
    }
    /**
     * Gets post type object
     */
    public function get_post_type()
    {
        return $this->post_type;
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
    public function get_args()
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
     * @param bool $register_cpt
     */
    public function set_register_cpt(bool $register_cpt) : void
    {
        $this->register_cpt = $register_cpt;
    }
    /**
     * @param string $singular Singular name of the post type
     * @param string $plural Plural name of the post type
     *
     * @return array
     */
    protected function get_generic_labels(string $singular, string $plural) : array
    {
        $labels = array('name' => \sprintf(_x('%s', 'post type general name', 'wpify'), $plural), 'singular_name' => \sprintf(_x('%s', 'post type singular name', 'wpify'), $singular), 'menu_name' => \sprintf(_x('%s', 'admin menu', 'wpify'), $plural), 'name_admin_bar' => \sprintf(_x('%s', 'add new on admin bar', 'wpify'), $singular), 'add_new' => __('Add New', 'add new', 'wpify'), 'add_new_item' => \sprintf(__('Add New %s', 'wpify'), $singular), 'new_item' => \sprintf(__('New %s', 'wpify'), $singular), 'edit_item' => \sprintf(__('Edit %s', 'wpify'), $singular), 'view_item' => \sprintf(__('View %s', 'wpify'), $singular), 'all_items' => \sprintf(__('All %s', 'wpify'), $plural), 'search_items' => \sprintf(__('Search %s', 'wpify'), $plural), 'parent_item_colon' => \sprintf(__('Parent %s:', 'wpify'), $plural), 'not_found' => \sprintf(__('No %s found.', 'wpify'), $plural), 'not_found_in_trash' => \sprintf(__('No %s found in Trash.', 'wpify'), $plural));
        return $labels;
    }
    /**
     * Set post type args
     *
     * @return array
     */
    protected function taxonomies() : array
    {
        return array();
    }
}
