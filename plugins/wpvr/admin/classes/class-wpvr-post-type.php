<?php
if ( ! defined( 'ABSPATH' ) ) exit; // Exit if accessed directly
/**
 * Define the custom post type
 *
 *
 * @link       http://rextheme.com/
 * @since      8.0.0
 *
 * @package    Wpvr
 * @subpackage Wpvr/includes/classes
 */


class WPVR_Post_Type {

    /**
     * The ID of this plugin.
     *
     * @since    8.0.0
     * @access   private
     * @var      string    $plugin_name    The ID of this plugin.
     */
    private $plugin_name;

    /**
     * The version of this plugin.
     *
     * @since    8.0.0
     * @access   private
     * @var      string    $version    The current version of this plugin.
     */
    private $version;

    /**
     * The post type of this plugin.
     *
     * @since 8.0.0
     */
    private $post_type;

    /**
     * Initialize the class and set its properties.
     *
     * @since    8.0.0
     * @param      string    $plugin_name       The name of this plugin.
     * @param      string    $version    The version of this plugin.
     */
    public function __construct($plugin_name, $version, $post_type) {
 
        $this->plugin_name = $plugin_name;
        $this->version = $version;
        $this->post_type = $post_type;

        // Register the post type
        add_action('init', array($this, 'register'));
 
        // Admin set post columns
        add_filter('manage_edit-' . $this->post_type . '_columns', array($this, 'set_columns')) ;

        // Set messages
        add_filter('post_updated_messages', array($this, 'wpvr_post_updated_messages') );
 
        // Admin edit post columns
        add_filter('manage_' . $this->post_type . '_posts_custom_column', array($this, 'edit_columns'));
 
    }

	/**
     * Register the custom post type
     *
     * @since 8.0.0
     */
    public function register()
    {
        $labels = array(
            'name'              => __('Tours', $this->plugin_name),
            'singular_name'     => __('Tours', $this->plugin_name),
            'add_new'           => __('Add New Tour', $this->plugin_name),
            'add_new_item'      => __('Add New Tour', $this->plugin_name),
            'edit_item'         => __('Edit Tour', $this->plugin_name),
            'new_item'          => __('New Tour', $this->plugin_name),
            'view_item'         => __('View Tour', $this->plugin_name),
            'search_items'      => __('Search Wpvr Tour', $this->plugin_name),
            'not_found'         => __('No Wpvr Tour found', $this->plugin_name),
            'not_found_in_trash'=> __('No Wpvr Tour found in Trash', $this->plugin_name),
            'parent_item_colon' => '',
            'all_items'         => __('All Tours', $this->plugin_name),
            'menu_name'         => __('WP VR', $this->plugin_name),
        );

        $args = array(
            'labels'          => $labels,
            'public'          => false,
            'show_ui'         => true,
            'show_in_menu'   	=> false,
            'menu_position'   => 100,
            'supports'        => array( 'title' ),
            'menu_icon'           => plugins_url(). '/wpvr/images/icon.png',
            'capabilities' => array(
                    'edit_post' => 'edit_wpvr_tour',
                    'edit_posts' => 'edit_wpvr_tours',
                    'edit_others_posts' => 'edit_other_wpvr_tours',
                    'publish_posts' => 'publish_wpvr_tours',
                    'read_post' => 'read_wpvr_tour',
                    'read_private_posts' => 'read_private_wpvr_tours',
                    'delete_post' => 'delete_wpvr_tour'
            ),
            'map_meta_cap'    => true,
        );

        /**
         * Documentation : https://codex.wordpress.org/Function_Reference/register_post_type
         */
        register_post_type($this->post_type, $args);
    }

    /**
     * @param $columns
     * @return mixed
     *
     * Choose the columns you want in
     * the admin table for this post
     * @since 8.0.0
     */
    public function set_columns($columns) {
        // Set/unset post type table columns 
        $columns = array(
            'cb'        => '<input type="checkbox" />',
            'title'     => __('Title', $this->plugin_name),
            'shortcode' => __('Shortcodes', $this->plugin_name),
            'author'    => __('Author', $this->plugin_name),
            'date'      => __('Date', $this->plugin_name)
        );
        return $columns;
    }
 
    /**
     * @param $column
     * @param $post_id
     *
     * Edit the contents of each column in
     * the admin table for this post
     * @since 8.0.0
     */
    public function edit_columns($column) {
        // Post type table column content 
        $post = get_post();

        switch ($column) {
            case 'shortcode':
                echo '<code>[wpvr id="' . $post->ID . '"]</code>';
                break;
            default:
                break;
        }
    }

    /**
     * Sets the messages for the custom post type
     *
     * @since 8.0.0
     */
    public function wpvr_post_updated_messages($messages)
    {
        $messages[$this->post_type][1] = __('WP VR item updated.', $this->plugin_name);
        $messages[$this->post_type][4] = __('WP VR item updated.', $this->plugin_name);

        return $messages;
    }

}
