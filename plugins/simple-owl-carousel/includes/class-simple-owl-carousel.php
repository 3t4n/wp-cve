<?php
/**
 * The file that defines the core plugin class
 *
 * A class definition that includes attributes and functions used across both the
 * public-facing side of the site and the admin area.This is used to define internationalization, 
 * admin-specific hooks, and public-facing site hooks.
 * 
 * Also maintains the unique identifier of this plugin as well as the current
 * version of the plugin.
 *
 * @link       https://wordpress.org/plugins/simple-owl-carousel/
 * @since      1.0.0
 *
 * @package    Simple_Owl_Carousel
 * @subpackage Simple_Owl_Carousel/includes
 * @author     PressTigers <support@presstigers.com>
 */

class Simple_Owl_Carousel {

    /**
     * The loader that's responsible for maintaining and registering all hooks that power
     * the plugin.
     *
     * @since   1.0.0
     * @access  protected
     * @var     Simple_Owl_Carousel_Loader    $loader    Maintains and registers all hooks for the plugin.
     */
    protected $loader;

    /**
     * The unique identifier of this plugin.
     *
     * @since   1.0.0
     * @access  protected
     * @var     string      0$plugin_name    The string used to uniquely identify this plugin.
     */
    protected $simple_owl_carousel;

    /**
     * The current version of the plugin.
     *
     * @since    1.0.0
     * @access   protected
     * @var      string    $version    The current version of the plugin.
     */
    protected $version;

    /**
     * Define the core functionality of the plugin.
     *
     * Set the plugin name and the plugin version that can be used throughout the plugin.
     * Load the dependencies, define the locale, and set the hooks for the admin area and
     * the public-facing side of the site.
     *
     * @since   1.0.0
     */
    public function __construct() {

        $this->simple_owl_carousel = 'simple-owl-carousel';
        $this->version = '1.1.1';

        $this->load_dependencies();
        $this->set_locale();
        $this->define_admin_hooks();
        $this->define_public_hooks();
    }

    /**
     * Load the required dependencies for this plugin.
     *
     * Include the following files that make up the plugin:
     *
     * - Simple_Owl_Carousel_Loader. Orchestrates the hooks of the plugin.
     * - Simple_Owl_Carousel_i18n. Defines internationalization functionality.
     * - Simple_Owl_Carousel_Admin. Defines all hooks for the admin area.
     * - Simple_Owl_Carousel_Public. Defines all hooks for the public side of the site.
     *
     * Create an instance of the loader which will be used to register the hooks
     * with WordPress.
     *
     * @since    1.0.0
     * @access   private
     */
    private function load_dependencies() {
        
        /**
         * The class responsible for orchestrating the actions and filters of the
         * core plugin.
         */
        require_once plugin_dir_path(dirname(__FILE__)) . 'includes/class-simple-owl-carousel-loader.php';

        /**
         * The class responsible for defining internationalization functionality
         * of the plugin.
         */
        require_once plugin_dir_path(dirname(__FILE__)) . 'includes/class-simple-owl-carousel-i18n.php';

        /**
         * The class responsible for defining all actions that occur in the admin area.
         */
        require_once plugin_dir_path(dirname(__FILE__)) . 'admin/class-simple-owl-carousel-admin.php';

        /**
         * The class responsible for defining all actions that occur in the public-facing
         * side of the site.
         */
        require_once plugin_dir_path(dirname(__FILE__)) . 'public/class-simple-owl-carousel-public.php';

        $this->loader = new Simple_Owl_Carousel_Loader();
    }

    /**
     * Define the locale for this plugin for internationalization.
     *
     * Uses the Simple_Owl_Carousel_i18n class in order to set the domain and to register the hook
     * with WordPress.
     *
     * @since   1.0.0
     * @access  private
     */
    private function set_locale() {

        $plugin_i18n = new Simple_Owl_Carousel_i18n();

        $this->loader->add_action('plugins_loaded', $plugin_i18n, 'load_plugin_textdomain');
    }

    /**
     * Register all of the hooks related to the admin area functionality
     * of the plugin.
     *
     * @since   1.0.0
     * @access  private
     */
    private function define_admin_hooks() {

        $plugin_admin = new Simple_Owl_Carousel_Admin($this->get_simple_owl_carousel(), $this->get_version());

        $this->loader->add_action('admin_enqueue_scripts', $plugin_admin, 'enqueue_styles');
        $this->loader->add_action('admin_enqueue_scripts', $plugin_admin, 'enqueue_scripts');
    }

    /**
     * Register all of the hooks related to the public-facing functionality
     * of the plugin.
     *
     * @since   1.0.0
     * @access  private
     */
    private function define_public_hooks() {

        $plugin_public = new Simple_Owl_Carousel_Public($this->get_simple_owl_carousel(), $this->get_version());

        $this->loader->add_action('wp_enqueue_scripts', $plugin_public, 'enqueue_styles');
        $this->loader->add_action('wp_enqueue_scripts', $plugin_public, 'enqueue_scripts');
    }

    /**
     * Run the loader to execute all of the hooks with WordPress.
     *
     * @since   1.0.0
     */
    public function run() {
        $this->loader->run();
    }

    /**
     * The name of the plugin used to uniquely identify it within the context of
     * WordPress and to define internationalization functionality.
     *
     * @since   1.0.0
     * @return  string  The name of the plugin.
     */
    public function get_simple_owl_carousel() {
        return $this->simple_owl_carousel;
    }

    /**
     * The reference to the class that orchestrates the hooks with the plugin.
     *
     * @since   1.0.0
     * @return  Simple_Owl_Carousel_Loader  Orchestrates the hooks of the plugin.
     */
    public function get_loader() {
        return $this->loader;
    }

    /**
     * Retrieve the version number of the plugin.
     *
     * @since   1.0.0
     * @return  string    The version number of the plugin.
     */
    public function get_version() {
        return $this->version;
    }

}