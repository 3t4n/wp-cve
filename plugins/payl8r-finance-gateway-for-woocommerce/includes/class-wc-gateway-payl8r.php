<?php

if ( ! defined( 'ABSPATH' ) ) {
	exit;
}

/**
 * Handles gateway configuration.
 *
 * @class       WC_Gateway_PayL8r
 * @package     WC_PayL8r
 * @category    Class
 * @extends WC_Payment_Gateway
 */
class WC_Gateway_PayL8r extends WC_Payment_Gateway {

	/**
	 * Checkout enabled.
	 *
	 * @var bool
	 */
	public $enabled;

	/**
	 * Api access publishable key.
	 *
	 * @var string
	 */
	public $public_key;

	/**
	 * Account username.
	 *
	 * @var bool
	 */
	public $username;

	/**
	 * Is test mode active?
	 *
	 * @var bool
	 */
	public $testmode;

	/**
	 * Logging enabled?
	 *
	 * @var bool
	 */
	public $logging;

	/**
	 * Reference to logging class.
	 *
	 * @var WC_Logger
	 */
	private static $log;

	/**
	 * Constructor.
	 *
	 * Initialises class propertyies form config and registers hook listeners.
	 */
	public function __construct() {
		$this->id                   = 'payl8r';
		$this->method_title         = __( 'PayL8r', 'woocommerce-gateway-payl8r' );

		// Load the form fields.
		$this->init_form_fields();

		// Load the settings.
		$this->init_settings();

		// Get setting values.
		$this->title                  = $this->get_option( 'title' );
		$this->description            = apply_filters( 'woocommerce_payl8r_checkout_description', $this->get_option( 'description' ) );
		$this->testmode               = $this->get_option( 'testmode' ) === 'yes';
		$this->enabled                = $this->get_option( 'enabled' ) === 'yes';
		$this->public_key             = $this->get_option( 'public_key' );
		$this->username               = $this->get_option( 'username' );
		$this->logging                = $this->get_option( 'logging' ) === 'yes';
		//$this->icon                   = plugins_url( 'https://assets.payl8r.com/images/payl8r.svg', WC_PAYL8R_MAIN_FILE );
		$this->icon                   = "https://assets.payl8r.com/images/Payl8rLogoButton.png";

		// Forcing the SANDBOX credentials, if the test mode is enabled
		if($this->testmode) {
			$this->public_key = '-----BEGIN PUBLIC KEY-----
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
-----END PUBLIC KEY-----
';
			$this->username = 'SANDBOX';
		}

		$this->order_button_text = __( 'Continue to payment', 'woocommerce-gateway-payl8r' );

		// Hooks.
		add_action( 'woocommerce_update_options_payment_gateways_' . $this->id, array( $this, 'process_admin_options' ) );

        add_action( 'woocommerce_before_checkout_form', array( $this, 'checkout_notices' ) );
        
        add_action('woocommerce_before_checkout_form', array($this, 'apply_payl8r_button_styles'));

		// Responses Handlers.
		include_once( __DIR__ . '/class-wc-gateway-payl8r-response.php' );
		new WC_Gateway_PayL8r_Response( $this );
	}

	/**
	 * Get gateway form settings.
	 */
	public function init_form_fields() {
		$this->form_fields = include( 'settings-payl8r.php' );
	}

	/**
	 * Check if this gateway is enabled.
	 */
	public function is_available() {
		return $this->enabled && $this->public_key;
	}

	/**
	 * Check if SSL is enabled and notify the user.
	 */
	public function admin_notices() {
		if ( 'no' === $this->enabled ) {
			return;
		}

		// Show message if enabled and FORCE SSL is disabled and WordpressHTTPS plugin is not detected.
		if ( ( function_exists( 'wc_site_is_https' ) && ! wc_site_is_https() ) && ( 'no' === get_option( 'woocommerce_force_ssl_checkout' ) && ! class_exists( 'WordPressHTTPS' ) ) ) {
			echo '<div class="error payl8r-ssl-message">%s<p>' . sprintf( __( "<strong>%1$s</strong> is enabled and WooCommerce is not forcing the SSL certificate on your checkout page. Please ensure that you have a valid SSL certificate and that you are <a href=\"%1$s\">forcing the checkout pages to be secured.</a>" ), $this->method_title, admin_url( 'admin.php?page=wc-settings&tab=checkout' ) ) . '</p></div>';
		}
	}

	/**
	 * Displays notices on the checkout page.
	 */
	public function checkout_notices() {
		if ( ! empty( $_GET['payl8r_error'] ) ) {
			wc_print_notice( 'There was an error processing your payment', 'error' );
		}
	}

	/**
	 * Process the payment and return the result.
	 *
	 * @param int $order_id Order to process.
	 * @return array
	 */
	public function process_payment( $order_id ) {

		include_once( __DIR__ . '/class-wc-gateway-payl8r-request.php' );

		$order          = wc_get_order( $order_id );
		$payl8r_request = new WC_Gateway_PayL8r_Request( $this );

		return array(
			'result'   => 'success',
			'redirect' => $payl8r_request->get_request_url( $order ),
		);
	}

	/**
	 * Logs messages to the woocommerce log.
	 *
	 * @param string $message The message to log.
	 */
	public static function log( $message ) {
		if ( empty( self::$log ) ) {
			self::$log = new WC_Logger();
		}

		self::$log->add( 'woocommerce-gateway-payl8r', $message );
    }
    
    public function apply_payl8r_button_styles() {
        ob_start();
        $settings = get_option('woocommerce_payl8r_settings');
        $custom_css = $settings['custom_payl8r_checkout_label_css'];
        ?>
            <style>
                <?php echo $custom_css; ?>
            </style>
        <?php
        echo ob_get_clean();
    }
}
