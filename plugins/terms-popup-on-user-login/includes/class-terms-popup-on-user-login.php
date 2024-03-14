<?php

use WpLHLAdminUi\LicenseKeys\LicenseKeyHandler;


/**
 * The file that defines the core plugin class
 *
 * A class definition that includes attributes and functions used across both the
 * public-facing side of the site and the admin area.
 *
 * @link       https://www.lehelmatyus.com
 * @since      1.0.0
 *
 * @package    terms_popup_on_user_login
 * @subpackage terms_popup_on_user_login/includes
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
 * @package    terms_popup_on_user_login
 * @subpackage terms_popup_on_user_login/includes
 * @author     Lehel Matyus <contact@lehelmatyus.com>
 */
class terms_popup_on_user_login {

	/**
	 * The loader that's responsible for maintaining and registering all hooks that power
	 * the plugin.
	 *
	 * @since    1.0.0
	 * @access   protected
	 * @var      terms_popup_on_user_login_Loader    $loader    Maintains and registers all hooks for the plugin.
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
		if (defined('TERMS_POPUP_ON_USER_LOGIN_VERSION')) {
			$this->version = TERMS_POPUP_ON_USER_LOGIN_VERSION;
		} else {
			$this->version = '1.0.0';
		}
		$this->plugin_name = 'terms-popup-on-user-login';

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
	 * - terms_popup_on_user_login_Loader. Orchestrates the hooks of the plugin.
	 * - terms_popup_on_user_login_i18n. Defines internationalization functionality.
	 * - terms_popup_on_user_login_Admin. Defines all hooks for the admin area.
	 * - terms_popup_on_user_login_Public. Defines all hooks for the public side of the site.
	 *
	 * Create an instance of the loader which will be used to register the hooks
	 * with WordPress.
	 *
	 * @since    1.0.0
	 * @access   private
	 */
	private function load_dependencies() {

		/**
		 * Models
		 */

		require_once __DIR__ . '/../vendor/autoload.php';
		require_once plugin_dir_path(dirname(__FILE__)) . 'includes/license-key/class-license-key-data.php';

		require_once plugin_dir_path(dirname(__FILE__)) . 'includes/models/class-user-action-handler.php';
		require_once plugin_dir_path(dirname(__FILE__)) . 'includes/models/class-general-options.php';
		require_once plugin_dir_path(dirname(__FILE__)) . 'includes/models/class-modal-options.php';
		require_once plugin_dir_path(dirname(__FILE__)) . 'includes/models/class-email-options.php';
		require_once plugin_dir_path(dirname(__FILE__)) . 'includes/models/class-display-options.php';
		require_once plugin_dir_path(dirname(__FILE__)) . 'includes/models/class-woo-options.php';
		require_once plugin_dir_path(dirname(__FILE__)) . 'includes/models/class-license-options.php';
		require_once plugin_dir_path(dirname(__FILE__)) . 'includes/models/class-popup-type.php';
		require_once plugin_dir_path(dirname(__FILE__)) . 'admin/class-user-log-csv.php';

		require_once plugin_dir_path(dirname(__FILE__)) . 'includes/class-terms-popup-on-user-login-analytics.php';
		require_once plugin_dir_path(dirname(__FILE__)) . 'includes/class-terms-popup-on-user-login-modal-visibility-manger.php';

		/**
		 * The class responsible for admin ui helper functions
		 */
		if (!class_exists('LHL_Admin_UI_TPUL')) {
			require_once plugin_dir_path(dirname(__FILE__)) . 'includes/lhl-admin-ui/class-lhl-admin-ui.php';
		}

		/**
		 * Email Sender
		 */
		require_once plugin_dir_path(dirname(__FILE__)) . 'includes/class-terms-popup-on-user-login-emailsender.php';

		/**
		 * The class responsible for creating and managing custom DB Tabled
		 * of the plugin.
		 */
		require_once plugin_dir_path(dirname(__FILE__)) . 'includes/db/class-terms-popup-on-user-login-db-api.php';

		/**
		 * The class responsible for handling interaction with woocommerce
		 */
		require_once plugin_dir_path(dirname(__FILE__)) . 'includes/class-terms-popup-on-user-login-woo.php';
		require_once plugin_dir_path(dirname(__FILE__)) . 'includes/class-terms-popup-on-user-login-wootagger.php';

		/**
		 * The class responsible for orchestrating the actions and filters of the
		 * core plugin.
		 */
		require_once plugin_dir_path(dirname(__FILE__)) . 'includes/class-terms-popup-on-user-login-loader.php';

		/**
		 * The class responsible for orchestrating the actions and filters of the
		 * core plugin.
		 */
		require_once plugin_dir_path(dirname(__FILE__)) . 'includes/class-terms-popup-on-user-utils.php';

		/**
		 * The class responsible for defining internationalization functionality
		 * of the plugin.
		 */
		require_once plugin_dir_path(dirname(__FILE__)) . 'includes/class-terms-popup-on-user-login-i18n.php';

		/**
		 * The class responsible for Creating the RestAPI
		 */
		require_once plugin_dir_path(dirname(__FILE__)) . 'includes/class-terms-popup-on-user-login-restapi.php';

		/**
		 * The class responsible for adding the modal
		 */
		require_once plugin_dir_path(dirname(__FILE__)) . 'includes/class-terms-popup-on-user-login-modal.php';

		/**
		 * The class responsible for Creating the RestAPI
		 */
		require_once plugin_dir_path(dirname(__FILE__)) . 'admin/class-terms-popup-on-user-login-plugin-settings.php';

		/**
		 * The class responsible for defining all actions that occur in the admin area.
		 */
		require_once plugin_dir_path(dirname(__FILE__)) . 'admin/class-terms-popup-on-user-login-admin.php';

		/**
		 * The class responsible for defining all actions that occur in the admin area.
		 */
		require_once plugin_dir_path(dirname(__FILE__)) . 'admin/class-terms-popup-on-user-login-user-edit-page.php';

		/**
		 * The class responsible for defining all actions that occur in the public-facing
		 * side of the site.
		 */
		require_once plugin_dir_path(dirname(__FILE__)) . 'public/class-terms-popup-on-user-login-public.php';


		$this->loader = new Terms_Popup_On_User_Login_Loader();
	}

