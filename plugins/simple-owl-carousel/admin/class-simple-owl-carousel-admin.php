<?php
/**
 * The admin-specific functionality of the plugin.
 *
 * Defines the plugin name, version, and two examples hooks for how to
 * enqueue the admin-specific stylesheet and JavaScript.
 * 
 * @link       http://presstigers.com
 * @since      1.0.0
 *
 * @package    Simple_Owl_Carousel
 * @subpackage Simple_Owl_Carousel/admin
 * @author     PressTigers <support@presstigers.com>
 */

class Simple_Owl_Carousel_Admin {

    /**
     * The ID of this plugin.
     *
     * @since    1.0.0
     * @access   private
     * @var      string    $plugin_name    The ID of this plugin.
     */
    private $simple_owl_carousel;

    /**
     * The version of this plugin.
     *
     * @since   1.0.0
     * @access  private
     * @var     string  $version    The current version of this plugin.
     */
    private $version;

    /**
     * Initialize the class and set it's properties.
     *
     * @since   1.0.0
     * @param   string  $plugin_name    The name of this plugin.
     * @param   string  $version        The version of this plugin.
     */
    public function __construct($simple_owl_carousel, $version) {

        $this->simple_owl_carousel = $simple_owl_carousel;
        $this->version = $version;
        
        /**
         * The class is responsible for defining shortcode's generator in page and post.
         */
        require_once plugin_dir_path(dirname(__FILE__)) . 'admin/class-simple-owl-carousel-admin-shortcode-generator.php';
        
        /**
         * The class is responsible for defining all the post meta options under 'soc_slider' post type
         */
        require_once plugin_dir_path(dirname(__FILE__)) . 'admin/class-simple-owl-carousel-admin-meta-box-init.php';
    }

    /**
     * Register the stylesheets for the admin area.
     *
     * @since   1.0.0
     */
    public function enqueue_styles() {

        /**
         * This function is provided for demonstration purposes only.
         *
         * An instance of this class should be passed to the run() function
         * defined in Simple_Owl_Carousel_Loader as all of the hooks are defined
         * in that particular class.
         *
         * The Simple_Owl_Carousel_Loader will then create the relationship
         * between the defined hooks and the functions defined in this
         * class.
         */
        wp_enqueue_style( $this->simple_owl_carousel."-fontawsome", '//maxcdn.bootstrapcdn.com/font-awesome/4.6.3/css/font-awesome.min.css', array( ), '4.6.3', 'all' );
        wp_enqueue_style( $this->simple_owl_carousel, plugin_dir_url(__FILE__) . 'css/simple-owl-carousel-admin.css', array( 'thickbox' ), $this->version, 'all' );
    }

    /**
     * Register the JavaScript for the admin area.
     *
     * @since   1.0.0
     */
    public function enqueue_scripts() {

        /**
         * This function is provided for demonstration purposes only.
         *
         * An instance of this class should be passed to the run() function
         * defined in Simple_Owl_Carousel_Loader as all of the hooks are defined
         * in that particular class.
         *
         * The Simple_Owl_Carousel_Loader will then create the relationship
         * between the defined hooks and the functions defined in this
         * class.
         */         
          wp_enqueue_media();
          wp_enqueue_script( $this->simple_owl_carousel. 'admin-scripts', plugin_dir_url(__FILE__) . 'js/simple-owl-carousel-admin.js', array( 'jquery', 'jquery-ui-sortable', 'thickbox','media-upload' ), $this->version, TRUE );
    }

}