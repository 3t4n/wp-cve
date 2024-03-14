<?php

/**
 * The core plugin class.
 *
 * This is used to define internationalization, admin-specific hooks, and
 * public-facing site hooks.
 *
 * Also maintains the unique identifier of this plugin as well as the current
 * version of the plugin.
 *
 * @since      1.0.0
 * @package    Google_Reviews
 * @subpackage Google_Reviews/includes
 * @author     David Maucher <hallo@maucher-online.com>
 */

Class GRWP_Google_Reviews_Startup {

    protected $loader;

    protected $plugin_name;

    protected $version;

    /**
     * Define the core functionality of the plugin.
     *
     * Set the plugin name and the plugin version that can be used throughout the plugin.
     * Load the dependencies, define the locale, and set the hooks for the admin area and
     * the public-facing side of the site.
     *
     * @since    1.0.0
     */
    public function __construct() {

        if ( defined( 'GRWP_GOOGLE_REVIEWS_VERSION' ) ) {
            $this->version = GRWP_GOOGLE_REVIEWS_VERSION;
        }
        else {
            $this->version = '1.0.0';
        }

        require_once __DIR__ . '/allowed-html.php';

        $this->plugin_name = 'google-reviews';

        $this->load_dependencies();
        $this->set_locale();
        $this->define_admin_hooks();
        $this->define_public_hooks();

        new GRWP_Shortcode();

    }


    /**
     * Load the required dependencies for this plugin.
     *
     * Include the following files that make up the plugin:
     *
     * - GRWP_Google_Reviews_Loader. Orchestrates the hooks of the plugin.
     * - GRWP_Google_Reviews_i18n . Defines internationalization functionality.
     * - Google_Reviews_Admin. Defines all hooks for the admin area.
     * - GRWP_Google_Reviews_Public. Defines all hooks for the public side of the site.
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
        require_once GR_BASE_PATH_ADMIN . 'includes/class-grwp-loader.php';

        /**
         * The class responsible for defining internationalization functionality
         * of the plugin.
         */
        require_once GR_BASE_PATH_ADMIN . 'includes/class-grwp-i18n.php';

        /**
         * The class responsible for defining all actions that occur in the admin area.
         */
        require_once GR_BASE_PATH_ADMIN . 'class-google-reviews-admin.php';

        /**
         * The class responsible for defining all actions that occur in the public-facing
         * side of the site.
         */
        require_once GR_BASE_PATH_PUBLIC . 'includes/class-grwp-google-reviews-public.php';

        $this->loader = new GRWP_Loader();

    }

    /**
     * Define the locale for this plugin for internationalization.
     *
     * Uses the GRWP_Google_Reviews_i18n  class in order to set the domain and to register the hook
     * with WordPress.
     *
     * @since    1.0.0
     * @access   private
     */
    private function set_locale() {

        $plugin_i18n = new GRWP_i18n ();

        $this->loader->add_action( 'plugins_loaded', $plugin_i18n, 'load_plugin_textdomain' );

    }

    /**
     * Register all of the hooks related to the admin area functionality
     * of the plugin.
     *
     * @since    1.0.0
     * @access   private
     */
    private function define_admin_hooks() {

        if ( is_admin() ) {

            $plugin_admin = new GRWP_Google_Reviews_Admin($this->get_plugin_name(), $this->get_version());

            $this->loader->add_action('admin_enqueue_scripts', $plugin_admin, 'enqueue_styles');
            $this->loader->add_action('admin_enqueue_scripts', $plugin_admin, 'enqueue_scripts');

            // load public styles and scripts for backend preview
            $plugin_public = new GRWP_Google_Reviews_Public( $this->get_plugin_name(), $this->get_version() );

            $this->loader->add_action( 'admin_enqueue_scripts', $plugin_public, 'enqueue_styles' );
            $this->loader->add_action( 'admin_enqueue_scripts', $plugin_public, 'enqueue_scripts' );

        }

    }

    /**
     * Register all of the hooks related to the public-facing functionality
     * of the plugin.
     *
     * @since    1.0.0
     * @access   private
     */
    private function define_public_hooks() {

        $plugin_public = new GRWP_Google_Reviews_Public( $this->get_plugin_name(), $this->get_version() );

        $this->loader->add_action( 'wp_enqueue_scripts', $plugin_public, 'enqueue_styles' );
        $this->loader->add_action( 'wp_enqueue_scripts', $plugin_public, 'enqueue_scripts' );

        // Provide hook for wp cron actions
        $this->loader->add_action( 'plugins_loaded', GRWP_WP_Cron::get_instance(), 'plugin_setup' );

    }

    /**
     * Run the loader to execute all of the hooks with WordPress.
     *
     * @since    1.0.0
     */
    public function run() {
        $this->loader->run();
    }

    /**
     * The name of the plugin used to uniquely identify it within the context of
     * WordPress and to define internationalization functionality.
     *
     * @since     1.0.0
     * @return    string    The name of the plugin.
     */
    public function get_plugin_name() {
        return $this->plugin_name;
    }

    /**
     * The reference to the class that orchestrates the hooks with the plugin.
     *
     * @since     1.0.0
     * @return    GRWP_Google_Reviews_Loader    Orchestrates the hooks of the plugin.
     */
    public function get_loader() {
        return $this->loader;
    }

    /**
     * Retrieve the version number of the plugin.
     *
     * @since     1.0.0
     * @return    string    The version number of the plugin.
     */
    public function get_version() {
        return $this->version;
    }


}
