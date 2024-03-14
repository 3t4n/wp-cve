<?php
/**
 * The file that defines the core plugin class
 *
 * A class definition that includes attributes and functions used across both the
 * public-facing side of the site and the admin area.
 *
 * @link       https://etracker.com
 * @since      1.0.0
 *
 * @package    Etracker
 */

namespace Etracker;

use Etracker\Plugin\Loader;
use Etracker\Plugin\I18n;
use Etracker\Plugin\Activator;
use Etracker\Plugin\CapabilityManager;
use Etracker\Frontend\Generator\TrackletGenerator;
use Etracker\Frontend\TrackletIntegrator;
use Etracker\Frontend\Hooks\ECommerceAPI;
use Etracker\Backend\Admin;
use Etracker\Backend\Cron;
use Etracker\Frontend\Hooks\ThirdParty\WP_Rocket;
use Etracker\Reporting\Report\ReportConfigFilter\ReportConfigFilterFactory;
use Etracker\Reporting\Report\ReportConfigFilter\CurrentQuarter;
use Etracker\Reporting\Report\ReportConfigFilter\CurrentYear;
use Etracker\Reporting\Report\ReportConfigFilter\Last30Days;

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
 *
 * @package    Etracker
 *
 * @author     etracker GmbH <support@etracker.com>
 */
class Etracker_Main {
	/**
	 * The loader that's responsible for maintaining and registering all hooks that power
	 * the plugin.
	 *
	 * @since    1.0.0
	 *
	 * @access   protected
	 *
	 * @var Loader $loader    Maintains and registers all hooks for the plugin.
	 */
	protected $loader;

	/**
	 * The tracklet that's responsible for generating etracker tracklet.
	 *
	 * @since    1.0.0
	 *
	 * @access   protected
	 *
	 * @var TrackletGenerator $tracklet    Generates etracker tracklet.
	 */
	protected $tracklet;

	/**
	 * The unique identifier of this plugin.
	 *
	 * @since    1.0.0
	 *
	 * @access   protected
	 *
	 * @var string $plugin_name    The string used to uniquely identify this plugin.
	 */
	protected $plugin_name;

	/**
	 * The current version of the plugin.
	 *
	 * @since    1.0.0
	 *
	 * @access   protected
	 *
	 * @var string $version    The current version of the plugin.
	 */
	protected $version;

	/**
	 * The cron handler.
	 *
	 * @var Cron $cron The cron object.
	 */
	protected $cron;

	/**
	 * The Admin backend.
	 *
	 * @var Admin $admin The admin backend object.
	 */
	protected $admin;

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
		if ( defined( 'ETRACKER_VERSION' ) ) {
			$this->version = ETRACKER_VERSION;
		} else {
			$this->version = '1.0.0';
		}
		$this->plugin_name = 'etracker';

