<?php
/**
 * Currency Switcher Setup
 *
 * @since 0.0.1
 * @package pmcs
 */
class PMCS_Plugin {
	/**
	 * Exchange rate class
	 *
	 * @var PMCS_Exchange_Rate_API
	 */
	public $exchange_rates = null;

	/**
	 * Exchange rate class
	 *
	 * @var PMCS_Switcher
	 */
	public $switcher = null;

	/**
	 * Shortcode
	 *
	 * @var PMCS_Shorcode
	 */
	public $shortcode = null;

	/**
	 * Ajax class
	 *
	 * @var PMCS_Ajax
	 */
	public $ajax = null;

	/**
	 * Crons job
	 *
	 * @var PMCS_Crons
	 */
	public $crons = null;

	/**
	 * The single instance of the class.
	 *
	 * @var PMCS_Plugin
	 * @since 2.1
	 */
	protected static $_instance = null;

	/**
	 * Crons job
	 *
	 * @var PMCS_Admin
	 */
	public $admin = null;

	/**
	 * Instance
	 *
	 * @return PMCS_Plugin
	 */
	public static function instance() {
		if ( is_null( self::$_instance ) ) {
			self::$_instance = new self();
			self::$_instance->setup();
			if ( is_admin() ) {
				self::$_instance->setup_admin();
			}
		}
		return self::$_instance;
	}

	/**
	 * PM_CS Constructor.
	 */
	public function __construct() {

	}

	/**
	 * Setup admin
	 *
	 * @return void
	 */
	public function setup_admin() {
		require_once PMCS_INC . 'admin/class-admin.php';
		$this->admin = new PMCS_Admin();
	}

	public function get_flag_folder() {
		$url = PMCS_URL . '/assets/flags/';
		return $url;
	}

	public function get_flag_url( $code ) {
		$url = PMCS_URL . '/assets/flags/' . strtolower( $code ) . '.png';
		return $url;
	}

	public function get_flag( $code ) {
		return '<img src="' . esc_attr( $this->get_flag_url( $code ) ) . '" alt=""/>';
	}

	/**
	 * Set a cookie - wrapper for setcookie using WP constants.
	 *
	 * @param  string  $name   Name of the cookie being set.
	 * @param  string  $value  Value of the cookie.
	 * @param  integer $expire Expiry of the cookie.
	 * @param  bool    $secure Whether the cookie should be served only over https.
	 */
	public function setcookie( $name, $value, $expire = 0, $secure = false ) {
		if ( ! headers_sent() ) {
			setcookie( $name, $value, $expire, COOKIEPATH ? COOKIEPATH : '/', COOKIE_DOMAIN, $secure, apply_filters( 'woocommerce_cookie_httponly', false, $name, $value, $expire, $secure ) );
		}
	}


	/**
	 * Default setup routine
	 *
	 * @uses add_action()
	 * @uses do_action()
	 *
	 * @return void
	 */
	public function setup() {
		add_action( 'init', array( $this, 'i18n' ) );

		require_once PMCS_INC . 'class-pmcs-exchange-rates.php';
		require_once PMCS_INC . 'exchange-rate-api/class-exchange-server-abstract.php';
		require_once PMCS_INC . 'exchange-rate-api/class-server-openexchangerates.php';
		require_once PMCS_INC . 'exchange-rate-api/class-server-fixer-io.php';
		require_once PMCS_INC . 'exchange-rate-api/class-server-currencylayer.php';
		require_once PMCS_INC . 'exchange-rate-api/class-server-yahoo.php';

		$this->exchange_rates = new PMCS_Exchange_Rate_API();
		$this->exchange_rates->add_server( 'PMSC_Server_Openexchangerates' );
		$this->exchange_rates->add_server( 'PMSC_Server_Yahoo' );
		$this->exchange_rates->add_server( 'PMSC_Server_Fixer_IO' );
		$this->exchange_rates->add_server( 'PMSC_Server_Currencylayer' );

		require_once PMCS_INC . 'class-pmcs-switcher.php';
		$this->switcher = new PMCS_Switcher();

		require_once PMCS_INC . 'class-pmcs-ajax.php';
		$this->ajax = new PMCS_Ajax();

		require_once PMCS_INC . 'class-pmcs-crons.php';
		$this->crons = new PMCS_Crons();

		require_once PMCS_INC . 'class-pmcs-shortcode.php';
		$this->shortcode = new PMCS_Shortocde();

		require_once PMCS_INC . 'class-pmcs-widget.php';
		add_action( 'widgets_init', array( $this, 'init_widgets' ) );

		add_action( 'wp_enqueue_scripts', array( $this, 'scripts' ) );

	

		do_action( 'pmcs_loaded' );
	}

	/**
	 * Proper way to enqueue scripts and styles
	 */
	public function scripts() {
		wp_enqueue_style( 'style-name', PMCS_URL . 'assets/css/frontend.css', array(), false );
	}

	public function init_widgets() {
		register_widget( 'PMCS_Widget' );
	}

	/**
	 * Registers the default textdomain.
	 *
	 * @uses apply_filters()
	 * @uses get_locale()
	 * @uses load_textdomain()
	 * @uses load_plugin_textdomain()
	 * @uses plugin_basename()
	 *
	 * @return void
	 */
	public function i18n() {
		$locale = apply_filters( 'plugin_locale', get_locale(), 'pmcs' );
		load_textdomain( 'pmcs', WP_LANG_DIR . '/pmcs/pmcs-' . $locale . '.mo' );
		load_plugin_textdomain( 'pmcs', false, plugin_basename( PMCS_PATH ) . '/languages/' );
	}

	/**
	 * Activate the plugin
	 *
	 * @uses init()
	 * @uses flush_rewrite_rules()
	 *
	 * @return void
	 */
	public static function activate() {
		// First load the init scripts in case any rewrite functionality is being loaded.
		do_action( 'pmcs_active' );
	}

	/**
	 * Deactivate the plugin
	 *
	 * Uninstall routines should be in uninstall.php
	 *
	 * @return void
	 */
	public static function deactivate() {
		do_action( 'pmcs_deactivate' );
	}

}







