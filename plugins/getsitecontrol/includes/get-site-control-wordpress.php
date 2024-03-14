<?php

/**
 * Class GetsitecontrolWordPress
 */
class GetsitecontrolWordPress {


	public static $version          = '3.0.0';
	public static $registerLink     = 'https://dash.getsitecontrol.com/api/v1/users/register';
	public static $loginLink        = 'https://dash.getsitecontrol.com/api/v1/users/login?dual=1';
	public static $googleSigninLink = 'https://dash.getsitecontrol.com/api/v1/socialauth-begin/google-oauth2/?mode=signin-popup&dual=1';
	public static $googleSignupLink = 'https://dash.getsitecontrol.com/api/v1/socialauth-begin/google-oauth2/?mode=signup-popup';

	public static $sitesLink        = 'https://{{API_DOMAIN}}/api/v1/sites/own';
	public static $autoLoginLink    = 'https://{{API_DOMAIN}}/api/v1/users/autologin';

	public static $settings         = array();
	public static $errors           = array();
	public static $actions          = array(
		'index' => array(
			'slug'     => 'getsitecontrol',
			'function' => 'action_admin_menu_page',
			'name'     => 'Getsitecontrol',
			'title'    => 'Getsitecontrol for WordPress settings',
		),
		'auth'  => array(
			'sign-out' => array(
				'slug'     => 'getsitecontrol_sign_out',
				'function' => 'action_admin_menu_sign_out',
				'name'     => 'Sign out',
				'title'    => 'Sign out - Getsitecontrol',
			),
		),
		'guest' => array(
			'sign-in' => array(
				'slug'     => 'getsitecontrol_sign_in',
				'function' => 'action_admin_menu_sign_in',
				'name'     => 'Sign in',
				'title'    => 'Sign in to Getsitecontrol',
			),
			'sign-up' => array(
				'slug'     => 'getsitecontrol_sign_up',
				'function' => 'action_admin_menu_sign_up',
				'name'     => 'Sign up',
				'title'    => 'Sign up to Getsitecontrol',
			),
		),

	);
	private static $getSiteControl = null;

	public function __construct() {

	}


	/**
	 * Get instance
	 *
	 * @return GetsitecontrolWordPress|null
	 */
	public static function init() {
		if ( is_null( self::$getSiteControl ) ) {
			self::$getSiteControl = new self();
			self::add_actions();
			self::$settings = self::gsc_settings();
			if ( empty( self::$settings ) || ( self::$version !== self::$settings['version'] ) ) {
				self::install( self::$settings );
			}
		}

		return self::$getSiteControl;
	}

	/**
	 * Add actions for plugin
	 */
	public static function add_actions() {
		if ( is_admin() ) {
			add_action( 'admin_init', array( __CLASS__, 'redirect_rule' ) );

			add_action( 'admin_menu', array( __CLASS__, 'admin_menu_add' ) );
			add_action( 'admin_menu', array( __CLASS__, 'admin_sub_menu_add' ) );

			add_action( 'wp_ajax_gsc_post_site_select', array( __CLASS__, 'gsc_post_site_select' ) );
			add_action( 'wp_ajax_gsc_post_clear_api_key', array( __CLASS__, 'gsc_post_clear_api_key' ) );
			add_action( 'wp_ajax_gsc_post_sign_in', array( __CLASS__, 'gsc_post_sign_in' ) );

			add_action( 'admin_enqueue_scripts', array( __CLASS__, 'admin_scripts' ) );
		} else {
			add_action( 'wp_enqueue_scripts', array( __CLASS__, 'add_script' ) );
		}
	}

	/**
	 * Get Getsitecontrol settings
	 *
	 * @return mixed
	 */
	public static function gsc_settings() {
		return get_option( 'get_site_control_settings' );
	}

	/**
	 * Set Getsitecontrol settings
	 *
	 * @param $gsc_settings
	 */
	public static function install( $gsc_settings ) {
		if ( empty( $gsc_settings ) ) {
			$gsc_settings = array(
				'api_key'           => null,
				'api_domain'        => null,
				'version'           => self::$version,
				'script'            => null,
				'site_id'           => null,
			);
			add_option( 'get_site_control_settings', $gsc_settings );
		}

		// support old deprecated settings
        if ( ! empty( $gsc_settings['widget_link'] ) ) {
		    $gsc_settings['script'] = $gsc_settings['widget_link'];
		    $gsc_settings['site_id'] = $gsc_settings['widget_id'];
		}
		if ( empty( $gsc_settings['api_domain'] ) ){
		    $gsc_settings['api_domain'] = 'app.getsitecontrol.com';
		}

		if ( self::$version !== $gsc_settings['version'] ) {
			self::update( $gsc_settings );
		}
	}

	/**
	 * Update Getsitecontrol settings
	 *
	 * @param $gsc_settings
	 *
	 * @return bool
	 */
	public static function update( $gsc_settings ) {
		$gsc_settings['version'] = self::$version;

		return update_option( 'get_site_control_settings', $gsc_settings );
	}

