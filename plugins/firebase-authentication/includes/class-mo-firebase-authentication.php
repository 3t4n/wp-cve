<?php
/**
 * The file that defines the core plugin class
 *
 * A class definition that includes attributes and functions used across both the
 * public-facing side of the site and the admin area.
 *
 * @link       https://miniorange.com
 * @since      1.0.0
 *
 * @package    Firebase_Authentication
 * @subpackage Firebase_Authentication/includes
 */

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
 * @package    Firebase_Authentication
 * @subpackage Firebase_Authentication/includes
 * @author     miniOrange <info@miniorange.com>
 */
class MO_Firebase_Authentication {

	/**
	 * The loader that's responsible for maintaining and registering all hooks that power
	 * the plugin.
	 *
	 * @since    1.0.0
	 * @access   protected
	 * @var      MO_Firebase_Authentication_Loader    $loader    Maintains and registers all hooks for the plugin.
	 */
	protected $loader;

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
		if ( defined( 'MO_FIREBASE_AUTHENTICATION_VERSION' ) ) {
			$this->version = MO_FIREBASE_AUTHENTICATION_VERSION;
		} else {
			$this->version = '1.0.0';
		}
		update_option( 'mo_firebase_authentication_current_plugin_version ', $this->version );
		$this->plugin_name = 'firebase-authentication';

		$this->load_dependencies();
		$this->set_locale();
		$this->define_admin_hooks();
	}

	/**
	 * Load the required dependencies for this plugin.
	 *
	 * Include the following files that make up the plugin:
	 *
	 * - MO_Firebase_Authentication_Loader. Orchestrates the hooks of the plugin.
	 * - MO_Firebase_Authentication_I18n. Defines internationalization functionality.
	 * - MO_Firebase_Authentication_Admin. Defines all hooks for the admin area.
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
		require_once MO_FIREBASE_AUTHENTICATION_DIR . 'includes' . DIRECTORY_SEPARATOR . 'class-mo-firebase-authentication-loader.php';

		/**
		 * The class responsible for defining internationalization functionality
		 * of the plugin.
		 */
		require_once MO_FIREBASE_AUTHENTICATION_DIR . 'includes' . DIRECTORY_SEPARATOR . 'class-mo-firebase-authentication-i18n.php';

		/**
		 * The class responsible for defining all actions that occur in the admin area.
		 */
		require_once MO_FIREBASE_AUTHENTICATION_DIR . 'admin' . DIRECTORY_SEPARATOR . 'class-mo-firebase-authentication-admin.php';

		$this->loader = new MO_Firebase_Authentication_Loader();

	}

	/**
	 * Define the locale for this plugin for internationalization.
	 *
	 * Uses the MO_Firebase_Authentication_i18n class in order to set the domain and to register the hook
	 * with WordPress.
	 *
	 * @since    1.0.0
	 * @access   private
	 */
	private function set_locale() {

		$plugin_i18n = new MO_Firebase_Authentication_I18n();

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

		$plugin_admin = new MO_Firebase_Authentication_Admin( $this->get_plugin_name(), $this->get_version() );
		add_action( 'admin_menu', array( $this, 'miniorange_firebase_menu' ) );
		$this->loader->add_action( 'admin_enqueue_scripts', $plugin_admin, 'enqueue_styles' );
		$this->loader->add_action( 'admin_enqueue_scripts', $plugin_admin, 'enqueue_scripts' );
	}

	/**
	 * Add miniOrange plugin to the menu
	 *
	 * @return void
	 */
	public function miniorange_firebase_menu() {
		$page = add_menu_page( 'Configuration', 'Firebase Authentication', 'manage_options', 'mo_firebase_authentication', array( $this, 'mo_firebase_auth_options' ), MO_FIREBASE_AUTHENTICATION_URL . 'public/images/miniorange.png' );
	}

	/**
	 * Initialize plugin screen from admin menu
	 *
	 * @return void
	 */
	public function mo_firebase_auth_options() {
		$plugin_admin = new MO_Firebase_Authentication_Admin( $this->get_plugin_name(), $this->get_version() );
		$plugin_admin->mo_firebase_auth_page();
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
	 * @return    MO_Firebase_Authentication_Loader    Orchestrates the hooks of the plugin.
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
