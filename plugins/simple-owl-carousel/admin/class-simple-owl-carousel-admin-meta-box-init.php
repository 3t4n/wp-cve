<?php if (!defined('ABSPATH')) { exit; } // Exit if accessed directly
/**
 * Simple_Owl_Carousel_Admin_Meta_Box_Init Class
 * 
 * @link       http://presstigers.com
 * @since       1.0.0
 *
 * @package     Simple_Owl_Carousel
 * @subpackage  Simple_Owl_Carousel/admin 
 * @author      PressTigers <support@presstigers.com>
 */

class Simple_Owl_Carousel_Admin_Meta_Box_Init {

    /**
     * Initialize the class and set it's properties.
     *
     * @since   1.0.0
     */
    public function __construct() {

        //Including Meta Box of 'soc_slider' Custom Post Type 
        require_once plugin_dir_path(__FILE__) . 'partials/meta-boxes/class-simple-owl-carousel-meta-box-slider.php';

        // Check If SOC's Meta Box Class Exists
        if (class_exists('Simple_Owl_Carousel_Meta_Box_Slider')) {

            // Initialize SOC's Meta Box Slider Class Object
            new Simple_Owl_Carousel_Meta_Box_Slider();
        }
    }

}

new Simple_Owl_Carousel_Admin_Meta_Box_Init();