<?php
/**
 * Plugin Name: metaps PAYMENT for WooCommerce
 * Plugin URI: https://wordpress.org/plugins/woo-paydesign/
 * Description: metaps PAYMENT (before PAYDESIGN) gateway payment for WooCommerce. 
 * Version: 1.3.0
 * Author: Artisan Workshop
 * Author URI: https://wc.artws.info/
 * Requires at least: 5.0.0
 * Tested up to: 6.1.1
 * WC requires at least: 2.6.0
 * WC tested up to: 7.5.0
 *
 * Text Domain: woo-paydesign
 * Domain Path: /i18n/
 *
 * @package woo-paydesign
 * @category Core
 * @author Artisan Workshop
 */
//use ArtisanWorkshop\WooCommerce\PluginFramework\v2_0_12 as Framework;

if ( ! defined( 'ABSPATH' ) ) {
	exit; // Exit if accessed directly
}

if ( ! class_exists( 'WC4JP_PAYDESIGN' ) ) :

class WC4JP_PAYDESIGN{

	/**
	 * metaps PAYMENT version.
	 *
	 * @var string
	 */
	public $version = '1.3.0';

	/**
	 * metaps PAYMENT for WooCommerce Framework version.
	 *
	 * @var string
	 */
	public $framework_version = '2.0.12';

	/**
	 * The single instance of the class.
	 *
	 * @var object
	 */
	protected static $instance = null;

	/**
	 * metaps PAYMENT for WooCommerce Constructor.
	 * @access public
	 * @return WC4JP_PAYDESIGN
	 */
	public function __construct() {
		// rated appeal
		add_action( 'wp_ajax_wc4jp_paydesign_rated', array( __CLASS__, 'wc4jp_paydesign_rated') );
		add_filter( 'admin_footer_text', array( $this, 'admin_footer_text' ), 1 );
		// metaps PAYMENT for WooCommerce version
		define( 'WC4JP_METAPS_VERSION', $this->version );
        define( 'WC_METAPS_PLUGIN_URL', plugin_dir_url( __FILE__ ) );
	}

	/**
	 * Get class instance.
	 *
	 * @return object Instance.
	 */
	public static function instance() {
		if ( null === static::$instance ) {
			static::$instance = new static();
		}
		return static::$instance;
	}

	/**
	 * Init the feature plugin, only if we can detect WooCommerce.
	 *
	 * @since 2.0.0
	 * @version 2.0.0
	 */
	public function init() {
		$this->define_constants();
		register_deactivation_hook( M4WC_PLUGIN_FILE, array( $this, 'on_deactivation' ) );
		add_action( 'plugins_loaded', array( $this, 'on_plugins_loaded' ), 20 );
	}

	/**
	 * Flush rewrite rules on deactivate.
	 *
	 * @return void
	 */
	public function on_deactivation() {
		flush_rewrite_rules();
	}

	/**
	 * Setup plugin once all other plugins are loaded.
	 *
	 * @return void
	 */
	public function on_plugins_loaded() {
		$this->load_plugin_textdomain();
		$this->includes();
	}

	/**
	 * Define Constants.
	 */
	protected function define_constants() {
		$this->define( 'M4WC_ABSPATH', dirname( __FILE__ ) . '/' );
		$this->define( 'M4WC_URL_PATH', plugins_url( '/', __FILE__ ) );
		$this->define( 'M4WC_INCLUDES_PATH', M4WC_ABSPATH . 'includes/' );
		$this->define( 'M4WC_PLUGIN_FILE', __FILE__ );
		$this->define( 'M4WC_VERSION', $this->version );
		$this->define( 'M4WC_FRAMEWORK_VERSION', $this->framework_version );
		// Config Setting
		$this->define( 'M4WC_URL', 'https://www.paydesign.jp/settle/' );
		// Credit Card
		$this->define( 'PAYDESIGN_CC_SALES_URL', M4WC_URL.'settle3/bp3.dll' );
		$this->define( 'PAYDESIGN_CC_SALES_USER_URL', M4WC_URL.'settlex/credit2.dll' );
		$this->define( 'PAYDESIGN_CC_SALES_COMP_URL', M4WC_URL.'Fixation/crDkakutei.dll' );
		$this->define( 'PAYDESIGN_CC_SALES_CANCEL_URL', M4WC_URL.'Fixation/canauthp.dll' );
		$this->define( 'PAYDESIGN_CC_SALES_REFUND_URL', M4WC_URL.'Fixation/cantorip.dll' );
		$this->define( 'PAYDESIGN_CC_SALES_AUTH_URL', M4WC_URL.'inquiry/reskaricr.dll' );
		$this->define( 'PAYDESIGN_CC_SALES_CHECK_URL', M4WC_URL.'inquiry/result3.dll' );
		// Convenience Store
		$this->define( 'PAYDESIGN_CS_SALES_URL', M4WC_URL.'settle2/ubp3.dll' );
		$this->define( 'PAYDESIGN_CS_CANCEL_URL', M4WC_URL.'Fixation/can_cvs.dll' );
	}

	/**
	 * Load Localisation files.
	 */
	protected function load_plugin_textdomain() {
		load_plugin_textdomain( 'woo-paydesign', false, basename( dirname( __FILE__ ) ) . '/i18n' );
	}

