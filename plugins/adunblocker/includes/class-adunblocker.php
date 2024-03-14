<?php

/**
 * The file that defines the core plugin class
 *
 * A class definition that includes attributes and functions used across both the
 * public-facing side of the site and the admin area.
 *
 * @link       https://digitalapps.com
 * @since      1.0.0
 *
 * @package    AdUnblocker
 * @subpackage AdUnblocker/includes
 */

class AdUnblocker {

    /**
     * The loader that's responsible for maintaining and registering all hooks that power
     * the plugin.
     *
     * @since    1.0.0
     * @access   protected
     * @var      AdUnblocker_Loader    $loader    Maintains and registers all hooks for the plugin.
     */
    protected $loader;

    /**
     * The unique prefix of this plugin.
     *
     * @since    1.0.0
     * @access   protected
     * @var      string    $plugin_prefix    The string used to uniquely prefix this plugin.
     */
    protected $plugin_prefix;

    /**
     * The unique identifier of this plugin.
     *
     * @since    1.0.0
     * @access   protected
     * @var      string    $plugin_name    The string used to uniquely identify this plugin.
     */
    protected $plugin_name;

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
     * @since    1.0.0
     */
    public function __construct() {
        if ( defined( 'DAAU_PLUGIN_VERSION' ) ) {
            $this->version = DAAU_PLUGIN_VERSION;
        } else {
            $this->version = '1.1.6';
        }
        $this->plguin_prefix = 'daabd';
        $this->plugin_name = 'adunblocker';

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
     * - AdUnblocker_Loader. Orchestrates the hooks of the plugin.
     * - AdUnblocker_i18n. Defines internationalization functionality.
     * - AdUnblocker_Admin. Defines all hooks for the admin area.
     * - AdUnblocker_Public. Defines all hooks for the public side of the site.
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
        require_once plugin_dir_path( dirname( __FILE__ ) ) . 'includes/class-adunblocker-loader.php';

        /**
         * The class responsible for defining internationalization functionality
         * of the plugin.
         */
        require_once plugin_dir_path( dirname( __FILE__ ) ) . 'includes/class-adunblocker-i18n.php';

        /**
         * The class responsible for defining all actions that occur in the admin area.
         */
        require_once plugin_dir_path( dirname( __FILE__ ) ) . 'admin/class-adunblocker-admin.php';

        /**
         * The class responsible for defining all actions that occur in the public-facing
         * side of the site.
         */
        require_once plugin_dir_path( dirname( __FILE__ ) ) . 'public/class-adunblocker-public.php';

        /**
         * The class responsible for sanitizing user input
         */
        require_once plugin_dir_path( dirname( __FILE__ ) ) . 'includes/class-adunblocker-sanitize.php';

        $this->loader = new AdUnblocker_Loader();
        $this->sanitizer = new AdUnblocker_Sanitize( $this->plugin_name );

    }

