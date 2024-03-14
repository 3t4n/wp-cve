<?php
/**
 * The file that defines the core plugin class
 *
 * A class definition that includes attributes and functions used across both the
 * public-facing side of the site and the admin area.
 *
 * @link       https://alttext.ai
 * @since      1.0.0
 *
 * @package    ATAI
 * @subpackage ATAI/includes
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
 * @package    ATAI
 * @subpackage ATAI/includes
 * @author     AltText.ai <info@alttext.ai>
 */
class ATAI {
	/**
	 * The loader that's responsible for maintaining and registering all hooks that power
	 * the plugin.
	 *
	 * @since    1.0.0
	 * @access   protected
	 * @var      ATAI_Loader    $loader    Maintains and registers all hooks for the plugin.
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
		if ( defined( 'ATAI_VERSION' ) ) {
			$this->version = ATAI_VERSION;
		} else {
			$this->version = '1.0.1';
		}
		$this->plugin_name = 'atai';

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
	 * - ATAI_Loader. Orchestrates the hooks of the plugin.
	 * - ATAI_i18n. Defines internationalization functionality.
	 * - ATAI_Admin. Defines all hooks for the admin area.
	 * - ATAI_Public. Defines all hooks for the public side of the site.
	 *
	 * Create an instance of the loader which will be used to register the hooks
	 * with WordPress.
	 *
	 * @since    1.0.0
	 * @access   private
	 */
	private function load_dependencies() {
    /**
		 * Database creation and migration methods.
		 */
		require_once plugin_dir_path( dirname( __FILE__ ) ) . 'includes/class-atai-database.php';

    /**
		 * The class housing utility methods used across the plugin.
		 */
		require_once plugin_dir_path( dirname( __FILE__ ) ) . 'includes/class-atai-utility.php';

		/**
		 * The class responsible for orchestrating the actions and filters of the
		 * core plugin.
		 */
		require_once plugin_dir_path( dirname( __FILE__ ) ) . 'includes/class-atai-loader.php';

		/**
		 * The class responsible for defining internationalization functionality
		 * of the plugin.
		 */
		require_once plugin_dir_path( dirname( __FILE__ ) ) . 'includes/class-atai-i18n.php';

    /**
		 * The class responsible for the API connection.
		 */
		require_once plugin_dir_path( dirname( __FILE__ ) ) . 'includes/class-atai-api.php';

    /**
		 * The class responsible for attachment handling.
		 */
		require_once plugin_dir_path( dirname( __FILE__ ) ) . 'includes/class-atai-attachment.php';

    /**
		 * The class responsible for post handling.
		 */
		require_once plugin_dir_path( dirname( __FILE__ ) ) . 'includes/class-atai-post.php';

		/**
		 * The class responsible for defining all actions that occur in the admin area.
		 */
		require_once plugin_dir_path( dirname( __FILE__ ) ) . 'admin/class-atai-admin.php';

    /**
		 * The class responsible for managing the settings page.
		 */
		require_once plugin_dir_path( dirname( __FILE__ ) ) . 'admin/class-atai-settings.php';

		$this->loader = new ATAI_Loader();
	}

	/**
	 * Define the locale for this plugin for internationalization.
	 *
	 * Uses the ATAI_i18n class in order to set the domain and to register the hook
	 * with WordPress.
	 *
	 * @since    1.0.0
	 * @access   private
	 */
	private function set_locale() {
		$plugin_i18n = new ATAI_i18n();

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
		$database = new ATAI_Database();
		$admin = new ATAI_Admin( $this->get_plugin_name(), $this->get_version() );
		$settings = new ATAI_Settings();
		$attachment = new ATAI_Attachment();
		$post = new ATAI_Post();

    // Database
    $this->loader->add_action( 'plugins_loaded', $database, 'check_database_schema' );

    // Admin
		$this->loader->add_action( 'admin_enqueue_scripts', $admin, 'enqueue_styles' );
		$this->loader->add_action( 'admin_enqueue_scripts', $admin, 'enqueue_scripts' );
		$this->loader->add_filter( 'plugin_row_meta', $admin, 'modify_plugin_row_meta', 10, 4 );

    // Settings
    $this->loader->add_action( 'admin_menu', $settings, 'register_settings_pages' );
		$this->loader->add_action( 'admin_init', $settings, 'register_settings' );
    $this->loader->add_action( 'admin_init', $settings, 'clear_error_logs' );
    $this->loader->add_action( 'admin_init', $settings, 'remove_api_key_missing_param' );
    $this->loader->add_action( 'admin_notices', $settings, 'display_insufficient_credits_notice' );
    $this->loader->add_action( 'admin_notices', $settings, 'display_api_key_missing_notice' );
    $this->loader->add_action( 'wp_ajax_atai_expire_insufficient_credits_notice', $settings, 'expire_insufficient_credits_notice' );

    $this->loader->add_filter( 'pre_update_option_atai_api_key', $settings, 'save_api_key', 10, 2 );

    // Attachment
    $this->loader->add_action( 'admin_init', $attachment, 'action_single_generate', 99 );
    $this->loader->add_action( 'add_attachment', $attachment, 'add_attachment', 10, 1 );
    $this->loader->add_action( 'wp_ajax_atai_single_generate', $attachment, 'ajax_single_generate' );
    $this->loader->add_action( 'wp_ajax_atai_bulk_generate', $attachment, 'ajax_bulk_generate' );
    $this->loader->add_action( 'wp_ajax_atai_check_image_eligibility', $attachment, 'ajax_check_attachment_eligibility' );
    $this->loader->add_action( 'admin_notices', $attachment, 'render_bulk_select_notice' );

    $this->loader->add_filter( 'bulk_actions-upload', $attachment, 'add_bulk_select_action', 10, 1 );
    $this->loader->add_filter( 'handle_bulk_actions-upload', $attachment, 'bulk_select_action_handler', 10, 3 );

    // Post
    $this->loader->add_action( 'deleted_post', $post, 'on_post_deleted' );
    $this->loader->add_action( 'add_meta_boxes', $post, 'add_bulk_generate_meta_box' );
    $this->loader->add_action( 'wp_ajax_atai_enrich_post_content', $post, 'enrich_post_content' );
    $this->loader->add_action( 'wp_ajax_atai_check_enrich_post_content_transient', $post, 'display_enrich_post_content_success_notice' );
    $this->loader->add_action( 'admin_notices', $post, 'display_enrich_post_content_success_notice' );

    // Bulk action post types
    $post_types = array( 'post', 'page' ); // Default to Post and Page types
    $post_types = apply_filters( 'atai_bulk_action_post_types', $post_types ); // Allow user-defined custom types
    foreach ($post_types as $post_type) {
      $this->loader->add_filter( "bulk_actions-edit-{$post_type}", $post, 'add_bulk_select_action', 10, 1 );
      $this->loader->add_filter( "handle_bulk_actions-edit-{$post_type}", $post, 'bulk_select_action_handler', 10, 3 );
    }

    // Other plugin integrations
    $this->loader->add_action( 'pll_translate_media', $attachment, 'on_translation_created', 99, 3 );
  }

  /**
	 * Register all of the hooks related to the public-facing functionality
	 * of the plugin.
	 *
	 * @since    1.0.5
	 * @access   private
	 */
	private function define_public_hooks() {
		$api = new ATAI_API( null );

    $this->loader->add_action( 'rest_api_init', $api, 'register_webhook_endpoint' );
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
	 * @return    ATAI_Loader    Orchestrates the hooks of the plugin.
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
