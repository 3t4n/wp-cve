<?php

/**
 * The file that defines the core plugin class
 *
 * A class definition that includes attributes and functions used across both the
 * public-facing side of the site and the admin area.
 *
 * @link       https://ays-pro.com/
 * @since      1.0.0
 *
 * @package    Ays_Chatgpt_Assistant
 * @subpackage Ays_Chatgpt_Assistant/includes
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
 * @package    Ays_Chatgpt_Assistant
 * @subpackage Ays_Chatgpt_Assistant/includes
 * @author     Ays_ChatGPT Assistant Team <info@ays-pro.com>
 */
class Chatgpt_Assistant {

	/**
	 * The loader that's responsible for maintaining and registering all hooks that power
	 * the plugin.
	 *
	 * @since    1.0.0
	 * @access   protected
	 * @var      Chatgpt_Assistant_Loader    $loader    Maintains and registers all hooks for the plugin.
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
		if ( defined( 'CHATGPT_ASSISTANT_VERSION' ) ) {
			$this->version = CHATGPT_ASSISTANT_VERSION;
		} else {
			$this->version = '1.0.0';
		}
		$this->plugin_name = "ays-chatgpt-assistant";

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
	 * - Chatgpt_Assistant_Loader. Orchestrates the hooks of the plugin.
	 * - Chatgpt_Assistant_i18n. Defines internationalization functionality.
	 * - Chatgpt_Assistant_Admin. Defines all hooks for the admin area.
	 * - Chatgpt_Assistant_Public. Defines all hooks for the public side of the site.
	 *
	 * Create an instance of the loader which will be used to register the hooks
	 * with WordPress.
	 *
	 * @since    1.0.0
	 * @access   private
	 */
	private function load_dependencies() {
		if ( ! class_exists( 'WP_List_Table' ) ) {
            require_once(ABSPATH . 'wp-admin/includes/class-wp-list-table.php');
        }

		/**
		 * The class responsible for defining all functions for getting all survey data
		 */
		require_once plugin_dir_path( dirname( __FILE__ ) ) . 'includes/class-chatgpt-assistant-data.php';

		/**
		 * The classes responsible for defining all actions including db that occur in the plugin area.
		 */		
		require_once plugin_dir_path( dirname( __FILE__ ) ) . 'includes/chatgpt-assistant-db-actions/class-chatgpt-assistant-main-db-actions.php';
		require_once plugin_dir_path( dirname( __FILE__ ) ) . 'includes/chatgpt-assistant-db-actions/class-chatgpt-assistant-settings-db-actions.php';
		require_once plugin_dir_path( dirname( __FILE__ ) ) . 'includes/chatgpt-assistant-db-actions/class-chatgpt-assistant-general-settings-db-actions.php';
		require_once plugin_dir_path( dirname( __FILE__ ) ) . 'includes/chatgpt-assistant-db-actions/class-chatgpt-assistant-front-chat-db-actions.php';
		require_once plugin_dir_path( dirname( __FILE__ ) ) . 'includes/chatgpt-assistant-db-actions/class-chatgpt-assistant-db-actions.php';
		// require_once plugin_dir_path( dirname( __FILE__ ) ) . 'includes/class-chatgpt-assistant-settings-db-actions.php';
		// require_once plugin_dir_path( dirname( __FILE__ ) ) . 'includes/class-chatgpt-assistant-db-actions.php';

		/**
		 * The class responsible for orchestrating the actions and filters of the
		 * core plugin.
		 */
		require_once plugin_dir_path( dirname( __FILE__ ) ) . 'includes/class-chatgpt-assistant-loader.php';

		/**
		 * The class responsible for defining internationalization functionality
		 * of the plugin.
		 */
		require_once plugin_dir_path( dirname( __FILE__ ) ) . 'includes/class-chatgpt-assistant-i18n.php';

		/**
		 * The class responsible for defining all actions that occur in the admin area.
		 */
		require_once plugin_dir_path( dirname( __FILE__ ) ) . 'admin/class-chatgpt-assistant-admin.php';

		/*
         * The class is responsible for showing logs data in wordpress default WP_LIST_TABLE style
         */
		require_once plugin_dir_path( dirname( __FILE__ ) ) . 'includes/lists/class-chatgpt-assistant-rates-list-table.php';

		/**
		 * The class responsible for defining all actions that occur in the public-facing
		 * side of the site.
		 */
		require_once plugin_dir_path( dirname( __FILE__ ) ) . 'public/class-chatgpt-assistant-public.php';

		$this->loader = new Chatgpt_Assistant_Loader();
	}

