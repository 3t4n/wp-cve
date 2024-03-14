<?php

if ( ! defined( 'ABSPATH' ) ) {
	exit;
}
/**
 * The file that defines the core plugin class
 * A class definition that includes attributes and functions used across both the
 * public-facing side of the site and the admin area.
 *
 * @link       https://payamito.com/
 * @since      1.0.0
 * @package    Payamito
 * @subpackage Payamito/includes
 */

/**
 * The core plugin class.
 * This is used to define internationalization, admin-specific hooks, and
 * public-facing site hooks.
 * Also maintains the unique identifier of this plugin as well as the current
 * version of the plugin.
 *
 * @since      1.0.0
 * @package    Payamito
 * @subpackage Payamito/includes
 * @author     payamito <payamito@gmail.com>
 */
if ( ! class_exists( 'Payamito' ) ) {
	class Payamito
	{

		/**
		 * The loader that's responsible for maintaining and registering all hooks that power
		 * the plugin.
		 *
		 * @since    1.0.0
		 * @access   protected
		 * @var      Payamito_Loader $loader Maintains and registers all hooks for the plugin.
		 */
		protected $loader;

		/**
		 * The unique identifier of this plugin.
		 *
		 * @since    1.0.0
		 * @access   protected
		 * @var      string $plugin_name The string used to uniquely identify this plugin.
		 */
		protected $plugin_name;


		/**
		 * The current version of the plugin.
		 *
		 * @since    1.0.0
		 * @access   protected
		 * @var      string $version The current version of the plugin.
		 */
		protected $version;

		public static $_instance = null;

		// If the single instance hasn't been set, set it now.
		public static function get_instance()
		{
			if ( ! self::$_instance ) {
				self::$_instance = new self();
			}

			return self::$_instance;
		}

		/**
		 * Define the core functionality of the plugin.
		 * Set the plugin name and the plugin version that can be used throughout the plugin.
		 * Load the dependencies, define the locale, and set the hooks for the admin area and
		 * the public-facing side of the site.
		 *
		 * @since    1.0.0
		 */
		public function __construct()
		{
			if ( defined( 'PAYAMITO_VERSION' ) ) {
				$this->version = PAYAMITO_VERSION;
			} else {
				$this->version = '2.0.6';
			}
			$this->plugin_name = 'payamito';
			$this->load_dependencies();
			Payamito_DB::create_tabls();
			Payamito_DB::updateTable2024122();
			$this->define_admin_hooks();
			$this->define_public_hooks();
			$this->init_class();
		}

		public function init_class()
		{
			new Payamito_Cron;
		}

		/**
		 * Load the required dependencies for this plugin.
		 * Include the following files that make up the plugin:
		 * - Payamito_Loader. Orchestrates the hooks of the plugin.
		 * - Payamito_i18n. Defines internationalization functionality.
		 * - Payamito_Admin. Defines all hooks for the admin area.
		 * - Payamito_Public. Defines all hooks for the public side of the site.
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
			require_once plugin_dir_path( dirname( __FILE__ ) ) . 'includes/class-db.php';
			require_once plugin_dir_path( dirname( __FILE__ ) ) . 'admin/class-sent.php';
			require_once plugin_dir_path( dirname( __FILE__ ) ) . 'admin/class-other-plugins.php';
			require_once plugin_dir_path( dirname( __FILE__ ) ) . 'includes/class-payamito-loader.php';
			require_once plugin_dir_path( dirname( __FILE__ ) ) . 'includes/class-payamito-connection.php';
			require_once plugin_dir_path( dirname( __FILE__ ) ) . 'includes/class-payamito-getway.php';
			require_once plugin_dir_path( dirname( __FILE__ ) ) . 'includes/class-payamito-opt.php';
			require_once plugin_dir_path( dirname( __FILE__ ) ) . 'includes/class-payamito-cron.php';
			require_once plugin_dir_path( dirname( __FILE__ ) ) . 'includes/utils/functions.php';

			/**
			 * The class responsible for defining internationalization functionality
			 * of the plugin.
			 */
			require_once plugin_dir_path( dirname( __FILE__ ) ) . 'includes/class-payamito-i18n.php';

			/**
			 * The class responsible for defining all actions that occur in the public-facing
			 * side of the site.
			 */
			require_once plugin_dir_path( dirname( __FILE__ ) ) . 'public/class-payamito-public.php';

			/**
			 * The class responsible for defining all actions that occur in the admin area.
			 */
			require_once plugin_dir_path( dirname( __FILE__ ) ) . 'admin/class-payamito-admin.php';

			$this->loader = new Payamito_Loader();

			if ( is_admin() ) {
				//require_once plugin_dir_path( dirname( __FILE__ ) ) . 'includes/class-payamito-phonebook.php';
				require_once plugin_dir_path( dirname( __FILE__ ) ) . 'includes/direct-send/class-direct-send.php';
			}
		}

		/**
		 * Register all the hooks related to the admin area functionality
		 * of the plugin.
		 *
		 * @since    1.0.0
		 * @access   private
		 */
		private function define_admin_hooks()
		{
			$plugin_admin = new Payamito_Admin( $this->get_plugin_name(), $this->get_version() );
			$this->loader->add_action( 'wp_ajax_init_ajax', $plugin_admin, 'payamito_init_ajax' );
			$this->loader->add_action( 'admin_enqueue_scripts', $plugin_admin, 'enqueue_styles' );
			$this->loader->add_action( 'admin_enqueue_scripts', $plugin_admin, 'enqueue_scripts' );
		}

		/**
		 * Register all the hooks related to the public-facing functionality
		 * of the plugin.
		 *
		 * @since    1.0.0
		 * @access   private
		 */
		private function define_public_hooks()
		{
			$plugin_public = new Payamito_Public( $this->get_plugin_name(), $this->get_version() );

			$this->loader->add_action( 'wp_enqueue_scripts', $plugin_public, 'enqueue_styles' );
			$this->loader->add_action( 'wp_enqueue_scripts', $plugin_public, 'enqueue_scripts' );

			add_action( 'admin_enqueue_scripts', [ $this, 'admin_enqueue_scripts' ] );

			add_action( 'wp_enqueue_scripts', [ $this, 'enqueue_scripts' ] );

			add_action( 'plugins_loaded', [ $this, 'on_plugins_loaded' ], - 10 );
		}

		/**
		 * This ensures `payamito_loaded` is called only after all other plugins
		 *
		 * @since 1.1.2
		 */
		public function on_plugins_loaded()
		{
			do_action( 'payamito_loaded' );
		}

		/**
		 * Register and enqueue public scripts
		 * of the plugin.
		 *
		 * @since    1.1.1
		 * @access   public
		 */
		public function admin_enqueue_scripts()
		{
			if ( isset( $_GET['page'] ) ) {
				if ( $_GET['page'] === 'payamito' || $_GET['page'] === 'payamito_logs' ) {
					wp_enqueue_script( "payamito-modal-js", PAYAMITO_URL . "/admin/js/modal.js", [ 'jquery' ], $this->get_version(), true );
					wp_enqueue_script( "payamito-tooltipster-js", PAYAMITO_URL . "/admin/js/tooltipster.main.min.js", [ 'jquery' ], $this->get_version(), true );
					wp_enqueue_script( "payamito-copy-js", PAYAMITO_URL . "/admin/js/copy.min.js", [ 'jquery' ], $this->get_version(), true );
					wp_enqueue_script( "payamito-chosen-js", PAYAMITO_URL . "/admin/js/chosen.jquery.min.js", [ 'jquery' ], $this->get_version(), true );
					wp_enqueue_script( "payamito-spinner-js", PAYAMITO_URL . "/assets/js/spinner.js", [ 'jquery' ], $this->get_version(), true );

					/////////////////css

					wp_enqueue_style( "payamito-modal-css", PAYAMITO_URL . "/admin/css/modal.css", [], $this->get_version() );
					wp_enqueue_style( "payamito-tooltips-css", PAYAMITO_URL . "/admin/css/tooltipster.min.css", [], $this->get_version() );
					wp_enqueue_style( "payamito-chosen-css", PAYAMITO_URL . "/admin/css/chosen.min.css", [], $this->get_version() );
					wp_enqueue_style( "payamito-settings-css", PAYAMITO_URL . "/assets/css/settings.css", [], $this->get_version() );
					wp_enqueue_style( "payamito-spinner-css", PAYAMITO_URL . "/assets/css/spinner.css", [], $this->get_version() );
					$direction = is_rtl() ? '.rtl' : '';
					wp_enqueue_style( 'payamito_bootstrap', PAYAMITO_URL . "/admin/css/bootstrap{$direction}.min.css", [], $this->version, 'all' );
				}
			}
		}

		/**
		 * Register and enqueue public scripts
		 * of the plugin.
		 *
		 * @since    1.1.2
		 * @access   public
		 */
		public function enqueue_scripts()
		{
			wp_enqueue_script( "payamito-notification-js", PAYAMITO_URL . "/assets/js/notification.js", [ 'jquery' ], $this->get_version(), true );
			wp_enqueue_style( "payamito-notification-css", PAYAMITO_URL . "/assets/css/notification.css", [], $this->get_version() );

			wp_enqueue_style( "payamito-spinner-css", PAYAMITO_URL . "/assets/css/spinner.css", [], $this->get_version() );
			wp_enqueue_script( "payamito-spinner-js", PAYAMITO_URL . "/assets/js/spinner.js", [ 'jquery' ], $this->get_version(), true );
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
		 * @return    string    The name of the plugin.
		 * @since     1.0.0
		 */
		public function get_plugin_name()
		{
			return $this->plugin_name;
		}

		/**
		 * The reference to the class that orchestrates the hooks with the plugin.
		 *
		 * @return    Payamito_Loader    Orchestrates the hooks of the plugin.
		 * @since     1.0.0
		 */
		public function get_loader()
		{
			return $this->loader;
		}

		/**
		 * Retrieve the version number of the plugin.
		 *
		 * @return    string    The version number of the plugin.
		 * @since     1.0.0
		 */
		public function get_version()
		{
			return $this->version;
		}
	}
}
