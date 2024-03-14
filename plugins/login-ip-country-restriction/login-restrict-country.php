<?php
/**
 * Plugin Name: Login IP & Country Restriction
 * Plugin URI: https://iuliacazan.ro/login-ip-country-restriction/
 * Description: This plugin hooks in the authenticate filter. By default, the plugin is set to allow all access and you can configure the plugin to allow the login only from some specified IPs or the specified countries. PLEASE MAKE SURE THAT YOU CONFIGURE THE PLUGIN TO ALLOW YOUR OWN ACCESS. If you set a restriction by IP, then you have to add your own IP (if you are using the plugin in a local setup the IP is 127.0.0.1 or ::1, this is added in your list by default). If you set a restriction by country, then you have to select from the list of countries at least your country. The both types of restrictions work independent, so you can set only one type of restriction or both if you want.
 *
 * Text Domain: slicr
 * Domain Path: /langs
 * Version: 6.4.1
 * Author: Iulia Cazan
 * Author URI: https://profiles.wordpress.org/iulia-cazan
 * Donate link: https://www.paypal.com/cgi-bin/webscr?cmd=_s-xclick&hosted_button_id=JJA37EHZXWUTJ
 * License: GPL2
 *
 * @package ic-devops
 *
 * Copyright (C) 2014-2023 Iulia Cazan
 *
 * This program is free software; you can redistribute it and/or modify
 * it under the terms of the GNU General Public License, version 2, as
 * published by the Free Software Foundation.
 *
 * This program is distributed in the hope that it will be useful,
 * but WITHOUT ANY WARRANTY; without even the implied warranty of
 * MERCHANTABILITY or FITNESS FOR A PARTICULAR PURPOSE.  See the
 * GNU General Public License for more details.
 *
 * You should have received a copy of the GNU General Public License
 * along with this program; if not, write to the Free Software
 * Foundation, Inc., 51 Franklin St, Fifth Floor, Boston, MA  02110-1301  USA
 */

// Define the plugin version.
define( 'SISANU_RCIL_DB_OPTION', 'sisanu_rcil' );
define( 'SISANU_RCIL_CURRENT_DB_VERSION', 6.41 );
define( 'SISANU_RCIL_SLUG', 'slicr' );
define( 'SISANU_RCIL_DIR', trailingslashit( plugin_dir_path( __FILE__ ) ) );
define( 'SISANU_RCIL_URL', trailingslashit( plugins_url( '/', plugin_basename( __FILE__ ) ) ) );

/**
 * Class for Login IP & Country Restriction.
 */
class SISANU_Restrict_Country_IP_Login {
	const PLUGIN_NAME        = 'Login IP & Country Restriction';
	const PLUGIN_SUPPORT_URL = 'https://wordpress.org/support/plugin/login-ip-country-restriction/';
	const PLUGIN_TRANSIENT   = 'sislrc-plugin-notice';
	const CHAR_IPPIN         = '&#x1F4CD;';
	const CHAR_ALLOW         = '&#10004;';
	const CHAR_BLOCK         = '&#10006;';

	/**
	 * Other settings.
	 *
	 * @var array
	 */
	public static $settings = [];

	/**
	 * Allowed countries.
	 *
	 * @var array
	 */
	public static $allowed_countries = [];

	/**
	 * Blocked countries.
	 *
	 * @var array
	 */
	public static $blocked_countries = [];

	/**
	 * Allowed IPs.
	 *
	 * @var array
	 */
	public static $allowed_ips = [];

	/**
	 * Blocked IPs.
	 *
	 * @var array
	 */
	public static $blocked_ips = [];

	/**
	 * Allowed Roles.
	 *
	 * @var array
	 */
	public static $bypass_roles = [];

	/**
	 * All countries.
	 *
	 * @var boolean
	 */
	public static $all_countries = false;

	/**
	 * All IPs.
	 *
	 * @var boolean
	 */
	public static $all_ips = false;

	/**
	 * No roles bypass.
	 *
	 * @var boolean
	 */
	public static $no_roles_bypass = true;

	/**
	 * Restriction rules.
	 *
	 * @var null
	 */
	public static $rules = null;

	/**
	 * Maybe redirect the URLs.
	 *
	 * @var array
	 */
	public static $custom_redirects = [
		'status'   => 0,
		'login'    => 0,
		'register' => 0,
		'urls'     => [],
	];

	/**
	 * If he current user restriction was assessed.
	 *
	 * @var boolean
	 */
	private static $curent_user_assessed = false;

	/**
	 * If he current user has restriction.
	 *
	 * @var boolean
	 */
	private static $curent_user_restriction = false;

	/**
	 * The plugin URL.
	 *
	 * @var string
	 */
	private static $plugin_url = '';

	/**
	 * The plugin debug.
	 *
	 * @var boolean
	 */
	private static $is_pro = false;

	/**
	 * Maybe simulate restriction.
	 *
	 * @var array
	 */
	public static $simulate;

	/**
	 * Maybe auth user ID.
	 *
	 * @var integer
	 */
	public static $user_id = 0;

	/**
	 * Class instance.
	 *
	 * @var object
	 */
	private static $instance;

	/**
	 * Get active object instance
	 *
	 * @return object
	 */
	public static function get_instance() { // phpcs:ignore
		if ( ! self::$instance ) {
			self::$instance = new SISANU_Restrict_Country_IP_Login();
		}
		return self::$instance;
	}

	/**
	 * Class constructor. Includes constants, includes and init method.
	 *
	 * @return void
	 */
	public function __construct() {
		$this->init();
	}

	/**
	 * Run action and filter hooks.
	 *
	 * @return void
	 */
	private function init() {
		self::load_settings();

		$ob_class = get_called_class();
		add_action( 'plugins_loaded', [ $ob_class, 'load_textdomain' ] );
		if ( file_exists( dirname( __FILE__ ) . '/pro-settings.php' ) ) {
			self::$is_pro = true;
			include_once dirname( __FILE__ ) . '/pro-settings.php';
		}

		if ( empty( self::$settings['temp_disable'] ) ) {
			if ( self::$is_pro && function_exists( 'RCIL\Pro\maybe_simulate_restriction' ) ) {
				self::$simulate = RCIL\Pro\maybe_simulate_restriction();
				self::hookup_the_custom_restrictions();
			} else {
				if ( false === self::$all_countries || false === self::$all_ips ) {
					self::hookup_the_custom_restrictions();
				}
			}
		}

		if ( is_admin() ) {
			add_action( 'init', [ $ob_class, 'maybe_upgrade_version' ], 1 );
			add_action( 'init', [ $ob_class, 'maybe_save_settings' ], 1 );
			self::$plugin_url = admin_url( 'options-general.php?page=login-ip-country-restriction-settings' );
			add_action( 'admin_menu', [ $ob_class, 'admin_menu' ] );
			add_filter( 'check_rule_type_save', [ $ob_class, 'check_rule_type_save' ] );
			add_action( 'admin_notices', [ $ob_class, 'admin_notices' ] );

			// Enqueue the plugin assets for back-end.
			add_action( 'admin_enqueue_scripts', [ $ob_class, 'load_assets' ] );
			add_filter( 'plugin_action_links_' . plugin_basename( __FILE__ ), [ $ob_class, 'plugin_action_links' ] );
		}

		add_filter( 'assess_rule_by_type', [ $ob_class, 'assess_rule_by_type' ], 10, 3 );
		add_action( 'admin_notices', [ $ob_class, 'plugin_admin_notices' ] );
		add_action( 'wp_ajax_plugin-deactivate-notice-' . SISANU_RCIL_SLUG, [ $ob_class, 'plugin_admin_notices_cleanup' ] );
		add_action( 'plugins_loaded', [ $ob_class, 'plugin_ver_check' ] );
	}

	/**
	 * Hookup the custom restrictions filters and actions.
	 *
	 * @return void
	 */
	public static function hookup_the_custom_restrictions() {
		$ob_class = get_called_class();
		add_filter( 'authenticate', [ $ob_class, 'sisanu_restrict_country' ], 30, 3 );
		add_filter( 'xmlrpc_enabled', [ $ob_class, 'xmlrpc_auth_methods_enabled' ], 30, 3 );

		// Maybe hookup redirects.
		if ( ! empty( self::$custom_redirects['status'] ) ) {
			if ( ! empty( self::$custom_redirects['register'] ) ) {
				add_action( 'wp_loaded', [ $ob_class, 'maybe_restrict_register_url' ] );
			}
			if ( ! empty( self::$custom_redirects['login'] ) ) {
				add_filter( 'wp_loaded', [ $ob_class, 'maybe_restrict_login_url' ] );
			}
			if ( ! empty( self::$custom_redirects['urls'] ) ) {
				add_filter( 'template_redirect', [ $ob_class, 'maybe_restrict_custom_url' ] );
			}
		}
	}

	/**
	 * Redirect the login URL.
	 *
	 * @return void
	 */
	public static function maybe_restrict_login_url() {
		if ( ( substr_count( $_SERVER['REQUEST_URI'], 'wp-login' ) // phpcs:ignore
			&& ! substr_count( $_SERVER['REQUEST_URI'], 'action=' ) ) // phpcs:ignore
			|| get_permalink() === wp_login_url() ) {
			$restrict = self::user_has_restriction();
			if ( $restrict ) {
				status_header( 403 );
				wp_redirect( home_url() );
				exit();
			}
		}
	}

	/**
	 * Redirect the register URL.
	 *
	 * @return void
	 */
	public static function maybe_restrict_register_url() {
		if ( ( substr_count( $_SERVER['REQUEST_URI'], 'wp-login' ) // phpcs:ignore
			&& substr_count( $_SERVER['REQUEST_URI'], 'action=register' ) ) // phpcs:ignore
			|| get_permalink() === wp_registration_url() ) {
			$restrict = self::user_has_restriction();
			if ( $restrict ) {
				status_header( 403 );
				wp_redirect( home_url() );
				exit();
			}
		}
	}

	/**
	 * Redirect the custom URL.
	 *
	 * @return void
	 */
	public static function maybe_restrict_custom_url() {
		if ( in_array( get_permalink(), self::$custom_redirects['urls'], true ) ) {
			$restrict = self::user_has_restriction();
			if ( $restrict ) {
				status_header( 403 );
				wp_redirect( home_url() );
				exit();
			}
		}
	}

	/**
	 * Load the plugin settings.
	 *
	 * @return void
	 */
	public static function load_settings() {
		self::$allowed_ips       = maybe_unserialize( get_option( SISANU_RCIL_DB_OPTION . '_allow_ips', [ '*' ] ) );
		self::$blocked_ips       = maybe_unserialize( get_option( SISANU_RCIL_DB_OPTION . '_block_ips', [] ) );
		self::$allowed_countries = maybe_unserialize( get_option( SISANU_RCIL_DB_OPTION . '_allow_countries', [ '*' ] ) );
		self::$blocked_countries = maybe_unserialize( get_option( SISANU_RCIL_DB_OPTION . '_block_countries', [] ) );

		if ( ! is_array( self::$allowed_countries ) ) {
			self::$allowed_countries = [ '*' ];
		}
		if ( ! is_array( self::$blocked_countries ) ) {
			self::$blocked_countries = [];
		}
		if ( ! is_array( self::$allowed_ips ) ) {
			self::$allowed_ips = [ '*' ];
		}
		if ( ! is_array( self::$blocked_ips ) ) {
			self::$blocked_ips = [];
		}

		self::$all_countries = ( in_array( '*', self::$allowed_countries, true ) ) ? true : false;
		self::$all_ips       = ( in_array( '*', self::$allowed_ips, true ) ) ? true : false;

		if ( ! empty( self::$blocked_ips ) ) {
			self::$all_ips = false;
		}
		if ( ! empty( self::$blocked_countries ) ) {
			self::$all_countries = false;
		}

		$redirects              = maybe_unserialize( get_option( SISANU_RCIL_DB_OPTION . '_custom_redirects', [] ) );
		self::$custom_redirects = wp_parse_args( $redirects, self::$custom_redirects );
		self::$bypass_roles     = maybe_unserialize( get_option( SISANU_RCIL_DB_OPTION . '_bypass_roles', [] ) );
		self::$no_roles_bypass  = empty( self::$bypass_roles ) ? true : false;

		$default = [
			'keep_settings'       => true,
			'temp_disable'        => false,
			'lockout_duration'    => 60, // * MINUTE_IN_SECONDS,
			'redirect_404'        => false,
			'users_lockout'       => false,
			'wc_customer_country' => false,
			'user_login_ip'       => [],
			'simulate_ip'         => '',
			'simulate_country'    => '',
			'simulate_token'      => '',
			'forbidden_text'      => __( 'For some reason the authentication for your account is restricted. Please contact the administrator.', 'slicr' ),
			'xmlrpc_auth_filter'  => '',
			'rule_type'           => 0,
			'bypass_php_geoip'    => false,
			'force_remove_local'  => false,
		];

		self::$settings = maybe_unserialize( get_option( SISANU_RCIL_DB_OPTION . '_settings', [] ) );
		self::$settings = wp_parse_args( self::$settings, $default );

		$ips = implode( ',', array_merge( self::$allowed_ips, self::$blocked_ips ) );
		$cos = implode( ',', array_merge( self::$allowed_countries, self::$blocked_countries ) );

		self::$rules = (object) [
			'type'     => self::$settings['rule_type'],
			'restrict' => (object) [
				'ip' => ( empty( self::$all_ips ) ) ? true : false,
				'co' => ( empty( self::$all_countries ) ) ? true : false,
			],
			'block'    => (object) [
				'ip' => array_diff( self::$blocked_ips, [ '*' ] ),
				'co' => array_diff( self::$blocked_countries, [ '*' ] ),
			],
			'allow'    => (object) [
				'ip' => array_diff( self::$allowed_ips, [ '*' ] ),
				'co' => array_diff( self::$allowed_countries, [ '*' ] ),
			],
		];

		if ( ! empty( self::$settings['force_remove_local'] ) ) {
			self::$rules->allow->ip = array_diff( self::$rules->allow->ip, [ '127.0.0.1', '::1' ] );
		}

		self::$rules->wildcard = (object) [
			'ip' => ( substr_count( $ips, '*' ) ) ? true : false,
			'co' => ( empty( $cos ) || substr_count( $cos, '*' ) ) ? true : false,
		];
	}