    /**
     * Define the locale for this plugin for internationalization.
     *
     * Uses the AdUnblocker_i18n class in order to set the domain and to register the hook
     * with WordPress.
     *
     * @since    1.0.0
     * @access   private
     */
    private function set_locale() {

        $plugin_i18n = new AdUnblocker_i18n();

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

        $plugin_admin = new AdUnblocker_Admin( $this->get_plugin_name(), $this->get_version() );

        $this->loader->add_action( 'admin_enqueue_scripts', $plugin_admin, 'enqueue_styles' );
        $this->loader->add_action( 'admin_enqueue_scripts', $plugin_admin, 'enqueue_scripts' );
        $this->loader->add_action( 'admin_menu', $plugin_admin, 'add_menu' );
        $this->loader->add_action( 'admin_init', $plugin_admin, 'register_settings' );
        $this->loader->add_action( 'admin_init', $plugin_admin, 'register_sections' );
        $this->loader->add_action( 'admin_init', $plugin_admin, 'register_fields' );
        $this->loader->add_action( 'admin_init', $plugin_admin, 'http_prepare_download_log' );
        $this->loader->add_action( 'admin_init', $plugin_admin, 'go_pro_redirect' );
        $this->loader->add_action( 'admin_notices', $plugin_admin, 'display_admin_notices' );

        // ajax
        $this->loader->add_action( 'wp_ajax_daau_get_log', $plugin_admin, 'ajax_get_log' );
        $this->loader->add_action( 'wp_ajax_daau_clear_log', $plugin_admin, 'ajax_get_log' );
        $this->loader->add_action( 'admin_enqueue_scripts', $plugin_admin, 'localize_script' );

        //Review Notice
        $this->loader->add_action( 'admin_notices', $plugin_admin, 'review_notice' );
        $this->loader->add_action( 'wp_ajax_adunblocker_hide_review_notice', $plugin_admin, 'hide_review_notice' );
        
        // plugins page
        $this->loader->add_filter( 'plugin_action_links_adunblocker/adunblocker.php', $plugin_admin, 'add_go_pro_link_plugins_page' );
        
        // activation notice
        $this->loader->add_action( 'admin_notices', $plugin_admin, 'admin_notice_activation_hook' );
        
    }

    /**
     * Register all of the hooks related to the public-facing functionality
     * of the plugin.
     *
     * @since    1.0.0
     * @access   private
     */
    private function define_public_hooks() {

        $plugin_public = new AdUnblocker_Public( $this->get_plugin_name(), $this->get_version() );

        // public function add_action( $hook, $component, $callback, $priority = 10, $accepted_args = 1 )
        $this->loader->add_action( 'wp_enqueue_scripts', $plugin_public, 'check_version', 9 );
        $this->loader->add_action( 'wp_enqueue_scripts', $plugin_public, 'regenerate_files', 10 );
        $this->loader->add_action( 'wp_enqueue_scripts', $plugin_public, 'enqueue_styles', 11 );
        $this->loader->add_action( 'wp_enqueue_scripts', $plugin_public, 'enqueue_scripts', 12 );

        // ajax
        $this->loader->add_action( 'wp_ajax_get_daau_settings', $plugin_public, 'ajax_request_callback' );
        $this->loader->add_action( 'wp_ajax_nopriv_get_daau_settings', $plugin_public, 'ajax_request_callback' );
        $this->loader->add_action( 'wp_enqueue_scripts', $plugin_public, 'localize_script', 13 );

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
     * @return    AdUnblocker_Loader    Orchestrates the hooks of the plugin.
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

    /**
     * Retrieve the prefix of the plugin.
     *
     * @since     1.0.0
     * @return    string    The prefix of the plugin.
     */
    public function get_prefix() {
        return $this->prefix;
    }

    static function get_random_string() {
        return substr(str_shuffle("abcdefghijklmnopqrstuvwxyz"), 0, rand(6, 9));
    }

        /**
     * Retrieve plugin defaults.
     *
     * @since     1.1.0
     * @return    array    Array of defaults.
     */
    static function get_defaults() {
        return array (
            'adunblocker-status'            =>      1,
            'adunblocker-content'           =>      '<h2>Adblock Detected!</h2><p>Our website is made possible by displaying online advertisements to our visitors.<br>Please consider supporting us by whitelisting our website.</p>',
            'adunblocker-bg-color'          =>      '#FFFFFF',
            'adunblocker-overlay-color'     =>      '#000000',
            'adunblocker-text-color'        =>      '#000000',
            'adunblocker-type'              =>      'permanent',
            'adunblocker-scope'             =>      'page',
            'adunblocker-style'             =>      'modal',
            'adunblocker-delay'             =>      '5000',
            'adunblocker-file-name'         =>      AdUnblocker::get_random_string(),
            'adunblocker-version'           =>      DAAU_PLUGIN_VERSION
        );
    }

}