		$this->load_dependencies();
		$this->set_locale();
		$this->define_admin_hooks();
		$this->define_public_hooks();
		$this->define_ecommerce_hooks();
		$this->define_cron_hooks();
		$this->configure_tracklet();
		$this->define_capabilities();
		$this->define_plugin_update_actions();
		$this->define_third_party_hooks();
		$this->register_report_config_filters();
	}

	/**
	 * Load the required dependencies for this plugin.
	 *
	 * Create an instance of the loader which will be used to register the hooks
	 * with WordPress.
	 *
	 * @since    1.0.0
	 *
	 * @access   private
	 */
	private function load_dependencies() {
		$this->loader   = new Loader();
		$this->tracklet = new TrackletGenerator( $this->get_plugin_name(), $this->get_version() );
		$this->cron     = new Cron( $this->get_plugin_name(), $this->get_version() );
		$this->admin    = new Admin( $this->get_plugin_name(), $this->get_version() );
	}

	/**
	 * Define the locale for this plugin for internationalization.
	 *
	 * Uses the I18n class in order to set the domain and to register the hook
	 * with WordPress.
	 *
	 * @since    1.0.0
	 *
	 * @access   private
	 */
	private function set_locale() {
		$plugin_i18n = new I18n();

		$this->loader->add_action( 'plugins_loaded', $plugin_i18n, 'load_plugin_textdomain' );
	}

	/**
	 * Register all of the hooks related to the admin area functionality
	 * of the plugin.
	 *
	 * @since    1.0.0
	 *
	 * @access   private
	 */
	private function define_admin_hooks() {
		$this->loader->add_action( 'admin_menu', $this->admin, 'add_admin_menu' );
		$this->loader->add_action( 'admin_init', $this->admin, 'settings_init' );
		$this->loader->add_filter( 'plugin_action_links_etracker/etracker.php', $this->admin, 'add_settings_link' );
		$this->loader->add_action( 'generate_rewrite_rules', $this->admin, 'generate_rewrite_rules' );
		// Add etracker data to posts and pages queries.
		$this->loader->add_filter( 'posts_join', $this->admin, 'posts_join', 10, 2 );
		$this->loader->add_filter( 'posts_orderby', $this->admin, 'posts_orderby', 10, 2 );
		// Hide etracker columns by default.
		$this->loader->add_filter( 'default_hidden_columns', $this->admin, 'column_hidden' );
		// Add styles for admin backend.
		$this->loader->add_action( 'admin_enqueue_scripts', $this->admin, 'enqueue_custom_admin_style' );
		// Admin notices.
		$this->loader->add_action( 'admin_notices', $this->admin, 'action_admin_notice_enable_tracking' );
		$this->loader->add_action( 'admin_notices', $this->admin, 'action_admin_notice_enable_integrated_reporting' );
		$this->loader->add_action( 'admin_notices', $this->admin, 'action_admin_notice_request_customer_polling' );
		$this->loader->add_action( 'wp_ajax_etracker_dismiss_customer_polling', $this->admin, 'action_dismiss_customer_polling' );
		$this->loader->add_action( 'wp_ajax_etracker_dismiss_notice_enable_integrated_reporting', $this->admin, 'action_dismiss_notice_enable_integrated_reporting' );
		// Add columns to manage posts overview. (and all other public post types).
		$this->loader->add_action( 'load-edit.php', $this, 'register_reporting_post_types', 99 );
	}

	/**
	 * Action hook to register reporting_post_types during `load-edit.php` hook.
	 *
	 * @since 2.1.0
	 *
	 * We need this action to support for example: WeDocs.
	 */
	public function register_reporting_post_types() {
		// We need another loader to be able to register hooks later.
		$reporting_loader = new Loader();

		foreach ( $this->admin->get_reporting_post_types() as $post_type ) {
			$reporting_loader->add_filter( "manage_{$post_type}_posts_columns", $this->admin, 'column_heading' );
			$reporting_loader->add_action( "manage_{$post_type}_posts_custom_column", $this->admin, 'column_content', 10, 2 );
			$reporting_loader->add_action( "manage_edit-{$post_type}_sortable_columns", $this->admin, 'column_sort' );
		}

		$reporting_loader->run();
	}

	/**
	 * Register all of the hooks related to the public-facing functionality
	 * of the plugin.
	 *
	 * @since    1.0.0
	 *
	 * @access   private
	 */
	private function define_public_hooks() {
		$plugin_public = new TrackletIntegrator( $this->get_plugin_name(), $this->get_version(), $this->tracklet );

		$this->loader->add_action( 'wp_head', $plugin_public, 'html_head_code' );
		$this->loader->add_filter( 'document_title_parts', $this->tracklet, 'document_title_parts', PHP_INT_MAX, 1 );
		$this->loader->add_filter( 'wpseo_title', $this->tracklet, 'wpseo_title', PHP_INT_MAX, 1 );
	}

	/**
	 * Register all of the hooks related to the woocommerce plugin.
	 *
	 * @since    1.8.0
	 *
	 * @access   private
	 */
	private function define_ecommerce_hooks() {
		if ( is_admin() ) {
			return;
		}

		$plugins = get_option( 'active_plugins' );
		if ( ! stripos( json_encode( $plugins ), '/woocommerce.php' ) ) {
			// Check sitewide plugins (multisite installations).
			$sitewide_plugins = get_site_option( 'active_sitewide_plugins' );
			if ( ! stripos( json_encode( $sitewide_plugins ), '/woocommerce.php' ) ) {
				return;
			}
		}

		$plugin_ecommerce_api = new ECommerceAPI();

		// The viewProduct event.
		$this->loader->add_action( 'woocommerce_after_single_product', $plugin_ecommerce_api, 'handle_view_product' );
		// The insertToBasket event (product detail page).
		$this->loader->add_action( 'woocommerce_after_add_to_cart_button', $plugin_ecommerce_api, 'handle_insert_to_basket' );
		// The inserToBasket event (list view). Prepare the links and attach events.
		$this->loader->add_filter( 'woocommerce_loop_add_to_cart_link', $plugin_ecommerce_api, 'filter_wc_loop_add_to_cart_link', 10, 3 );
		$this->loader->add_action( 'wp_footer', $plugin_ecommerce_api, 'js_event_loop_insert_to_basket' );
		// The order event.
		$this->loader->add_action( 'woocommerce_thankyou', $plugin_ecommerce_api, 'handle_order' );
		// The removeFromBasket event (cart page). Prepare the links and attach events.
		$this->loader->add_filter( 'woocommerce_cart_item_remove_link', $plugin_ecommerce_api, 'filter_wc_cart_item_remove_link', 10, 2 );
		$this->loader->add_action( 'woocommerce_after_cart', $plugin_ecommerce_api, 'js_event_remove_from_basket' );
		$this->loader->add_action( 'woocommerce_after_mini_cart', $plugin_ecommerce_api, 'js_event_remove_from_basket' );
		// Update quantity on cart page.
		$this->loader->add_action( 'woocommerce_after_cart', $plugin_ecommerce_api, 'js_event_update_quantity' );
		$this->loader->add_action( 'woocommerce_after_mini_cart', $plugin_ecommerce_api, 'js_event_update_quantity' );

		$this->loader->add_shortcode( 'etracker_send_wc_order', $plugin_ecommerce_api, 'handle_order_shortcode' );
	}

	/**
	 * Get plugin specific WordPress setting.
	 *
	 * @param string $name    Settings name.
	 * @param string $default Default value if setting does not exist.
	 *
	 * @return string|boolean|integer
	 *
	 * @since    1.0.0
	 *
	 * @access   private
	 */
	private function get_setting( $name, $default = false ) {
		$options = get_option( 'etracker_settings' );
		return ( empty( $options[ $name ] ) ) ? $default : $options[ $name ];
	}

	/**
	 * Setup tracklet with all settings from backend.
	 *
	 * Defines default settings for tracklet.
	 *
	 * @since    1.0.0
	 *
	 * @access   private
	 */
	private function configure_tracklet() {
		$this->tracklet->set_secure_code(
			$this->get_setting( 'etracker_secure_code', '' )
		);
		$this->tracklet->set_respect_dnt(
			( 'true' === $this->get_setting( 'etracker_respect_dnt', 'true' ) ) ? true : false
		);
		$this->tracklet->set_block_cookies(
			( 'true' === $this->get_setting( 'etracker_block_cookies', 'true' ) ) ? true : false
		);
		$this->tracklet->set_disable_et_pagename(
			( 'true' === $this->get_setting( 'etracker_disable_et_pagename', 'false' ) ) ? true : false
		);
		$this->tracklet->set_custom_attributes(
			$this->get_setting( 'etracker_custom_attributes', '' )
		);
		$this->tracklet->set_custom_tracking_domain(
			$this->get_setting( 'etracker_custom_tracking_domain', '' )
		);
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
	 *
	 * @return string The name of the plugin.
	 */
	public function get_plugin_name() {
		return $this->plugin_name;
	}

	/**
	 * The reference to the class that orchestrates the hooks with the plugin.
	 *
	 * @since     1.0.0
	 *
	 * @return Etracker_Loader Orchestrates the hooks of the plugin.
	 */
	public function get_loader() {
		return $this->loader;
	}

	/**
	 * Retrieve the version number of the plugin.
	 *
	 * @since     1.0.0
	 *
	 * @return string The version number of the plugin.
	 */
	public function get_version() {
		return $this->version;
	}

	/**
	 * Retrieve cron manager instance.
	 *
	 * @return Cron Cron manager.
	 */
	protected function get_cron(): Cron {
		return $this->cron;
	}

	/**
	 * Register all of the hooks related to the cron functionality
	 * of the plugin.
	 *
	 * @since    2.0.0
	 *
	 * @access   private
	 */
	private function define_cron_hooks() {
		$this->loader->add_action( 'etracker_cron_fetch_reports', $this->cron, 'action_fetch_reports' );
		$this->loader->add_action( 'etracker_cron_cleanup_logging', $this->cron, 'action_cleanup_logging' );
		$this->loader->add_action( 'updated_option', $this->cron, 'action_schedule_fetch_reports_on_settings_changes', 10, 3 );
		$this->loader->add_action( 'updated_option', $this->cron, 'action_schedule_customer_polling', 10, 3 );
		$this->loader->add_action( 'etracker_cron_trigger_customer_polling', $this->cron, 'action_enable_customer_polling' );
	}

	/**
	 * Add etracker capabilities.
	 *
	 * @since 2.0.0
	 *
	 * @access private
	 */
	private function define_capabilities() {
		$capability_manager = new CapabilityManager();

		$this->loader->add_action( 'init', $capability_manager, 'add_capabilities' );
	}

	/**
	 * Register actions for plugin update maintainance tasks.
	 *
	 * @since 2.0.0
	 *
	 * @access private
	 */
	private function define_plugin_update_actions() {
		$this->loader->add_action( 'plugins_loaded', Activator::class, 'update_db_check', 10, 0 );
	}

	/**
	 * Register 3rd party hooks to ensure etracker works as designed.
	 *
	 * @since 2.1.0
	 *
	 * @access private
	 */
	private function define_third_party_hooks() {
		$integrations = array(
			WP_Rocket::class,
		);
		foreach ( $integrations as $i ) {
			$filters = call_user_func( $i . '::get_subscribed_filters' );
			foreach ( $filters as $hook => $listeners ) {
				foreach ( $listeners as $listener ) {
					if ( array_key_exists( 'accepted_args', $listener ) && array_key_exists( 'priority', $listener ) ) {
						$this->loader->add_filter( $hook, $listener['component'], $listener['callback'], $listener['priority'], $listener['accepted_args'] );
					} elseif ( array_key_exists( 'priority', $listener ) ) {
						$this->loader->add_filter( $hook, $listener['component'], $listener['callback'], $listener['priority'] );
					} else {
						$this->loader->add_filter( $hook, $listener['component'], $listener['callback'] );
					}
				}
			}
		}
	}

	/**
	 * Register ReportConfigFilters.
	 *
	 * @access private
	 */
	private function register_report_config_filters() {
		$report_config_filters = array(
			Last30Days::class,
			CurrentQuarter::class,
			CurrentYear::class,
		);
		// Register each ReportConfigFilter.
		foreach ( $report_config_filters as $filter_class ) {
			ReportConfigFilterFactory::register_filter( new $filter_class() );
		}
	}
}