	/**
	 * The actions to be executed when the plugin is activated.
	 *
	 * @return void
	 */
	public static function maybe_upgrade_version() {
		$db_version = get_option( SISANU_RCIL_DB_OPTION . '_db_ver', 0 );
		if ( empty( $db_version ) || (float) SISANU_RCIL_CURRENT_DB_VERSION !== (float) $db_version ) {
			// Preserve the previous settings if possible.
			$get_prev_ip  = get_option( SISANU_RCIL_DB_OPTION . '_allow_ips', [ '*' ] );
			$get_prev_co  = get_option( SISANU_RCIL_DB_OPTION . '_allow_countries', [ '*' ] );
			$get_prev_bco = get_option( SISANU_RCIL_DB_OPTION . '_block_countries', [] );
			$get_prev_ro  = get_option( SISANU_RCIL_DB_OPTION . '_bypass_roles', [] );
			$get_prev_se  = get_option( SISANU_RCIL_DB_OPTION . '_settings', self::$settings );

			update_option( SISANU_RCIL_DB_OPTION . '_allow_countries', $get_prev_co );
			update_option( SISANU_RCIL_DB_OPTION . '_allow_ips', $get_prev_ip );
			update_option( SISANU_RCIL_DB_OPTION . '_block_countries', $get_prev_bco );
			update_option( SISANU_RCIL_DB_OPTION . '_bypass_roles', $get_prev_ro );
			update_option( SISANU_RCIL_DB_OPTION . '_settings', $get_prev_se );
			update_option( SISANU_RCIL_DB_OPTION . '_db_ver', SISANU_RCIL_CURRENT_DB_VERSION );
		}
	}

	/**
	 * The actions to be executed when the plugin is activated.
	 *
	 * @return void
	 */
	public static function activate_plugin() {
		self::maybe_upgrade_version();
		set_transient( self::PLUGIN_TRANSIENT, true );
	}

	/**
	 * The actions to be executed when the plugin is deactivated.
	 *
	 * @return void
	 */
	public static function deactivate_plugin() {
		if ( empty( self::$settings['keep_settings'] ) ) {
			delete_option( SISANU_RCIL_DB_OPTION . '_db_ver' );
			delete_option( SISANU_RCIL_DB_OPTION . '_allow_countries' );
			delete_option( SISANU_RCIL_DB_OPTION . '_allow_ips' );
			delete_option( SISANU_RCIL_DB_OPTION . '_block_countries' );
			delete_option( SISANU_RCIL_DB_OPTION . '_block_ips' );
			delete_option( SISANU_RCIL_DB_OPTION . '_custom_redirects' );
			delete_option( SISANU_RCIL_DB_OPTION . '_bypass_roles' );
			delete_option( SISANU_RCIL_DB_OPTION . '_settings' );
			delete_option( SISANU_RCIL_DB_OPTION . '_actions_notices' );
		}
		self::plugin_admin_notices_cleanup( false );
	}

	/**
	 * Load text domain for internalization
	 *
	 * @return void
	 */
	public static function load_textdomain() {
		load_plugin_textdomain( 'slicr', false, basename( dirname( __FILE__ ) ) . '/langs/' );
	}

	/**
	 * Load the plugin assets.
	 *
	 * @return void
	 */
	public static function load_assets() {
		$uri = ( ! empty( $_SERVER['REQUEST_URI'] ) ) ? $_SERVER['REQUEST_URI'] : ''; // phpcs:ignore
		if ( ! substr_count( $uri, 'page=login-ip-country-restriction-settings' )
			&& ! substr_count( $uri, 'user-edit.php' ) ) {
			// Fail-fast, we only add assets to this page.
			return;
		}

		if ( file_exists( SISANU_RCIL_DIR . 'build/index.asset.php' ) ) {
			$dependencies = require_once SISANU_RCIL_DIR . 'build/index.asset.php';
		} else {
			$dependencies = [
				'dependencies' => [],
				'version'      => filemtime( SISANU_RCIL_DIR . 'build/index.js' ),
			];
		}

		if ( file_exists( SISANU_RCIL_DIR . 'build/index.js' ) ) {
			wp_register_script(
				SISANU_RCIL_SLUG,
				SISANU_RCIL_URL . 'build/index.js',
				$dependencies['dependencies'],
				$dependencies['version'],
				true
			);
			wp_localize_script(
				SISANU_RCIL_SLUG,
				str_replace( '-', '', SISANU_RCIL_SLUG ) . 'Settings',
				[
					'ajaxUrl' => admin_url( 'admin-ajax.php' ),
				]
			);
			wp_enqueue_script( SISANU_RCIL_SLUG );
		}

		if ( file_exists( SISANU_RCIL_DIR . 'build/style.css' ) ) {
			wp_enqueue_style(
				SISANU_RCIL_SLUG,
				SISANU_RCIL_URL . 'build/style.css',
				[],
				filemtime( SISANU_RCIL_DIR . 'build/style.css' ),
				false
			);
		}
	}

	/**
	 * Add the new menu in settings section that allows to configure the restriction.
	 *
	 * @return void
	 */
	public static function admin_menu() {
		add_submenu_page(
			'options-general.php',
			'<div class="dashicons dashicons-admin-site"></div> ' . esc_html__( 'Login IP & Country Restriction Settings', 'slicr' ),
			'<div class="dashicons dashicons-admin-site"></div> ' . esc_html__( 'Login IP & Country Restriction Settings', 'slicr' ),
			'manage_options',
			'login-ip-country-restriction-settings',
			[ get_called_class(), 'login_ip_country_restriction_settings' ]
		);
	}

	/**
	 * Reset all options.
	 *
	 * @return void
	 */
	public static function reset_all_settings() {
		$setup = [ '_allow_countries', '_allow_ips', '_block_countries', '_block_ips', '_custom_redirects', '_bypass_roles', '_settings' ];

		foreach ( $setup as $item ) {
			delete_option( SISANU_RCIL_DB_OPTION . $item );
		}

		// Reset the plugin cache.
		self::reset_plugin_transients();
		delete_option( SISANU_RCIL_DB_OPTION . '_db_ver' );

		// Refresh the plugin object properties.
		self::load_settings();

		// Add admin notice on flushed transients.
		self::add_admin_notice( esc_html__( 'The settings were reset to default.', 'slicr' ) );
	}

	/**
	 * Import settings.
	 *
	 * @param string $import Import setting JSON string.
	 * @return void
	 */
	public static function import_settings( $import ) { //phpcs:ignore
		$data = json_decode( $import, true );
		if ( empty( $data ) || ! is_array( $data ) ) {
			// Add admin notice on flushed transients.
			self::add_admin_notice( esc_html__( 'The settings were not imported.', 'slicr' ), 'error' );
			return;
		}

		$setup = [ '_allow_countries', '_allow_ips', '_block_countries', '_block_ips', '_custom_redirects', '_bypass_roles', '_settings' ];

		foreach ( $data as $slug => $item ) {
			if ( in_array( $slug, $setup, true ) ) {
				update_option( SISANU_RCIL_DB_OPTION . $slug, $item );
			}
		}

		// Reset the plugin cache.
		self::reset_plugin_transients();
		delete_option( SISANU_RCIL_DB_OPTION . '_db_ver' );

		// Refresh the plugin object properties.
		self::load_settings();

		// Add admin notice on flushed transients.
		self::add_admin_notice( esc_html__( 'The settings were imported.', 'slicr' ) );
	}

	/**
	 * Remove the transients set when verifying the restrictions.
	 *
	 * @return void
	 */
	public static function reset_plugin_transients() {
		global $wpdb;
		// Remove all the transients records in one query.
		$tmp_query = $wpdb->prepare(
			' DELETE FROM ' . $wpdb->options . ' WHERE option_name LIKE %s OR option_name LIKE %s ',
			$wpdb->esc_like( '_transient_rcil-geo' ) . '%',
			$wpdb->esc_like( '_transient_timeout_rcil-geo' ) . '%'
		);
		$wpdb->query( $tmp_query ); // phpcs:ignore

		if ( is_multisite() ) {
			// Attempt to flush transient also on multisite.
			$tmp_query = $wpdb->prepare(
				' DELETE FROM ' . $wpdb->sitemeta . ' WHERE meta_key LIKE %s OR option_name LIKE %s ',
				$wpdb->esc_like( '_transient_rcil-geo' ) . '%',
				$wpdb->esc_like( '_transient_timeout_rcil-geo' ) . '%'
			);
			$wpdb->query( $tmp_query ); // phpcs:ignore
		}
	}

	/**
	 * Maybe execute the options update if the nonce is valid, then redirect.
	 *
	 * @return void
	 */
	public static function maybe_save_settings() {
		$nonce = filter_input( INPUT_POST, '_login_ip_country_restriction_settings_nonce', FILTER_DEFAULT );
		if ( ! empty( $nonce ) ) {
			if ( ! wp_verify_nonce( $nonce, '_login_ip_country_restriction_settings_save' ) ) {
				wp_die( esc_html__( 'Action not allowed.', 'slicr' ), esc_html__( 'Security Breach', 'slicr' ) );
			}

			$tab = filter_input( INPUT_POST, 'tab', FILTER_DEFAULT );
			$tab = ( empty( $tab ) ) ? 0 : (int) $tab;
			$tab = ( $tab < 0 || $tab > 5 ) ? 0 : $tab;
			$url = admin_url( 'options-general.php?page=login-ip-country-restriction-settings' );

			// Reset the plugin cache.
			self::reset_plugin_transients();

			self::load_settings();
			$opt = self::$settings;
			$sel = filter_input(
				INPUT_POST,
				'_login_ip_country_restriction_settings',
				FILTER_DEFAULT, FILTER_REQUIRE_ARRAY
			);

			switch ( $tab ) {
				case 0:
					$opt['xmlrpc_auth_filter'] = filter_input( INPUT_POST, 'xmlrpc_auth_filter' );

					$opt['rule_type'] = filter_input( INPUT_POST, 'rule_type', FILTER_VALIDATE_INT );
					$opt['rule_type'] = apply_filters( 'check_rule_type_save', $opt['rule_type'] );
					update_option( SISANU_RCIL_DB_OPTION . '_settings', $opt );

					// Refresh the plugin object properties.
					self::load_settings();

					// Add admin notice on flushed transients.
					self::add_admin_notice( esc_html__( 'The settings were updated.', 'slicr' ) );
					do_action( 'sislrc_after_save_settings' );

					wp_safe_redirect( $url );
					exit;

				case 1:
					$_allow_ip_all      = sanitize_text_field( $sel['allow_ip_all'] );
					$_allow_ip_restrict = sanitize_text_field( $sel['allow_ip_restrict'] );
					$_allow_ip_block    = sanitize_text_field( $sel['allow_ip_block'] );

					if ( 'all' === $sel['allow_ip_all'] ) {
						$allow_ip = [ '*' ];
						$block_ip = [];

						// Not using the local removal option.
						$opt['force_remove_local'] = false;
						update_option( SISANU_RCIL_DB_OPTION . '_settings', $opt );
						self::load_settings();
					} else {

						$force_remove_local = ! empty( $sel['force_remove_local'] );
						$opt['force_remove_local'] = $force_remove_local;
						update_option( SISANU_RCIL_DB_OPTION . '_settings', $opt );
						self::load_settings();

						$allow_ip = [];
						$block_ip = [];

						if ( ! empty( $_allow_ip_restrict ) ) {
							$_allow     = preg_replace( '/\s/', '', $_allow_ip_restrict );
							$allow_ip   = explode( ',', $_allow );
							if ( false === $force_remove_local ) {
								$allow_ip[] = '127.0.0.1';
								$allow_ip[] = '::1';
							}
							$allow_ip   = array_unique( $allow_ip );
							asort( $allow_ip );
						}

						if ( ! empty( $_allow_ip_block ) ) {
							$_allow_ip_block = preg_replace( '/\s/', '', $_allow_ip_block );
							$block_ip        = explode( ',', $_allow_ip_block );
							$block_ip        = array_unique( $block_ip );
							asort( $block_ip );
						}
					}
					update_option( SISANU_RCIL_DB_OPTION . '_allow_ips', $allow_ip );
					update_option( SISANU_RCIL_DB_OPTION . '_block_ips', $block_ip );

					// Add admin notice on flushed transients.
					self::add_admin_notice( esc_html__( 'The settings were updated.', 'slicr' ) );
					do_action( 'sislrc_after_save_settings' );

					wp_safe_redirect( $url . '&tab=1' );
					exit;

				case 2:
					if ( 'restrict' !== $sel['allow_country_all'] ) {
						$_allow_country_restrict = [ '*' ];
						$_allow_country_block    = [];
					} else {
						$_allow_country_restrict = ( ! empty( $sel['allow_country_restrict'] ) ) ? $sel['allow_country_restrict'] : [];
						$_allow_country_block    = ( ! empty( $sel['allow_country_block'] ) ) ? $sel['allow_country_block'] : [];
					}

					if ( ! empty( $sel['countries_filter'] ) && 'restrict' === $sel['allow_country_all'] ) {
						$sel['countries_filter'] = array_filter( $sel['countries_filter'] );
						if ( ! empty( $sel['countries_filter'] ) ) {
							foreach ( $sel['countries_filter'] as $key => $value ) {
								if ( 'allow' === $value ) {
									$_allow_country_restrict[] = $key;
								} elseif ( 'block' === $value ) {
									$_allow_country_block[] = $key;
								}
							}
						}

						if ( empty( $_allow_country_restrict ) && empty( $_allow_country_block ) ) {
							$_allow_country_restrict = [ '*' ];
						}
					}
					update_option( SISANU_RCIL_DB_OPTION . '_allow_countries', $_allow_country_restrict );
					update_option( SISANU_RCIL_DB_OPTION . '_block_countries', $_allow_country_block );

					// Add admin notice on flushed transients.
					self::add_admin_notice( esc_html__( 'The settings were updated.', 'slicr' ) );
					do_action( 'sislrc_after_save_settings' );

					wp_safe_redirect( $url . '&tab=2' );
					exit;

				case 3:
					// Process redirects settings.
					$_urls = [];
					if ( ! empty( $sel['redirect_urls'] ) ) {
						$_urls = preg_replace( '/\s/', '', $sel['redirect_urls'] );
						$_urls = explode( ',', $_urls );
						$_urls = array_unique( $_urls );
						asort( $_urls );
					}

					$custom_redirects             = self::$custom_redirects;
					$custom_redirects['status']   = ( ! empty( $sel['use_redirect'] ) ) ? 1 : 0;
					$custom_redirects['login']    = ( ! empty( $sel['redirect_login'] ) ) ? 1 : 0;
					$custom_redirects['register'] = ( ! empty( $sel['redirect_register'] ) ) ? 1 : 0;
					$custom_redirects['urls']     = $_urls;
					update_option( SISANU_RCIL_DB_OPTION . '_custom_redirects', $custom_redirects );

					// Add admin notice on flushed transients.
					self::add_admin_notice( esc_html__( 'The settings were updated.', 'slicr' ) );
					do_action( 'sislrc_after_save_settings' );

					wp_safe_redirect( $url . '&tab=3' );
					exit;

				case 4:
					do_action( 'sislrc_save_pro_settings' );
					do_action( 'sislrc_after_save_settings' );
					wp_safe_redirect( $url . '&tab=4' );
					exit;

				case 5:
					$maybe_reset = filter_input( INPUT_POST, 'reset-all-settings' );
					if ( ! empty( $maybe_reset ) ) {
						// Execute the reset.
						self::reset_all_settings();
					}
					$maybe_import = filter_input( INPUT_POST, 'import-all-settings' );
					$import       = filter_input( INPUT_POST, 'import' );
					if ( ! empty( $maybe_import ) && ! empty( $import ) ) {
						// Execute the reset.
						self::import_settings( $import );
					}

					$maybe_test = filter_input( INPUT_POST, 'test-ip' );
					$test_ip    = filter_input( INPUT_POST, 'test_ip' );
					if ( ! empty( $maybe_test ) && ! empty( $test_ip ) ) {
						global $country_code_detected_api;
						$trans_id  = 'rcil-test-' . md5( gmdate( 'Y-m-d' ) );
						$test_info = [
							'ip'  => $test_ip,
							'co'  => self::get_user_country_name( $test_ip, true ),
							'api' => $country_code_detected_api,
						];
						set_transient( $trans_id, $test_info, 1 * HOUR_IN_SECONDS );
					}

					$maybe_set_bypass = filter_input( INPUT_POST, 'disable-geoip-function' );
					if ( ! empty( $maybe_set_bypass ) ) {
						self::load_settings();
						$opt = self::$settings;

						$opt['bypass_php_geoip'] = true;
						update_option( SISANU_RCIL_DB_OPTION . '_settings', $opt );
					}

					$maybe_unset_bypass = filter_input( INPUT_POST, 'enable-geoip-function' );
					if ( ! empty( $maybe_unset_bypass ) ) {
						self::load_settings();
						$opt = self::$settings;

						$opt['bypass_php_geoip'] = false;
						update_option( SISANU_RCIL_DB_OPTION . '_settings', $opt );
					}

					do_action( 'sislrc_after_save_settings' );
					wp_safe_redirect( $url . '&tab=5' );
					exit;
			}
		}
	}

