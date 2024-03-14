<?php
/**
 * WC_Gateway_Fygaro class
 *
 * @author   Fygaro <support@fygaro.com>
 * @package  WooCommerce Fygaro Payments Gateway
 * @since    0.0.0
 */

// Exit if accessed directly.
if ( ! defined( 'ABSPATH' ) ) {
	exit;
}

/**
 * Fygaro Gateway.
 *
 * @class    WC_Gateway_Fygaro
 * @version  0.0.6
 */
class WC_Gateway_Fygaro extends WC_Payment_Gateway {

	/**
	 * Constructor for the gateway.
	 */
	public function __construct() {

		$this->id                 = 'fygaro';
		$this->icon               = '';  // Overwritten in get_icon
		$this->has_fields         = false;
		$this->plugin_url         = WC_Fygaro_Payments::plugin_url();
		$this->supports           = array(
			'products',
			/*'subscriptions',
			'subscription_cancellation',
			'subscription_suspension',
			'subscription_reactivation',
			'subscription_amount_changes',
			'subscription_date_changes',
			'multiple_subscriptions'*/
		);

		$this->custom_bank_logo_ids = ["mini_cuotas", "tasa_cero"];

		$this->method_title       = _x( 'Fygaro Gateway', 'Fygaro Payment Method', 'woocommerce-gateway-fygaro' );
		$this->method_description = sprintf(__( 'Accept Secure Payments with Fygaro. <br><br> In Fygaro set the Hook URL to %s/?wc-api=fgwcb_webhook <br><br> If needed, contact support@fygaro.com for assistance.', 'woocommerce-gateway-fygaro' ), home_url());

		// Load the settings.
		$this->init_form_fields();
		$this->init_settings();

		// Define user set variables.
		$this->title        = $this->get_option( 'title' );
		$this->description  = $this->get_option( 'description' );
		$this->instructions = $this->get_option( 'instructions', $this->description );
		$this->enabled = $this->get_option( 'enabled' );
		$this->private_key = $this->get_option( 'private_key' );
		$this->public_key =  $this->get_option( 'public_key' );
		$this->exp_date = $this->get_option( 'exp_date' );
		$this->base_url = $this->get_option( 'base_url' );

		// Actions.
		add_action( 'woocommerce_update_options_payment_gateways_' . $this->id, array( $this, 'process_admin_options' ) );

		// add_action( 'woocommerce_scheduled_subscription_payment_fygaro', array( $this, 'process_subscription_payment' ), 10, 2 );

		// We need custom CSS and JavaScript
		add_action( 'wp_enqueue_scripts', array( $this, 'fygaro_enqueue_scripts' ) );

		// Register a WebHook
		add_action( 'woocommerce_api_fgwcb_webhook', array( $this, 'webhook' ) );
	}

