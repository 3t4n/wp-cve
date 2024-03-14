<?php

/**
 * The file that defines the core plugin class
 *
 * A class definition that includes attributes and functions used across both the
 * public-facing side of the site and the admin area.
 *
 * @link       https://tyganeutronics.com
 * @since      1.0.0
 *
 * @package    Force_Reinstall
 * @subpackage Force_Reinstall/includes
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
 * @package    Force_Reinstall
 * @subpackage Force_Reinstall/includes
 * @author     Richard Muvirimi <tygalive@gmail.com>
 */
class Force_Reinstall
{

	/**
	 * The loader that's responsible for maintaining and registering all hooks that power
	 * the plugin.
	 *
	 * @since    1.0.0
	 * @access   protected
	 * @var      Force_Reinstall_Loader    $loader    Maintains and registers all hooks for the plugin.
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
	public function __construct()
	{
		if (defined('FORCE_REINSTALL_VERSION')) {
			$this->version = FORCE_REINSTALL_VERSION;
		} else {
			$this->version = '1.0.0';
		}
		$this->plugin_name = 'force-reinstall';

		$this->load_dependencies();
		$this->set_locale();
		$this->define_admin_hooks();
		$this->define_public_hooks();
		$this->define_ajax_hooks();
	}

	/**
	 * Load the required dependencies for this plugin.
	 *
	 * Include the following files that make up the plugin:
	 *
	 * - Force_Reinstall_Loader. Orchestrates the hooks of the plugin.
	 * - Force_Reinstall_i18n. Defines internationalization functionality.
	 * - Force_Reinstall_Admin. Defines all hooks for the admin area.
	 * - Force_Reinstall_Public. Defines all hooks for the public side of the site.
	 *
	 * Create an instance of the loader which will be used to register the hooks
	 * with WordPress.
	 *
	 * @since    1.0.0
	 * @access   private
	 */
	private function load_dependencies()
	{

		/**
		 * The class responsible for orchestrating the actions and filters of the
		 * core plugin.
		 */
		require_once plugin_dir_path(dirname(__FILE__)) . 'includes/class-force-reinstall-loader.php';

		/**
		 * The class responsible for defining internationalization functionality
		 * of the plugin.
		 */
		require_once plugin_dir_path(dirname(__FILE__)) . 'includes/class-force-reinstall-i18n.php';

		/**
		 * The class responsible for defining all actions that occur in the admin area.
		 */
		require_once plugin_dir_path(dirname(__FILE__)) . 'admin/class-force-reinstall-admin.php';

		/**
		 * The class responsible for defining all actions that occur in the public-facing
		 * side of the site.
		 */
		require_once plugin_dir_path(dirname(__FILE__)) . 'public/class-force-reinstall-public.php';

		/**
		 * The class responsible for defining all actions that occur in the ajax-facing
		 * side of the site.
		 */
		require_once plugin_dir_path(dirname(__FILE__)) . 'ajax/class-force-reinstall-ajax.php';

		/**
		 * Functions file
		 */
		require_once plugin_dir_path(__FILE__) . 'force-reinstall-functions.php';

		$this->loader = new Force_Reinstall_Loader();
	}

	/**
	 * Define the locale for this plugin for internationalization.
	 *
	 * Uses the Force_Reinstall_i18n class in order to set the domain and to register the hook
	 * with WordPress.
	 *
	 * @since    1.0.0
	 * @access   private
	 */
	private function set_locale()
	{

		$plugin_i18n = new Force_Reinstall_i18n();

		$this->loader->add_action('plugins_loaded', $plugin_i18n, 'load_plugin_textdomain');
	}

	/**
	 * Register all of the hooks related to the admin area functionality
	 * of the plugin.
	 *
	 * @since    1.0.0
	 * @version  1.0.3
	 * @access   private
	 */
	private function define_admin_hooks()
	{

		$plugin_admin = new Force_Reinstall_Admin($this->get_plugin_name(), $this->get_version());

		$this->loader->add_action('admin_enqueue_scripts', $plugin_admin, 'enqueue_styles');
		$this->loader->add_action('admin_enqueue_scripts', $plugin_admin, 'enqueue_scripts');

		$this->loader->add_filter('plugin_action_links', $plugin_admin, 'plugin_action_links', 10, 3);

		$this->loader->add_filter('admin_notices', $plugin_admin, 'show_rating');

		$this->loader->add_filter('admin_action_' . $this->plugin_name, $plugin_admin, 'handle_action');

		$this->loader->add_filter('bulk_actions-plugins', $plugin_admin, 'add_plugins_bulk_action');
		$this->loader->add_filter('handle_bulk_actions-plugins', $plugin_admin, 'plugins_bulk_update', 10, 3);
	}

	/**
	 * Register all of the hooks related to the public-facing functionality
	 * of the plugin.
	 *
	 * @access private
	 * @since 1.0.0
	 * @version 1.0.0
	 */
	private function define_ajax_hooks()
	{

		$plugin_ajax = new Force_reinstall_Ajax($this->get_plugin_name(), $this->get_version());

		$this->loader->add_action('wp_ajax_' . $this->get_plugin_name() . '-rate', $plugin_ajax, 'ajaxDoRate');
		$this->loader->add_action('wp_ajax_' . $this->get_plugin_name() . '-remind', $plugin_ajax, 'ajaxDoRemind');
		$this->loader->add_action('wp_ajax_' . $this->get_plugin_name() . '-cancel', $plugin_ajax, 'ajaxDoCancel');
	}

	/**
	 * Register all of the hooks related to the public-facing functionality
	 * of the plugin.
	 *
	 * @since    1.0.0
	 * @access   private
	 */
	private function define_public_hooks()
	{

		$plugin_public = new Force_Reinstall_Public($this->get_plugin_name(), $this->get_version());

		$this->loader->add_action('wp_enqueue_scripts', $plugin_public, 'enqueue_styles');
		$this->loader->add_action('wp_enqueue_scripts', $plugin_public, 'enqueue_scripts');
	}

	/**
	 * Run the loader to execute all of the hooks with WordPress.
	 *
	 * @since    1.0.0
	 */
	public function run()
	{
		$this->loader->run();
	}

	/**
	 * The name of the plugin used to uniquely identify it within the context of
	 * WordPress and to define internationalization functionality.
	 *
	 * @since     1.0.0
	 * @return    string    The name of the plugin.
	 */
	public function get_plugin_name()
	{
		return $this->plugin_name;
	}

	/**
	 * The reference to the class that orchestrates the hooks with the plugin.
	 *
	 * @since     1.0.0
	 * @return    Force_Reinstall_Loader    Orchestrates the hooks of the plugin.
	 */
	public function get_loader()
	{
		return $this->loader;
	}

	/**
	 * Retrieve the version number of the plugin.
	 *
	 * @since     1.0.0
	 * @return    string    The version number of the plugin.
	 */
	public function get_version()
	{
		return $this->version;
	}
}