	/**
	 * Check rule type save.
	 *
	 * @param  int $type Rule type.
	 * @return int
	 */
	public static function check_rule_type_save( $type ) { // phpcs:ignore
		if ( ! self::$is_pro && ! in_array( $type, [ 0, 1, 6, 7, 8, 9 ], true ) ) {
			return 0;
		}
		if ( ! in_array( $type, [ 0, 1, 2, 3, 4, 5, 6, 7, 8, 9 ], true ) ) {
			return 0;
		}
		return $type;
	}

	/**
	 * Add admin notices.
	 *
	 * @param string $text  The text to be outputted as the admin notice.
	 * @param string $class The admin notice class (notice-success is-dismissible, notice-error).
	 * @return void
	 */
	public static function add_admin_notice( $text, $class = 'notice-success is-dismissible' ) { //phpcs:ignore
		$items   = get_option( SISANU_RCIL_DB_OPTION . '_actions_notices', [] );
		$items[] = [
			'type' => $class,
			'text' => $text,
		];
		update_option( SISANU_RCIL_DB_OPTION . '_actions_notices', $items );
	}

	/**
	 * Outputs custom admin notices.
	 *
	 * @return void
	 */
	public static function admin_notices() {
		$items = get_option( SISANU_RCIL_DB_OPTION . '_actions_notices', [] );
		if ( ! empty( $items ) ) {
			foreach ( $items as $item ) {
				?>
				<div class="notice <?php echo esc_attr( $item['type'] ); ?>">
					<p><?php echo wp_kses_post( $item['text'] ); ?></p>
				</div>
				<?php
			}
		}
		update_option( SISANU_RCIL_DB_OPTION . '_actions_notices', [] );
	}

	/**
	 * Display the current status in terms of restrictions.
	 *
	 * @return void
	 */
	public static function current_restriction_notice_card() {
		if ( self::$is_pro
			&& ( ! empty( self::$settings['simulate_ip'] ) || ! empty( self::$settings['simulate_country'] ) ) ) {
			if ( function_exists( 'RCIL\Pro\key_is_active' ) && \RCIL\Pro\key_is_active() ) {
				$res2  = self::current_user_has_restriction( self::$settings['simulate_ip'], self::$settings['simulate_country'] );
				$icon2 = ( true === $res2 ) ? '<span class="dashicons dashicons-warning"></span>' : '<span class="dashicons dashicons-yes-alt"></span>';
				?>
				<div class="card">
					<?php echo wp_kses_post( $icon2 ); ?>
					<ul>
						<li>
							<?php
							echo wp_kses_post( sprintf(
								// Translators: %1$s - list of IPs.
								__( 'You currently enabled a simulation with IP %1$s and country code %2$s.', 'slicr' ),
								'<b>' . self::$settings['simulate_ip'] . '</b> (' . self::get_user_country_name( self::$settings['simulate_ip'] ) . ')',
								'<b>' . self::$settings['simulate_country'] . '</b>'
							) );
							?>
							<br>
							<?php if ( false === $res2 ) : ?>
								<?php esc_html_e( 'The login is allowed, based on assessing the current combination of IPs + country codes + rule type.', 'slicr' ); ?>
							<?php else : ?>
								<?php esc_html_e( 'The login is blocked, based on assessing the current combination of IPs + country codes + rule type.', 'slicr' ); ?>
							<?php endif; ?>
							<?php
							if ( function_exists( 'RCIL\Pro\sislrc_pro_simulate_info' ) ) {
								\RCIL\Pro\sislrc_pro_simulate_info( false );
							}
							?>
						</li>
					</ul>
				</div>
				<?php
			}
		}

		$res  = self::current_user_has_restriction( self::get_current_ip(), self::get_user_country_name() );
		$icon = ( true === $res ) ? '<span class="dashicons dashicons-warning"></span>' : '<span class="dashicons dashicons-yes-alt"></span>';
		?>
		<div class="card">
			<?php echo wp_kses_post( $icon ); ?>
			<ul>
				<?php if ( true === $res ) : ?>
					<li class="info notice-error">
						<?php esc_html_e( 'The restriction will apply to your user as well! Please make sure you change the restriction to allow your own access.', 'slicr' ); ?>
					</li>
				<?php endif; ?>
				<li>
					<?php
					$text = '';
					if ( true === self::$settings['temp_disable'] ) {
						$text .= esc_html__( 'Based on the current setup all settings are temporarily disabled.', 'slicr' );
					} else {
						$text = self::describe_rule_by_type();
					}
					echo esc_html( $text );

					if ( ! empty( self::$rules->wildcard->ip )
						&& in_array( self::$rules->type, [ 0, 1, 2, 3, 4, 5, 6, 8 ], true ) ) {
						echo ' <b>' . esc_html__( 'Please note that there is no IP specified or you have * in the IPs list, meaning there is no IP filter to apply.', 'slicr' ) . '</b>';
					}
					if ( ! empty( self::$rules->wildcard->co )
						&& in_array( self::$rules->type, [ 0, 1, 2, 3, 4, 5, 7, 9 ], true ) ) {
						echo ' <b>' . esc_html__( 'Please note that there is no country filter to apply.', 'slicr' ) . '</b>';
					}
					?>
					<br>
					<?php
					echo wp_kses_post( sprintf(
						// Translators: %1$s - IP, %2$s - country code.
						__( 'Your current IP is %1$s and the country code is %2$s.', 'slicr' ),
						'<b>' . self::get_current_ip() . '</b>',
						'<b>' . self::get_user_country_name() . '</b>'
					) );
					?>
					<a id="sislrc-toggle-debug"><?php esc_html_e( 'Debug', 'slicr' ); ?></a>
					<span id="sislrc-debug-ip" class="is-hidden">
						<?php
						// phpcs:disable
						echo wp_kses_post( sprintf(
							// Translators: %1$s - IP, %2$s - country code.
							__( ': SERVER_ADDR %1$s / REMOTE_ADDR %2$s / HTTP_CF_IPCOUNTRY %3$s / HTTP_CF_CONNECTING_IP %4$s / HTTP_CLIENT_IP %5$s', 'slicr' ),
							( ! empty( $_SERVER['SERVER_ADDR'] ) ) ? '<b>' . wp_unslash( $_SERVER['SERVER_ADDR'] ) . '</b>' : '',
							( ! empty( $_SERVER['REMOTE_ADDR'] ) ) ? '<b>' . wp_unslash( $_SERVER['REMOTE_ADDR'] ) . '</b>' : '',
							( ! empty( $_SERVER['HTTP_CF_IPCOUNTRY'] ) ) ? '<b>' . wp_unslash( $_SERVER['HTTP_CF_IPCOUNTRY'] ) . '</b>' : '',
							( ! empty( $_SERVER['HTTP_CF_CONNECTING_IP'] ) ) ? '<b>' . wp_unslash( $_SERVER['HTTP_CF_CONNECTING_IP'] ) . '</b>' : '',
							( ! empty( $_SERVER['HTTP_CLIENT_IP'] ) ) ? '<b>' . wp_unslash( $_SERVER['HTTP_CLIENT_IP'] ) . '</b>' : ''
						) );
						// phpcs:enable
						?>
					</span>
				</li>
			</ul>
		</div>
		<br>
		<?php
	}

	/**
	 * Get current IP.
	 *
	 * @return string
	 */
	public static function get_current_ip() { //phpcs:ignore
		$ip = '';
		// phpcs:disable
		if ( ! empty( $_SERVER['HTTP_CF_CONNECTING_IP'] ) ) {
			$ip = wp_unslash( $_SERVER['HTTP_CF_CONNECTING_IP'] );
		} elseif ( ! empty( $_SERVER['HTTP_CLIENT_IP'] ) ) {
			$ip = wp_unslash( $_SERVER['HTTP_CLIENT_IP'] );
		} elseif ( ! empty( $_SERVER['REMOTE_ADDR'] ) ) {
			$ip = wp_unslash( $_SERVER['REMOTE_ADDR'] );
		}
		// phpcs:enable
		return (string) $ip;
	}

	/**
	 * Show the current settings and allow you to change the settings.
	 *
	 * @return void
	 */
	public static function login_ip_country_restriction_settings() {
		// Verify user capabilities in order to deny the access if the user does not have the capabilities.
		if ( ! current_user_can( 'manage_options' ) ) {
			wp_die( esc_html__( 'Action not allowed.', 'slicr' ) );
		}

		$all_countries = self::get_countries_list();

		$tab = filter_input( INPUT_GET, 'tab', FILTER_DEFAULT );
		$tab = ( empty( $tab ) ) ? 0 : (int) $tab;
		$tab = ( $tab < 0 || $tab > 5 ) ? 0 : $tab;

		$rules = [
			6 => [
				'is_pro' => false,
				'title'  => __( 'Allow login only for allowed IPs', 'slicr' ),
			],
			7 => [
				'is_pro' => false,
				'title'  => __( 'Allow login only for allowed countries', 'slicr' ),
			],
			0 => [
				'is_pro' => false,
				'title'  => __( 'Allow login only for allowed countries or allowed IPs', 'slicr' ),
			],
			8 => [
				'is_pro' => false,
				'title'  => __( 'Block login only for blocked IPs', 'slicr' ),
			],
			9 => [
				'is_pro' => false,
				'title'  => __( 'Block login only for blocked countries', 'slicr' ),
			],
			1 => [
				'is_pro' => false,
				'title'  => __( 'Block login only for blocked countries or blocked IPs', 'slicr' ),
			],
			2 => [
				'is_pro' => true,
				'title'  => __( 'Allow login only for allowed countries or allowed IPs, but not from blocked IPs', 'slicr' ),
			],
			3 => [
				'is_pro' => true,
				'title'  => __( 'Allow login only for allowed countries or allowed IPs, but not from blocked IPs or blocked countries', 'slicr' ),
			],
			4 => [
				'is_pro' => true,
				'title'  => __( 'Block login only for blocked countries or blocked IPs, but not for allowed IPs', 'slicr' ),
			],
			5 => [
				'is_pro' => true,
				'title'  => __( 'Block login only for blocked countries or blocked IPs, but not for allowed IPs or allowed countries', 'slicr' ),
			],
		];
		?>

		<div class="wrap licr-feature">
			<h1 class="plugin-title">
				<span>
					<span class="dashicons dashicons-admin-site"></span>
					<span class="h1"><?php esc_html_e( 'Login IP & Country Restriction Settings', 'slicr' ); ?></span>
				</span>
				<span><?php self::show_donate_text(); ?></span>
			</h1>

			<?php self::current_restriction_notice_card(); ?>

			<?php $url = admin_url( 'options-general.php?page=login-ip-country-restriction-settings' ); ?>
			<div class="licr-feature tabs-wrap">
				<a href="<?php echo esc_url( $url ); ?>"
					class="button<?php echo esc_attr( 0 === $tab ? ' button-primary on' : '' ); ?>">
					<div class="dashicons dashicons-admin-tools"></div>
					<?php esc_html_e( 'Rule Type', 'slicr' ); ?>
				</a>
				<a href="<?php echo esc_url( $url . '&tab=1' ); ?>"
					class="button<?php echo esc_attr( 1 === $tab ? ' button-primary on' : '' ); ?>">
					<div class="dashicons dashicons-shield"></div>
					<?php esc_html_e( 'IP Restriction', 'slicr' ); ?>
				</a>
				<a href="<?php echo esc_url( $url . '&tab=2' ); ?>"
					class="button<?php echo esc_attr( 2 === $tab ? ' button-primary on' : '' ); ?>">
					<div class="dashicons dashicons-shield-alt"></div>
					<?php esc_html_e( 'Country Restriction', 'slicr' ); ?>
				</a>
				<a href="<?php echo esc_url( $url . '&tab=3' ); ?>"
					class="button<?php echo esc_attr( 3 === $tab ? ' button-primary on' : '' ); ?>">
					<span class="dashicons dashicons-randomize"></span>
					<?php esc_html_e( 'Redirects', 'slicr' ); ?>
				</a>
				<?php
				if ( ! self::$is_pro ) {
					?>
					<a href="<?php echo esc_url( $url . '&tab=4' ); ?>" class="button pro-item disabled">
						<span class="dashicons dashicons-admin-generic"></span>
						<?php esc_html_e( 'Other Settings', 'slicr' ); ?>
					</a>
					<?php
				}
				do_action( 'sislrc_display_pro_tabs' );
				?>
				<a href="<?php echo esc_url( $url . '&tab=5' ); ?>"
					class="button<?php echo esc_attr( 5 === $tab ? ' button-primary on' : '' ); ?>">
					<span class="dashicons dashicons-info"></span>
					<?php esc_html_e( 'Debug', 'slicr' ); ?>
				</a>
			</div>

			<div class="licr-feature tab-content-wrap">
				<form action="<?php echo esc_url( self::$plugin_url ); ?>" method="POST">
					<?php wp_nonce_field( '_login_ip_country_restriction_settings_save', '_login_ip_country_restriction_settings_nonce' ); ?>
					<input type="hidden" name="tab" id="tab" value="<?php echo (int) $tab; ?>">

					<?php
					switch ( $tab ) {
						case 1:
							// IP restriction.
							self::tab1_content( $rules );
							break;

						case 2:
							// Country restriction.
							self::tab2_content( $all_countries );
							break;

						case 3:
							// Redirects.
							self::tab3_content();
							break;

						case 4:
							// Other Settings.
							self::tab4_content( $rules );
							break;

						case 5:
							// Debug.
							self::setup_debug_output();
							break;

						case 0:
						default:
							// Rule type.
							self::tab0_content( $rules );
							break;
					}
					?>
				</form>
			</div>
		</div>
		<?php
	}

