<?php

/**
 * The public-facing functionality of the plugin.
 *
 * @link       https://objectiv.co
 * @since      1.0.0
 *
 * @package    Simple_Content_Templates
 * @subpackage Simple_Content_Templates/public
 */

/**
 * The public-facing functionality of the plugin.
 *
 * Defines the plugin name, version, and two examples hooks for how to
 * enqueue the dashboard-specific stylesheet and JavaScript.
 *
 * @package    Simple_Content_Templates
 * @subpackage Simple_Content_Templates/public
 * @author     Clifton Griffin <clif@cgd.io>
 */
class Simple_Content_Templates_Public
{

    /**
     * The ID of this plugin.
     *
     * @since    1.0.0
     * @access   private
     * @var      string    $Simple_Content_Templates    The ID of this plugin.
     */
    private $plugin_name;

    /**
     * The version of this plugin.
     *
     * @since    1.0.0
     * @access   private
     * @var      string    $version    The current version of this plugin.
     */
    private $version;

    /**
     * Main instance of this plugin.
     *
     * @since    1.0.0
     * @access   private
     * @var      string    $plugin_name    The ID of this plugin.
     */
    private $plugin;

    /**
     * Initialize the class and set its properties.
     *
     * @since    1.0.0
     * @param      string    $Simple_Content_Templates       The name of the plugin.
     * @param      string    $version    The version of this plugin.
     */
    public function __construct($Simple_Content_Templates, $version, $plugin)
    {

        $this->plugin_name  = $Simple_Content_Templates;
        $this->version = $version;
        $this->plugin = $plugin;
    }

    /**
     * Registers the post type
     *
     * @access public
     * @return void
     */
    function register_post_type()
    {

        // Register Post Type
        $menu_icon = "dashicons-welcome-write-blog";

        register_post_type(
            $this->plugin->post_type,
            array(
                'label' => 'Simple Content Templates',
                'description' => '',
                'public' => false,
                'publicly_queryable' => false,
                'has_archive' => false,
                'show_ui' => true,
                'show_in_menu' => true,
                'capability_type' => 'post',
                'menu_icon' => $menu_icon,
                'hierarchical' => false,
                'rewrite' => array('slug' => ''),
                'query_var' => true,
                'exclude_from_search' => true,
                'show_in_nav_menus' => false,
                'supports' =>
                    array(
                        'title',
                        'editor',
                        'excerpt',
                        'comments',
                        'revisions'
                    ),
                'labels' =>
                    array (
                        'name' => 'Simple Content Templates',
                        'singular_name' => 'Content Template',
                        'menu_name' => 'Content Templates',
                        'add_new' => 'Add Template',
                        'add_new_item' => 'Add New Template',
                        'edit' => 'Edit',
                        'edit_item' => 'Edit Template',
                        'new_item' => 'New Template',
                        'view' => 'View Template',
                        'view_item' => 'View Template',
                        'search_items' => 'Search Templates',
                        'not_found' => 'No Templates Found',
                        'not_found_in_trash' => 'No Templates Found in Trash',
                        'parent' => 'Parent Template',
                    ),
            )
        );
    }
}
