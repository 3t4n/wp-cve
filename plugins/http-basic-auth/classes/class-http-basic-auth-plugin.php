<?php

class HTTP_Basic_Auth_Plugin extends WPDesk_Plugin_1_9 {

	/**
	 * @var WP_Admin_Bar
	 */
	private $admin_bar;

	/**
	 * @var bool
	 */
	public $authenticated_custom = false;

	/**
	 * @var bool
	 */
	public $authenticated_wordpress_user = false;

	/**
	 * @var string
	 */
	public $support_url = '';

	/**
	 * Basic_Auth_Plugin constructor.
	 *
	 * @param $base_file
	 * @param $plugin_data
	 */
	public function __construct( $base_file, $plugin_data ) {

		$this->plugin_namespace = 'http-basic-auth';
		$this->plugin_text_domain = 'http-basic-auth';

		$this->plugin_has_settings = true;
		$this->default_settings_tab = 'settings';

		$this->plugin_is_active = true;

		parent::__construct( $base_file, $plugin_data );

	}

	/**
	 *
	 */
	public function init_dependencies() {
		require_once __DIR__ . '/class-http-basic-auth-settings-hooks.php';
		$this->settings_hooks = new HTTP_Basic_Auth_Settings_Hooks( $this );
		$this->settings_hooks->hooks();

		require_once __DIR__ . '/class-admin-bar.php';
		$this->admin_bar = new HTTP_Basic_Auth_Admin_Bar( $this );
		$this->admin_bar->hooks();
	}

	/**
	 *
	 */
	public function check_auth() {
		$settings = $this->get_settings();

		global $pagenow;

		$doing_ajax = defined( 'DOING_AJAX' ) && DOING_AJAX;

		if ( $settings->get_option( 'enable_basic_auth', '0' ) == '1' ) {
			$require_auth = false;
			$is_authenticated = false;
			if ( $settings->get_option( 'custom_login', '0' ) == '1' ) {
				if ( $settings->get_option( 'protect_login', '0' ) == '1' && $pagenow == 'wp-login.php' ) {
					$require_auth = true;
					$is_authenticated = $this->is_authenticated_custom( $settings->get_option( 'login' ), $settings->get_option( 'password' ) );
				}
				else if ( $settings->get_option( 'protect_admin', '0' ) == '1' && is_admin() && ! $doing_ajax ) {
					$require_auth = true;
					$is_authenticated = $this->is_authenticated_custom( $settings->get_option( 'login' ), $settings->get_option( 'password' ) );
				}
				else if ( $settings->get_option( 'protect_frontend', '0' ) == '1' ) {
					$require_auth = true;
					$is_authenticated = $this->is_authenticated_custom( $settings->get_option( 'login' ), $settings->get_option( 'password' ) );
				}
				if ( $require_auth && !$is_authenticated && $settings->get_option( 'wordpress_login', '0' ) != '1' ) {
					$this->require_auth( $settings->get_option( 'realm', '' ) );
				}
				$this->authenticated_custom = $is_authenticated;
			}
			if ( $settings->get_option( 'wordpress_login', '0' ) == '1' ) {
				if ( ! $is_authenticated ) {
					add_action( 'plugins_loaded', array( $this, 'plugins_loaded_action' ) );
				}
			}
		}

	}

	/**
	 *
	 */
	public function init() {
		$this->hooks();
	}

	/**
	 *
	 */
	public function hooks() {
		parent::hooks();
	}

	/**
	 * action_links function.
	 *
	 * @access public
	 * @param mixed $links
	 * @return void
	 */
	public function links_filter( $links ) {

		$this->support_url = 'https://wordpress.org/support/plugin/basic-auth';

		if ( $this->support_url ) {
			$plugin_links = array(
				'<a href="' . $this->support_url . '">' . __( 'Support', 'basic-auth' ) . '</a>',
			);
			$links = array_merge( $plugin_links, $links );
		}

		$this->docs_url = 'https://wordpress.org/plugins/basic-auth/';

		if ( $this->docs_url ) {
			$plugin_links = array(
				'<a href="' . $this->docs_url . '">' . __( 'Docs', 'basic-auth' ) . '</a>',
			);
			$links = array_merge( $plugin_links, $links );
		}

		$this->settings_url = admin_url( 'options-general.php?page=http-basic-auth-settings' );

		if ( $this->settings_url ) {
			$plugin_links = array(
				'<a href="' . $this->settings_url . '">' . __( 'Settings', 'basic-auth' ) . '</a>',
			);
			$links = array_merge( $plugin_links, $links );
		}

		return $links;
	}

	/**
	 * @return bool
	 */
	public function is_authenticated_wordpress_user() {
		$is_authenticated = false;
		$has_supplied_credentials = !( empty( $_SERVER['PHP_AUTH_USER'] ) && empty( $_SERVER['PHP_AUTH_PW'] ) );
		if ( $has_supplied_credentials ) {
			$current_user = wp_get_current_user();
			if ( $current_user && $current_user->ID !== 0 && $current_user->user_login == $_SERVER['PHP_AUTH_USER'] ) {
				$is_authenticated = true;
			} else {
				$current_user = wp_authenticate( $_SERVER['PHP_AUTH_USER'], $_SERVER['PHP_AUTH_PW'] );
				if ( ! is_wp_error( $current_user ) && $current_user && $current_user->ID !== 0 ) {
					$is_authenticated = true;
				}
			}
		}
		return $is_authenticated;
	}

	/**
	 *
	 */
	public function plugins_loaded_action() {
		$settings = $this->get_settings();
		if ( ! $this->is_authenticated_wordpress_user()  ) {
			$this->require_auth( $settings->get_option( 'realm', '' )  );
		}
		$this->authenticated_wordpress_user = true;
	}

	/**
	 * @param $login
	 * @param $password
	 *
	 * @return bool
	 */
	public function is_authenticated_custom( $login, $password ) {
		$has_supplied_credentials = !( empty( $_SERVER['PHP_AUTH_USER'] ) && empty( $_SERVER['PHP_AUTH_PW'] ) );
		$is_authenticated = (
			$has_supplied_credentials &&
			$_SERVER['PHP_AUTH_USER'] === $login &&
			$_SERVER['PHP_AUTH_PW']   === $password
		);
		return $is_authenticated;
	}

	/**
	 * @param string $realm
	 */
	function send_auth_headers( $realm = '' ) {
		if ( headers_sent() ) {
			echo __( 'Basic Auth Plugin: something go wrong! HTTP headers already sent!', 'basic-auth' );
		}
		else {
			header( 'Cache-Control: no-cache, must-revalidate, max-age=0' );
			header( 'HTTP/1.1 401 Authorization Required' );
			header( 'WWW-Authenticate: Basic realm="' . esc_attr( $realm ) . '"' );
		}
	}

	/**
	 *
	 */
	function require_auth() {
		$this->send_auth_headers();
		exit;
	}

}