	/**
	 * First tab content.
	 *
	 * @param  array $rules Custom rules.
	 * @return void
	 */
	public static function tab0_content( $rules ) { //phpcs:ignore
		$true_pro = ( self::$is_pro
			&& function_exists( '\RCIL\Pro\key_is_active' ) && true === \RCIL\Pro\key_is_active() ) ? true : false;
		?>
		<div class="licr-feature rows">
			<div class="span3">
				<h3 class="h4"><?php esc_html_e( 'Login Restriction Rules', 'slicr' ); ?></h3>
				<ol>
					<?php
					foreach ( $rules as $key => $value ) {
						$class = '';
						if ( true === $value['is_pro'] ) {
							$class = ( ! $true_pro ) ? 'pro-item disabled' : 'pro-item';
							if ( ! $true_pro ) {
								?>
								<li>
									<label class="pro-item disabled">
										<?php echo esc_html( $value['title'] ); ?>
									</label>
								</li>
								<?php
							} else {
								?>
								<li>
									<label class="pro-item">
										<input type="radio" name="rule_type" value="<?php echo (int) $key; ?>"<?php checked( self::$rules->type, $key ); ?>>
										<?php echo esc_html( $value['title'] ); ?>
									</label>
								</li>
								<?php
							}
						} else {
							?>
							<li>
								<label>
									<input type="radio" name="rule_type" value="<?php echo (int) $key; ?>"<?php checked( self::$rules->type, $key ); ?>>
									<?php echo esc_html( $value['title'] ); ?>
								</label>
							</li>
							<?php
						}
					}
					?>
				</ol>

				<p class="info">
					<?php esc_html_e( 'The login filter can be configured to work in a different way, depending on what type of rules to be assessed and in which order.', 'slicr' ); ?>
				</p>
			</div>

			<div class="span3">
				<h3 class="h4"><?php esc_html_e( 'Filter XML-RPC Authenticated Methods', 'slicr' ); ?></h3>
				<ul>
					<li>
						<label>
							<input type="radio" name="xmlrpc_auth_filter"
								id="xmlrpc_auth_filter"
								value=""
								<?php checked( '', self::$settings['xmlrpc_auth_filter'] ); ?>/>
							<?php esc_html_e( 'Default', 'slicr' ); ?>
						</label>
					</li>
					<li>
						<label>
							<input type="radio" name="xmlrpc_auth_filter"
								id="xmlrpc_auth_filter_all"
								value="all"
								<?php checked( 'all', self::$settings['xmlrpc_auth_filter'] ); ?>/>
							<?php esc_html_e( 'Disable all', 'slicr' ); ?>
						</label>
					</li>
					<li>
						<label>
							<input type="radio" name="xmlrpc_auth_filter"
								id="xmlrpc_auth_filter_restriction"
								value="restriction"
								<?php checked( 'restriction', self::$settings['xmlrpc_auth_filter'] ); ?>/>
							<?php esc_html_e( 'Disable only when matching a restriction rule', 'slicr' ); ?>
						</label>
					</li>
				</ul>

				<p class="info">
					<?php esc_html_e( 'The option above controls whether XML-RPC methods requiring authentication (such as for publishing purposes) are enabled and does not interfere with pingbacks or other custom endpoints that don\'t require authentication.', 'slicr' ); ?>
				</p>
			</div>
		</div>

		<div class="main-button-wrap">
			<?php submit_button( '', 'primary', '', false ); ?>
		</div>
		<?php
	}

	/**
	 * Second tab content.
	 *
	 * @param  array $rules Custom rules.
	 * @return void
	 */
	public static function tab1_content( $rules ) { //phpcs:ignore
		?>
		<div class="rows">
			<div class="span6">
				<label class="sislrc-toggle"
					data-target="#restrict_ip_list"
					data-action="hide">
					<input type="radio" value="all"
						name="_login_ip_country_restriction_settings[allow_ip_all]"
						id="_login_ip_country_restriction_settings_allow_ip_all"
						<?php checked( false, self::$rules->restrict->ip ); ?>/>
					<span>
						<h3 class="h4"><?php esc_html_e( 'No IP restriction', 'slicr' ); ?></h3>
						<?php esc_html_e( 'No IP restriction', 'slicr' ); ?>
					</span>
				</label>
			</div>
		</div>

		<div class="rows">
			<div class="span6">
				<label class="sislrc-toggle" data-target="#restrict_ip_list" data-action="show">
					<input type="radio" value="restrict"
						name="_login_ip_country_restriction_settings[allow_ip_all]"
						id="_login_ip_country_restriction_settings_allow_ip_restrict"
						<?php checked( true, self::$rules->restrict->ip ); ?>/>
					<span>
						<h3 class="h4"><?php esc_html_e( 'Setup IP restriction', 'slicr' ); ?></h3>
						<?php esc_html_e( 'Allow or block only specific IPs', 'slicr' ); ?>
					</span>
				</label>

				<div id="restrict_ip_list"
					class="rcil_elem <?php echo esc_attr( ( false === self::$rules->restrict->ip ) ? 'is-hidden' : '' ); ?>">

					<div class="rows">
						<div class="span3">
							<h3 class="h4"><?php echo esc_attr( self::CHAR_ALLOW ); ?> <?php esc_html_e( 'Allow specific IPs', 'slicr' ); ?></h3>
							<?php
							$list_ip   = self::$allowed_ips;
							$list_ip[] = self::get_current_ip();
							$list_ip   = array_unique( $list_ip );
							if ( ! empty( self::$settings['force_remove_local'] ) ) {
								$list_ip = array_diff( $list_ip, [ '127.0.0.1', '::1' ] );
							}
							?>
							<textarea
								name="_login_ip_country_restriction_settings[allow_ip_restrict]"
								placeholder="111.111.111,222.222.222,333.333.333"
								class="wide" rows="2"><?php echo esc_html( implode( ', ', $list_ip ) ); ?></textarea>
							<p class="info">
								<?php esc_html_e( '* means any IP, you must remove it from the list if you want to apply a restriction.', 'slicr' ); ?>
								<?php esc_html_e( 'Separate the IPs with comma if there are more.', 'slicr' ); ?>
							</p>

							<h3 class="h4 has-warning"><span class="dashicons dashicons-warning"></span> <?php esc_html_e( 'Danger zone', 'slicr' ); ?></h3>
							<label class="inline">
								<input type="checkbox" value="1"
									name="_login_ip_country_restriction_settings[force_remove_local]"
									id="_login_ip_country_restriction_settings_force_remove_local"
									<?php checked( true, ! empty( self::$settings['force_remove_local'] ) ); ?>/>
								<span>
									<?php esc_html_e( 'remove the 127.0.0.1 and ::1 from the allowed IPs', 'slicr' ); ?>
								</span>
							</label>
							<p class="info">
								<?php esc_html_e( 'Please note that this setting is not recommended and it is risky to enable it, as it will block your access when you are using this on your local environment. The option is intended only for use with hosts like Cloudflare, when the server IP is masked as 127.0.0.1.', 'slicr' ); ?>
							</p>

						</div>
						<div class="span3">
							<h3 class="h4"><?php echo esc_attr( self::CHAR_BLOCK ); ?>  <?php esc_html_e( 'Block specific IPs', 'slicr' ); ?></h3>
							<textarea
								name="_login_ip_country_restriction_settings[allow_ip_block]"
								placeholder="111.111.111,222.222.222,333.333.333"
								class="wide" rows="2"><?php echo esc_html( implode( ', ', self::$blocked_ips ) ); ?></textarea>
							<p class="info">
								<?php esc_html_e( 'Separate the IPs with comma if there are more.', 'slicr' ); ?>
							</p>
						</div>
					</div>
				</div>
			</div>
		</div>

		<div class="main-button-wrap">
			<?php submit_button( '', 'primary', 'submit-tab1', false ); ?>
		</div>
		<?php
	}

	/**
	 * Third tab content.
	 *
	 * @param  array $all_countries Countries list.
	 * @return void
	 */
	public static function tab2_content( $all_countries ) { //phpcs:ignore
		?>
		<div class="rows">
			<div class="span6">
				<label class="sislrc-toggle" data-target="#restrict-country-list" data-action="hide">
					<input type="radio"
						name="_login_ip_country_restriction_settings[allow_country_all]"
						id="_login_ip_country_restriction_settings_allow_country_all"
						value="all"
						<?php checked( false, self::$rules->restrict->co ); ?>/>
					<span>
						<h3 class="h4"><?php esc_html_e( 'No country restriction', 'slicr' ); ?></h3>
						<?php esc_html_e( 'No country restriction', 'slicr' ); ?>
					</span>
				</label>
			</div>
		</div>

		<div class="rows">
			<div class="span6">
				<label class="sislrc-toggle" data-target="#restrict-country-list" data-action="show">
					<input type="radio"
						name="_login_ip_country_restriction_settings[allow_country_all]"
						id="_login_ip_country_restriction_settings_allow_country_restrict"
						value="restrict"
						<?php checked( true, self::$rules->restrict->co ); ?>/>
					<span>
						<h3 class="h4"><?php esc_html_e( 'Setup country restriction', 'slicr' ); ?></h3>
						<?php esc_html_e( 'Allow or block only the selected countries', 'slicr' ); ?>
					</span>
				</label>

				<div id="restrict-country-list"
					class="rcil_elem <?php echo esc_attr( ( false === self::$rules->restrict->co ) ? 'is-hidden' : '' ); ?>">
					<?php
					$allow = [];
					$block = [];
					$reset = [];
					foreach ( $all_countries as $key => $name ) {
						if ( in_array( $key, self::$allowed_countries, true ) ) {
							$allow[ $key ] = $name;
						} elseif ( in_array( $key, self::$blocked_countries, true ) ) {
							$block[ $key ] = $name;
						} else {
							$reset[ $key ] = $name;
						}
					}
					?>
					<div class="rows">
						<div class="span2 filter-selected">
							<div class="filter-allowed">
								<h3 class="h4"><?php echo esc_attr( self::CHAR_ALLOW ); ?> <?php esc_html_e( 'Allowed countries', 'slicr' ); ?></h3>
								<p class="info">
									<?php esc_html_e( 'This is the list of countries from where the login is allowed.', 'slicr' ); ?>
								</p>
								<p>
									<?php
									// Translators: %1$s - count selected.
									echo wp_kses_post( sprintf( __( '%1$s selected', 'slicr' ), '<b>' . count( $allow ) . '</b>' ) );
									?>
								</p>
								<div class="list allowed">
									<?php if ( ! empty( $allow ) ) : ?>
										<ul>
											<?php foreach ( $allow as $key => $value ) : ?>
												<li>
													<label class="fake-checkbox">
														<input type="checkbox"
															name="_login_ip_country_restriction_settings[allow_country_restrict][]"
															id="_login_ip_country_restriction_settings_allow_country_all"
															value="<?php echo esc_attr( $key ); ?>"
															checked="checked" />
														<?php echo esc_html( $value ); ?>
														(<?php echo esc_html( $key ); ?>)
													</label>
												</li>
											<?php endforeach; ?>
										</ul>
									<?php else : ?>
										(<?php esc_html_e( 'you did not select any country yet', 'slicr' ); ?>)
									<?php endif; ?>
								</div>
							</div>

							<div class="filter-blocked">
								<h3 class="h4"><?php echo esc_attr( self::CHAR_BLOCK ); ?> <?php esc_html_e( 'Blocked countries', 'slicr' ); ?></h3>
								<p class="info"><?php esc_html_e( 'This is the list of countries from where the login is blocked.', 'slicr' ); ?></p>
								<p>
									<?php
									// Translators: %1$s - count selected.
									echo wp_kses_post( sprintf( __( '%1$s selected', 'slicr' ), '<b>' . count( $block ) . '</b>' ) );
									?>
								</p>
								<div class="list blocked">
									<?php if ( ! empty( $block ) ) : ?>
										<ul>
											<?php foreach ( $block as $key => $value ) : ?>
												<li>
													<label class="fake-checkbox">
														<input type="checkbox"
															name="_login_ip_country_restriction_settings[allow_country_block][]"
															id="_login_ip_country_restriction_settings_allow_country_block"
															value="<?php echo esc_attr( $key ); ?>"
															checked="checked"/>

														<?php echo esc_html( $value ); ?>
														(<?php echo esc_html( $key ); ?>)
													</label>
												</li>
											<?php endforeach; ?>
										</ul>
									<?php else : ?>
										(<?php esc_html_e( 'you did not select any country yet', 'slicr' ); ?>)
									<?php endif; ?>
								</div>
							</div>
						</div>
						<div class="span4 filter-unfiltered">
							<h3 class="h4"><?php esc_html_e( 'Countries list', 'slicr' ); ?></h3>
							<div class="rcil-letters-list">
								<?php foreach ( range( 'A', 'Z' ) as $letter ) { ?>
									<a rel="nofollow" class="button" onclick="licrGoToLetter( '<?php echo esc_attr( $letter ); ?>' );"><?php echo esc_html( $letter ); ?></a>
								<?php } ?>
							</div>
							<div class="list">
								<?php
								$letter = '';
								foreach ( $reset as $key => $value ) :
									if ( $value[0] !== $letter ) :
										$letter = $value[0];
										?>
										<?php if ( 'A' !== $letter ) : ?>
											</ul>
										<?php endif; ?>

										<div name="letter<?php echo esc_attr( $letter ); ?>" id="letter<?php echo esc_attr( $letter ); ?>"></div>
										<hr>
										<p>
											<a class="button button-primary"><?php echo esc_attr( $letter ); ?></a>
											<?php submit_button( '', 'letter' . esc_attr( $letter ), 'submit-tab2', false ); ?>
										</p>
									<ul class="rows">
									<?php endif; ?>
									<li class="span2 country-opt">
										<label class="fake-checkbox clear">
											<input type="radio"
												name="_login_ip_country_restriction_settings[countries_filter][<?php echo esc_attr( $key ); ?>]"
												value=""
												checked="checked"
												data-letter="<?php echo esc_attr( $letter ); ?>" />
												<?php echo esc_html( $value ); ?>
												(<?php echo esc_html( $key ); ?>)
										</label>
										<label class="fake-checkbox allowed">
											<?php echo esc_attr( self::CHAR_ALLOW ); ?>
											<input type="radio"
												name="_login_ip_country_restriction_settings[countries_filter][<?php echo esc_attr( $key ); ?>]"
												value="allow"
												title="<?php esc_html_e( 'Allowed countries', 'slicr' ); ?>"
												data-letter="<?php echo esc_attr( $letter ); ?>" />
										</label>
										<label class="fake-checkbox blocked">
											<?php echo esc_attr( self::CHAR_BLOCK ); ?>
											<input type="radio"
												name="_login_ip_country_restriction_settings[countries_filter][<?php echo esc_attr( $key ); ?>]"
												value="block"
												title="<?php esc_html_e( 'Blocked countries', 'slicr' ); ?>"
												data-letter="<?php echo esc_attr( $letter ); ?>" />
										</label>
									</li>
								<?php endforeach; ?>
								</ul>
							</div>
						</div>
					</div>
				</div>
			</div>
		</div>

		<div class="main-button-wrap">
			<?php submit_button( '', 'primary', 'submit-tab2', false ); ?>
		</div>
		<?php
	}