	/**
	 * Initialise Gateway Settings Form Fields.
	 */
	public function init_form_fields() {

		$this->form_fields = array(
			'enabled' => array(
				'title'   => __( 'Enable/Disable', 'woocommerce-gateway-fygaro' ),
				'type'    => 'checkbox',
				'label'   => __( 'Enable Payments with Fygaro', 'woocommerce-gateway-fygaro' ),
				'default' => 'no'
			),
			'title' => array(
				'title'       => __( 'Title', 'woocommerce-gateway-fygaro' ),
				'type'        => 'text',
				'description' => __( 'This controls the title which the user sees during checkout.', 'woocommerce-gateway-fygaro' ),
				'default'     => _x( 'Credit/Debit Card', 'Fygaro Payment Method', 'woocommerce-gateway-fygaro' ),
				'desc_tip'    => true,
			),
			'description' => array(
				'title'       => __( 'Description', 'woocommerce-gateway-fygaro' ),
				'type'        => 'textarea',
				'description' => __( 'Payment method description that the customer will see on your checkout.', 'woocommerce-gateway-fygaro' ),
				'default'     => __( 'Pay with your credit or debit card via Fygaro\'s secure checkout.', 'woocommerce-gateway-fygaro' ),
				'desc_tip'    => true,
			),
			'public_key' => array(
				'title'    => __( 'Public Key', 'woocommerce-gateway-fygaro' ),
				'id'       => 'woo_fygaro_public_key',
				'type'     => 'text',
			),
			'private_key' => array(
				'title'    => __( 'Secret Key', 'woocommerce-gateway-fygaro' ),
				'id'       => 'woo_fygaro_private_key',
				'type'     => 'password',
			),
			'exp_date' => array(
				'title'       => __( 'Expiration time', 'woocommerce-gateway-fygaro' ),
				'description' => __( 'Time in minutes during which the payment link is valid. Use 0 for unlimited.', 'woocommerce-gateway-fygaro' ),
				'id'          => 'woo_fygaro_exp_date',
				'type'        => 'number',
				'value'       => '0',
				'default'     => '0'
			),
			'base_url' => array(
				'title'    => __( 'Fygaro\'s Button URL', 'woocommerce-gateway-fygaro' ),
				'type'     => 'text'
			),
			'cc_logos' => array(
				'title'       => __('Accepted Payment Methods', 'woocommerce-gateway-fygaro'),
				'description' => __('Select which payment method\'s logos to show.', 'woocommerce-gateway-fygaro'),
				'type'        => 'multiselect',
				'id'          => 'woo_cc_logos',
				'options'     => array(
					'visa'        => 'Visa',
					'mastercard'  => 'Mastercard',
					'amex'        => 'American Express',
					'credix'      => 'Credix',
					'mini_cuotas' => 'Minicuotas',
					'tasa_cero'   => 'Tasa Cero',
				)
			),
			// FOR TEST MODE
			'mode' => array(
				'title'    => __( 'Mode/Environment', 'woocommerce-gateway-fygaro' ),
				'desc'     => __( 'Determine if the gateway is in test mode or production (real/active) mode. While in test mode, payments will not be actually processed and their success depends on the value set on "Test Payment Result".', 'woocommerce-gateway-fygaro' ),
				'id'       => 'woo_fygaro_payment_mode',
				'type'     => 'select',
				'options'  => array(
					'test'       => __( 'Test', 'woocommerce-gateway-fygaro' ),
					'production' => __( 'Production', 'woocommerce-gateway-fygaro' ),
				),
				'default'  => 'production',
				'desc_tip' => true,
			),
			'result' => array(
				'title'    => __( 'Test Payment Result (For test mode only)', 'woocommerce-gateway-fygaro' ),
				'desc'     => __( 'Determine if order payments are successful when using this gateway in Test mode.', 'woocommerce-gateway-fygaro' ),
				'id'       => 'woo_fygaro_payment_result',
				'type'     => 'select',
				'options'  => array(
					'success'  => __( 'Success', 'woocommerce-gateway-fygaro' ),
					'failure'  => __( 'Failure', 'woocommerce-gateway-fygaro' ),
				),
				'default'  => 'success',
				'desc_tip' => true,
			),
		);
	}

