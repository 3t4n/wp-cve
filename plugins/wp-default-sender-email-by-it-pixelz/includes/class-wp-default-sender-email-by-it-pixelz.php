<?php

/**
 * The core plugin class.
 *
 * This is used to define internationalization, admin-specific hooks, and
 * common hooks.
 *
 * @since      2.0.0
 * @package    Wp_Default_Sender_Email_By_It_Pixelz
 * @subpackage Wp_Default_Sender_Email_By_It_Pixelz/includes
 * @author     Umar Draz <umar.draz001@gmail.com>
 */
class Wp_Default_Sender_Email_By_It_Pixelz {

	/**
	 * The loader that's responsible for maintaining and registering all hooks
	 * that power the plugin.
	 *
	 * @since    2.0.0
	 * @access   protected
	 * @var      Wp_Default_Sender_Email_By_It_Pixelz_Loader $loader Maintains
	 *     and registers all hooks for the plugin.
	 */
	protected $loader;

	/**
	 * The unique identifier of this plugin.
	 *
	 * @since    2.0.0
	 * @access   protected
	 * @var      string $plugin_name The string used to uniquely identify this
	 *     plugin.
	 */
	protected $plugin_name;

	/**
	 * The current version of the plugin.
	 *
	 * @since    2.0.0
	 * @access   protected
	 * @var      string $version The current version of the plugin.
	 */
	protected $version;

	/**
	 * Options settings.
	 *
	 * @since    2.0.0
	 * @access   public
	 * @var      string $options_settings options
	 */
	public $options_settings;

	/**
	 * Define the core functionality of the plugin.
	 *
	 * Set the plugin name and the plugin version that can be used throughout
	 * the plugin. Load the dependencies, define the locale, and set the hooks
	 * for the admin area and the public-facing side of the site.
	 *
	 * @since    2.0.0
	 */
	public function __construct() {
		if ( defined( 'WP_DEFAULT_SENDER_EMAIL_BY_IT_PIXELZ_VERSION' ) ) {
			$this->version = WP_DEFAULT_SENDER_EMAIL_BY_IT_PIXELZ_VERSION;
		} else {
			$this->version = '2.0.0';
		}
		$this->plugin_name = 'wp-default-sender-email-by-it-pixelz';

		$this->options_settings = get_option( WP_DEFAULT_SENDER_EMAIL_BY_IT_PIXELZ_OPTIONS_KEY );

		$this->load_dependencies();
		$this->set_locale();
		$this->define_admin_hooks();
		$this->define_common_hooks();
	}

	/**
	 * Load the required dependencies for this plugin.
	 *
	 * Include the following files that make up the plugin:
	 *
	 * - Wp_Default_Sender_Email_By_It_Pixelz_Loader. Orchestrates the hooks of
	 * the plugin.
	 * - Wp_Default_Sender_Email_By_It_Pixelz_i18n. Defines
	 * internationalization functionality.
	 * - Wp_Default_Sender_Email_By_It_Pixelz_Admin. Defines all hooks for the
	 * admin area.
	 * - Wp_Default_Sender_Email_By_It_Pixelz_Public. Defines all hooks for the
	 * public side of the site.
	 *
	 * Create an instance of the loader which will be used to register the
	 * hooks
	 * with WordPress.
	 *
	 * @since    2.0.0
	 * @access   private
	 */
	private function load_dependencies() {

		/**
		 * The class responsible for orchestrating the actions and filters of the
		 * core plugin.
		 */
		require_once plugin_dir_path( dirname( __FILE__ ) ) . 'includes/class-wp-default-sender-email-by-it-pixelz-loader.php';

		/**
		 * The class responsible for defining internationalization functionality
		 * of the plugin.
		 */
		require_once plugin_dir_path( dirname( __FILE__ ) ) . 'includes/class-wp-default-sender-email-by-it-pixelz-i18n.php';

		/**
		 * The class responsible for defining all actions that occur in the admin area.
		 */
		require_once plugin_dir_path( dirname( __FILE__ ) ) . 'admin/class-wp-default-sender-email-by-it-pixelz-admin.php';


		$this->loader = new Wp_Default_Sender_Email_By_It_Pixelz_Loader();
	}

	/**
	 * Define the locale for this plugin for internationalization.
	 *
	 * Uses the Wp_Default_Sender_Email_By_It_Pixelz_i18n class in order to set
	 * the domain and to register the hook with WordPress.
	 *
	 * @since    2.0.0
	 * @access   private
	 */
	private function set_locale() {
		$plugin_i18n = new Wp_Default_Sender_Email_By_It_Pixelz_i18n();
		$this->loader->add_action( 'plugins_loaded', $plugin_i18n, 'load_plugin_textdomain' );
	}

	/**
	 * Register all of the hooks related to the admin area functionality
	 * of the plugin.
	 *
	 * @since    2.0.0
	 * @access   private
	 */
	private function define_admin_hooks() {
		$plugin_admin = new Wp_Default_Sender_Email_By_It_Pixelz_Admin( $this->get_plugin_name(), $this->get_version() );

		$this->loader->add_action( 'admin_enqueue_scripts', $plugin_admin, 'enqueue_styles' );

		// admin menu
		$this->loader->add_action( 'admin_menu', $plugin_admin, 'admin_menu' );

		// register settings fields
		$this->loader->add_action( 'admin_init', $plugin_admin, 'wp_setting_init', 10, 0 );
		$this->loader->add_action( 'admin_init', $plugin_admin, 'admin_redirect', 10, 0 );

		$this->loader->add_filter( 'plugin_action_links_' . WP_DEFAULT_SENDER_EMAIL_BY_IT_PIXELZ_BASE_FILE, $plugin_admin, 'add_settings_links' );
	}

	/**
	 * The name of the plugin used to uniquely identify it within the context of
	 * WordPress and to define internationalization functionality.
	 *
	 * @return    string    The name of the plugin.
	 * @since     1.0.0
	 */
	public function get_plugin_name() {
		return $this->plugin_name;
	}

	/**
	 * Retrieve the version number of the plugin.
	 *
	 * @return    string    The version number of the plugin.
	 * @since     1.0.0
	 */
	public function get_version() {
		return $this->version;
	}

	/**
	 * Register all of the hooks related to the common functionality
	 * of the plugin.
	 *
	 * @since    2.0.0
	 * @access   private
	 */
	private function define_common_hooks() {

		$this->loader->add_filter( 'wp_mail_from', $this, 'email_sender_email' );
		$this->loader->add_filter( 'wp_mail_from_name', $this, 'email_sender_name' );

	}


	function email_sender_email( $old ) {
		return $this->options_settings['sender_mail'];
	}

	function email_sender_name( $old ) {
		return $this->options_settings['sender_name'];
	}

	/**
	 * Run the loader to execute all of the hooks with WordPress.
	 *
	 * @since    2.0.0
	 */
	public function run() {
		$this->loader->run();
	}

	/**
	 * The reference to the class that orchestrates the hooks with the plugin.
	 *
	 * @return    Wp_Default_Sender_Email_By_It_Pixelz_Loader    Orchestrates
	 *     the hooks of the plugin.
	 * @since     1.0.0
	 */
	public function get_loader() {
		return $this->loader;
	}

}