	/**
	 * Redirects tab content.
	 *
	 * @return void
	 */
	public static function tab3_content() {
		?>
		<div class="rows">
			<div class="span6">
				<label class="sislrc-toggle"
					data-target="#use_redirects_list"
					data-action="hide">
					<input type="radio"
						name="_login_ip_country_restriction_settings[use_redirect]"
						id="_login_ip_country_restriction_settings_use_redirect0"
						value="0" <?php checked( 0, self::$custom_redirects['status'] ); ?>/>
					<span>
						<h3 class="h4"><?php esc_html_e( 'No redirect', 'slicr' ); ?></h3>
						<?php esc_html_e( 'No redirects', 'slicr' ); ?>
					</span>
				</label>
			</div>
		</div>

		<div class="rows">
			<div class="span6">
				<label class="sislrc-toggle" data-target="#use_redirects_list" data-action="show">
					<input type="radio"
						name="_login_ip_country_restriction_settings[use_redirect]"
						id="_login_ip_country_restriction_settings_use_redirect1"
						value="1" <?php checked( 1, self::$custom_redirects['status'] ); ?>/>
					<span>
						<h3 class="h4"><?php esc_html_e( 'Use redirects', 'slicr' ); ?></h3>
						<?php esc_html_e( 'Yes, use redirects to the front page when the URLs are accessed by someone that has a restriction.', 'slicr' ); ?>
					</span>
				</label>

				<div id="use_redirects_list"
					class="rcil_elem <?php echo esc_attr( ( 0 === (int) self::$custom_redirects['status'] ) ? 'is-hidden' : '' ); ?>">
					<div class="rows">
						<div class="span3">
							<h3 class="h4"><?php esc_html_e( 'Login & Registration native pages', 'slicr' ); ?></h3>
							<label>
								<input type="checkbox" name="_login_ip_country_restriction_settings[redirect_login]"
									id="_login_ip_country_restriction_settings_redirect_login"
									value="1" <?php checked( 1, (int) self::$custom_redirects['login'] ); ?>/>
								<?php
								echo wp_kses_post( sprintf(
									// Translators: %1$s - url, %2$s - new url.
									__( 'Redirect login from %1$s to %2$s.', 'slicr' ),
									'<b><em>' . wp_login_url() . '</em></b>',
									'<b><em>' . home_url() . '</em></b>'
								) );
								?>
							</label>
							<label>
								<input type="checkbox" name="_login_ip_country_restriction_settings[redirect_register]"
									id="_login_ip_country_restriction_settings_redirect_register"
									value="1" <?php checked( 1, (int) self::$custom_redirects['register'] ); ?>/>
								<?php
								echo wp_kses_post( sprintf(
									// Translators: %1$s - url, %2$s - new url.
									__( 'Redirect registration from %1$s to %2$s.', 'slicr' ),
									'<b><em>' . wp_registration_url() . '</em></b>',
									'<b><em>' . home_url() . '</em></b>'
								) );
								?>
							</label>
							<p class="info"><?php esc_html_e( 'Please note that the restriction to the pages configured above will apply if the login restriction is matched.', 'slicr' ); ?></p>
						</div>
						<div class="span3">
							<h3 class="h4"><?php esc_html_e( 'The following specified URLs', 'slicr' ); ?></h3>
							<textarea name="_login_ip_country_restriction_settings[redirect_urls]" class="wide" rows="3"><?php echo esc_html( implode( ', ', self::$custom_redirects['urls'] ) ); ?></textarea>
							<p class="info"><?php esc_html_e( '(separate the URLs with comma)', 'slicr' ); ?></p>

							<?php
							if ( function_exists( 'RCIL\Pro\sislrc_pro_simulate_info' ) ) {
								\RCIL\Pro\sislrc_pro_simulate_info( true );
							}
							?>
						</div>
					</div>
				</div>
			</div>
		</div>

		<div class="main-button-wrap">
			<?php submit_button( '', 'primary', 'submit-tab3', false ); ?>
		</div>
		<?php
	}

	/**
	 * Pro tab content.
	 *
	 * @return void
	 */
	public static function tab4_content() {
		if ( ! self::$is_pro ) {
			self::pro_teaser();
		}
		do_action( 'sislrc_display_pro_tabs_content' );
	}

	/**
	 * PRO teaser.
	 *
	 * @param  string $type Teaser type.
	 * @return void
	 */
	public static function pro_teaser( $type = 'regular' ) { //phpcs:ignore
		?>
		<div class="rows">
			<div class="span2">
				<?php if ( 'regular' === $type ) : ?>
					<h2><?php esc_html_e( 'You are using the free version.', 'slicr' ); ?></h2>
					<p>
						<?php
						echo wp_kses_post( sprintf(
							// Translators: %1$s - extensions URL.
							__( 'Click the button to see more and get the <a class="pro-item button button-primary" href="%1$s" target="_blank">version</a> of the plugin!', 'slicr' ),
							'https://iuliacazan.ro/wordpress-extension/login-ip-country-restriction-pro/'
						) );
						?>
					</p>
				<?php else : ?>
					<h2><?php esc_html_e( 'You are using the PRO version.', 'slicr' ); ?></h2>
					<p>
						<?php esc_html_e( 'It seems that you either did not input yet your license key, or that is not valid or has expired already.', 'slicr' ); ?>

						<?php
						echo wp_kses_post( sprintf(
							// Translators: %1$s - extensions URL.
							__( 'Click the button to get a valid license key for the <a class="pro-item button button-primary" href="%1$s" target="_blank">version</a> of the plugin!', 'slicr' ),
							'https://iuliacazan.ro/wordpress-extension/login-ip-country-restriction-pro/'
						) );
						?>
					</p>
				<?php endif; ?>
			</div>

			<div class="span2">
				<h2><?php esc_html_e( 'Login IP & Country Restriction', 'slicr' ); ?></h2>
				<p>
					<?php esc_html_e( 'This plugin allows you to restrict the login on your website, based on the custom rules you apply. This helps with tightening your website security and fights against dictionary bot attacks originating from other countries, by denying access.', 'slicr' ); ?>
				</p>
				<img src="<?php echo esc_url( SISANU_RCIL_URL . 'assets/images/banner-772x250.png' ); ?>" loading="lazy">
			</div>

			<div class="span2">
				<h2><?php esc_html_e( 'The PRO version includes additional useful features', 'slicr' ); ?></h2>
				<ol>
					<li><?php esc_html_e( 'Additional Rule Types', 'slicr' ); ?></li>
					<li><?php esc_html_e( 'Redirect Restricted Login', 'slicr' ); ?></li>
					<li><?php esc_html_e( 'Lockout duration', 'slicr' ); ?></li>
					<li><?php esc_html_e( 'Individual lockout', 'slicr' ); ?></li>
					<li><?php esc_html_e( 'WooCommerce Integration', 'slicr' ); ?></li>
					<li><?php esc_html_e( 'Bypass the IP and country restriction for the specified roles', 'slicr' ); ?></li>
					<li><?php esc_html_e( 'Single IP Login Per User', 'slicr' ); ?></li>
					<li><?php esc_html_e( 'Simulate IP and Country', 'slicr' ); ?></li>
					<li><?php esc_html_e( 'Temporarily disable all settings', 'slicr' ); ?></li>

				</ol>
			</div>
		</div>
		<?php
	}

