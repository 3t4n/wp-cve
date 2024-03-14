<?php
if ( ! defined( 'ABSPATH' ) ) {
	exit;
}

/**
 * Class that handles bank2bank payment method.
 *
 * @extends WC_Gateway_walletdoc
 *
 * @since 1.5.1
 */
class WC_Gateway_Walletdoc_bank2bank extends WC_Walletdoc_Payment_Gateway  {

	/**
	 * Bank2Bank ID
	 *
	 * @var string
	 */
	const ID = 'walletdoc_bank2bank';

	/**
	 * ID used by WooCommerce to identify the payment method
	 *
	 * @var string
	 */
	public $id = 'walletdoc_bank2bank';

	/**
	 * ID used by Walletdoc
	 */
	protected $walletdoc_id = 'bank2bank';

	

	/**
	 * List of accepted countries
	 */

	public $testmode;
	public $client_id;
	public $client_secret;

	/**
	 * Constructor
	 *
	 * @since 1.5.1
	 */
	public function __construct() {
		$this->method_title = __( 'Walletdoc Bank2Bank', 'woocommerce-gateway-walletdoc' );
		// parent::__construct();

		add_filter( 'wc_walletdoc_allowed_payment_processing_statuses', [ $this, 'add_allowed_payment_processing_statuses' ], 10, 2 );
	
	
	
			$this->id = 'bank2bank';
			// $this->icon = apply_filters( 'woocommerce_walletdoc_icon', plugins_url( '/assets/icon.png', __FILE__ ) );
			$this->has_fields = false;
		
			$this->method_description = sprintf(
				/* translators: 1) HTML anchor open tag 2) HTML anchor closing tag */
					__( 'All other general Walletdoc settings can be adjusted %1$shere%2$s ', 'woocommerce-gateway-walletdoc' ),
					'<a href="' . esc_url( admin_url( 'admin.php?page=wc-settings&tab=checkout&section=walletdoc' ) ) . '">',
					'</a>'
				);
	
			$this->init_form_fields();
			$this->init_settings();
			 $main_settings = get_option('woocommerce_walletdoc_settings');
	
			//  WC_Walletdoc_log( 'main_settings'.print_r( get_option('woocommerce_walletdoc_settings'),true ));

			$this->title = $this->get_option( 'title' );
			$this->description = $this->get_option( 'description' );
			$this->testmode = isset($main_settings['testmode']) ? $main_settings['testmode'] : "yes";
			$this->settings['testmode'] = isset($main_settings['testmode']) ? $main_settings['testmode'] : "yes" ;

			$this->api_details =  isset($main_settings['api_details']) ? $main_settings['api_details'] : "" ;
			if(isset($main_settings['client_secret'])){
				$client_secret = ( $this->testmode == 'yes' ) ? $main_settings['client_secret'] :  $main_settings[ 'production_secret'];
			}else{
				$client_secret = "";
			}
			$this->client_secret = $client_secret;
		
	
			$this->client_id = '';
			// WC_Walletdoc_log( '$this'.print_r( $this,true ));
			global $walletdoc_params;
			$walletdoc_params = array(
				'key'  =>$this->client_secret,
				'checkout'=>0,
				'transaction_id'=>'',
	
			);
			$this->supports = array(
				// 'refunds',
				// 'add_payment_method',
			
	
			);
	
			// wp_register_script( 'walletdoc', 'https://js.walletdoc.com/v1/walletdoc.js', '', '', true );
			// wp_enqueue_script( 'walletdoc' );
	
			// wp_enqueue_script( 'woocommerce_walletdoc', plugins_url( 'assets/js/bank2bank-setting.js', __FILE__ ), array(), '1', true );
			
			// wp_enqueue_script( 'woocommerce_walletdoc' );
	
		
	
			wp_localize_script( 'woocommerce_walletdoc', 'wc_walletdoc_params', $walletdoc_params );
	
			wp_register_style( 'walletdocCss',  plugins_url( 'assets/css/walletdoc.css', __FILE__ ) );
			wp_enqueue_style( 'walletdocCss' );
			add_action( 'woocommerce_update_options_payment_gateways_' . $this->id, array( $this, 'process_admin_options' ) );
			//capture_payment
			add_action( 'woocommerce_order_status_processing', array( $this, 'capture_payment' ) );
			add_action( 'woocommerce_order_status_completed', array( $this, 'capture_payment' ) );
			// add wehbhook
			add_action( 'woocommerce_api_' . $this->id, array( $this, 'webhook' ) );
			// do_action( 'woocommerce_set_cart_cookies', true );
			// display the credit card used for a subscription in the 'My Subscriptions' table
			// add_filter( 'woocommerce_my_subscriptions_payment_method', array( $this, 'maybe_render_subscription_payment_method' ), 10, 2 );
	
			// add_action( 'woocommerce_scheduled_subscription_payment_' . $this->id, array( $this, 'scheduled_subscription_payment' ), 10, 2 );
	
		}
	
		
		public function init_form_fields() {
			// include_once '../lib/Walletdoc.php';
	
			$this->form_fields = require WC_WALLETDOC_PLUGIN_PATH . '/includes/admin/bank2bank-settings.php';
		
	        // $this->form_fields = include( 'walletdoc-settings.php' );
			// WC_Walletdoc_log( 'saved_Data'.print_r( get_option('woocommerce_walletdoc_settings'),true ) );
		}


	/**
	 * Payment form on checkout page
	 *
	 * @since 1.5.1
	 */
	public function payment_fields() {
		if ( $this->description ) {
            echo wpautop( wp_kses_post( apply_filters( 'wc_bank2bank_description', $this->description ) ) );
        }
	
	}





	
}