	/**
	 * Include JP4WC classes.
	 */
	private function includes() {
		//load framework
		$version_text = 'v'.str_replace('.', '_', M4WC_FRAMEWORK_VERSION);
		if ( ! class_exists( '\\ArtisanWorkshop\\WooCommerce\\PluginFramework\\'.$version_text.'\\JP4WC_Plugin' ) ) {
			require_once M4WC_INCLUDES_PATH . 'jp4wc-framework/class-jp4wc-framework.php';
		}
		// Admin Setting Screen
		require_once M4WC_INCLUDES_PATH . 'admin/class-wc-admin-screen-paydesign.php';
		// Admin Notice
		require_once M4WC_INCLUDES_PATH . 'gateways/paydesign/class-wc-metaps-admin-notices.php';
		// metaps PAYMENT Payment Gateway
		if(get_option('wc_paydesign_cc')){
			include_once( M4WC_INCLUDES_PATH . 'gateways/paydesign/class-wc-gateway-paydesign-cc.php' );	// Credit Card
			include_once( M4WC_INCLUDES_PATH . 'gateways/paydesign/class-wc-gateway-addon-paydesign-cc.php' );	// Credit Card Subscription
		}
		if(get_option('wc_paydesign_cc_token')){
			include_once( M4WC_INCLUDES_PATH . 'gateways/paydesign/class-wc-gateway-paydesign-cc-token.php' );// Credit Card with Token
			include_once( M4WC_INCLUDES_PATH . 'gateways/paydesign/class-wc-gateway-addon-paydesign-cc-token.php' );// Credit Card with Token Subscription
		}
		if(get_option('wc_paydesign_cs')) include_once( M4WC_INCLUDES_PATH . 'gateways/paydesign/class-wc-gateway-paydesign-cs.php' );	// Convenience store
		if(get_option('wc_paydesign_pe')) include_once( M4WC_INCLUDES_PATH . 'gateways/paydesign/class-wc-gateway-paydesign-pe.php' );	// Pay-Easy
    }

	/**
	 * Change the admin footer text on WooCommerce for Japan admin pages.
	 *
	 * @since  1.1
	 * @param  string $footer_text
	 * @return string
	 */
	public function admin_footer_text( $footer_text ) {
		if ( ! current_user_can( 'manage_woocommerce' ) ) {
			return null;
		}
		$current_screen = get_current_screen();
		$wc4jp_paydesign_pages = 'wc4jp-paydesign-output';
		// Check to make sure we're on a WooCommerce admin page
		if ( isset( $current_screen->id ) && $current_screen->id == $wc4jp_paydesign_pages ) {
			if ( ! get_option( 'wc4jp_paydesign_admin_footer_text_rated' ) ) {
				$footer_text = sprintf( __( 'If you like <strong>metaps PAYMENT for WooCommerce.</strong> please leave us a %s&#9733;&#9733;&#9733;&#9733;&#9733;%s rating. A huge thanks in advance!', 'woo-paydesign' ), '<a href="https://wordpress.org/support/plugin/woocommerce-for-japan/reviews/#postform" target="_blank" class="wc4jp-rating-link" data-rated="' . esc_attr__( 'Thanks :)', 'woocommerce-for-japan' ) . '">', '</a>' );
				wc_enqueue_js( "
					jQuery( 'a.wc4jp-rating-link' ).click( function() {
						jQuery.post( '" . WC()->ajax_url() . "', { action: 'wc4jp_paydesign_rated' } );
						jQuery( this ).parent().text( jQuery( this ).data( 'rated' ) );
					});
				" );
			}else{
				$footer_text = __( 'Thank you for selling with WooCommerce for metaps PAYMENT.', 'woo-paydesign' );
			}
		}
		return $footer_text;
	}
	/**
	 * Triggered when clicking the rating footer.
	 */
	public static function wc4jp_paydesign_rated() {
		if ( ! current_user_can( 'manage_woocommerce' ) ) {
			die(-1);
		}

		update_option( 'wc4jp_paydesign_admin_footer_text_rated', 1 );
		die();
	}

	/**
	 * Define constant if not already set.
	 *
	 * @param string      $name  Constant name.
	 * @param string|bool $value Constant value.
	 */
	protected function define( $name, $value ) {
		if ( ! defined( $name ) ) {
			define( $name, $value );
		}
	}
}

endif;

/**
 * Load plugin functions.
 */
add_action( 'plugins_loaded', 'WC4JP_PAYDESIGN_plugin');

function WC4JP_PAYDESIGN_plugin() {
	if ( is_woocommerce_active() && class_exists('WooCommerce') ) {
		WC4JP_PAYDESIGN::instance()->init();
	} else {
		add_action( 'admin_notices', 'm4wc_fallback_notice' );
	}
}

function m4wc_fallback_notice() {
	?>
    <div class="error">
        <ul>
            <li><?php echo __( 'metaps PAYMENT for WooCommerce is enabled but not effective. It requires WooCommerce in order to work.', 'woocommerce-for-japan' );?></li>
        </ul>
    </div>
	<?php
}

/**
 * WC Detection
 */
if ( ! function_exists( 'is_woocommerce_active' ) ) {
	function is_woocommerce_active() {
		if ( ! isset($active_plugins) ) {
			$active_plugins = (array) get_option( 'active_plugins', array() );

			if ( is_multisite() )
				$active_plugins = array_merge( $active_plugins, get_site_option( 'active_sitewide_plugins', array() ) );
		}
		return in_array( 'woocommerce/woocommerce.php', $active_plugins ) || array_key_exists( 'woocommerce/woocommerce.php',$active_plugins );
	}
}
