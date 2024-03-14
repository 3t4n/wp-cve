<?php

/**
 * The file that defines the core plugin class
 *
 * A class definition that includes attributes and functions used across both the
 * public-facing side of the site and the admin area.
 *
 * @link       https://mythemeshop.com/
 * @since      1.0.0
 *
 * @package    MY_WP_Translate
 * @subpackage MY_WP_Translate/includes
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
 * @package    MY_WP_Translate
 * @subpackage MY_WP_Translate/includes
 * @author     MyThemeShop <support@mythemeshop.com>
 */
class MY_WP_Translate {

	/**
	 * The loader that's responsible for maintaining and registering all hooks that power
	 * the plugin.
	 *
	 * @since    1.0.0
	 * @access   protected
	 * @var      MY_WP_Translate_Loader    $loader    Maintains and registers all hooks for the plugin.
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

		$this->plugin_name = 'my-wp-translate';
		$this->version = '1.1';

		$this->load_dependencies();
		$this->set_locale();
		$this->define_admin_hooks();

	}

	/**
	 * Load the required dependencies for this plugin.
	 *
	 * Include the following files that make up the plugin:
	 *
	 * - MY_WP_Translate_Loader. Orchestrates the hooks of the plugin.
	 * - MY_WP_Translate_I18n. Defines internationalization functionality.
	 * - MY_WP_Translate_Admin. Defines all hooks for the admin area.
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
		require_once plugin_dir_path( dirname( __FILE__ ) ) . 'includes/class-my-wp-translate-loader.php';

		/**
		 * The class responsible for defining internationalization functionality
		 * of the plugin.
		 */
		require_once plugin_dir_path( dirname( __FILE__ ) ) . 'includes/class-my-wp-translate-i18n.php';

		require_once plugin_dir_path( dirname( __FILE__ ) ) . 'includes/class-my-wp-translate-po-parser.php';

		/**
		 * The class responsible for defining all actions that occur in the admin area.
		 */
		require_once plugin_dir_path( dirname( __FILE__ ) ) . 'admin/class-my-wp-translate-admin.php';

		$this->loader = new MY_WP_Translate_Loader();
	}

	/**
	 * Define the locale for this plugin for internationalization.
	 *
	 * Uses the MY_WP_Translate_I18n class in order to set the domain and to register the hook
	 * with WordPress.
	 *
	 * @since    1.0.0
	 * @access   private
	 */
	private function set_locale() {

		$plugin_i18n = new MY_WP_Translate_I18n();
		$plugin_i18n->set_domain( $this->get_plugin_name() );

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

		$plugin_admin = new MY_WP_Translate_Admin( $this->get_plugin_name(), $this->get_version() );

		$this->loader->add_action( 'admin_enqueue_scripts', $plugin_admin, 'enqueue_styles' );
		$this->loader->add_action( 'admin_enqueue_scripts', $plugin_admin, 'enqueue_scripts' );

		$this->loader->add_action( 'admin_menu', $plugin_admin, 'admin_menu_page' );
		$this->loader->add_action( 'admin_init', $plugin_admin, 'settings_init' );

		//ajax for translation panel form
		$this->loader->add_action( 'wp_ajax_mtswpt_translation_panel', $plugin_admin, 'ajax_translation_panel' );
		$this->loader->add_action( 'wp_ajax_mtswpt_save_translation', $plugin_admin, 'ajax_save_translation' );
		$this->loader->add_action( 'wp_ajax_mtswpt_add_plugin', $plugin_admin, 'ajax_add_plugin' );
		$this->loader->add_action( 'wp_ajax_mtswpt_remove_plugin', $plugin_admin, 'ajax_remove_plugin' );
		$this->loader->add_action( 'wp_ajax_mtswpt_save_state', $plugin_admin, 'ajax_save_state' );
		$this->loader->add_action( 'wp_ajax_mtswpt_import_strings', $plugin_admin, 'ajax_import_strings' );
		$this->loader->add_action( 'wp_ajax_mtswpt_update_export_code', $plugin_admin, 'ajax_update_export_code' );

		$this->loader->add_action( 'after_setup_theme', $plugin_admin, 'remove_theme_custom_translate' );
		$this->loader->add_filter( 'gettext', $plugin_admin, 'custom_translate', 20, 3 );
		$this->loader->add_filter( 'ngettext', $plugin_admin, 'custom_translate_n', 20, 5 );
		$this->loader->add_filter( 'gettext_with_context', $plugin_admin, 'custom_translate_x', 20, 4 );
		$this->loader->add_filter( 'ngettext_with_context', $plugin_admin, 'custom_translate_nx', 20, 6 );
		$this->loader->add_filter( 'nhp-opts-args', $plugin_admin, 'disable_theme_options_panel_translate' );

		$this->loader->add_action( 'load_textdomain', $plugin_admin, 'get_textdomains', 10, 2 );

		$this->loader->add_action( 'plugins_loaded', $plugin_admin, 'get_po' );
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
	 * @return    MY_WP_Translate_Loader    Orchestrates the hooks of the plugin.
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