	/**
	 * Setup debug.
	 *
	 * @return void
	 */
	public static function setup_debug_output() {
		$setup = [
			'_ver'              => SISANU_RCIL_CURRENT_DB_VERSION,
			'_db_ver'           => get_option( SISANU_RCIL_DB_OPTION . '_db_ver', '' ),
			'_allow_countries'  => get_option( SISANU_RCIL_DB_OPTION . '_allow_countries', [] ),
			'_allow_ips'        => get_option( SISANU_RCIL_DB_OPTION . '_allow_ips', [] ),
			'_block_countries'  => get_option( SISANU_RCIL_DB_OPTION . '_block_countries', [] ),
			'_block_ips'        => get_option( SISANU_RCIL_DB_OPTION . '_block_ips', [] ),
			'_custom_redirects' => get_option( SISANU_RCIL_DB_OPTION . '_custom_redirects', [] ),
			'_bypass_roles'     => get_option( SISANU_RCIL_DB_OPTION . '_bypass_roles', [] ),
			'_settings'         => get_option( SISANU_RCIL_DB_OPTION . '_settings', [] ),
		];
		?>
		<div class="licr-feature rows">
			<div class="span2">
				<h3 class="h4"><?php esc_html_e( 'Export Settings', 'slicr' ); ?></h3>
				<p>
					<?php esc_html_e( 'Here are some details about the current settings of this plugin, these can be reset or exported into another instance.', 'slicr' ); ?>
				</p>
				<textarea name="export"
					rows="15"
					class="code wide"><?php echo wp_json_encode( $setup ); ?></textarea>
				<p>
					<input type="submit" class="button" name="reset-all-settings" value="<?php esc_attr_e( 'Reset to default', 'slicr' ); ?>">
				</p>
				<p>
					<?php esc_html_e( 'Please note that reset to default is not requiring for a confirmation, so be careful with clicking this button.', 'slicr' ); ?>
				</p>
			</div>
			<div class="span2">
				<h3 class="h4"><?php esc_html_e( 'Import Settings', 'slicr' ); ?></h3>
				<p>
					<?php esc_html_e( 'You can paste here the settings you want to import from another instance. This is a string in JSON format.', 'slicr' ); ?>
				</p>
				<textarea name="import"
					rows="17"
					class="wide"
					placeholder="<?php esc_attr_e( 'Paste here the JSON code.', 'slicr' ); ?>"></textarea>
				<p>
					<input type="submit" class="button" name="import-all-settings" value="<?php esc_attr_e( 'Import settings', 'slicr' ); ?>">
				</p>
				<p>
					<?php esc_html_e( 'Please note that this will override all the existing settings.', 'slicr' ); ?>
				</p>
			</div>
			<div class="span2">
				<?php
				if ( ! class_exists( 'WP_Debug_Data' ) && file_exists( ABSPATH . 'wp-admin/includes/class-wp-debug-data.php' ) ) {
					require_once ABSPATH . 'wp-admin/includes/class-wp-debug-data.php';
				}
				if ( class_exists( 'WP_Debug_Data' ) ) {
					$info = \WP_Debug_Data::debug_data();
				}

				$allow = [
					'wp-core'           => [ 'version', 'site_language', 'timezone', 'home_url', 'site_url', 'permalink', 'https_status', 'multisite', 'environment_type', 'dotorg_communication' ],
					'wp-paths-sizes'    => [ 'wordpress_path', 'uploads_path', 'themes_path', 'plugins_path' ],
					'wp-active-theme'   => [ 'name', 'version', 'author', 'author_website', 'parent_theme', 'theme_features', 'theme_path', 'auto_update' ],
					'wp-parent-theme'   => [ 'name', 'version' ],
					'wp-plugins-active' => '*',
					'wp-media'          => '*',
					'wp-server'         => '*',
					'wp-database'       => [ 'extension', 'server_version', 'client_version' ],
					'wp-constants'      => '*',
					'wp-filesystem'     => '*',
				];

				$details = '';
				if ( ! empty( $info ) ) {
					foreach ( $info as $section => $item ) {
						if ( ! empty( $allow[ $section ] ) && ! empty( $item['fields'] ) ) {
							$details .= PHP_EOL . '*************************************';
							$details .= PHP_EOL . esc_html( $item['label'] );
							$details .= PHP_EOL . '-------------------------------------';

							if ( '*' === $allow[ $section ] ) {
								$keys = array_keys( $item['fields'] );
							} else {
								$keys = $allow[ $section ];
							}

							foreach ( $keys as $key ) {
								$str = ( ! empty( $item['fields'][ $key ]['label'] ) ) ? $item['fields'][ $key ]['label'] : '';
								if ( is_scalar( $item['fields'][ $key ]['value'] ) ) {
									$str .= ': ' . $item['fields'][ $key ]['value'];
								} else {
									$str .= ': ' . print_r( $item['fields'][ $key ]['value'], true ); //phpcs:ignore
								}
								if ( ! empty( $str ) ) {
									$details .= PHP_EOL . '- ' . esc_html( $str );
								}
							}
							$details .= PHP_EOL;
						}
					}

					if ( isset( $info['wp-paths-sizes']['fields']['wordpress_path']['value'] ) ) {
						$details = str_replace(
							$info['wp-paths-sizes']['fields']['wordpress_path']['value'], '{{ROOT}}', $details
						);
					}
				}
				$details .= PHP_EOL . '*************************************';
				$details .= PHP_EOL . esc_html__( 'Debug', 'slicr' );
				$details .= PHP_EOL . '-------------------------------------';
				$details .= PHP_EOL . '- ' . sprintf(
					// Translators: %1$s - IP, %2$s - country code.
					__( 'Your current IP is %1$s and the country code is %2$s.', 'slicr' ),
					self::get_current_ip(),
					self::get_user_country_name()
				);

				$details .= PHP_EOL . '- SERVER_ADDR: ';
				$details .= ( ! empty( $_SERVER['SERVER_ADDR'] ) ) ? wp_unslash( $_SERVER['SERVER_ADDR'] ) : ''; //phpcs:ignore

				$details .= PHP_EOL . '- REMOTE_ADDR: ';
				$details .= ( ! empty( $_SERVER['REMOTE_ADDR'] ) ) ? wp_unslash( $_SERVER['REMOTE_ADDR'] ) : ''; //phpcs:ignore

				$details .= PHP_EOL . '- HTTP_CF_IPCOUNTRY: ';
				$details .= ( ! empty( $_SERVER['HTTP_CF_IPCOUNTRY'] ) ) ? wp_unslash( $_SERVER['HTTP_CF_IPCOUNTRY'] ) : ''; //phpcs:ignore

				$details .= PHP_EOL . '- HTTP_CF_CONNECTING_IP: ';
				$details .= ( ! empty( $_SERVER['HTTP_CF_CONNECTING_IP'] ) ) ? wp_unslash( $_SERVER['HTTP_CF_CONNECTING_IP'] ) : ''; //phpcs:ignore

				$details .= PHP_EOL . '- HTTP_CLIENT_IP: ';
				$details .= ( ! empty( $_SERVER['HTTP_CLIENT_IP'] ) ) ? wp_unslash( $_SERVER['HTTP_CLIENT_IP'] ) : ''; //phpcs:ignore

				if ( ! empty( $details ) ) {
					?>
					<h3 class="h4"><?php esc_html_e( 'Status/Debug', 'slicr' ); ?></h3>
					<p>
						<?php esc_html_e( 'Here are some details about your current WordPress instance and the services versions that run currently in this environment.', 'slicr' ); ?>
					</p>
					<textarea rows="15" class="code wide"><?php echo esc_html( $details ); ?></textarea>
					<?php
				}
				?>
			</div>

			<?php
			$test_info = get_transient( 'rcil-test-' . md5( gmdate( 'Y-m-d' ) ) );
			$test_ip   = ( ! empty( $test_info['ip'] ) ) ? $test_info['ip'] : '';
			$test_co   = ( ! empty( $test_info['co'] ) ) ? $test_info['co'] : '';
			$test_api  = ( ! empty( $test_info['api'] ) ) ? $test_info['api'] : '';
			?>
			<div class="span6">
				<h3 class="h4"><?php esc_html_e( 'Test country code for IP', 'slicr' ); ?></h3>
				<p>
					<?php esc_html_e( 'IP', 'slicr' ); ?>
					<input type="text" name="test_ip" value="<?php echo esc_attr( $test_ip ); ?>">
					<input type="submit" class="button" name="test-ip" value="<?php esc_attr_e( 'Test', 'slicr' ); ?>">
					<?php
					if ( ! empty( $test_ip ) ) {
						echo wp_kses_post( sprintf(
							// Translators: %1$s - IP, %2$s - code, %3$s - method.
							__( 'The country code detected for the IP %1$s is %2$s. The detection was done through the %3$s method.', 'slicr' ),
							'<b>' . $test_ip . '</b>',
							'<code>' . $test_co . '</code>',
							'<b>' . $test_api . '</b>'
						) );
					}
					?>
				</p>
				<p>
					<?php
					if ( ! empty( $test_ip ) && ( 'PHP `geoip_record_by_name`' === $test_api ) ) {
						echo wp_kses_post( sprintf(
							// Translators: %s - method.
							__( 'Please note that the %s function is part of the PHP service used on your server, and this is used as the default detection method. If this does not return the expected country code for the test IP, then you can try to bypass it and allow for other detection methods to run.', 'slicr' ),
							'<b>' . $test_api . '</b>'
						) );
						?>
						<br>
						<?php
						if ( empty( self::$settings['bypass_php_geoip'] ) ) {
							?>
							<input type="submit" class="button" name="disable-geoip-function" value="<?php esc_attr_e( 'Bypass the PHP `geoip_record_by_name` function', 'slicr' ); ?>">
							<?php
						} else {
							?>
							<input type="submit" class="button" name="enable-geoip-function" value="<?php esc_attr_e( 'Enable the PHP `geoip_record_by_name` function', 'slicr' ); ?>">
							<?php
						}
					}
					?>
				</p>
			</div>
		</div>
		<?php
	}

	/**
	 * Return the countries list.
	 *
	 * @return array
	 */
	public static function get_countries_list() { //phpcs:ignore
		$all_countries = [
			'AF' => 'Afghanistan',
			'AX' => 'Aland Islands',
			'AL' => 'Albania',
			'DZ' => 'Algeria',
			'AS' => 'American Samoa',
			'AD' => 'Andorra',
			'AO' => 'Angola',
			'AI' => 'Anguilla',
			'AQ' => 'Antarctica',
			'AG' => 'Antigua And Barbuda',
			'AR' => 'Argentina',
			'AM' => 'Armenia',
			'AW' => 'Aruba',
			'AU' => 'Australia',
			'AT' => 'Austria',
			'AZ' => 'Azerbaijan',
			'BS' => 'Bahamas',
			'BH' => 'Bahrain',
			'BD' => 'Bangladesh',
			'BB' => 'Barbados',
			'BY' => 'Belarus',
			'BE' => 'Belgium',
			'BZ' => 'Belize',
			'BJ' => 'Benin',
			'BM' => 'Bermuda',
			'BT' => 'Bhutan',
			'BO' => 'Bolivia',
			'BA' => 'Bosnia And Herzegovina',
			'BW' => 'Botswana',
			'BV' => 'Bouvet Island',
			'BR' => 'Brazil',
			'IO' => 'British Indian Ocean Territory',
			'BN' => 'Brunei Darussalam',
			'BG' => 'Bulgaria',
			'BF' => 'Burkina Faso',
			'BI' => 'Burundi',
			'KH' => 'Cambodia',
			'CM' => 'Cameroon',
			'CA' => 'Canada',
			'CV' => 'Cape Verde',
			'KY' => 'Cayman Islands',
			'CF' => 'Central African Republic',
			'TD' => 'Chad',
			'CL' => 'Chile',
			'CN' => 'China',
			'CX' => 'Christmas Island',
			'CC' => 'Cocos (Keeling) Islands',
			'CO' => 'Colombia',
			'KM' => 'Comoros',
			'CG' => 'Congo',
			'CD' => 'Congo, Democratic Republic',
			'CK' => 'Cook Islands',
			'CR' => 'Costa Rica',
			'CI' => 'Cote D\'Ivoire',
			'HR' => 'Croatia',
			'CU' => 'Cuba',
			'CY' => 'Cyprus',
			'CZ' => 'Czech Republic',
			'DK' => 'Denmark',
			'DJ' => 'Djibouti',
			'DM' => 'Dominica',
			'DO' => 'Dominican Republic',
			'EC' => 'Ecuador',
			'EG' => 'Egypt',
			'SV' => 'El Salvador',
			'GQ' => 'Equatorial Guinea',
			'ER' => 'Eritrea',
			'EE' => 'Estonia',
			'ET' => 'Ethiopia',
			'FK' => 'Falkland Islands (Malvinas)',
			'FO' => 'Faroe Islands',
			'FJ' => 'Fiji',
			'FI' => 'Finland',
			'FR' => 'France',
			'GF' => 'French Guiana',
			'PF' => 'French Polynesia',
			'TF' => 'French Southern Territories',
			'GA' => 'Gabon',
			'GM' => 'Gambia',
			'GE' => 'Georgia',
			'DE' => 'Germany',
			'GH' => 'Ghana',
			'GI' => 'Gibraltar',
			'GR' => 'Greece',
			'GL' => 'Greenland',
			'GD' => 'Grenada',
			'GP' => 'Guadeloupe',
			'GU' => 'Guam',
			'GT' => 'Guatemala',
			'GG' => 'Guernsey',
			'GN' => 'Guinea',
			'GW' => 'Guinea-Bissau',
			'GY' => 'Guyana',
			'HT' => 'Haiti',
			'HM' => 'Heard Island & Mcdonald Islands',
			'VA' => 'Holy See (Vatican City State)',
			'HN' => 'Honduras',
			'HK' => 'Hong Kong',
			'HU' => 'Hungary',
			'IS' => 'Iceland',
			'IN' => 'India',
			'ID' => 'Indonesia',
			'IR' => 'Iran, Islamic Republic Of',
			'IQ' => 'Iraq',
			'IE' => 'Ireland',
			'IM' => 'Isle Of Man',
			'IL' => 'Israel',
			'IT' => 'Italy',
			'JM' => 'Jamaica',
			'JP' => 'Japan',
			'JE' => 'Jersey',
			'JO' => 'Jordan',
			'KZ' => 'Kazakhstan',
			'KE' => 'Kenya',
			'KI' => 'Kiribati',
			'KR' => 'Korea',
			'KW' => 'Kuwait',
			'KG' => 'Kyrgyzstan',
			'LA' => 'Lao People\'s Democratic Republic',
			'LV' => 'Latvia',
			'LB' => 'Lebanon',
			'LS' => 'Lesotho',
			'LR' => 'Liberia',
			'LY' => 'Libyan Arab Jamahiriya',
			'LI' => 'Liechtenstein',
			'LT' => 'Lithuania',
			'LU' => 'Luxembourg',
			'MO' => 'Macao',
			'MK' => 'Macedonia',
			'MG' => 'Madagascar',
			'MW' => 'Malawi',
			'MY' => 'Malaysia',
			'MV' => 'Maldives',
			'ML' => 'Mali',
			'MT' => 'Malta',
			'MH' => 'Marshall Islands',
			'MQ' => 'Martinique',
			'MR' => 'Mauritania',
			'MU' => 'Mauritius',
			'YT' => 'Mayotte',
			'MX' => 'Mexico',
			'FM' => 'Micronesia, Federated States Of',
			'MD' => 'Moldova',
			'MC' => 'Monaco',
			'MN' => 'Mongolia',
			'ME' => 'Montenegro',
			'MS' => 'Montserrat',
			'MA' => 'Morocco',
			'MZ' => 'Mozambique',
			'MM' => 'Myanmar',
			'NA' => 'Namibia',
			'NR' => 'Nauru',
			'NP' => 'Nepal',
			'NL' => 'Netherlands',
			'AN' => 'Netherlands Antilles',
			'NC' => 'New Caledonia',
			'NZ' => 'New Zealand',
			'NI' => 'Nicaragua',
			'NE' => 'Niger',
			'NG' => 'Nigeria',
			'NU' => 'Niue',
			'NF' => 'Norfolk Island',
			'MP' => 'Northern Mariana Islands',
			'NO' => 'Norway',
			'OM' => 'Oman',
			'PK' => 'Pakistan',
			'PW' => 'Palau',
			'PS' => 'Palestinian Territory, Occupied',
			'PA' => 'Panama',
			'PG' => 'Papua New Guinea',
			'PY' => 'Paraguay',
			'PE' => 'Peru',
			'PH' => 'Philippines',
			'PN' => 'Pitcairn',
			'PL' => 'Poland',
			'PT' => 'Portugal',
			'PR' => 'Puerto Rico',
			'QA' => 'Qatar',
			'RE' => 'Reunion',
			'RO' => 'Romania',
			'RU' => 'Russian Federation',
			'RW' => 'Rwanda',
			'BL' => 'Saint Barthelemy',
			'SH' => 'Saint Helena',
			'KN' => 'Saint Kitts And Nevis',
			'LC' => 'Saint Lucia',
			'MF' => 'Saint Martin',
			'PM' => 'Saint Pierre And Miquelon',
			'VC' => 'Saint Vincent And Grenadines',
			'WS' => 'Samoa',
			'SM' => 'San Marino',
			'ST' => 'Sao Tome And Principe',
			'SA' => 'Saudi Arabia',
			'SN' => 'Senegal',
			'RS' => 'Serbia',
			'SC' => 'Seychelles',
			'SL' => 'Sierra Leone',
			'SG' => 'Singapore',
			'SK' => 'Slovakia',
			'SI' => 'Slovenia',
			'SB' => 'Solomon Islands',
			'SO' => 'Somalia',
			'ZA' => 'South Africa',
			'GS' => 'South Georgia And Sandwich Isl.',
			'ES' => 'Spain',
			'LK' => 'Sri Lanka',
			'SD' => 'Sudan',
			'SR' => 'Suriname',
			'SJ' => 'Svalbard And Jan Mayen',
			'SZ' => 'Swaziland',
			'SE' => 'Sweden',
			'CH' => 'Switzerland',
			'SY' => 'Syrian Arab Republic',
			'TW' => 'Taiwan',
			'TJ' => 'Tajikistan',
			'TZ' => 'Tanzania',
			'TH' => 'Thailand',
			'TL' => 'Timor-Leste',
			'TG' => 'Togo',
			'TK' => 'Tokelau',
			'TO' => 'Tonga',
			'TT' => 'Trinidad And Tobago',
			'TN' => 'Tunisia',
			'TR' => 'Turkey',
			'TM' => 'Turkmenistan',
			'TC' => 'Turks And Caicos Islands',
			'TV' => 'Tuvalu',
			'UG' => 'Uganda',
			'UA' => 'Ukraine',
			'AE' => 'United Arab Emirates',
			'GB' => 'United Kingdom',
			'US' => 'United States',
			'UM' => 'United States Outlying Islands',
			'UY' => 'Uruguay',
			'UZ' => 'Uzbekistan',
			'VU' => 'Vanuatu',
			'VE' => 'Venezuela',
			'VN' => 'Vietnam',
			'VG' => 'Virgin Islands, British',
			'VI' => 'Virgin Islands, U.S.',
			'WF' => 'Wallis And Futuna',
			'EH' => 'Western Sahara',
			'YE' => 'Yemen',
			'ZM' => 'Zambia',
			'ZW' => 'Zimbabwe',
		];

		return $all_countries;
	}

