<?php

defined( 'CANVAS_URL' ) || die();

class Canvas {


	public static $instance              = null;
	public $canvas_theme_object          = null;
	public $canvas_theme_settings_object = null;

	const PRIORITY        = 10000;
	const THEME_OPTION    = 'theme-for-app';
	const THEME_DIFFERENT = 'different-theme-for-app';

	const DB_VERSION = 4;

	public static $slug          = 'canvas';
	protected static $slug_theme = 'canvas-theme-setup';

	public static function get() {
		if ( null === self::$instance ) {
			self::$instance = new self();
		}
		return self::$instance;
	}

	function __construct() {
		self::$slug       = apply_filters( 'canvas_slug', self::$slug );
		self::$slug_theme = apply_filters( 'canvas_slug_theme', self::$slug_theme );

		add_action( 'plugins_loaded', array( $this, 'on_plugins_loaded' ) );

		require_once dirname( __FILE__ ) . '/canvas_theme_settings.class.php';

		$this->canvas_theme_settings_object = new CanvasThemeSettings();

		if ( self::get_option( 'push_auto_enabled' ) ) {
			require_once CANVAS_DIR . 'core/push/canvas-notifications.class.php';
			add_action( 'transition_post_status', array( 'CanvasNotifications', 'post_published_notification' ), 10, 3 );
		}
		if ( self::get_option( 'db-version' ) != self::DB_VERSION ) {
			// update db on db version change.
			self::update_db();
		}

		add_filter( 'auth_cookie_expiration', array( __CLASS__, 'set_login_forever' ) );
	}

	/**
	 * Plugin activation hook
	 */
	static function activate() {
		set_transient( '__canvas_activation_redirect', 1, 60 );
		Canvas_Api::activate();
		require_once CANVAS_DIR . 'core/canvas-admin.class.php';
		self::update_db();
		self::set_option( 'schedule_dismiss', false );
		self::add_user_role();
	}

	function on_plugins_loaded() {
		require_once dirname( __FILE__ ) . '/canvas_theme.class.php';
		$this->canvas_theme_object = new CanvasTheme();
	}

	/**
	 * Add custom user role based on Subscriber permission
	 */
	public static function add_user_role() {
		$subscriber = get_role( 'subscriber' );

		$capabilities = ( $subscriber instanceof \WP_Role ) ? $subscriber->capabilities : array( 'read' => true );

		add_role( 'app_user', 'App User', $capabilities );
	}


	/*
	* Some of the code for theme switching is a derivative work of the code from the Apppresser plugin,
	* which is licensed GPLv2. This code is also licensed under the terms of the GNU Public License, verison 2.
	*/

	/**
	 * External options set. Required function
	 *
	 * @param string $name
	 * @param mixed  $value
	 */
	public static function set_account( $name, $value ) {
		self::set_option( $name, $value );
	}

	/**
	 * Set theme for switching
	 *
	 * @param bool   $theme_different
	 * @param string $theme
	 */
	public static function set_theme( $theme_different, $theme = '' ) {
		self::set_account( self::THEME_DIFFERENT, $theme_different );
		self::set_account( self::THEME_OPTION, $theme_different && ! empty( $theme ) ? $theme : '' );
	}

	/**
	 * Update option
	 *
	 * @param string $name
	 * @param mixed  $value
	 */
	public static function set_option( $name, $value ) {
		return update_option( 'canvas-' . $name, $value );
	}

	/**
	 * Get option value
	 *
	 * @param string $name
	 * @param mixed  $default
	 * @return mixed
	 */
	public static function get_option( $name, $default = false ) {
		$option_value = get_option( 'canvas-' . $name, $default );
		if ( empty( $option_value ) && ! empty( $default ) ) {
			$option_value = $default;
		}
		return $option_value;
	}

	/**
	 * Get customizer url for current theme
	 */
	public static function get_theme_customize_url() {
		$url = add_query_arg(
			array(
				self::$slug_theme => 1,
				'theme'           => self::get_option( self::THEME_OPTION ),
			),
			admin_url( 'customize.php' )
		);
		return $url;
	}

	/**
	 * Get plugin settings url
	 */
	public static function main_settings_url() {
		return add_query_arg( 'page', self::$slug, admin_url( 'admin.php' ) );
	}
	/**
	 * App id and key set
	 */
	public static function push_keys_set() {
		return self::get_option( 'push_app_id' ) && self::get_option( 'push_key' );
	}

	/**
	 * Source of request is a Canvas application
	 *
	 * @return bool
	 */
	public static function is_request_from_application() {
		if ( self::identify_app_by_get_param() ) {
			return ! empty( $_GET['app'] );
		} else {
			return isset( $_SERVER['HTTP_USER_AGENT'] ) && strstr( strtolower( $_SERVER['HTTP_USER_AGENT'] ), 'canvas' );
		}
	}

	/**
	* Identify App using get parameter, otherwise indentify using user agent
	*
	* @since 3.5.3
	*
	* @return bool
	*/
	public static function identify_app_by_get_param() {
		return ! empty( self::get_option( 'identify_app_by_get_param', false ) );
	}

	public static function update_db() {
		global $wpdb;
		require_once ABSPATH . 'wp-admin/includes/upgrade.php';

		// append boolean "private" field (default true) to push messages table.
		$table_name = $wpdb->prefix . 'canvas_notifications';
		$sql        = 'CREATE TABLE ' . $table_name . " (
		id bigint(11) NOT NULL AUTO_INCREMENT,
		time bigint(11) DEFAULT '0' NOT NULL,
		post_id bigint(11),
		url VARCHAR(255) NULL DEFAULT NULL,
		title text NOT NULL DEFAULT '',
		msg blob,
		android varchar(1) NOT NULL,
		ios varchar(1) NOT NULL,
		tags blob,
		private tinyint(1) NOT NULL DEFAULT '1',
		UNIQUE KEY id (id),
		KEY post_id (post_id)
		);";

		dbDelta( $sql );

		if ( 1 === self::get_option( 'db-version', 1 ) ) {
			// for existing push messages: all messages with any platform are public.
			$wpdb->query( 'UPDATE ' . $table_name . " SET `private`=0 WHERE `ios`='Y' OR android = 'Y'" );
		}
		self::set_option( 'db-version', self::DB_VERSION );
	}

	/**
	 * Sets auth cookie expiration time.
	 *
	 * @param int $length Duration of the expiration period in seconds.
	 *
	 * @return int
	 */
	public static function set_login_forever( $length ) {
		$should_login_forever = Canvas::get_option( 'forever_logged_in', false );
		$is_req_from_app      = Canvas::is_request_from_application();

		if ( ! ( $is_req_from_app && $should_login_forever ) ) {
			return $length;
		}

		return YEAR_IN_SECONDS;
	}
}