	/**
	 * Get_icon function
	 * Return logo and supported card brands.
	 */
	public function get_icon() {
		$card_icons = $this->get_card_icons();

		// CC Network Logos & Custom Bank Logos
		$card_logo_imgs = '';
		// $bank_logo_imgs = '';
		foreach ($card_icons as $key => $value) {
			if ($value["isBankLogo"]) {
				$card_logo_imgs .= sprintf(
					'<img class="pm-fygaro-bank-logo" src="%1$s" alt="%2$s" />',
					$value["src"],
					$value["alt"]
				);
			} else {
				$card_logo_imgs .= sprintf(
					'<img class="pm-fygaro-cc-logo" src="%1$s" alt="%2$s" />',
					$value["src"],
					$value["alt"]
				);
			}
		}

		$card_logo_lists_str = '';
		if ($card_logo_imgs !== '') {
			$card_logo_lists_str .= sprintf(
				'<div class="pm-fygaro-cc-logos">%s</div>',
				$card_logo_imgs
			);
		}

		// if ($bank_logo_imgs !== '') {
		// 	$card_logo_lists_str .= sprintf(
		// 		'<div class="pm-fygaro-bank-logos">%s</div>',
		// 		$bank_logo_imgs
		// 	);
		// }

		// Wrap lists in container
		if ($card_logo_lists_str !== '') {
			$card_logo_lists_str = sprintf(
				'<div class="pm-fygaro-cc-logo-container">%s</div>',
				$card_logo_lists_str
			);
		}

		$fygaro_icon = $this->get_fygaro_icon();
		$fygaro_icon_str = '<img class="pm-fygaro-logo" src="'.$fygaro_icon["src"].'" alt="'.$fygaro_icon["alt"].'" />';

		$icon = '<label for="payment_method_fygaro" id="pm-fygaro-container" class="pm-fygaro-container"><div id="pm-fygaro-logo-container" class="pm-fygaro-logo-container">'.$fygaro_icon_str.'</div>'.$card_logo_lists_str.'</label>';

		return apply_filters( 'woocommerce_gateway_icon', $icon, $this->id );
	}

	/**
	 * Return Fygaro's Icon
	 */
	public function get_fygaro_icon() {
		$fygaro_logo_path = '/assets/images/fygaro.png';

		return array(
			"id" => "fygaro",
			"src" => $this->plugin_url . $fygaro_logo_path,
			"alt" => "Fygaro"
		);
	}

	/**
	 * Return cc icons and supported card brands.
	 */
	public function get_card_icons() {
		$cc_logos = $this->get_option( 'cc_logos' );

		// Add fallback to always support Visa & MasterCard
		if (!is_array($cc_logos) || empty($cc_logos)) {
			$cc_logos = ['visa', 'mastercard'];
		}

		return array_map(
			function ($value) {
				$logo_path = '';
				$isBankLogo = false;
				if (in_array($value, $this->custom_bank_logo_ids)) {
					$logo_path = "/assets/images/".$value.".png";
					$isBankLogo = true;
				} else {
					$logo_path = "/assets/images/".$value.".svg";
				}

				return array(
					"id" => $value,
					"src" => $this->plugin_url . $logo_path,
					"alt" => $value,
					"isBankLogo" => $isBankLogo,
				);
			},
			$cc_logos
		);
	}

	/**
	 * Enqueue scripts and Styles only when needed
	 */
 	public function fygaro_enqueue_scripts() {
		// we need JavaScript to process a token only on cart/checkout pages, right?
		if ( !is_cart() && !is_checkout() && !isset($_GET['pay_for_order']) ) {
			return;
		}

		// if our payment gateway is disabled, we do not have to enqueue JS too
		if ( 'no' === $this->enabled ) {
			return;
		}

		// no reason to enqueue JavaScript if API keys are not set
		// if ( empty( $this->private_key ) || empty( $this->public_key ) ) {
		// 	return;
		// }

		// do not work with card detailes without SSL unless your website is in a test mode
		// if ( !$this->testmode && !is_ssl() ) {
		// 	return;
		// }

		wp_enqueue_style(
			'fygaro-style-css',
			$this->plugin_url . '/assets/css/fygaro-style.css',
			false
		);

		wp_enqueue_script(
			'fygaro-style-js',
			$this->plugin_url . '/assets/js/frontend/fygaro-dynamic-style.js',
			array('jquery'),
			false,
			true
		);
 	}