	/**
	 * Maybe fetch url content with cUrl.
	 *
	 * @param  string $url URL to be crawled.
	 * @return string
	 */
	public static function maybe_fetch_url( $url = '' ) { // phpcs:ignore
		$result = '';
		if ( function_exists( 'curl_setopt' ) ) {
			// phpcs:disable
			$ch = curl_init();
			curl_setopt( $ch, CURLOPT_URL, $url );
			curl_setopt( $ch, CURLOPT_AUTOREFERER, false );
			curl_setopt( $ch, CURLOPT_FAILONERROR, true );
			curl_setopt( $ch, CURLOPT_RETURNTRANSFER, true );
			curl_setopt( $ch, CURLOPT_FOLLOWLOCATION, true );
			curl_setopt( $ch, CURLOPT_HTTP_VERSION, CURL_HTTP_VERSION_1_1 );
			curl_setopt( $ch, CURLOPT_HEADER, false );
			$result = @curl_exec( $ch );
			$code   = @curl_getinfo( $ch );
			curl_close( $ch );
			if ( ! empty( $code['http_code'] ) && '404' === $code['http_code'] ) {
				$result = '';
			}
			// phpcs:enable
		}
		return (string) $result;
	}

	/**
	 * Maybe a country code by cUrl.
	 *
	 * @param  string $url URL to be crawled.
	 * @return string
	 */
	public static function country_code_by_curl( $url = '' ) { //phpcs:ignore
		$code = '';
		$body = self::maybe_fetch_url( $url );
		if ( ! empty( $body ) ) {
			$user = @json_decode( $body ); // phpcs:ignore
			$code = ( ! empty( $user->geoplugin_countryCode ) ) ? $user->geoplugin_countryCode : ''; // PHPCS:ignore WordPress.NamingConventions.ValidVariableName
		}
		return (string) $code;
	}

	/**
	 * Maybe a country code by JSON fetch.
	 *
	 * @param  string $url URL to be crawled.
	 * @return string
	 */
	public static function country_code_by_json( $url = '' ) { //phpcs:ignore
		$code = '';
		$body = wp_remote_get( $url, [ 'timeout' => 120 ] );
		if ( ! is_wp_error( $body ) && ! empty( $body['body'] ) ) {
			$body = @json_decode( $body['body'] ); // phpcs:ignore
			$code = ( ! empty( $body->geoplugin_countryCode ) ) ? $body->geoplugin_countryCode : ''; // PHPCS:ignore WordPress.NamingConventions.ValidVariableName
		}
		return (string) $code;
	}

	/**
	 * Maybe a country code by php fetch.
	 *
	 * @param  string $url URL to be crawled.
	 * @return string
	 */
	public static function country_code_by_php( $url = '' ) { //phpcs:ignore
		$code = '';
		$body = maybe_unserialize( @file_get_contents( $url ) ); // phpcs:ignore
		if ( ! empty( $body['geoplugin_countryCode'] ) ) {
			$code = (string) $body['geoplugin_countryCode'];
		}
		return $code;
	}

	/**
	 * Retrieves the current user country code based on the user IP.
	 *
	 * @param string $ip           Maybe an explicit IP.
	 * @param bool   $bypass_cache Bypass or not the cache (defaults to false).
	 * @return string
	 */
	public static function get_user_country_name( $ip = '', $bypass_cache = false ) { //phpcs:ignore
		global $country_code_detected_api;
		$country_code = '!NA';
		$user_ip      = ( ! empty( $ip ) ) ? $ip : self::get_current_ip();
		$trans_id     = 'rcil-geo-' . md5( $user_ip );
		$country_code = get_transient( $trans_id );
		if ( true === $bypass_cache || false === $country_code ) {
			$duration = ( ! empty( self::$settings['lockout_duration'] ) ) ? (int) self::$settings['lockout_duration'] : 60;
			$duration = $duration * MINUTE_IN_SECONDS;
			if ( function_exists( 'geoip_record_by_name' ) ) {
				if ( empty( self::$settings['bypass_php_geoip'] ) ) {
					// If GeoIP library is available, then let's use this.
					$user_details = geoip_record_by_name( $user_ip );
					$country_code = ( ! empty( $user_details['country_code'] ) ) ? $user_details['country_code'] : $country_code;

					$country_code_detected_api = 'PHP `geoip_record_by_name`';
					set_transient( $trans_id, $country_code, $duration );
					return $country_code;
				}
			}

			// First attempt by cUrl.
			$country_code = self::country_code_by_curl( 'http://www.geoplugin.net/json.gp?ip=' . $user_ip );
			if ( ! empty( $country_code ) && '!NA' !== $country_code ) {
				// Fail-fast, we found it.
				$country_code_detected_api = 'CURL';
				set_transient( $trans_id, $country_code, $duration );
				return $country_code;
			}

			// The GeoIP library is not available, so we are trying to use the public GeoPlugin.
			$country_code = self::country_code_by_json( 'http://www.geoplugin.net/json.gp?ip=' . $user_ip );
			if ( ! empty( $country_code ) && '!NA' !== $country_code ) {
				// Fail-fast, we found it.
				$country_code_detected_api = 'JSON';
				set_transient( $trans_id, $country_code, $duration );
				return $country_code;
			}

			$country_code = self::country_code_by_php( 'http://www.geoplugin.net/php.gp?ip=' . $user_ip );
			if ( ! empty( $country_code ) && '!NA' !== $country_code ) {
				// Fail-fast, we found it.
				$country_code_detected_api = 'PHP';
				set_transient( $trans_id, $country_code, $duration );
				return $country_code;
			}

			$country_code = '!NA';
			set_transient( $trans_id, $country_code, $duration );
		}

		return (string) $country_code;
	}

	/**
	 * Check bypass single login.
	 *
	 * @param  int    $forbid Current count.
	 * @param  string $ip     Check IP.
	 * @return int
	 */
	public static function check_bypass_single_login( $forbid, $ip ) { // phpcs:ignore
		if ( ! empty( self::$user_id ) && self::$is_pro && function_exists( 'RCIL\Pro\user_bypass_single_login' ) ) {
			$bypass = RCIL\Pro\user_bypass_single_login( self::$user_id, $ip );
			if ( false === $bypass ) {
				++ $forbid;
			}
		}
		return $forbid;
	}

	/**
	 * Forbidden screen.
	 *
	 * @return void
	 */
	public static function forbidden_screen() {
		if ( self::$is_pro && function_exists( 'RCIL\Pro\forbidden_custom_splash' ) ) {
			RCIL\Pro\forbidden_custom_splash();
		} else {
			// This is the default forbidden screen for all cases.
			status_header( 403 );
			wp_die( esc_html__( 'Forbidden!', 'slicr' ) );
		}
	}

	/**
	 * Assess if the current user has restrictions.
	 *
	 * @return bool
	 */
	public static function user_has_restriction() { //phpcs:ignore
		if ( false === self::$curent_user_assessed || ! empty( self::$simulate ) ) {
			// Proceed with the computation.
			if ( ! empty( self::$simulate ) ) {
				// This is a simulation.
				$code_co = self::$simulate['simulate_country'];
				$user_ip = self::$simulate['simulate_ip'];
				if ( empty( $code_co ) ) {
					$code_co = self::get_user_country_name( $user_ip );
				}
			} else {
				// This is real check, no simulation.
				$user_ip = self::get_current_ip();
				$code_co = self::get_user_country_name();
			}

			self::$curent_user_restriction = self::current_user_has_restriction( $user_ip, $code_co );
			self::$curent_user_assessed    = true;
		}

		// If we got this far, the user restriction was assessed.
		return self::$curent_user_restriction;
	}

	/**
	 * Country code is whitelisted.
	 *
	 * @param  string $code Country code.
	 * @return bool
	 */
	public static function country_is_whitelisted( $code = '' ) { // phpcs:ignore
		if ( false === self::$rules->restrict->co || '!NA' === $code ) {
			// Fail-fast, no country restriction or code not identified.
			return true;
		}
		if ( ! in_array( $code, self::$rules->allow->co, true ) ) {
			// There is a restriction but the country is not in the allowed list.
			return false;
		}
		return true;
	}

	/**
	 * Country code is blacklisted.
	 *
	 * @param  string $code Country code.
	 * @return bool
	 */
	public static function country_is_blacklisted( $code = '' ) { // phpcs:ignore
		if ( false === self::$rules->restrict->co || '!NA' === $code ) {
			// Fail-fast, no country restriction or code not identified.
			return false;
		}
		if ( in_array( $code, self::$rules->block->co, true ) ) {
			// There is a restriction but the country is in the blocked list.
			return true;
		}
		return false;
	}

	/**
	 * IP is whitelisted.
	 *
	 * @param  string $ip IP code.
	 * @return bool
	 */
	public static function ip_is_whitelisted( $ip = '' ) { // phpcs:ignore
		if ( false === self::$rules->restrict->ip ) {
			// Fail-fast, no IP restriction.
			return true;
		}
		if ( in_array( $ip, self::$rules->allow->ip, true ) ) {
			// There is a restriction and the IP is in the allowed list.
			return true;
		}
		return false;
	}

	/**
	 * IP is blacklisted.
	 *
	 * @param  string $ip IP code.
	 * @return bool
	 */
	public static function ip_is_blacklisted( $ip = '' ) { // phpcs:ignore
		if ( false === self::$rules->restrict->ip ) {
			// Fail-fast, no IP restriction.
			return false;
		}
		if ( in_array( $ip, self::$rules->block->ip, true ) ) {
			// There is a restriction and the IP is in the blocked list.
			return true;
		}
		return false;
	}

	/**
	 * Assess rule by type.
	 *
	 * @param  int    $forbid Forbidden rules matched.
	 * @param  string $co     Maybe a country code.
	 * @param  string $ip     Maybe an IP code.
	 * @return bool
	 */
	public static function assess_rule_by_type( $forbid, $co = '', $ip = '' ) { // phpcs:ignore
		$ip       = ( ! empty( $ip ) ) ? $ip : self::get_current_ip();
		$co       = ( ! empty( $co ) ) ? $co : self::get_user_country_name( $ip );
		$forbid   = self::check_bypass_single_login( $forbid, $ip );
		$ip_white = self::ip_is_whitelisted( $ip );
		$ip_black = self::ip_is_blacklisted( $ip );
		$co_white = self::country_is_whitelisted( $co );
		$co_black = self::country_is_blacklisted( $co );

		if ( 0 === self::$rules->type ) {
			// Allow login only for allowed countries or allowed IPs.
			if ( ! ( $ip_white || $co_white ) ) {
				++ $forbid;
			}
		} elseif ( 1 === self::$rules->type ) {
			// Block login only for blocked countries or blocked IPs.
			if ( $ip_black || $co_black ) {
				++ $forbid;
			}
		} elseif ( 6 === self::$rules->type ) {
			// Allow login only for allowed IPs.
			if ( ! $ip_white ) {
				++ $forbid;
			}
		} elseif ( 7 === self::$rules->type ) {
			// Allow login only from allowed countries.
			if ( ! $co_white ) {
				++ $forbid;
			}
		} elseif ( 8 === self::$rules->type ) {
			// Block login only for blocked IPs.
			if ( $ip_black ) {
				++ $forbid;
			}
		} elseif ( 9 === self::$rules->type ) {
			// Block login only from blocked countries.
			if ( $co_black ) {
				++ $forbid;
			}
		}

		return $forbid;
	}

	/**
	 * Assess if the specified user has restrictions.
	 *
	 * @param  string $ip           IP address.
	 * @param  string $country_code Country code.
	 * @return bool
	 */
	public static function current_user_has_restriction( $ip, $country_code ) { //phpcs:ignore
		$forbid = 0;
		$forbid = apply_filters( 'assess_rule_by_type', $forbid, $country_code, $ip );
		return ( ! empty( $forbid ) ) ? true : false;
	}

