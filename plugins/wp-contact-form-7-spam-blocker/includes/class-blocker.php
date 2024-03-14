<?php

/**
 * The file that defines the core plugin class
 *
 * A class definition that includes attributes and functions used across both the
 * public-facing side of the site and the admin area.
 *
 * @since      1.0.0
 *
 * @package    Spam_Protect_for_Contact_Form7
 * @subpackage Spam_Protect_for_Contact_Form7/includes
 */

/**
 * The core plugin class.
 *
 * @since      1.0.0
 * @package    Spam_Protect_for_Contact_Form7
 * @subpackage Spam_Protect_for_Contact_Form7/includes
 * @author     New York Software Lab
 * $link       https://nysoftwarelab.com
 */
class Spam_Protect_for_Contact_Form7 {

    /**
     * The loader that's responsible for maintaining and registering all hooks that powers the plugin.
     */
    protected $loader;

    /**
     * The unique identifier of this plugin.
     */
    protected $plugin_name;

    /**
     * The current version of the plugin.
     */
    protected $version;

    /**
     * Constructor of the plugin.
     */
    public function __construct() {
            if ( defined( 'PLUGIN_NAME_VERSION' ) ) {
                    $this->version = PLUGIN_NAME_VERSION;
            } else {
                    $this->version = '1.0.0';
            }
            $this->plugin_name = 'spam-protect-for-contact-form7';

            $this->load_classes();
            $this->set_locale();
            $this->set_admin_hooks();
            $this->set_public_hooks();
    }

    /**
     * Load the classes required for this plugin.
     */
    private function load_classes() {

            /**
             * The class responsible for orchestrating the actions and filters of the
             * core plugin.
             */
            require_once plugin_dir_path( dirname( __FILE__ ) ) . 'includes/class-loader.php';

            /**
             * The class responsible for defining internationalization functionality
             * of the plugin.
             */
            require_once plugin_dir_path( dirname( __FILE__ ) ) . 'includes/class-i18n.php';

            /**
             * The class responsible for defining all actions that occur in the admin area.
             */
            require_once plugin_dir_path( dirname( __FILE__ ) ) . 'admin/class-admin.php';

            /**
             * The class responsible for defining all actions that occur in the public-facing
             * side of the site.
             */
            require_once plugin_dir_path( dirname( __FILE__ ) ) . 'frontend/class-front.php';

            $this->loader = new Spam_Protect_for_Contact_Form7_Loader();

    }

    /**
     * Define the locale for this plugin.
     */
    private function set_locale() {

            $plugin_i18n = new Spam_Protect_for_Contact_Form7_i18n();

            $this->loader->add_action( 'plugins_loaded', $plugin_i18n, 'load_plugin_textdomain' );

    }

    /**
     * Register all of the hooks related to the admin area functionality of the plugin.
     */
    private function set_admin_hooks() {

            $plugin_admin = new Spam_Protect_for_Contact_Form7_Admin( $this->get_plugin_name(), $this->get_version() );

            $this->loader->add_action( 'admin_enqueue_scripts', $plugin_admin, 'spcf7_enqueue_styles' );
            $this->loader->add_action( 'admin_enqueue_scripts', $plugin_admin, 'spcf7_enqueue_scripts' );

    }

    /**
     * Register all of the hooks related to the public functionality of the plugin.
     */
    private function set_public_hooks() {

            $plugin_public = new Spam_Protect_for_Contact_Form7_Front( $this->get_plugin_name(), $this->get_version() );

            $this->loader->add_action( 'wp_enqueue_scripts', $plugin_public, 'spcf7_enqueue_styles' );
            $this->loader->add_action( 'wp_enqueue_scripts', $plugin_public, 'spcf7_enqueue_scripts' );

    }

    /**
     * Run the loader to execute all of the hooks with WordPress.
     */
    public function run() {
            $this->loader->run();
    }

    /**
     * The name of the plugin, used for unique identification
    */
    public function get_plugin_name() {
            return $this->plugin_name;
    }

    /**
     * The reference to the loader class.
     */
    public function get_loader() {
            return $this->loader;
    }

    /**
     * Retrieve the version number of the plugin.
     */
    public function get_version() {
            return $this->version;
    }
}
