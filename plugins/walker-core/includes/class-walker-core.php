<?php

/**
 * The file that defines the core plugin class
 *
 * A class definition that includes attributes and functions used across both the
 * public-facing side of the site and the admin area.
 *
 * @link       http://walkerwp.com/
 * @since      1.0.0
 *
 * @package    Walker_Core
 * @subpackage Walker_Core/includes
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
 * @package    Walker_Core
 * @subpackage Walker_Core/includes
 * @author     WalkerWp <support@walkerwp.com>
 */
class Walker_Core
{

	/**
	 * The loader that's responsible for maintaining and registering all hooks that power
	 * the plugin.
	 *
	 * @since    1.0.0
	 * @access   protected
	 * @var      Walker_Core_Loader    $loader    Maintains and registers all hooks for the plugin.
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
		if (defined('WALKER_CORE_VERSION')) {
			$this->version = WALKER_CORE_VERSION;
		} else {
			$this->version = '1.0.0';
		}
		$this->plugin_name = 'walker-core';

		$this->load_dependencies();
		$this->set_locale();
		$pluginList = walker_core_plugin_check_activated();
		$this->define_admin_hooks();
		$this->define_public_hooks();
	}

	/**
	 * Load the required dependencies for this plugin.
	 *
	 * Include the following files that make up the plugin:
	 *
	 * - Walker_Core_Loader. Orchestrates the hooks of the plugin.
	 * - Walker_Core_i18n. Defines internationalization functionality.
	 * - Walker_Core_Admin. Defines all hooks for the admin area.
	 * - Walker_Core_Public. Defines all hooks for the public side of the site.
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
		require_once plugin_dir_path(dirname(__FILE__)) . 'includes/class-walker-core-loader.php';

		/**
		 * The class responsible for defining internationalization functionality
		 * of the plugin.
		 */
		require_once plugin_dir_path(dirname(__FILE__)) . 'includes/class-walker-core-i18n.php';

		/**
		 * The class responsible for defining all actions that occur in the admin area.
		 */
		require_once plugin_dir_path(dirname(__FILE__)) . 'admin/class-walker-core-admin.php';

		/**
		 * The class responsible for defining all actions that occur in the public-facing
		 * side of the site.
		 */

		require_once WALKER_CORE_PATH . 'admin/functions.php';
		require_once WALKER_CORE_PATH . 'admin/hooks.php';
		require_once WALKER_CORE_PATH . 'admin/walker-widgets.php';
		require_once plugin_dir_path(dirname(__FILE__)) . 'public/class-walker-core-public.php';
		$wc_current_theme = wp_get_theme()->get('Name');
		if ($wc_current_theme == 'BlockVerse' || 'Walker News Template' && wc_fs()->can_use_premium_code()) {
			require_once WALKER_CORE_PATH . 'includes/block-patterns.php';
		}

		$this->loader = new Walker_Core_Loader();
	}

	/**
	 * Define the locale for this plugin for internationalization.
	 *
	 * Uses the Walker_Core_i18n class in order to set the domain and to register the hook
	 * with WordPress.
	 *
	 * @since    1.0.0
	 * @access   private
	 */
	private function set_locale()
	{

		$plugin_i18n = new Walker_Core_i18n();

		$this->loader->add_action('plugins_loaded', $plugin_i18n, 'load_plugin_textdomain');
	}

	private function define_hooks()
	{

		$plugin_admin = walker_core_hooks();

		$this->loader->add_action('admin_init', $plugin_admin, 'redirect');
		$this->loader->add_action('advanced_import_demo_lists', $plugin_admin, 'add_demo_lists', 999);
		$this->loader->add_action('admin_menu', $plugin_admin, 'import_menu');
		$this->loader->add_action('wp_ajax_walker_core_getting_started', $plugin_admin, 'install_advanced_import');
	}

	/**
	 * Register all of the hooks related to the admin area functionality
	 * of the plugin.
	 *
	 * @since    1.0.0
	 * @access   private
	 */
	private function define_admin_hooks()
	{

		$plugin_admin = new Walker_Core_Admin($this->get_plugin_name(), $this->get_version());

		$this->loader->add_action('admin_enqueue_scripts', $plugin_admin, 'enqueue_styles');
		$this->loader->add_action('admin_enqueue_scripts', $plugin_admin, 'enqueue_scripts');
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

		$plugin_public = new Walker_Core_Public($this->get_plugin_name(), $this->get_version());

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
		if (defined('WALKER_CORE_VERSION')) {
			$this->version = WALKER_CORE_VERSION;
		} else {
			$this->version = '1.0.0';
		}
		$this->plugin_name = 'walker-core';
		$pluginList = walker_core_plugin_check_activated();

		if ($pluginList == '1') {
			$this->define_hooks();
			$this->load_hooks();
		} else {
			add_action('admin_notices', array($this, 'walker_core_missing_notice'));
			$this->load_hooks();
		}
	}


	public function load_hooks()
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
	 * @return    Walker_Core_Loader    Orchestrates the hooks of the plugin.
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

	public function walker_core_missing_notice()
	{


		$pluginList = walker_core_plugin_check_activated();

		if ($pluginList != '1') {

			$fileexists = walker_core_plugin_file_exists();

			if ($fileexists == '1') {
				$walker_core_plugin = 'advanced-import/advanced-import.php';
				$activation_url = wp_nonce_url('plugins.php?action=activate&amp;plugin=' . $walker_core_plugin . '&amp;plugin_status=all&amp;paged=1&amp;s', 'activate-plugin_' . $walker_core_plugin);
				$message = '<p>' . __('Walker Core Demo Import Feature is not working because you need to activate Advanced Import Plugin.') . '</p>';

				$activate_msg = __('Activate Advanced Import Now');
				$message .= '<p>' . sprintf('<a href="%s" class="button-primary">%s</a>', $activation_url, $activate_msg) . '</p>';

				echo '<div class="error"><p>' . $message . '</p></div>';
			}
		}
	}

	public function walker_core_premium_status()
	{
		if (wc_fs()->can_use_premium_code()) {
			return true;
		}
	}
}