	/**
	 * Add plugin link in sidebar
	 */
	public static function admin_menu_add() {
		add_menu_page(
			self::$actions['index']['title'],
			self::$actions['index']['name'],
			'manage_options',
			self::$actions['index']['slug'],
			array( __CLASS__, self::$actions['index']['function'] ),
			GSC_URL . 'templates/images/gsc-logo-white.png'
		);
	}


	/**
	 * Add plugin sub links in sidebar
	 */
	public static function admin_sub_menu_add() {
		$type = 'auth';

		if ( empty( self::$settings['api_key'] ) ) {
			$type = 'guest';
		}

		foreach ( self::$actions[ $type ] as $action ) {
			add_submenu_page(
				self::$actions['index']['slug'], $action['title'], $action['name'], 'manage_options', $action['slug'], array(
					__CLASS__,
					$action['function'],
				)
			);
		}
	}


	/**
	 * Register styles and scripts
	 */
	public static function admin_scripts() {
		wp_enqueue_style( 'gsc_admin_style', GSC_URL . 'templates/css/get-site-control-admin.css', '', self::$version );
		wp_enqueue_script( 'gsc_admin_script', GSC_URL . 'templates/js/get-site-control-admin.js', '', self::$version, true );
	}


	/**
	 * Add script before </body>
	 */
	public static function add_script() {
		if ( ! empty( self::$settings['script'] ) ) {
			wp_enqueue_script( 'gsc_widget_script', self::$settings['script'], '', self::$version, true );
			add_filter( 'script_loader_tag', array( __CLASS__, 'filter_script_loader_tag' ), 10, 2 );
		}
	}


	/**
	 * Filter script loader
	 *
	 * @param $tag
	 * @param $handle
	 *
	 * @return mixed
	 */
	public static function filter_script_loader_tag( $tag, $handle ) {
		if ( 'gsc_widget_script' !== $handle ) {
			return $tag;
		}

		return str_replace( ' src', ' data-cfasync="false" async src', $tag );
	}

	/**
	 * Main page action
	 */
	public static function action_admin_menu_page() {
		self::check_access_die();

        $sitesLink = str_replace("{{API_DOMAIN}}", self::$settings['api_domain'], self::$sitesLink);
        $relSitesPath = '/#/dashboard/sites/<SITE_ID>/widgets/list';
        if (self::$settings['api_domain'] == 'dash.getsitecontrol.com'){
            $relSitesPath = '/sites/<SITE_ID>/widgets';
        }
        $autoLoginLink = str_replace("{{API_DOMAIN}}", self::$settings['api_domain'], self::$autoLoginLink);

		$options                         = self::$settings;
		$options['site_selected_action'] = 'gsc_post_site_select';
		$options['clear_api_key_action'] = 'gsc_post_clear_api_key';
		$options['api_url']              = $sitesLink;
		$options['manage_site_link']     = $autoLoginLink . '?api_key=' .
										   self::$settings['api_key'] . '&next='.$relSitesPath;

		self::render_template(
			'index',
			array(
				'options'       => $options,
			)
		);
	}

	/**
	 * Check user access with die
	 */
	protected static function check_access_die() {
		if ( ! self::check_access() ) {
			wp_die( 'You do not have sufficient permissions to access this page' );
		}
	}

	/**
	 * Check user access
	 *
	 * @return bool
	 */
	protected static function check_access() {
		return current_user_can( 'manage_options' );
	}

	/**
	 * Render template by name
	 *
	 * @param $viewFile
	 * @param array    $params
	 */
	protected static function render_template( $viewFile, $params = array() ) {
		$path = GSC_PATH . '/templates/' . $viewFile . '.php';

		if ( file_exists( $path ) ) {
			foreach ( $params as $paramKey => $paramValue ) {
				$$paramKey = $paramValue;
			}
			include_once $path;
		} else {
			wp_die( 'The template file (' . esc_html( $viewFile ) . '.php) not found!' );
		}
	}

	/**
	 * Processing update site id form
	 */
	public static function gsc_post_site_select() {
		if ( self::check_access() && ! empty( self::$settings['api_key'] ) ) {
			self::$settings['script'] = self::post( 'gsc_script', null );
			self::$settings['site_id'] = self::post( 'gsc_site_id', null );
			echo wp_json_encode(
				array(
					'error' => ! self::update( self::$settings ),
				)
			);
			wp_die();
		}
	}

	/**
	 * Get value from $_POST
	 *
	 * @param $param
	 * @param bool|false $allowEmpty
	 * @param null       $default
	 *
	 * @return null
	 */
	protected static function post( $param, $allowEmpty = false, $default = null ) {
		if ( ( isset( $_POST[ $param ] ) && $allowEmpty ) || ( ! empty( $_POST[ $param ] ) && ! $allowEmpty ) ) {
			return sanitize_text_field( wp_unslash( $_POST[ $param ] ) );
		} else {
			return $default;
		}
	}