	/**
	 * Process the payment and return the result.
	 *
	 * @param  int  $order_id
	 * @return array
	 */
	public function process_payment( $order_id ) {
		//Check if test mode active
		$mode = $this->get_option( 'mode' );

		if ( 'production' === $mode ) {
			// Production Mode On

			$order = wc_get_order( $order_id );

			//Insert plugin code

			//Get Order Total
		 	$total = $order->get_total();
		 	$currency = get_woocommerce_currency();

		 	//Get BaseURL
		 	$base_url = $this->base_url;

		 	$public_key = $this->public_key;
		 	$private_key = $this->private_key;

		 	//Get JWT Expiration
		 	$exp_date = $this->exp_date;

		 	//Update language

		 	//get button id
			$button_id = substr($base_url, strrpos($base_url, '/pb/' )+1)."\n";

		 	//Check site language
		 	$currentLanguage = get_bloginfo('language');

		 	if( str_contains($currentLanguage, "es") ){
			    $base_url = "https://www.fygaro.com/es/".$button_id;
			}else {
				$base_url = "https://www.fygaro.com/en/".$button_id;
			}

			/*
		 	 * Create the JWT
			 */

		 	//Define NBF date
		 	$jwtNBF = time();

		 	// Create token header as a JSON string
			$header = json_encode(['typ' => 'JWT', 'alg' => 'HS256', 'kid' => $public_key]);

			// Create token payload as a JSON string
			$payload = json_encode(['nbf' => $jwtNBF,'amount' => $total, 'currency' => $currency, 'custom_reference' => "".$order_id.""]);

			if($exp_date > 0) {
				$payload = json_encode(['exp' => strtotime( date('c',$jwtNBF)." + ".$exp_date." minutes" ),'nbf' => $jwtNBF,'amount' => $total, 'currency' => $currency, 'custom_reference' => "".$order_id.""]);
			}

			// Encode Header to Base64Url String
			$base64UrlHeader = str_replace(['+', '/', '='], ['-', '_', ''], base64_encode($header));

			// Encode Payload to Base64Url String
			$base64UrlPayload = str_replace(['+', '/', '='], ['-', '_', ''], base64_encode($payload));

			// Create Signature Hash
			$signature = hash_hmac('sha256', $base64UrlHeader . "." . $base64UrlPayload, $private_key, true);

			// Encode Signature to Base64Url String
			$base64UrlSignature = str_replace(['+', '/', '='], ['-', '_', ''], base64_encode($signature));

			// Create JWT
			$jwt = $base64UrlHeader . "." . $base64UrlPayload . "." . $base64UrlSignature;

			/*
		 	 * Array with parameters for API interaction
			 */

			$args = array();

			/*Include generated link and JWT as an order note for future use */

			$order->add_order_note( $base_url."?jwt=".$jwt );

			/*
			 * Your API interaction could be built with wp_remote_post()
		 	 */

		 	$response = add_query_arg("jwt", $jwt, $base_url);

			if ( !is_wp_error( $response ) ) {
		 		// Redirect to the thank you page
				return array(
					'result' => 'success',
					'redirect' => $response
				);
			} else {
				wc_add_notice(  'Connection error.', 'error' );

				$message = __( 'Order payment failed. Please contact support for assistance.', 'woocommerce-gateway-fygaro' );

				throw new Exception( $message );
			}

			//End of plugin code
		} else {
			// Test Mode On
			$payment_result = $this->get_option( 'result' );

			if ( 'success' === $payment_result ) {
				$order = wc_get_order( $order_id );

				$order->payment_complete();

				// Remove cart
				WC()->cart->empty_cart();

				// Return thankyou redirect
				return array(
					'result' 	=> 'success',
					'redirect'	=> $this->get_return_url( $order )
				);
			} else {
				$message = __( 'Order payment failed. To make a successful payment using the the Fygaro Plugin Test Mode, please review the gateway settings.', 'woocommerce-gateway-fygaro' );

				throw new Exception( $message );
			}
		}
	}

