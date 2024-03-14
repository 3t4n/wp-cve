<?php if (!defined('ABSPATH')) { exit; } // Exit if accessed directly
/**
 * Simple_Owl_Carousel_Post_Type Class
 * 
 * This class is used to create custom post type for soc slider.
 *
 * @link        http://presstigers.com
 * @since       1.0.0
 *
 * @package     Simple_Owl_Carousel
 * @subpackage  Simple_Owl_Carousel/includes 
 * @author      PressTigers <support@presstigers.com>
 */
class Simple_Owl_Carousel_Post_Type
{
    /**
     * Initialize the class and set it's properties.
     *
     * @since   1.0.0
     */
    public function __construct()
    {
        // Add Hook into the 'init()' Action
        add_action('init', array($this, 'simple_owl_carousel_init'));

        // Add Hook into the 'init()' action
        add_action('admin_init', array($this, 'simple_owl_carousel_admin_init'));
    }

    /**
     * WordPress core launches at 'init' points
     *          
     * @since   1.0.0
     */
    public function simple_owl_carousel_init()
    {
        $this->create_post_type();

        // Flush Rewrite Rules 
        flush_rewrite_rules();
    }

    /**
     * Create_post_type function.
     *
     * @since   1.0.0
     */
    public function create_post_type()
    {
        if (post_type_exists("soc_slider"))
            return;

        /**
         * Post Type -> soc_slider
         */
        $singular = __('Slider', 'simple-owl-carousel');
        $plural = __('Sliders', 'simple-owl-carousel');

        $rewrite = array(
            'slug' => _x('soc', 'SOC permalink - resave permalinks after changing this', 'simple-owl-carousel'),
            'with_front' => FALSE,
            'feeds' => FALSE,
            'pages' => FALSE,
            'hierarchical' => FALSE,
        );

        // Post Type -> SOC Slider -> Labels
        $slider_labels = array(
            'name' => $plural,
            'singular_name' => $singular,
            'menu_name' => __('Simple Owl Carousel', 'simple-owl-carousel'),
            'all_items' => sprintf(__('All %s', 'simple-owl-carousel'), $plural),
            'add_new' => __('Add New', 'simple-owl-carousel'),
            'add_new_item' => sprintf(__('Add %s', 'simple-owl-carousel'), $singular),
            'edit' => __('Edit', 'simple-owl-carousel'),
            'edit_item' => sprintf(__('Edit %s', 'simple-owl-carousel'), $singular),
            'new_item' => sprintf(__('New %s', 'simple-owl-carousel'), $singular),
            'view' => sprintf(__('View %s', 'simple-owl-carousel'), $singular),
            'view_item' => sprintf(__('View %s', 'simple-owl-carousel'), $singular),
            'search_items' => sprintf(__('Search %s', 'simple-owl-carousel'), $plural),
            'not_found' => sprintf(__('No %s found', 'simple-owl-carousel'), $plural),
            'not_found_in_trash' => sprintf(__('No %s found in trash', 'simple-owl-carousel'), $plural),
            'parent' => sprintf(__('Parent %s', 'simple-owl-carousel'), $singular)
        );

        // Post Type -> SOC Slider -> Arguments
        $slider_args = array(
            'labels' => $slider_labels,
            'description' => sprintf(__('This is where you can create and manage %s.', 'simple-owl-carousel'), $plural),
            'public' => TRUE,
            'show_ui' => TRUE,
            'capability_type' => 'post',
            'map_meta_cap' => TRUE,
            'publicly_queryable' => TRUE,
            'exclude_from_search' => TRUE,
            'hierarchical' => FALSE,
            'rewrite' => array(
                'slug' => _x('soc', 'SOC permalink - resave permalinks after changing this', 'simple-owl-carousel'),
                'hierarchical' => TRUE,
                'with_front' => FALSE
            ),
            'query_var' => TRUE,
            'can_export' => TRUE,
            'supports' => array('title'),
            'has_archive' => TRUE,
            'show_in_nav_menus' => TRUE,
        );

        // Register SOC Slider Post Type
        register_post_type("soc_slider", apply_filters("register_post_type_soc_slider", $slider_args));
    }

    /**
     * A function hook that the WP core launches at 'admin_init' points
     * 
     * @since   1.0.0
     */
    public function simple_owl_carousel_admin_init()
    {
        // Hook - Shortcode -> Add New Column
        add_filter('manage_soc_slider_posts_columns', array($this, 'soc_slider_columns'));

        // Hook - Shortcode -> Add Value to New Column
        add_action('manage_soc_slider_posts_custom_column', array($this, 'soc_slider_columns_value'));
    }

    /**
     * Add custom column for 'SOC' shortcode 
     *
     * @since   1.0.0
     * @param   $columns   Custom Column 
     *  
     * @return  $columns   Custom Column
     */
    public function soc_slider_columns($columns)
    {
        $columns['shortcode'] = __('Shortcode', 'simple-owl-carousel');
        return $columns;
    }

    /**
     * Add custom column's value
     *
     * @since   1.0.0
     * @param   $name   custom column's name
     *  
     * @return  void
     */
    public function soc_slider_columns_value($name)
    {
        global $post;
        switch ($name) {
            case 'shortcode':
                echo ' <b> [soc_slider_shortcode id="' . intval( $post->ID ) . '"] </b>';
                break;
        }
    }
}
new Simple_Owl_Carousel_Post_Type();