	/**
	 * Sign out action
	 */
	public static function action_admin_menu_sign_out() {
		self::check_access_die();
		$options                         = self::$settings;
		$options['clear_api_key_action'] = 'gsc_post_clear_api_key';

		self::render_template(
			'sign_out', array(
				'options' => $options,
			)
		);
	}

	/**
	 * Processing sign out form
	 */
	public static function gsc_post_clear_api_key() {
		if ( self::post( 'gsc_clear_api_key' ) && self::check_access() ) {
			self::$settings['api_key'] = null;
			self::$settings['api_domain'] = null;
			self::$settings['script'] = null;
			echo wp_json_encode(
				array(
					'error'         => ! self::update( self::$settings ),
					'redirect_link' => admin_url( 'admin.php?page=' . self::$actions['guest']['sign-in']['slug'] ),
				)
			);
			wp_die();
		}
	}

	/**
	 * Sign up action
	 */
	public static function action_admin_menu_sign_up() {
		self::check_access_die();

		$data['email'] = get_option( 'admin_email' );
		$data['name']  = get_option( 'blogname' );
		$data['site']  = get_option( 'siteurl' );

		$options                   = self::$settings;
		$options['success_action'] = 'gsc_post_sign_in';
		$options['form_type']      = 'sign-up';
		$options['api_url']        = self::$registerLink;
		$options['timezone']       = get_option( 'gmt_offset' );
		$options['timezone_name']  = get_option( 'timezone_string' );

		$socialParams                  =
		'&name=' . rawurlencode( $data['name'] ) .
		'&site=' . rawurlencode( $data['site'] ) .
		'&tracking=%7B%22context%22%3A%22utm_campaign%3DWordpressPlugin%26utm_medium%3Dplugin%22%7D'; // encodeURIComponent(JSON.stringify({context:"utm_campaign=WordpressPlugin&utm_medium=plugin"}))
		$options['google_social_link'] = self::$googleSignupLink . $socialParams;

		self::render_template(
			'sign_up',
			array(
				'sign_in_link' => admin_url( 'admin.php?page=' . self::$actions['guest']['sign-in']['slug'] ),
				'data'         => $data,
				'options'      => $options,
			)
		);
	}

	/**
	 * Sign in action
	 */
	public static function action_admin_menu_sign_in() {
		self::check_access_die();

		$options                       = self::$settings;
		$options['success_action']     = 'gsc_post_sign_in';
		$options['form_type']          = 'sign-in';
		$options['api_url']            = self::$loginLink;
		$options['google_social_link'] = self::$googleSigninLink;

		$data['email'] = get_option( 'admin_email' );

		self::render_template(
			'sign_in',
			array(
				'sign_up_link' => admin_url( 'admin.php?page=' . self::$actions['guest']['sign-up']['slug'] ),
				'data'         => $data,
				'options'      => $options,
			)
		);
	}

	/**
	 * Processing a sign up form
	 */
	public static function gsc_post_sign_in() {
		if ( self::post( 'gsc_api_key' ) && self::check_access() ) {
			self::$settings['api_key'] = self::post( 'gsc_api_key' );
			self::$settings['api_domain'] = self::post( 'gsc_api_domain' );
			self::update( self::$settings );
			echo wp_json_encode(
				array(
					'redirect_link' => admin_url( 'admin.php?page=' . self::$actions['index']['slug'] ),
				)
			);
			wp_die();
		}
	}

	/**
	 * Redirect rules
	 */
	public static function redirect_rule() {
		$action = self::get( 'page' );
		if ( $action === self::$actions['index']['slug'] && empty( self::$settings['api_key'] ) ) {
			wp_safe_redirect( admin_url( 'admin.php?page=' . self::$actions['guest']['sign-up']['slug'] ) );
		}
	}

	/**
	 * Get value from $_GET
	 *
	 * @param $param
	 * @param bool|false $allowEmpty
	 * @param null       $default
	 *
	 * @return null
	 */
	protected static function get( $param, $allowEmpty = false, $default = null ) {
		if ( ( isset( $_GET[ $param ] ) && $allowEmpty ) || ( ! empty( $_GET[ $param ] ) && ! $allowEmpty ) ) {
			return wp_unslash( $_GET[ $param ] );
		} else {
			return $default;
		}
	}

	/**
	 * Scenarios list
	 *
	 * @return array
	 */
	protected static function scenarios() {
		return array(
			'auth'     => array( 'email', 'password' ),
			'register' => array( 'name', 'email', 'password', 'site' ),
		);
	}

	/**
	 * Compare Urls
	 *
	 * @param $url1
	 * @param $url2
	 *
	 * @return bool
	 */
	protected static function compare_urls( $url1, $url2 ) {
		$url1 = trim( str_replace( array( 'http://', 'https://' ), '', $url1 ), '/' );
		$url2 = trim( str_replace( array( 'http://', 'https://' ), '', $url2 ), '/' );

		return $url1 === $url2;
	}

}