	/*
	 * Setup a WebHook to receive Fygaro's Payment Confirmation
	 */
	public function webhook() {
		header( 'HTTP/1.1 200 OK' );

		// Takes raw data from the request
		$json = file_get_contents('php://input');

		// Converts it into a PHP object
		$data = json_decode($json);

		// Verify Data
		$order_id = isset($data->customReference) ? $data->customReference : null;
  		$jwt = isset($data->jwt) ? $data->jwt : null;

  		if (is_null($order_id)) return;
  		if (is_null($jwt)) return;

		// Suported Algs
		$supported_algs =  array(
			"HS256"=>"SHA256",
			"HS384"=>"SHA384",
			"HS512"=>"SHA512"
		);

		$signature_alg = 'SHA256';

		// Hook URL will look like:
		// https://www.site.com/?wc-api=fgwcb_webhook

		/**
		 * Check if JWT Valid
		 */
		$secret = $this->private_key;

		// Split the jwt
		$tokenParts = explode('.', $jwt);
		$header = base64_decode($tokenParts[0]);
		$payload = base64_decode($tokenParts[1]);
		$signature_provided = $tokenParts[2];

		// Json decode
		$payload_decode = json_decode($payload);
		$header_decode = json_decode($header);

		// Check the alg
		if ( !array_key_exists($header_decode->alg, $supported_algs) ) {
			return;
		} else {
			$format = $header_decode->alg;
			$signature_alg = $supported_algs["$format"];
		}

		// Check reference exists and matches post data
		if(!isset($payload_decode->customReference) && ($payload_decode->customReference != $order_id) ){
			return;
		}

		// Check number of segments
		if (count($tokenParts) !== 3) {
			return;
		}

		// Check not before (nbf)
		if(isset($payload_decode->nbf)){
			// Date format
			$today = date("Y-m-d");
			$nbf = date("Y-m-d",$payload_decode->nbf);

			// Convert to unix
			$today_time = strtotime($today);
			$nbf_time = strtotime($nbf);

			if($nbf_time > $today_time){
				return;
			}
		}

		// Check token has not been created in the future (iat)
		if(isset($payload_decode->iat)){
			// Date format
			$today = date("Y-m-d");
			$iat = date("Y-m-d",$payload_decode->iat);

			// Convert to unix
			$today_time = strtotime($today);
			$iat_time = strtotime($iat);

			if($iat_time > $today_time){
				return;
			}
		}

		// Check expiration date
		if(isset($payload_decode->exp)){
			// Date format
			$today = date("Y-m-d");
			$expire = date("Y-m-d",$payload_decode->exp);

			// Convert to unix
			$today_time = strtotime($today);
			$expire_time = strtotime($expire);

			// Verify if expire
			if ($expire_time <= $today_time){
				//is expired
				$is_token_expired = true;
			} else {
				//not expired
				$is_token_expired = false;
			}
		} else {
			// No exp token
			$is_token_expired = false;
		}

		// build a signature based on the header and payload using the secret

		// Encode Header to Base64Url String
		$base64_url_header = str_replace(['+', '/', '='], ['-', '_', ''], base64_encode($header));

		// Encode Payload to Base64Url String
		$base64_url_payload = str_replace(['+', '/', '='], ['-', '_', ''], base64_encode($payload));

		// Create Signature Hash
		$signature = hash_hmac($signature_alg, $base64_url_header . "." . $base64_url_payload, $secret, true);

		// Encode Signature to Base64Url String
		$base64_url_signature = str_replace(['+', '/', '='], ['-', '_', ''], base64_encode($signature));

		// verify it matches the signature provided in the jwt
		$is_signature_valid = hash_equals(
			$base64_url_signature,
			$signature_provided
		);

		if ($is_token_expired || !$is_signature_valid) {
			//return FALSE;
		} else {
			//return TRUE;
			$order = wc_get_order( $order_id );
  			$order->payment_complete();
		}

		//**** END JWT Validation ****//

		return "200 SUCCESS";

	}

	/**
	 * Process subscription payment.
	 *
	 * @param  float     $amount
	 * @param  WC_Order  $order
	 * @return void
	 *
	 public function process_subscription_payment( $amount, $order ) {
	 	$payment_result = $this->get_option( 'result' );

	 	if ( 'success' === $payment_result ) {
	 		$order->payment_complete();
	 	} else {
	 		$message = __( 'Order payment failed. To make a successful payment using Fygaro Payments, please review the gateway settings.', 'woocommerce-gateway-fygaro' );
	 		throw new Exception( $message );
	 	}
	 }
	 */
}
