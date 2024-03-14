<?php

/**
 * The core plugin class.
 */
class Send_Users_Email {
	protected $loader;
	protected $plugin_name;
	protected $version;

	/**
	 * Define the core functionality of the plugin.
	 */
	public function __construct() {

		if ( defined( 'SEND_USERS_EMAIL_VERSION' ) ) {
			$this->version = SEND_USERS_EMAIL_VERSION;
		} else {
			$this->version = '1.5.1';
		}

		$this->plugin_name = 'send-users-email';
		$this->load_dependencies();
		$this->set_locale();
		$this->define_admin_hooks();
	}

	/**
	 * Load the required dependencies for this plugin.
	 */
	private function load_dependencies() {
		require_once plugin_dir_path( dirname( __FILE__ ) ) . 'includes/class-send-users-email-loader.php';
		require_once plugin_dir_path( dirname( __FILE__ ) ) . 'includes/class-send-users-email-i18n.php';
		require_once plugin_dir_path( dirname( __FILE__ ) ) . 'admin/class-send-users-email-admin.php';
		require_once plugin_dir_path( dirname( __FILE__ ) ) . 'helpers/cleanup.class.php';
		require_once plugin_dir_path( dirname( __FILE__ ) ) . 'helpers/functions.php';
		$this->loader = new Send_Users_Email_Loader();
	}

	/**
	 * Define the locale for this plugin for internationalization.
	 */
	private function set_locale() {
		$plugin_i18n = new Send_Users_Email_i18n();
		$this->loader->add_action( 'plugins_loaded', $plugin_i18n, 'load_plugin_textdomain' );
	}

	/**
	 * Register all the hooks related to the admin area functionality of the plugin.
	 */
	private function define_admin_hooks() {
		$plugin_admin = new Send_Users_Email_Admin( $this->get_plugin_name(), $this->get_version() );
		$this->loader->add_action( 'admin_enqueue_scripts', $plugin_admin, 'enqueue_styles' );
		$this->loader->add_action( 'admin_enqueue_scripts', $plugin_admin, 'enqueue_scripts' );
		// Initialize Admin menu
		$this->loader->add_action( 'admin_menu', $plugin_admin, 'admin_menu' );
		// User email handler
		$this->loader->add_action( "wp_ajax_sue_user_email_ajax", $plugin_admin, 'handle_ajax_admin_user_email' );
		// User email send progress
		$this->loader->add_action( "wp_ajax_sue_email_users_progress", $plugin_admin,
			'handle_ajax_email_users_progress' );
		// Role email handler
		$this->loader->add_action( "wp_ajax_sue_role_email_ajax", $plugin_admin, 'handle_ajax_admin_role_email' );
		// Role email send progress
		$this->loader->add_action( "wp_ajax_sue_email_roles_progress", $plugin_admin,
			'handle_ajax_email_roles_progress' );
		// Settings ajax handler
		$this->loader->add_action( "wp_ajax_sue_settings_ajax", $plugin_admin, 'handle_ajax_admin_settings' );
		// wp mail log error
		$this->loader->add_action( "wp_mail_failed", $plugin_admin, 'handle_wp_mail_failed_action' );
		// Delete error log request handling
		$this->loader->add_action( 'admin_init', $plugin_admin, 'delete_error_log' );
		// Delete all queued emails
		$this->loader->add_action( 'admin_init', $plugin_admin, 'delete_all_queued_emails' );
		// Email log view request
		$this->loader->add_action( 'wp_ajax_sue_view_email_log_ajax', $plugin_admin, 'handle_ajax_view_email_log' );
		// Check admin capability when plugin initialize
		$this->loader->add_filter( 'init', $plugin_admin, 'check_administrator_capability' );
	}

	/**
	 * Run the loader to execute all the hooks with WordPress.
	 */
	public function run() {
		$this->loader->run();
	}

	/**
	 * The name of the plugin used to uniquely identify it within the context of
	 * WordPress and to define internationalization functionality.
	 */
	public function get_plugin_name() {
		return $this->plugin_name;
	}

	/**
	 * The reference to the class that orchestrates the hooks with the plugin.
	 */
	public function get_loader() {
		return $this->loader;
	}

	/**
	 * Retrieve the version number of the plugin.
	 */
	public function get_version() {
		return $this->version;
	}

}