	/**
	 * Define the locale for this plugin for internationalization.
	 *
	 * Uses the terms_popup_on_user_login_i18n class in order to set the domain and to register the hook
	 * with WordPress.
	 *
	 * @since    1.0.0
	 * @access   private
	 */
	private function set_locale() {

		$plugin_i18n = new Terms_Popup_On_User_Login_i18n();

		$this->loader->add_action('plugins_loaded', $plugin_i18n, 'load_plugin_textdomain');
	}

	/**
	 * Register all of the hooks related to the admin area functionality
	 * of the plugin.
	 *
	 * @since    1.0.0
	 * @access   private
	 */
	private function define_admin_hooks() {

		$plugin_admin = new Terms_Popup_On_User_Login_Admin($this->get_plugin_name(), $this->get_version());

		$this->loader->add_action('admin_enqueue_scripts', $plugin_admin, 'enqueue_styles');
		$this->loader->add_action('admin_enqueue_scripts', $plugin_admin, 'enqueue_scripts');

		$plugin_settings = new Terms_Popup_On_User_Login_Admin_Settings($this->get_plugin_name(), $this->get_version());

		$this->loader->add_action('admin_menu', $plugin_settings, 'setup_plugin_options_menu');
		$this->loader->add_action('admin_init', $plugin_settings, 'initialize_general_options');
		$this->loader->add_action('admin_init', $plugin_settings, 'initialize_terms_modal_options');
		$this->loader->add_action('admin_init', $plugin_settings, 'initialize_terms_modal_display_options');
		$this->loader->add_action('admin_init', $plugin_settings, 'initialize_terms_modal_woo_options');
		$this->loader->add_action('admin_init', $plugin_settings, 'initialize_terms_modal_email_options');
		$this->loader->add_action('admin_init', $plugin_settings, 'initialize_reset_users_options');

		/**
		 * Display on Admin Interfaces who accepted the terms
		 * User profile and user list
		 */

		$user_profile_edit = new Terms_Popup_On_User_Login_User_Edit($this->get_plugin_name(), $this->get_version());
		$this->loader->add_action('show_user_profile', $user_profile_edit, 'show_user_profile_accepted_field');
		$this->loader->add_action('edit_user_profile', $user_profile_edit, 'show_user_profile_accepted_field');
		// In user List
		$this->loader->add_filter('manage_users_columns', $user_profile_edit, 'add_accepted_column_to_userlist');
		$this->loader->add_filter('manage_users_custom_column', $user_profile_edit, 'add_accepted_column_data', 10, 3);

		/**
		 * Schedule and event to clean up the log files
		 * custom event tpul_schedule_log_file_cleanup_event
		 * registered in class-user-log-csv.php
		 */

		$user_log_csv_gen = new User_Log_CSV();
		$this->loader->add_filter('tpul_schedule_log_file_cleanup_event', $user_log_csv_gen, 'cleanup_reports_folder');

		/**
		 * Show admin notifications
		 */
		// $admin_notices = new TPUL_Admin_Notices();
		// $this->loader->add_action( 'admin_notices', $admin_notices, 'license_key_expiring_soon');
		$admin_notices = new LicenseKeyHandler(new TPUL_LicsenseKeyDataProvider());;
		$this->loader->add_action('admin_notices', $admin_notices, 'license__key_expiring_soon_notification');


		/**
		 * Associate Anonymous ID with order
		 */
		$woo_tagger = new TPUL_Woo_Tagger();
		$this->loader->add_action('woocommerce_checkout_create_order', $woo_tagger, 'associate_cookie_with_order', 10, 2);
		$this->loader->add_action('woocommerce_checkout_create_order', $woo_tagger, 'log_cookie_email_association', 10, 2);
	}