	/**
	 * Describe rule by type.
	 *
	 * @return string
	 */
	public static function describe_rule_by_type() { //phpcs:ignore
		$text = '';
		if ( ! self::$rules->restrict->ip && ! self::$rules->restrict->co ) {
			return esc_html__( 'Based on the current options there is no login restriction.', 'slicr' );
		} else {
			switch ( self::$rules->type ) {
				case 6:
					$text = esc_html( sprintf(
						// Translators: %1$s - list of country names.
						__( 'Based on the current options there is a login restriction, this is allowed only for these IPs: %1$s.', 'slicr' ),
						( empty( self::$rules->allow->ip ) )
							? __( 'any', 'slicr' )
							: self::CHAR_ALLOW . ' ' . implode( ', ' . self::CHAR_ALLOW . ' ', self::$rules->allow->ip )
					) );
					break;

				case 7:
					$text = esc_html( sprintf(
						// Translators: %1$s - list of country names.
						__( 'Based on the current options there is a login restriction, this is allowed only from these countries: %1$s.', 'slicr' ),
						( empty( self::$rules->allow->co ) )
							? __( 'none', 'slicr' )
							: self::CHAR_ALLOW . ' ' . implode( ', ' . self::CHAR_ALLOW . ' ', self::$rules->allow->co )
					) );
					break;

				case 8:
					$text = esc_html( sprintf(
						// Translators: %1$s - list of country names.
						__( 'Based on the current options there is a login restriction, this is blocked for these IPs: %1$s.', 'slicr' ),
						( empty( self::$rules->block->ip ) )
							? __( 'none', 'slicr' )
							: self::CHAR_BLOCK . ' ' . implode( ', ' . self::CHAR_BLOCK . ' ', self::$rules->block->ip )
					) );
					break;

				case 9:
					$text = esc_html( sprintf(
						// Translators: %1$s - list of country names.
						__( 'Based on the current options there is a login restriction, this is blocked from these countries: %1$s.', 'slicr' ),
						( empty( self::$rules->block->co ) )
							? __( 'none', 'slicr' )
							: self::CHAR_BLOCK . ' ' . implode( ', ' . self::CHAR_BLOCK . ' ', self::$rules->block->co )
					) );
					break;

				case 1:
					$text = esc_html( sprintf(
						// Translators: %1$s - list of country names.
						__( 'Based on the current options there is a login restriction, this is blocked for these IPs: %1$s and from these countries: %2$s.', 'slicr' ),
						( empty( self::$rules->block->ip ) )
							? __( 'none', 'slicr' )
							: self::CHAR_BLOCK . ' ' . implode( ', ' . self::CHAR_BLOCK . ' ', self::$rules->block->ip ),
						( empty( self::$rules->block->co ) )
							? __( 'none', 'slicr' )
							: self::CHAR_BLOCK . ' ' . implode( ', ' . self::CHAR_BLOCK . ' ', self::$rules->block->co )
					) );
					break;

				case 0:
				default:
					$text = esc_html( sprintf(
						// Translators: %1$s - list of country names.
						__( 'Based on the current options there is a login restriction, this is allowed from these IPs: %1$s and from these countries: %2$s.', 'slicr' ),
						( self::$rules->wildcard->ip )
							? __( 'any', 'slicr' )
							: self::CHAR_ALLOW . ' ' . implode( ', ' . self::CHAR_ALLOW . ' ', self::$rules->allow->ip ),
						( empty( self::$rules->allow->co ) )
							? __( 'none', 'slicr' )
							: self::CHAR_ALLOW . ' ' . implode( ', ' . self::CHAR_ALLOW . ' ', self::$rules->allow->co )
					) );
					break;
			}
		}

		$text = apply_filters( 'describe_rule_by_type', $text );
		return $text;
	}

	/**
	 * Returns the current user if this is allowed (hence defaults to WordPress functionality)
	 * or forbid access to authentication.
	 *
	 * @param  \WP_User $user     Potential WP_User instance.
	 * @param  string   $username Username.
	 * @param  string   $password Passeword.
	 * @return object
	 */
	public static function sisanu_restrict_country( $user, $username, $password ) { // phpcs:ignore
		self::$user_id = ( ! empty( $user->ID ) ) ? $user->ID : 0;

		$role_bypass = apply_filters( 'sislrc_maybe_role_bypass', false, $user );
		if ( true === $role_bypass ) {
			// This is probably a customer as his role is in the list of bypassed.
			if ( ! empty( self::$user_id ) && self::$is_pro && function_exists( 'RCIL\Pro\sislrc_pro_collect_first_ip' ) ) {
				RCIL\Pro\sislrc_pro_collect_first_ip( self::$user_id );
			}
			return $user;
		}

		$restrict = self::user_has_restriction();
		if ( ! empty( $restrict ) ) {
			// The user country based on the user IP is not in the list of allowed countries and also the user IP is not in the allowed IPs list.
			wp_logout();
			do_action( 'sislrc_maybe_404_redirect' );
			self::forbidden_screen();
		} else {
			// If we got this far, the user seems legit.
			if ( ! empty( self::$settings['users_lockout'] ) && ! empty( self::$user_id ) ) {
				$lockout = get_user_meta( self::$user_id, 'rcil-user-lockout', true );
				if ( ! empty( $lockout ) ) {
					wp_logout();
					do_action( 'sislrc_maybe_404_redirect' );
					self::forbidden_screen();
				}
			}

			if ( ! empty( self::$user_id ) && self::$is_pro && function_exists( 'RCIL\Pro\sislrc_pro_collect_first_ip' ) ) {
				RCIL\Pro\sislrc_pro_collect_first_ip( self::$user_id );
			}
			return $user;
		}
	}

	/**
	 * Disable or not the XML-RPC methods that require authentication,
	 * based on the current visitor restriction or not.
	 *
	 * @param  bool $enabled True if the XML-RPC methods that require authentication are enabled.
	 * @return bool
	 */
	public static function xmlrpc_auth_methods_enabled( $enabled ) { //phpcs:ignore
		if ( empty( self::$settings['xmlrpc_auth_filter'] ) ) {
			// Fallback to the initial state.
			return $enabled;
		} elseif ( 'all' === self::$settings['xmlrpc_auth_filter'] ) {
			// Disable all the time.
			return false;
		} else {
			$restrict = self::user_has_restriction();
			if ( ! empty( $restrict ) ) {
				// Disable only for a restriction.
				return false;
			}
		}

		// Fallback to the initial state.
		return $enabled;
	}

	/**
	 * Add the plugin settings and plugin URL links.
	 *
	 * @param  array $links The plugin links.
	 * @return array
	 */
	public static function plugin_action_links( $links ) { //phpcs:ignore
		$all   = [];
		$all[] = '<a href="' . esc_url( self::$plugin_url ) . '">' . esc_html__( 'Settings', 'slicr' ) . '</a>';
		$all[] = '<a href="https://iuliacazan.ro/login-ip-country-restriction">' . esc_html__( 'Plugin URL', 'slicr' ) . '</a>';
		$all   = array_merge( $all, $links );
		return $all;
	}

	/**
	 * The actions to be executed when the plugin is updated.
	 *
	 * @return void
	 */
	public static function plugin_ver_check() {
		$opt = str_replace( '-', '_', self::PLUGIN_TRANSIENT ) . '_db_ver';
		$dbv = get_option( $opt, 0 );
		if ( SISANU_RCIL_CURRENT_DB_VERSION !== (float) $dbv ) {
			update_option( $opt, SISANU_RCIL_CURRENT_DB_VERSION );
			self::activate_plugin();
		}
	}

	/**
	 * Execute notices cleanup.
	 *
	 * @param  bool $ajax Is AJAX call.
	 * @return void
	 */
	public static function plugin_admin_notices_cleanup( $ajax = true ) { //phpcs:ignore
		// Delete transient, only display this notice once.
		delete_transient( self::PLUGIN_TRANSIENT );

		if ( true === $ajax ) {
			// No need to continue.
			wp_die();
		}
	}

	/**
	 * Admin notices.
	 *
	 * @return void
	 */
	public static function plugin_admin_notices() {
		if ( apply_filters( 'slicr_filter_remove_update_info', false ) ) {
			return;
		}

		$maybe_trans = get_transient( self::PLUGIN_TRANSIENT );
		if ( ! empty( $maybe_trans ) ) {
			$slug      = md5( SISANU_RCIL_SLUG );
			$title     = __( 'Login IP & Country Restriction', 'slicr' );
			$donate    = 'https://www.paypal.com/cgi-bin/webscr?cmd=_s-xclick&hosted_button_id=JJA37EHZXWUTJ&item_name=Support for development and maintenance (' . rawurlencode( self::PLUGIN_NAME ) . ')';
			$thanks    = __( 'A huge thanks in advance!', 'slicr' );
			$maybe_pro = '';

			if ( empty( self::$is_pro ) ) {
				$maybe_pro = sprintf(
					// Translators: %1$s - extensions URL.
					__( 'You are using the free version. Get the <a href="%1$s" target="_blank"><b>PRO</b> version</a>. ', 'slicr' ),
					'https://iuliacazan.ro/wordpress-extension/login-ip-country-restriction-pro/'
				) . '<br>';
			} else {
				$maybe_pro = sprintf(
					// Translators: %1$s - pro version label, %2$s - PRO URL.
					__( 'Thank you for purchasing the <a href="%1$s" target="_blank"><b>PRO</b> version</a>! ', 'slicr' ),
					'https://iuliacazan.ro/wordpress-extension/login-ip-country-restriction-pro/'
				) . '<br>';
			}

			$other_notice = sprintf(
				// Translators: %1$s - plugins URL, %2$s - heart icon, %3$s - extensions URL, %4$s - star icon, %5$s - maybe PRO details.
				__( '%5$sCheck out my other <a href="%1$s" target="_blank" rel="noreferrer">%2$s free plugins</a> on WordPress.org and the <a href="%3$s" target="_blank" rel="noreferrer">%4$s other extensions</a> available!', 'slicr' ),
				'https://profiles.wordpress.org/iulia-cazan/#content-plugins',
				'<span class="dashicons dashicons-heart"></span>',
				'https://iuliacazan.ro/shop/',
				'<span class="dashicons dashicons-star-filled"></span>',
				$maybe_pro
			);

			?>

			<div id="item-<?php echo esc_attr( $slug ); ?>" class="updated notice">
				<div class="icon">
					<a href="<?php echo esc_url( self::$plugin_url ); ?>"><img src="<?php echo esc_url( SISANU_RCIL_URL . 'assets/images/icon-128x128.gif' ); ?>"></a>
				</div>
				<div class="content">
					<div>
						<h3>
							<?php
							echo wp_kses_post( sprintf(
								// Translators: %1$s - plugin name.
								__( '%1$s plugin was activated!', 'slicr' ),
								'<b>' . $title . '</b>'
							) );
							?>
						</h3>
						<div class="notice-other-items"><div><?php echo wp_kses_post( $other_notice ); ?></div></div>
					</div>
					<div>
						<?php
						echo wp_kses_post( sprintf(
								// Translators: %1$s - donate URL, %2$s - rating, %3$s - thanks.
							__( 'If you find the plugin useful and would like to support my work, please consider making a <a href="%1$s" target="_blank">donation</a>. It would make me very happy if you would leave a %2$s rating. %3$s', 'slicr' ),
							$donate,
							'<a href="' . self::PLUGIN_SUPPORT_URL . 'reviews/?rate=5#new-post" class="rating" target="_blank" rel="noreferrer" title="' . esc_attr( $thanks ) . '">★★★★★</a>',
							$thanks
						) );
						?>
					</div>
					<a class="notice-plugin-donate" href="<?php echo esc_url( $donate ); ?>" target="_blank"><img src="<?php echo esc_url( SISANU_RCIL_URL . 'assets/images/buy-me-a-coffee.png?v=' . SISANU_RCIL_CURRENT_DB_VERSION ); ?>" width="200"></a>
				</div>
				<div class="action">
					<div class="dashicons dashicons-no" onclick="dismiss_notice_for_<?php echo esc_attr( $slug ); ?>()"></div>
				</div>
			</div>
			<?php
			$style = '
			#trans123super{--color-bg:rgba(176,227,126,0.2); --color-border:rgb(176,227,126); display:grid; padding:0; gap:0; grid-template-columns:6rem auto 3rem; max-width:100%; width:100%; border-left-color: var(--color-border); box-sizing:border-box;} #trans123super .dashicons-no{font-size:2rem; cursor:pointer;} #trans123super .icon{ display:grid; align-content:start; background-color:var(--color-bg); padding: 1rem} #trans123super .icon img{object-fit:cover; object-position:center; width:100%; display:block} #trans123super .action{ display:grid; align-content:start; padding: 1rem 0.5rem} #trans123super .content{ align-items: center; display: grid; gap: 1rem; grid-template-columns: 1fr 1fr 12rem; padding: 1rem;} #trans123super .content .dashicons{color:var(--color-border);} #trans123super .content > div{color:#666;} #trans123super h3{margin:0 0 0.1rem 0;color:#666} #trans123super h3 b{color:#000} #trans123super a{color:#000;text-decoration:none;} #trans123super .notice-plugin-donate img{max-width: 100%;} @media all and (max-width: 1024px) {#trans123super .content{grid-template-columns:100%;}}';
			$style = str_replace( '#trans123super', '#item-' . esc_attr( $slug ), $style );
			echo '<style>' . $style . '</style>'; //phpcs:ignore
			?>
			<script>function dismiss_notice_for_<?php echo esc_attr( $slug ); ?>() { document.getElementById( 'item-<?php echo esc_attr( $slug ); ?>' ).style='display:none'; fetch( '<?php echo esc_url( admin_url( 'admin-ajax.php' ) ); ?>?action=plugin-deactivate-notice-<?php echo esc_attr( SISANU_RCIL_SLUG ); ?>' ); }</script>
			<?php
		}
	}

	/**
	 * Maybe donate or rate.
	 *
	 * @return void
	 */
	public static function show_donate_text() {
		?>
		<div>
			<?php
			if ( ! self::$is_pro ) {
				echo wp_kses_post(
					sprintf(
						// Translators: %1$s - donate URL, %2$s - rating, %3$s - thanks.
						__( 'If you find the plugin useful and would like to support my work, please consider making a <a href="%1$s" target="_blank">donation</a>. It would make me very happy if you would leave a %2$s rating. %3$s', 'slicr' ),
						'https://www.paypal.com/cgi-bin/webscr?cmd=_s-xclick&hosted_button_id=JJA37EHZXWUTJ&item_name=Support for development and maintenance (' . rawurlencode( self::PLUGIN_NAME ) . ')',
						'<a href="' . self::PLUGIN_SUPPORT_URL . 'reviews/?rate=5#new-post" class="rating" target="_blank" title="' . esc_attr__( 'A huge thanks in advance!', 'slicr' ) . '">★★★★★</a>',
						__( 'A huge thanks in advance!', 'slicr' )
					)
				);
			} else {
				echo wp_kses_post( sprintf(
					// Translators: %1$s - 5 stars, %2$s - thanks.
					__( 'It would make me very happy if you would leave a %1$s rating.<br>%2$s', 'slicr' ),
					'<a href="' . self::PLUGIN_SUPPORT_URL . 'reviews/?rate=5#new-post" class="rating" target="_blank" title="' . esc_attr__( 'A huge thanks in advance!', 'slicr' ) . '">★★★★★</a>',
					__( 'A huge thanks in advance!', 'slicr' )
				) );
			}
			?>
		</div>
		<img src="<?php echo esc_url( plugin_dir_url( __FILE__ ) . '/assets/images/icon-128x128.gif' ); ?>" width="32" height="32" alt="">
		<?php
	}
}

$srcil = SISANU_Restrict_Country_IP_Login::get_instance();
register_activation_hook( __FILE__, [ $srcil, 'activate_plugin' ] );
register_deactivation_hook( __FILE__, [ $srcil, 'deactivate_plugin' ] );
