<?php
/**
 * Plugin Name: LINE Pay for WooCommerce
 * Plugin URI: https://wordpress.org/plugins/linepay-wc/
 * Description: Line Pay for WooCommerce
 * Version: 1.1.2
 * Author: Artisan Workshop
 * Author URI: https://wc.artws.info/
 * Requires at least: 5.0
 * Requires PHP: 7.3
 * Tested up to: 5.8
 * WC requires at least: 5.0.0
 * WC tested up to: 5.6.0
 *
 * Text Domain: linepay-wc
 * Domain Path: /i18n/
 *
 * @package linepay-wc
 * @category Payments Method
 * @author Artisan Workshop
 */
//use ArtisanWorkshop\WooCommerce\PluginFramework\v2_0_12 as Framework;

if ( ! defined( 'ABSPATH' ) ) {
	exit; // Exit if accessed directly
}

if ( ! class_exists( 'WC_LINE_Pay' ) ) :

class WC_LINE_Pay{
	/**
	 * Line Pay for WooCommerce version.
	 *
	 * @var string
	 */
	public $version = '1.1.2';

    /**
     * Line Pay for WooCommerce Framework version.
     *
     * @var string
     */
    public $framework_version = '2.0.12';

    /**
     * @var Singleton The reference the *Singleton* instance of this class
     */
    private static $instance;

    /**
     * Returns the *Singleton* instance of this class.
     *
     * @return Singleton The *Singleton* instance.
     */
    public static function get_instance() {
        if ( null === self::$instance ) {
            self::$instance = new self();
        }
        return self::$instance;
    }

    /**
     * Private clone method to prevent cloning of the instance of the
     * *Singleton* instance.
     *
     * @return void
     */
    private function __clone() {}

    /**
     * Private unserialize method to prevent unserializing of the *Singleton*
     * instance.
     *
     * @return void
     */
    private function __wakeup() {}

    /**
     * Protected constructor to prevent creating a new instance of the
     * *Singleton* via the `new` operator from outside of this class.
     *
     * Line Pay for WooCommerce Constructor.
     * @access public
     * @return WooCommerce

     */
    private function __construct() {
		// WooCommerce For Softbank Payment Gateways version
		define( 'WC_LINEPAY_VERSION', $this->version );
		// WC4JP Framework version
		define( 'JP4WC_LINEPAY_FRAMEWORK_VERSION', $this->framework_version );
		// Line Pay for WooCommerce plugin url
		define( 'WC_LINEPAY_PLUGIN_URL', plugin_dir_url( __FILE__ ) );
        define( 'WC_LINEPAY_ABSPATH', dirname( __FILE__ ) . '/' );
        // Line Pay for WooCommerce plugin file
        define( 'WC_LINEPAY_PLUGIN_FILE', __FILE__ );
        // Include required files
        $this->includes();
        $this->init();
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
	 * Include required core files used in admin and on the frontend.
	 */
	private function includes() {
        //load framework
        $version_text = 'v'.str_replace('.', '_', JP4WC_LINEPAY_FRAMEWORK_VERSION);
        if ( ! class_exists( '\\ArtisanWorkshop\\WooCommerce\\PluginFramework\\'.$version_text.'\\JP4WC_Plugin' ) ) {
            require_once dirname( __FILE__ ) . '/includes/jp4wc-framework/class-jp4wc-framework.php';
        }
        require_once dirname( __FILE__ ) . '/includes/gateways/linepay/class-wc-gateway-linepay.php';
        require_once dirname( __FILE__ ) . '/includes/gateways/linepay/class-wc-gateway-linepay-cart-handler.php';
        require_once dirname( __FILE__ ) . '/includes/gateways/linepay/class-wc-linepay-endpoint.php';
	}
	/**
	 * Init Line Pay for WooCommerce when WordPress Initialises.
	 */
	public function init() {
		// Set up localisation
		$this->load_plugin_textdomain();
		// deactivation
        register_deactivation_hook( WC_LINEPAY_PLUGIN_FILE, array( $this, 'on_deactivation' ) );
	}
	/*
	 * Load Localisation files.
	 *
	 * Note: the first-loaded translation file overrides any following ones if the same translation is present
	 */
	public function load_plugin_textdomain() {
		// Global + Frontend Locale
		load_plugin_textdomain( 'linepay-wc', false, plugin_basename( dirname( __FILE__ ) ) . "/i18n" );
	}
}

endif;
/**
 * Load plugin functions.
 */
add_action( 'plugins_loaded', 'WC_Linepay_plugin', 0 );

//If WooCommerce Plugins is not activate notice
function WC_Linepay_fallback_notice(){
	?>
    <div class="error">
        <ul>
            <li><?php echo __( 'Paidy for WooCommerce is enabled but not effective. It requires WooCommerce in order to work.', 'woo-sbp' );?></li>
        </ul>
    </div>
    <?php
}
function WC_Linepay_plugin() {
    if ( in_array( 'woocommerce/woocommerce.php', apply_filters( 'active_plugins', get_option( 'active_plugins' ) ) ) ) {
        WC_LINE_Pay::get_instance();;
    } else {
        add_action( 'admin_notices', 'WC_Linepay_fallback_notice' );
    }
}
