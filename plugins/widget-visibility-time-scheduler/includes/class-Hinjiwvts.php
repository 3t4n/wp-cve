<?php

/**
 * The file that defines the core plugin class
 *
 * A class definition that includes attributes and functions used across both the
 * public-facing side of the site and the dashboard.
 *
 * @link       http://wordpress.org/plugins/widget-visibility-time-scheduler
 * @since      1.0.0
 *
 * @package    Hinjiwvts
 * @subpackage Hinjiwvts/includes
 */

/**
 * The core plugin class.
 *
 * This is used to define internationalization, dashboard-specific hooks, and
 * public-facing site hooks.
 *
 * Also maintains the unique identifier of this plugin as well as the current
 * version of the plugin.
 *
 * @since      1.0.0
 * @package    Hinjiwvts
 * @subpackage Hinjiwvts/includes
 * @author     Kybernetik Services <wordpress@kybernetik.com.de>
 */
class Hinjiwvts {

	/**
	 * The loader that's responsible for maintaining and registering all hooks that power
	 * the plugin.
	 *
	 * @since    1.0.0
	 * @access   protected
	 * @var      Hinjiwvts_Loader    $loader    Maintains and registers all hooks for the plugin.
	 */
	protected $loader;

	/**
	 * The slug of this plugin.
	 *
	 * @since    1.0.0
	 * @access   private
	 * @var      string    $plugin_slug    The slug of this plugin.
	 */
	private $plugin_slug;

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
	 * Load the dependencies, define the locale, and set the hooks for the Dashboard and
	 * the public-facing side of the site.
	 *
	 * @since    1.0.0
	 */
	public function __construct() {

		$this->plugin_slug = 'hinjiwvts';
		$this->version = '5.3.13';

		$this->load_dependencies();
		$this->set_locale();

		if ( is_admin() ) {
			$this->define_admin_hooks();
		} else {
			$this->define_public_hooks();
		}

	}

	/**
	 * Load the required dependencies for this plugin.
	 *
	 * Include the following files that make up the plugin:
	 *
	 * - Hinjiwvts_Loader. Orchestrates the hooks of the plugin.
	 * - Hinjiwvts_i18n. Defines internationalization functionality.
	 * - Hinjiwvts_Admin. Defines all hooks for the dashboard.
	 * - Hinjiwvts_Public. Defines all hooks for the public side of the site.
	 *
	 * Create an instance of the loader which will be used to register the hooks
	 * with WordPress.
	 *
	 * @since    1.0.0
	 * @access   private
	 */
	private function load_dependencies() {

		$this->loader = new Hinjiwvts_Loader();

	}

	/**
	 * Define the locale for this plugin for internationalization.
	 *
	 * Uses the Hinjiwvts_i18n class in order to set the domain and to register the hook
	 * with WordPress.
	 *
	 * @since    1.0.0
	 * @access   private
	 */
	private function set_locale() {

		$plugin_i18n = new Hinjiwvts_i18n();
		$plugin_i18n->set_domain( $this->get_plugin_slug() );

		$this->loader->add_action( 'plugins_loaded', $plugin_i18n, 'load_plugin_textdomain' );

	}

	/**
	 * Register all of the hooks related to the dashboard functionality
	 * of the plugin.
	 *
	 * @since    1.0.0
	 * @access   private
	 */
	private function define_admin_hooks() {

		$plugin_admin = new Hinjiwvts_Admin( $this->get_plugin_slug(), $this->get_version() );

		// load admin style sheet
		$this->loader->add_action( 'admin_enqueue_scripts', $plugin_admin, 'enqueue_styles' );
		// load admin javascript
		$this->loader->add_action( 'admin_enqueue_scripts', $plugin_admin, 'enqueue_scripts' );
		// add time input fields to each widget
		$this->loader->add_action( 'in_widget_form', $plugin_admin, 'display_time_fields', 10, 3 );
		// sanitize user inputs and update widget options
		$this->loader->add_action( 'widget_update_callback', $plugin_admin, 'widget_update', 10, 3 );

		// hook on displaying a message after plugin activation
		if ( isset( $_GET[ 'activate' ] ) or isset( $_GET[ 'activate-multi' ] ) ) {
			if ( false !== get_transient( $this->plugin_slug ) ) {
				$this->loader->add_action( 'admin_notices', $plugin_admin, 'display_activation_message' );
				delete_transient( $this->plugin_slug );
			}
		}

        if( self::is_wp_58_or_higher() ) {

            $this->loader->add_action( 'admin_notices', $plugin_admin, 'display_wp58_message' );

        }

    }

    /**
     * Check the version of WordPress and returns true if it is 5.8 or higher
     * Additional we consider if the plugin Classic Widgets is installed.
     *
     * @since    5.3.12
     * @access   private
     *
     * @return   bool
     *
     */
    private function is_wp_58_or_higher() {

        global $wp_version;

        include_once( ABSPATH . 'wp-admin/includes/plugin.php' );

        // get the major and minor version number as integer
        $pos = strpos( $wp_version, '.');
        $ver = intval( substr( $wp_version, '0', $pos ) . substr( $wp_version, $pos + 1 , 1 ) );

        if( !is_plugin_active( 'classic-widgets/classic-widgets.php' ) && $ver >= 58 ) {
            return true;
        }

        return false;

    }

    /**
	 * Register all of the hooks related to the public-facing functionality
	 * of the plugin.
	 *
	 * @since    1.0.0
	 * @access   private
	 */
	private function define_public_hooks() {

		$plugin_public = new Hinjiwvts_Public( $this->get_plugin_slug(), $this->get_version() );
		// check the visibility of each widget to display it or not
		$this->loader->add_action( 'widget_display_callback', $plugin_public, 'filter_widget' );

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
	public function get_plugin_slug() {
		return $this->plugin_slug;
	}

	/**
	 * The reference to the class that orchestrates the hooks with the plugin.
	 *
	 * @since     1.0.0
	 * @return    Hinjiwvts_Loader    Orchestrates the hooks of the plugin.
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
