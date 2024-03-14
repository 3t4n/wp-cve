<?php if (!defined('ABSPATH')) { exit; } // Exit if accessed directly
/**
 * Simple_Owl_Carousel_Admin_Shortcode_Generator Class
 *
 * Define the shortcode button and parameters in TinyMCE editor. Also creates 
 * shortcodes with these given parameters.
 * 
 * @link       http://presstigers.com
 * @since      1.0.0
 * 
 * @package    Simple_Owl_Carousel
 * @subpackage Simple_Owl_Carousel/admin
 * @author     PressTigers <support@presstigers.com>
 */

class Simple_Owl_Carousel_Admin_Shortcode_Generator {

    /**
     * Initialize the class and set its properties.
     * 
     * @since   1.0.0
     */
    public function __construct() {

        // Action -> Add TinyMCE Button. 
        add_action('admin_head', array($this, 'soc_add_tinymce_button'));
    }

    /**
     * Add filters for the TinyMCE buttton.
     *
     * @since   1.0.0
     */
    public function soc_add_tinymce_button() {
        global $typenow;

        // Check user permissions
        if (!current_user_can('edit_posts') && !current_user_can('edit_pages')) {
            return;
        }

        // Verify the post type
        if (!in_array($typenow, array('post', 'page'))) {
            return;
        }

        // Check if WYSIWYG is enabled
        if ('true' === get_user_option('rich_editing')) {
            add_filter('mce_external_plugins', array($this, 'soc_add_tinymce_plugin'));
            add_filter('mce_buttons', array($this, 'soc_register_tinymce_button'));
        }
    }

    /**
     * Loads the TinyMCE button js file.
     * 
     * This function specifies the path of JS for shortcode generator for TinyMCE.
     *
     * @since   1.0.0
     * 
     * @param   array   $plugin_array 
     * @return  array   $plugin_array
     */
    function soc_add_tinymce_plugin($plugin_array) {
        $plugin_array['soc_shortcodes_mce_button'] = plugins_url('/js/simple-owl-carousel-admin-shortcodes-generator.js', __FILE__);
        return $plugin_array;
    }

    /**
     * Adds the TinyMCE button to the post, page editor buttons.
     *
     * @since   1.0.0
     * 
     * @param   array   $buttons     TinyMCE buttons
     * @return  array   $buttons     Append Soc Slider Shortcode Generator Button with TinyMCE Editor Button List. 
     *                               
     */
    function soc_register_tinymce_button($buttons) {
        array_push($buttons, 'soc_shortcodes_mce_button');
        return $buttons;
    }

}

new Simple_Owl_Carousel_Admin_Shortcode_Generator();