<?php
/**
 * The public-facing functionality of the plugin.
 *
 * @link       http://presstigers.com
 * @since      1.0.0
 *
 * @package    Simple_Owl_Carousel
 * @subpackage Simple_Owl_Carousel/public
 * @author     PressTigers <support@presstigers.com>
 */
class Simple_Owl_Carousel_Public
{
    /**
     * The ID of this plugin.
     *
     * @since   1.0.0
     * @access  private
     * @var     string     $plugin_name    The ID of this plugin.
     */
    private $simple_owl_carousel;

    /**
     * The version of this plugin.
     *
     * @since   1.0.0
     * @access  private
     * @var     string      $version    The current version of this plugin.
     */
    private $version;

    /**
     * Initialize the class and set it's properties.
     *
     * @since   1.0.0
     * @param   string  $plugin_name    The name of the plugin.
     * @param   string  $version        The version of this plugin.
     */
    public function __construct($plugin_name, $version) {

        $this->simple_owl_carousel = $plugin_name;
        $this->version = $version;

        /**
         * The class is responsible for defining the post type 'soc_slider'.
         */
        require_once plugin_dir_path(dirname(__FILE__)) . 'includes/class-simple-owl-carousel-post-type.php';

        /**
         * The class is responsible for defining all shortcode of the SOC slider
         */
        require_once plugin_dir_path(dirname(__FILE__)) . 'includes/class-simple-owl-carousel-shortcode.php';
    }

    /**
     * Register the stylesheets for the public-facing side of the site.
     *
     * @since   1.0.0
     */
    public function enqueue_styles()
    {
        wp_enqueue_style($this->simple_owl_carousel.'-owl', plugin_dir_url(__FILE__) . 'css/owl.carousel.min.css', array(), '1.0.0', 'all');
        wp_enqueue_style($this->simple_owl_carousel.'owl-theme', plugin_dir_url(__FILE__) . 'css/owl.theme.default.min.css', array(), '1.0.0', 'all');
        wp_enqueue_style($this->simple_owl_carousel, plugin_dir_url(__FILE__) . 'css/simple-owl-carousel-public.css', array(), $this->version, 'all');
    }

    /**
     * Register the JavaScript for the public-facing side of the site.
     *
     * @since    1.0.0
     */
    public function enqueue_scripts()
    {
        wp_register_script($this->simple_owl_carousel . '-owl-carousel', plugin_dir_url(__FILE__) . 'js/owl.carousel.js', array('jquery'), '1.3.3', false);
        //wp_register_script($this->simple_owl_carousel . '-custom', plugin_dir_url(__FILE__) . 'js/custom.js', array($this->simple_owl_carousel . '-owl-carousel'), '1.0.0', false);
    }
}