	/**
	 * Register all of the hooks related to the public-facing functionality
	 * of the plugin.
	 *
	 * @since    1.0.0
	 * @access   private
	 */
	private function define_public_hooks() {

		/**
		 * JS Script Stuff
		 */
		$plugin_public = new Terms_Popup_On_User_Login_Public($this->get_plugin_name(), $this->get_version());

		$this->loader->add_action('wp_enqueue_scripts', $plugin_public, 'enqueue_styles');
		$this->loader->add_action('wp_enqueue_scripts', $plugin_public, 'enqueue_scripts');

		/**
		 * Rest APi stuff
		 */

		$plugin_restapi = new Terms_Popup_On_User_Login_Rest_API($this->get_plugin_name(), $this->get_version());
		$this->loader->add_action('rest_api_init', $plugin_restapi, 'register_routes');

		/**
		 * Login Modal handling
		 */

		$plugin_modal = new Terms_Popup_On_User_Login_Modal($this->get_plugin_name(), $this->get_version());


		// Add Modal to footer
		$this->loader->add_action('wp_footer', $plugin_modal, 'add_modal_to_footer');

		// Add Body Class to trigger modal popup
		$this->loader->add_action('body_class', $plugin_modal, 'add_slug_body_class', 10, 1);
		// Add Body Class to track location if option is turned on
		$this->loader->add_action('body_class', $plugin_modal, 'location_tracking_body_class', 10, 1);

		// Optionally load CSS in footer instead of header
		$plugin_public_assets = new Terms_Popup_On_User_Login_Public($this->get_plugin_name(), $this->get_version());
		$this->loader->add_action('wp_print_footer_scripts', $plugin_public_assets, 'enqueue_styles_in_footer');

		// Clear users acceptance for this session
		$this->loader->add_action('clear_auth_cookie', $plugin_modal, 'user_logout_clear_acceptance_for_this_session');

		/**
		 * Display Anonymous ID with order
		 */

		$woo_tagger = new TPUL_Woo_Tagger();
		$this->loader->add_action('woocommerce_admin_order_data_after_billing_address', $woo_tagger, 'display_cookie_value_in_order');
		// $this->loader->add_action('woocommerce_checkout_create_order', $woo_tagger, 'associate_cookie_with_order');
		// $this->loader->add_action('woocommerce_checkout_create_order', $woo_tagger, 'log_cookie_email_association');
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
	 * @return    terms_popup_on_user_login_Loader    Orchestrates the hooks of the plugin.
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
