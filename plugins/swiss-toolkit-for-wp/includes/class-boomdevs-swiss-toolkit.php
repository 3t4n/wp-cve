<?php

/**
 * Prevent direct access to this file.
 */
if (!defined('ABSPATH')) {
	exit;
}

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
 * @package    Boomdevs_Swiss_Toolkit
 * @subpackage Boomdevs_Swiss_Toolkit/includes
 * @author     BoomDevs <contact@boomdevs.com>
 */
if (!class_exists('BDSTFW_Swiss_Toolkit')) {
	class BDSTFW_Swiss_Toolkit
	{

		/**
		 * The loader that's responsible for maintaining and registering all hooks that power
		 * the plugin.
		 *
		 * @since    1.0.0
		 * @access   protected
		 * @var      BDSTFW_Swiss_Toolkit_Loader    $loader    Maintains and registers all hooks for the plugin.
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
			if (defined('BDSTFW_SWISS_TOOLKIT_VERSION')) {
				$this->version = BDSTFW_SWISS_TOOLKIT_VERSION;
			} else {
				$this->version = '1.0.4';
			}
			$this->plugin_name = 'swiss-toolkit-for-wp';

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
		 * - BDSTFW_Swiss_Toolkit_Loader. Orchestrates the hooks of the plugin.
		 * - BDSTFW_Swiss_Toolkit_i18n. Defines internationalization functionality.
		 * - BDSTFW_Swiss_Toolkit_Admin. Defines all hooks for the admin area.
		 * - BDSTFW_Swiss_Toolkit_Public. Defines all hooks for the public side of the site.
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
			require_once plugin_dir_path(dirname(__FILE__)) . 'includes/class-boomdevs-swiss-toolkit-loader.php';

			/**
			 * The class responsible for defining internationalization functionality
			 * of the plugin.
			 */
			require_once plugin_dir_path(dirname(__FILE__)) . 'includes/class-boomdevs-swiss-toolkit-i18n.php';

			/**
			 * The class responsible for defining all actions that occur in the admin area.
			 */
			require_once plugin_dir_path(dirname(__FILE__)) . 'admin/class-boomdevs-swiss-toolkit-admin.php';

			/**
			 * The class responsible for defining all actions that occur in the public-facing
			 * side of the site.
			 */
			require_once plugin_dir_path(dirname(__FILE__)) . 'public/class-boomdevs-swiss-toolkit-public.php';

			/**
			 * The class responsible for loading codestar framework of the plugin.
			 */
			if (!class_exists('Redux') && file_exists(plugin_dir_path(dirname(__FILE__)) . 'libs/redux-framework/redux-framework.php')) {
				require_once plugin_dir_path(dirname(__FILE__)) . 'libs/redux-framework/redux-framework.php';
			}

			/**
			 * The class responsible for loading codestar framework of the plugin.
			 */
			require_once plugin_dir_path(dirname(__FILE__)) . 'includes/class-boomdevs-swiss-toolkit-settings.php';
			require_once plugin_dir_path(dirname(__FILE__)) . 'includes/class-boomdevs-swiss-tookit-user-settings.php';

			/**
			 * The class responsible for defining internationalization functionality
			 * of the plugin.
			 */
			require_once plugin_dir_path(dirname(__FILE__)) . 'includes/plugins/class-boomdevs-swiss-toolkit-upload-size-limit.php';

			require_once plugin_dir_path(dirname(__FILE__)) . 'includes/plugins/class-boomdevs-swiss-toolkit-upload-chunk-files.php';

			require_once plugin_dir_path(dirname(__FILE__)) . 'includes/plugins/class-boomdevs-swiss-toolkit-favicon.php';

			require_once plugin_dir_path(dirname(__FILE__)) . 'includes/plugins/class-boomdevs-swiss-toolkit-extension-supports.php';

			require_once plugin_dir_path(dirname(__FILE__)) . 'includes/plugins/class-boomdevs-swiss-toolkit-avatar.php';

			require_once plugin_dir_path(dirname(__FILE__)) . 'includes/plugins/class-boomdevs-swiss-toolkit-username.php';

			require_once plugin_dir_path(dirname(__FILE__)) . 'generate-url/class-boomdevs-swiss-toolkit-generate-url.php';

			require_once plugin_dir_path(dirname(__FILE__)) . 'includes/plugins/class-boomdevs-swiss-toolkit-bulk-theme-delete.php';

			require_once plugin_dir_path(dirname(__FILE__)) . 'includes/plugins/class-boomdevs-swiss-toolkit-post-duplicate.php';

			require_once plugin_dir_path(dirname(__FILE__)) . 'includes/plugins/class-boomdevs-swiss-toolkit-header-footer-scripts.php';

			require_once plugin_dir_path(dirname(__FILE__)) . 'code-snippet/class-boomdevs-swiss-toolkit-code-snippet.php';

			require_once plugin_dir_path(dirname(__FILE__)) . 'includes/plugins/class-boomdevs-swiss-toolkit-generate-login-url.php';

			$this->loader = new BDSTFW_Swiss_Toolkit_Loader();
		}

		/**
		 * Define the locale for this plugin for internationalization.
		 *
		 * Uses the BDSTFW_Swiss_Toolkit_i18n class in order to set the domain and to register the hook
		 * with WordPress.
		 *
		 * @since    1.0.0
		 * @access   private
		 */
		private function set_locale()
		{

			$plugin_i18n = new BDSTFW_Swiss_Toolkit_i18n();

			$this->loader->add_action('plugins_loaded', $plugin_i18n, 'load_plugin_textdomain');
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
			$plugin_admin = new BDSTFW_Swiss_Toolkit_Admin($this->get_plugin_name(), $this->get_version());

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
			$plugin_public = new BDSTFW_Swiss_Toolkit_Public($this->get_plugin_name(), $this->get_version());

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
		 * @return    BDSTFW_Swiss_Toolkit_Loader    Orchestrates the hooks of the plugin.
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
}