	/**
	 * Define the locale for this plugin for internationalization.
	 *
	 * Uses the Chatgpt_Assistant_i18n class in order to set the domain and to register the hook
	 * with WordPress.
	 *
	 * @since    1.0.0
	 * @access   private
	 */
	private function set_locale() {

		$plugin_i18n = new Chatgpt_Assistant_i18n();

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

		$plugin_admin = new Chatgpt_Assistant_Admin( $this->get_plugin_name(), $this->get_version() );

        $this->loader->add_action( 'admin_menu', $plugin_admin, 'add_plugin_admin_menu' );

		$this->loader->add_action( 'admin_menu', $plugin_admin, 'add_plugin_settings_submenu', 85 );

		$this->loader->add_action( 'admin_menu', $plugin_admin, 'add_plugin_front_chat_submenu', 95 );

		$this->loader->add_action( 'admin_menu', $plugin_admin, 'add_plugin_embedding_submenu', 100 );
		
        $this->loader->add_action( 'admin_menu', $plugin_admin, 'add_plugin_content_generator_submenu', 105 );

		$this->loader->add_action( 'admin_menu', $plugin_admin, 'add_plugin_image_generator_submenu', 110 );

		$this->loader->add_action( 'admin_menu', $plugin_admin, 'add_plugin_logs_submenu', 115 );

		$this->loader->add_action( 'admin_menu', $plugin_admin, 'add_plugin_rates_submenu', 120 );

        $this->loader->add_action( 'admin_menu', $plugin_admin, 'add_plugin_general_settings_submenu', 125 );
		
        $this->loader->add_action( 'admin_menu', $plugin_admin, 'add_plugin_how_to_use_submenu', 130 );

        $this->loader->add_action( 'admin_menu', $plugin_admin, 'add_plugin_features_submenu', 135 );

        $this->loader->add_action( 'admin_menu', $plugin_admin, 'add_plugin_gift_submenu', 140 );		

		$this->loader->add_action( 'admin_enqueue_scripts', $plugin_admin, 'enqueue_styles' );
		$this->loader->add_action( 'admin_enqueue_scripts', $plugin_admin, 'enqueue_scripts' );
		$this->loader->add_action( 'admin_enqueue_scripts', $plugin_admin, 'disable_scripts', 100 );
		$this->loader->add_action( 'all_admin_notices', $plugin_admin, 'chatgpt_display_chat_icon' );

		// Sale Banner
		$this->loader->add_action( 'admin_notices', $plugin_admin, 'ays_chatgpt_sale_baner', 1 );
		// $this->loader->add_action( 'admin_notices', $plugin_admin, 'ays_chatgpt_gift_baner', 1 );
		$this->loader->add_action( 'wp_ajax_ays_chatgpt_dismiss_button', $plugin_admin, 'ays_chatgpt_dismiss_button' );
        $this->loader->add_action( 'wp_ajax_nopriv_ays_chatgpt_dismiss_button', $plugin_admin, 'ays_chatgpt_dismiss_button' );

		// Admin AJAX action
		$this->loader->add_action( 'wp_ajax_ays_chatgpt_admin_ajax', $plugin_admin, 'ays_chatgpt_admin_ajax' );
		$this->loader->add_action( 'wp_ajax_nopriv_ays_chatgpt_admin_ajax', $plugin_admin, 'ays_chatgpt_admin_ajax' );

		// Add Settings link to the plugin
		$plugin_basename = plugin_basename( plugin_dir_path( __DIR__ ) . 'chatgpt-assistant.php' );
		$this->loader->add_filter( 'plugin_action_links_' . $plugin_basename, $plugin_admin, 'add_action_links' );

	}

	/**
	 * Register all of the hooks related to the public-facing functionality
	 * of the plugin.
	 *
	 * @since    1.0.0
	 * @access   private
	 */
	private function define_public_hooks() {

		$plugin_public = new Chatgpt_Assistant_Public( $this->get_plugin_name(), $this->get_version() );

		$this->loader->add_action( 'wp_enqueue_scripts', $plugin_public, 'enqueue_styles' );
		$this->loader->add_action( 'wp_enqueue_scripts', $plugin_public, 'enqueue_scripts' );
		$this->loader->add_action( 'wp_footer', $plugin_public, 'ays_chatgpt_shortcodes_show_all');

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
	 * @return    Chatgpt_Assistant_Loader    Orchestrates the hooks of the plugin.
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
