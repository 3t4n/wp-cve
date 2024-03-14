<?php

class WC_Gateway_Valitor extends WC_Payment_Gateway {

	const VALITOR_ENDPOINT_SANDBOX = 'https://paymentweb.uat.valitor.is/';
	const VALITOR_ENDPOINT_LIVE = 'https://paymentweb.valitor.is/';

	/**
	 * Whether or not logging is enabled
	 *
	 * @var bool
	 */
	public static $log_enabled = true;

	/**
	 * Logger instance
	 *
	 * @var WC_Logger
	 */
	public static $log = false;

	/**
	 * Hash
	 *
	 * @var string
	 */
	private $Hash;

	/**
	 * Order line items grouping
	 *
	 * @var string
	 */
	private $TotalLineItem;

	/**
	 * Test Mode
	 *
	 * @var string
	 */
	private $testmode;

	/**
	 * Merchant ID
	 *
	 * @var string
	 */
	private $MerchantID;

	/**
	 * Verification Code
	 *
	 * @var string
	 */
	private $VerificationCode;

	/**
	 * Language of Payment Page
	 *
	 * @var string
	 */
	private $Language;

	/**
	 * Success Return Button Text
	 *
	 * @var string
	 */
	private $successurlText;

	/**
	 * Success Page URL
	 *
	 * @var string
	 */
	private $successurl;

	/**
	 * Cancel Page URL
	 *
	 * @var string
	 */
	private $cancelurl;

	/**
	 * Session Expired Page URL
	 *
	 * @var string
	 */
	private $expiredurl;

	/**
	 * Session Expired Timeout
	 *
	 * @var string
	 */
	private $receiptText;

	/**
	 * redirect Text
	 *
	 * @var string
	 */
	private $redirectText;

	/**
	 * SessionExpiredTimeout
	 *
	 * @var string
	 */
	private $SessionExpiredTimeout;

	public function __construct() {
		$this->id                 = 'valitor';
		$this->icon               = VALITOR_URL . 'cards.png';
		$this->has_fields         = false;
		$this->method_title       = __('Valitor', 'valitor_woocommerce');
		$this->method_description = __('Valitor Payment Page enables merchants to sell products securely on the web with minimal integration effort', 'valitor_woocommerce');
		// Load the form fields
		$this->init_form_fields();
		$this->init_settings();
		// Get setting values
		$this->enabled          = $this->get_option( 'enabled' );
		$this->title            = $this->get_option( 'title' );
		$this->description      = $this->get_option( 'description' );
		$this->Hash             = $this->get_option( 'Hash' );
		$this->TotalLineItem    = $this->get_option( 'TotalLineItem' );
		$this->testmode         = $this->get_option( 'testmode' );
		$this->MerchantID       = $this->get_option( 'MerchantID' );
		$this->VerificationCode = $this->get_option( 'VerificationCode' );
		$this->Language         = $this->get_option( 'Language' );
		$this->successurlText   = $this->get_option( 'successurlText' );
		$this->successurl       = $this->get_option( 'successurl' );
		$this->cancelurl        = $this->get_option( 'cancelurl' );
		$this->expiredurl       = $this->get_option( 'expiredurl' );
		$this->receiptText 		= $this->get_option( 'receiptText', __('Thank you - your order is now pending payment. We are now redirecting you to Valitor to make payment.', 'valitor_woocommerce') );
		$this->redirectText 	= $this->get_option( 'redirectText',  __('Thank you for your order. We are now redirecting you to Valitor to make payment.', 'valitor_woocommerce') );
		$this->SessionExpiredTimeout = $this->get_option( 'SessionExpiredTimeout' );
		// Filters
		add_filter( 'wcml_gateway_text_keys_to_translate', array( $this, 'valitor_text_keys_to_translate' ) );
		// Hooks
		add_action( 'woocommerce_receipt_' . $this->id, array( $this, 'receipt_page' ) );
		add_action( 'woocommerce_update_options_payment_gateways_' . $this->id, array(
			$this,
			'process_admin_options'
		) );
		add_action( 'woocommerce_api_wc_gateway_valitor', array( $this, 'check_valitor_response' ) );
		add_action( 'woocommerce_thankyou', array( $this, 'check_valitor_response' ) );
		if ( ! $this->is_valid_for_use() ) {
			$this->enabled = false;
		}

		$this->maybe_wrong_held_duration();
	}

	/**
	 * Logging method.
	 *
	 * @param string $message Log message.
	 * @param string $level Optional. Default 'info'. Possible values:
	 * 	emergency|alert|critical|error|warning|notice|info|debug.
	 */
	public static function log( $message, $level = 'info' ) {
		if ( self::$log_enabled ) {
			if ( empty( self::$log ) ) {
				self::$log = wc_get_logger();
			}
			self::$log->log( $level, $message, array( 'source' => 'valitor' ) );
		}
	}

	public function admin_options() {
		?>
		<h3>Valitor</h3>
		<p>Pay with your credit card via Valitor.</p>
		<?php if ( $this->is_valid_for_use() ) : ?>
			<table class="form-table"><?php $this->generate_settings_html(); ?></table>
		<?php else : ?>
			<div class="inline error"><p><strong><?php _e( 'Gateway Disabled:', 'valitor_woocommerce' ); ?></strong> <?php _e( 'Current Store currency is not valid for valitor gateway. Must be in ISK, EUR, USD, GBP, SEK, DKK, NOK or CAD', 'valitor_woocommerce'); ?></p></div>
			<?php
		endif;
	}

	//Check if this gateway is enabled and available in the user's country
	function is_valid_for_use() {
		if ( ! in_array( get_woocommerce_currency(), array('ISK', 'EUR', 'USD', 'GBP', 'SEK', 'DKK', 'NOK', 'CAD') ) ) {
			return false;
		}

		return true;
	}

	//Initialize Gateway Settings Form Fields
	function init_form_fields() {
		$hash_default = ( $this->get_option('Hash') ) ? $this->get_option('Hash') : 'SHA256';

		$this->form_fields = array(
			'enabled' => array(
				'title'       => __('Enable/Disable', 'valitor_woocommerce'),
				'label'       => __('Enable Valitor', 'valitor_woocommerce'),
				'type'        => 'checkbox',
				'description' => '',
				'default'     => 'no'
			),
			'title' => array(
				'title'       => __('Title', 'valitor_woocommerce'),
				'type'        => 'text',
				'description' => __('This controls the title which the user sees during checkout.', 'valitor_woocommerce'),
				'default'     => __('Valitor', 'valitor_woocommerce')
			),
			'description' => array(
				'title'       => __('Description', 'valitor_woocommerce'),
				'type'        => 'textarea',
				'description' => __('This controls the description which the user sees during checkout.', 'valitor_woocommerce'),
				'default'     => __('Pay with your credit card via Valitor.', 'valitor_woocommerce')
			),
			'testmode' => array(
				'title'       => __('Valitor Test Mode', 'valitor_woocommerce'),
				'label'       => __('Enable Test Mode', 'valitor_woocommerce'),
				'type'        => 'checkbox',
				'description' => __('Place the payment gateway in development mode.', 'valitor_woocommerce'),
				'default'     => 'no'
			),
			'MerchantID'       => array(
				'title'       => __('Merchant ID', 'valitor_woocommerce'),
				'type'        => 'text',
				'description' => __('This is the ID supplied by Valitor.', 'valitor_woocommerce'),
				'default'     => '1'
			),
			'VerificationCode' => array(
				'title'       => __('Verification Code', 'valitor_woocommerce'),
				'type'        => 'text',
				'description' => __('This is the Payment VerificationCode supplied by Valitor.', 'valitor_woocommerce'),
				'default'     => '12345'
			),
			'Hash' => array(
				'title'       => __('Hash', 'valitor_woocommerce'),
				'type'        => 'select',
				'options'     => array( 'SHA256' => 'SHA256', 'MD5' => 'MD5' ),
				'description' => __('Secure hashing algorithm.', 'valitor_woocommerce'),
				'default'     => $hash_default
			),
			'SessionExpiredTimeout' => array(
				'title'       => __('Session Expired Timeout', 'valitor_woocommerce'),
				'type'        => 'number',
				'description' => __('Session Expired Timeout in Seconds', 'valitor_woocommerce'),
				'default'     => $this->default_held_duration()
			),
			'TotalLineItem' => array(
				'title'       => __('Order line items grouping', 'valitor_woocommerce'),
				'label'       => __('Send order as 1 line item', 'valitor_woocommerce'),
				'type'        => 'checkbox',
				'description' => __('You can uncheck this if you don\'t use discounts and have integer quantities.', 'valitor_woocommerce'),
				'default'     => 'yes'
			),
			'Language' => array(
				'title'       =>  __('Language of Payment Page', 'valitor_woocommerce'),
				'type'        => 'select',
				'description' => __('Select which Language to show on Payment Page.', 'valitor_woocommerce'),
				'default'     => 'EN',
				'options'     => array( 'IS' => __('Icelandic', 'valitor_woocommerce' ), 'EN' => __('English', 'valitor_woocommerce' ), 'DE' => __('German', 'valitor_woocommerce' ), 'DA' =>  __('Danish', 'valitor_woocommerce' ) )
			),
			'successurlText' => array(
				'title'       => __('Success Return Button Text', 'valitor_woocommerce' ),
				'type'        => 'text',
				'description' => __('Buyer will see button to return to previous page after a successful payment.', 'valitor_woocommerce' ),
				'default'     => __('Back to Home', 'valitor_woocommerce' )
			),
			'successurl' => array(
				'title'       => __('Success Page URL', 'valitor_woocommerce' ),
				'type'        => 'text',
				'description' => __('Buyer will be sent to this page after a successful payment.', 'valitor_woocommerce' ),
				'default'     => ''
			),
			'cancelurl' => array(
				'title'       => __('Cancel Page URL', 'valitor_woocommerce' ),
				'type'        => 'text',
				'description' => __('Buyer will be sent to this page if he pushes the cancel button instead of finalizing the payment.', 'valitor_woocommerce' ),
				'default'     => ''
			),
			'expiredurl' => array(
				'title'       => __('Session Expired Page URL', 'valitor_woocommerce' ),
				'type'        => 'text',
				'description' =>  __('Buyer will be sent to this page if payment page session expires.', 'valitor_woocommerce' ),
				'default'     => ''
			),
			'receiptText' => array(
				'title'       => __('Receipt text', 'valitor_woocommerce' ),
				'type'        => 'textarea',
				'description' => __('Buyer will see this text after woocommerce order create.', 'valitor_woocommerce' ),
				'default'     => __('Thank you - your order is now pending payment. We are now redirecting you to Valitor to make payment.', 'valitor_woocommerce' )
			),
			'redirectText' => array(
				'title'       => __('Redirect text', 'valitor_woocommerce' ),
				'type'        => 'textarea',
				'description' => __('Buyer will see this text before redirecting to Valitor', 'valitor_woocommerce' ),
				'default'     => __('Thank you for your order. We are now redirecting you to Valitor to make payment.', 'valitor_woocommerce' )
			),
		);
	}

	/**
	 * @param WC_Order $order
	 *
	 * @return array
	 */
	function get_valitor_args( $order ) {
		$successUrl      = $this->get_return_url( $order );
		$ipnUrl          = WC()->api_request_url( 'WC_Gateway_Valitor' );
		$cancelUrl       = $order->get_cancel_order_url();
		$expiredUrl      = $order->get_cancel_order_url();
		$authOnly        = 0;
		$ReferenceNumber = 'WC-' . $order->get_id();

		if ( 'yes' == $this->testmode ) {
			$ReferenceNumber .= '-test-' . rand(100,1000);
		}
		$SessionExpiredTimeout = ( $this->SessionExpiredTimeout ) ? $this->SessionExpiredTimeout : $this->default_held_duration() ;
		//Valitor Args
		$valitor_args = array(
			'MerchantID'                          => $this->MerchantID,
			'ReferenceNumber'                     => $ReferenceNumber,
			'Currency'                            => get_woocommerce_currency(),
			'Language'                            => $this->Language,
			'Hash'                                => $this->Hash,
			'PaymentSuccessfulURL'                => esc_url_raw( $successUrl ),
			'PaymentSuccessfulURLText'            => $this->successurlText,
			'PaymentSuccessfulAutomaticRedirect'  => 1,
			'PaymentSuccessfulServerSideURL'      => esc_url_raw( $ipnUrl ),
			'PaymentCancelledURL'                 => esc_url_raw( $cancelUrl ),
			'DisplayBuyerInfo'                    => '0',
			'SessionExpiredTimeoutInSeconds'      => $SessionExpiredTimeout,
			'SessionExpiredRedirectURL'           => esc_url_raw( $expiredUrl ),
			//If set as 1 then cardholder is required to insert email,mobile number,address.
			'AuthorizationOnly'                   => $authOnly,
		);

		// Cart Contents
		$DigitalSignature = $this->VerificationCode . $authOnly;
		$total_line_item = $this->TotalLineItem;

		if ( sizeof( $order->get_items( array( 'line_item', 'fee' ) ) ) > 0 ) {
			if ( $total_line_item == "yes" ) {
				$item_loop        = 1;
				$item_description = '';
				foreach ( $order->get_items( array( 'line_item', 'fee' ) ) as $item ) {
					$item_name = strip_tags( $item->get_name() );
					if( !empty($item_description) ) $item_description .= ', ';
					$item_description .= $item_name;
				}
				if (strlen($item_description) > 499) $item_description = mb_substr($item_description, 0, 496) . '...';
				$valitor_args[ 'Product_' . $item_loop . '_Description' ] = html_entity_decode( $item_description, ENT_NOQUOTES, 'UTF-8' );
				$valitor_args[ 'Product_' . $item_loop . '_Quantity' ]    = 1;
				$valitor_args[ 'Product_' . $item_loop . '_Price' ]       = number_format( $order->get_total(), wc_get_price_decimals(), '.', '' );
				$valitor_args[ 'Product_' . $item_loop . '_Discount' ]    = 0;
				$DigitalSignature .= 1;
				$DigitalSignature .= number_format( $order->get_total(), wc_get_price_decimals(), '.', '' );
				$DigitalSignature .= 0;
			} else {
				$calculated_total = 0;
				$item_loop        = 1;
				$include_tax = $this->tax_display();

				foreach ( $order->get_items( array( 'line_item', 'fee' ) ) as $item ) {
					if ( 'fee' === $item['type'] ) {
						$fee = $item->get_total();
						if ( $include_tax && $this->fee_tax_display($item) ){
							$fee += $item->get_total_tax();
						}
						$fee_total = $this->round( $fee, $order );
						$item_name = strip_tags( $item->get_name() );
						$valitor_args[ 'Product_' . $item_loop . '_Description' ] = html_entity_decode( $item_name, ENT_NOQUOTES, 'UTF-8' );
						$valitor_args[ 'Product_' . $item_loop . '_Quantity' ]    = 1;
						$valitor_args[ 'Product_' . $item_loop . '_Price' ]       = number_format($fee_total, wc_get_price_decimals(), '.', '' );
						$valitor_args[ 'Product_' . $item_loop . '_Discount' ]    = 0;
						$DigitalSignature .= 1;
						$DigitalSignature .= number_format($fee_total, wc_get_price_decimals(), '.', '' );
						$DigitalSignature .= 0;
						$calculated_total += number_format($fee_total, wc_get_price_decimals(), '.', '');
						$item_loop ++;
					}
					if ( $item['qty'] ) {
						$item_name = $item['name'];
						if ( $meta = wc_display_item_meta( $item ) ) {
							$item_name .= ' ( ' . $meta . ' )';
						}
						$item_name = strip_tags( $item_name );
						$item_subtotal = $order->get_item_subtotal( $item, $include_tax, false );
						$calculated_subtotal = $item_subtotal * $item['qty'];
						$calculated_total += number_format($calculated_subtotal, wc_get_price_decimals(), '.', '');
						$calc_discount = 0;
						if( $order->get_total_discount() > 0 ) {
							$item_total = $order->get_item_total( $item, $include_tax , false);
							$calc_discount = $item_subtotal - $item_total;
						}

						if( $item_subtotal == 0 ) continue;

						$valitor_args[ 'Product_' . $item_loop . '_Description' ] = html_entity_decode( $item_name, ENT_NOQUOTES, 'UTF-8' );
						$valitor_args[ 'Product_' . $item_loop . '_Quantity' ]    = $item['qty'];
						$valitor_args[ 'Product_' . $item_loop . '_Price' ]       = number_format( $item_subtotal, wc_get_price_decimals(), '.', '' );
						$valitor_args[ 'Product_' . $item_loop . '_Discount' ]    = number_format( $calc_discount, wc_get_price_decimals(), '.', '' );
						$DigitalSignature                                         .= $item['qty'];
						$DigitalSignature                                         .= number_format( $item_subtotal, wc_get_price_decimals(), '.', '' );
						$DigitalSignature                                         .= number_format( $calc_discount, wc_get_price_decimals(), '.', '' );
						$item_loop ++;
					}
				}

				if ( $order->get_shipping_total() > 0 ) {
					$shipping_total = $order->get_shipping_total();
					if( $include_tax ) $shipping_total += $order->get_shipping_tax();
					$shipping_total = $this->round( $shipping_total, $order );

					$valitor_args[ 'Product_' . $item_loop . '_Description' ] = sprintf( /* translators: %s: Shipping */ __('Shipping (%s)', 'valitor_woocommerce' ), $order->get_shipping_method() );
					$valitor_args[ 'Product_' . $item_loop . '_Quantity' ]    = 1;
					$valitor_args[ 'Product_' . $item_loop . '_Price' ]       = number_format( $shipping_total, wc_get_price_decimals(), '.', '' );
					$valitor_args[ 'Product_' . $item_loop . '_Discount' ]    = 0;
					$DigitalSignature                                         .= 1;
					$DigitalSignature                                         .= number_format( $shipping_total, wc_get_price_decimals(), '.', '' );
					$DigitalSignature                                         .= 0;
					$calculated_total += $shipping_total;
					$item_loop ++;
				}


				if (!$include_tax && $order->get_total_tax() > 0){
					$tax_discount = $this->get_tax_discount( $order );
					$total_tax = $order->get_total_tax();
					$calculated_total += $total_tax;
					$total_tax = $total_tax + $tax_discount;
					$valitor_args[ 'Product_' . $item_loop . '_Description' ] = __('Taxes', 'valitor_woocommerce');
					$valitor_args[ 'Product_' . $item_loop . '_Quantity' ]    = 1;
					$valitor_args[ 'Product_' . $item_loop . '_Price' ]       = number_format( $total_tax, wc_get_price_decimals(), '.', '' );
					$valitor_args[ 'Product_' . $item_loop . '_Discount' ]    = number_format( $tax_discount, wc_get_price_decimals(), '.', '' );
					$DigitalSignature                                         .= 1;
					$DigitalSignature                                         .= number_format( $total_tax, wc_get_price_decimals(), '.', '' );
					$DigitalSignature                                         .= number_format( $tax_discount, wc_get_price_decimals(), '.', '' );
					$item_loop ++;
				}

				if ( $order->get_total_discount() > 0 ) {
					$total_discount = $order->get_total_discount();
					/*	Woocommerce can see any tax adjustments made thus far using subtotals.
					Since Woocommerce 3.2.3*/
					if(wc_tax_enabled() && method_exists('WC_Discounts','set_items') && $include_tax){
						$total_discount += $order->get_discount_tax();
					}
					if(wc_tax_enabled() && !method_exists('WC_Discounts','set_items') && !$include_tax){
						$total_discount -= $order->get_discount_tax();
					}

					$total_discount = $this->round($total_discount, $order);
					$calculated_total -= $total_discount;
				}

				// Check for mismatched totals.
				if ( $calculated_total != number_format( $order->get_total(), wc_get_price_decimals(), '.', '' ) ) {
					return;
				}
			}
		}

		$DigitalSignature                 .= $this->MerchantID;
		$DigitalSignature                 .= $ReferenceNumber;
		$DigitalSignature                 .= $successUrl;
		$DigitalSignature                 .= $ipnUrl;
		$DigitalSignature                 .= get_woocommerce_currency();
		$valitor_args['DigitalSignature'] = ( $valitor_args['Hash'] == 'MD5' ) ? md5( $DigitalSignature ) : hash( 'sha256', iconv('UTF-8','UTF-16LE', $DigitalSignature) );

		return $valitor_args;
	}

	//Generate the valitor button link
	function generate_valitor_form( $order_id ) {
		global $woocommerce;
		if ( function_exists( 'wc_get_order' ) ) {
			$order = wc_get_order( $order_id );
		} else {
			$order = new WC_Order( $order_id );
		}
		if ( 'yes' == $this->testmode ) {
			$valitor_adr = self::VALITOR_ENDPOINT_SANDBOX;
		} else {
			$valitor_adr = self::VALITOR_ENDPOINT_LIVE;
		}
		$valitor_args       = $this->get_valitor_args( $order );
		$valitor_args_array = array();
		if( !empty($valitor_args) ) {
			WC_Gateway_Valitor::log( wc_print_r(['message'=>__('Customer redirected to valitor paymentweb', 'valitor_woocommerce'),
						'order_id' => $order_id,
						'currency'=>$valitor_args['Currency'],
						'amount'=>number_format( $order->get_total(), wc_get_price_decimals(), '.', '' ),
						'testmode'=>$this->testmode
						], true));
			foreach ( $valitor_args as $key => $value ) {
				$valitor_args_array[] = '<input type="hidden" name="' . esc_attr( $key ) . '" value="' . esc_attr( $value ) . '" />';
			}
			$redirecttext = $this->get_translated_string($this->redirectText, 'redirectText');
			$redirecttext = str_replace(array("\r\n", "\r", "\n"), "<br />", $redirecttext);
			$code = sprintf( '$.blockUI({
				message: "%s",
				baseZ: 99999,
				overlayCSS: { background: "#fff", opacity: 0.6 },
				css: {
					padding:        "20px",
					zindex:         "9999999",
					textAlign:      "center",
					color:          "#555",
					border:         "3px solid #aaa",
					backgroundColor:"#fff",
					cursor:         "wait",
					lineHeight:     "24px",
				}
				});
				jQuery("#wc_submit_valitor_payment_form").click();', $redirecttext );
			wc_enqueue_js( $code );
			$html_form = '<form action="' . esc_url_raw( $valitor_adr ) . '" method="post" id="valitor_payment_form">'
			             . implode( '', $valitor_args_array )
			             . '<input type="submit" class="button" id="wc_submit_valitor_payment_form" value="' . __( 'Pay via Valitor', 'valitor_woocommerce' ) . '" /> <a class="button cancel" href="' . $order->get_cancel_order_url() . '">' . __( 'Cancel order &amp; restore cart', 'valitor_woocommerce' ) . '</a>'
			             . '</form>';

		}else{
			$html_form = '<div class="inline error"><p><strong>' . __( 'Line items total amount is not equal order total amount. Contact support', 'valitor_woocommerce') . '</strong></p></div>';
		}
		return $html_form;
	}

	function process_payment( $order_id ) {
		if ( function_exists( 'wc_get_order' ) ) {
			$order = wc_get_order( $order_id );
		} else {
			$order = new WC_Order( $order_id );
		}

		return array(
			'result'   => 'success',
			'redirect' => $order->get_checkout_payment_url( true )
		);
	}

	function check_valitor_response() {
		global $woocommerce;
		global $wp;
		global $wpdb;

		$posted = ! empty( $_REQUEST ) ? wp_unslash( $_REQUEST ) : false;
		if ( isset( $posted['ReferenceNumber'] ) ) {
			$mySignatureResponse      = ( $this->Hash == 'MD5' ) ? md5( $this->VerificationCode . $posted['ReferenceNumber'] ) : hash( 'sha256', iconv('UTF-8','UTF-16LE', $this->VerificationCode . $posted['ReferenceNumber']) );
			$request_type = ( ! empty( $wp->query_vars['wc-api'] ) ) ? "IPN" : "Redirect";
			$DigitalSignatureResponse = sanitize_text_field($posted['DigitalSignatureResponse']);
			$cardType                 = sanitize_text_field($posted['CardType']);
			$Date                     = sanitize_text_field($posted['Date']);
			$CardNumberMasked         = sanitize_text_field($posted['CardNumberMasked']);
			$AuthorizationNumber      = sanitize_text_field($posted['AuthorizationNumber']);
			$TransactionNumber        = sanitize_text_field($posted['TransactionNumber']);
			$orderNote                = sprintf( /* translators: %s: Card Type */ __( 'Card Type : %s', 'valitor_woocommerce' ), $cardType ) . "<br/>";
			$orderNote                .= sprintf( /* translators: %s: Masked card number */ __( 'Card Number Masked : %s', 'valitor_woocommerce' ), $CardNumberMasked ) . "<br/>";
			$orderNote                .= sprintf( /* translators: %s: Date */ __( 'Date : %s', 'valitor_woocommerce' ), $Date ) . "<br/>";
			$orderNote                .= sprintf( /* translators: %s: Authorization Number  */ __( 'Authorization Number : %s', 'valitor_woocommerce' ), $AuthorizationNumber ) . "<br/>";
			$orderNote                .= sprintf( /* translators: %s: Transaction Number  */ __( 'Transaction Number : %s', 'valitor_woocommerce' ), $TransactionNumber ) . "<br/>";
			$orderNote                .= sprintf( /* translators: %s: Transaction Number  */ __( 'Request type : %s', 'valitor_woocommerce' ), $request_type ) . "<br/>";
			$reference = sanitize_text_field($posted['ReferenceNumber']);

			if ( $mySignatureResponse == $DigitalSignatureResponse ) {
				WC_Gateway_Valitor::log(wc_print_r(['message'=>__('Сheck valitor response', 'valitor_woocommerce'),
						'request_type' => $request_type,
						'posted'=>$this->secure_posted($posted)
						], true));
				if ( ! empty($reference) ) {
					$order_id = (int) str_replace( 'WC-', '', $reference );
					if ( 'yes' == $this->testmode ) {
						$order_id = explode('-test-', $order_id);
						$order_id = $order_id[0];
					}
					if ( function_exists( 'wc_get_order' ) ) {
						$order = wc_get_order( $order_id );
					} else {
						$order = new WC_Order( $order_id );
					}

					$valitor_response_time = current_time('timestamp');
					if(get_transient('valitor_payment_' . $order_id . '_processing')){
						exit();
					}else{
						set_transient( 'valitor_payment_' . $order_id . '_processing', $valitor_response_time, $this->SessionExpiredTimeout);
					}

					if ( ! empty( $wp->query_vars['wc-api'] ) ) {
						sleep(1);
					}

					if( !$order->get_meta('_valitor_payment_received', true) ){
						$order->add_order_note( $orderNote );
						$order->update_meta_data('_valitor_payment_received', $valitor_response_time);
						$order->save();
					}

					$order_status = $wpdb->get_var( $wpdb->prepare( "SELECT post_status from $wpdb->posts WHERE ID =  %d", $order_id) );
					$order_status = str_replace('wc-', '', $order_status);
					if ( ! $order->is_paid() && !in_array( $order_status, wc_get_is_paid_statuses() ) ) {
						$order->payment_complete();
						$woocommerce->cart->empty_cart();
						delete_transient( 'valitor_payment_' . $order_id . '_processing' );
						WC_Gateway_Valitor::log(wc_print_r(['message'=>__('Payment complete', 'valitor_woocommerce'),
							'order_id'=>$order_id
						],true));

						//Valitor IPN request, don't need redirect.
						if ( ! empty( $wp->query_vars['wc-api'] ) ) {
							echo __('Payment confirmed', 'valitor_woocommerce');
							exit;
						}
						//user request, need to be redirected to successurl
						if(  !empty( $this->successurl ) ){
							wp_safe_redirect( $this->successurl );
							exit;
						}
					}
					else {
						WC_Gateway_Valitor::log(wc_print_r(['message'=>__('Payment already confirmed', 'valitor_woocommerce'),
							'order_id'=>$order_id
						],true));
						if ( ! empty( $wp->query_vars['wc-api'] ) ) {
							echo __('Payment already confirmed', 'valitor_woocommerce' );
							exit;
						}
					}
				}
			}
			else {
				$error_message = __('Digital signature does not match', 'valitor_woocommerce' );
				echo $error_message;
				WC_Gateway_Valitor::log(wc_print_r(['message'=>$error_message,
					'request_type'=>$request_type
				],true));
				exit;
			}
		}
		//echo __('ReferenceNumber is not set or request is empty', 'valitor_woocommerce' );
		//exit;
	}

	function receipt_page( $order ) {
		$receipttext = $this->get_translated_string($this->receiptText, 'receiptText');
		if( !empty($receipttext) ) echo sprintf('<p>%s</p>', $receipttext);
		echo '<div class="valitor-form">' . $this->generate_valitor_form( $order ) . '</div>';
	}
	/**
	 * Round prices.
	 * @param  double $price
	 * @param  WC_Order $order
	 * @return double
	 */
	protected function round( $price, $order ) {
		$precision = 2;

		if ( ! $this->currency_has_decimals( $order->get_currency() ) ) {
			$precision = 0;
		}

		return round( $price, $precision );
	}

	/**
	 * Check if currency has decimals.
	 * @param  string $currency
	 * @return bool
	 */
	protected function currency_has_decimals( $currency ) {
		if ( in_array( $currency, array( 'HUF', 'JPY', 'TWD', 'ISK' ) ) ) {
			return false;
		}

		return true;
	}

	/**
	 * Check tax display.
	 * @return bool
	 */
	protected function tax_display() {
		$tax_display = wc_tax_enabled() ? get_option( 'woocommerce_tax_display_cart' ) : 'incl';
		return ( $tax_display == 'incl' ) ? true : false ;
	}

	/**
	 * Check fee tax display.
	 * @param  WC_Order_Item_Fee $item
	 * @return bool
	 */
	protected function fee_tax_display( $item ) {
		$tax_display = $item->get_tax_status();
		return ( $tax_display == 'taxable' ) ? true : false ;
	}

	/**
	 * Round prices.
	 * @param  WC_Order $order
	 * @return string
	 */
	protected function get_discount( $order ) {
		$total_discount = $order->get_total_discount();
		/*				Woocommerce can see any tax adjustments made thus far using subtotals.
		Since Woocommerce 3.2.3*/
		if(wc_tax_enabled() && method_exists('WC_Discounts','set_items') && $this->tax_display()){
			$total_discount += $order->get_discount_tax();
		}
		if(wc_tax_enabled() && !method_exists('WC_Discounts','set_items') && !$this->tax_display()){
			$total_discount -= $order->get_discount_tax();
		}
		return $this->round($total_discount, $order);
	}
	/**
	 * Round prices.
	 * @param  WC_Order $order
	 * @return string
	 */
	protected function get_tax_discount( $order ) {
		$tax_discount = $order->get_discount_tax();

		return $this->round($tax_discount, $order);
	}
	/**
	 * Check for mismatched totals.
	 * @param  WC_Order $order
	 * @param  $calculated_total
	 * @return bool
	 */
	protected function mismatched_totals($order, $calculated_total ) {
		return ( $calculated_total != number_format( $order->get_total(), wc_get_price_decimals(), '.', '' ) ) ? true : false ;
	}


	/**
	 * Get Woo Hold stock
	 * @return number
	 */
	protected function get_held_duration(){
		$held_duration = (int) get_option( 'woocommerce_hold_stock_minutes', 0 );
		return $held_duration * 60;
	}

	/**
	 * Default Session Expired Timeout
	 *
	 * @return number
	 */
	protected function default_held_duration(){
		$held_duration = $this->get_held_duration();
		return $held_duration/2;
	}

	/**
	 * Shows admin notice if held duration is wrong
	 *
	 * @return void
	 */
	protected function maybe_wrong_held_duration(){
		$held_duration = $this->get_held_duration();

		if($this->SessionExpiredTimeout > $held_duration)
			add_action( 'admin_notices', array($this, 'valitor_held_duration_notice') );
	}

	/**
	 * Adds text keys to_translate.
	 * @param  $text_keys array
	 * @return array
	 */
	public function valitor_text_keys_to_translate( $text_keys ){
		if( !in_array( 'receiptText', $text_keys ) )
			$text_keys[] = 'receiptText';
		if( !in_array( 'redirectText', $text_keys ) )
			$text_keys[] = 'redirectText';
		return $text_keys;
	}

	/**
	 * Getting translated value
	 * @param  $string string Original field value
	 * @param  $name string Field key
	 * @return string
	 */
	public function get_translated_string( $string, $name ) {
		$translated_string = $string;
		$current_lang = apply_filters( 'wpml_current_language', NULL );
		if($current_lang && class_exists('WCML_WC_Gateways') ){
			$translated_string = apply_filters(
				'wpml_translate_single_string',
				$string,
				WCML_WC_Gateways::STRINGS_CONTEXT,
				$this->id . '_gateway_' . $name,
				$current_lang
			);
		}

		if ( $translated_string === $string ) {
			$translated_string = __( $string, 'valitor_woocommerce' );
		}

		return $translated_string;
	}

	/**
	 * Secure posted data
	 * @param  $posted array
	 *
	 * @return array
	 */
	private function secure_posted($posted){
		$secure_params = ['CardNumberMasked','AuthorizationNumber','DigitalSignatureResponse'];
		$data = [];
		if(!emptY($posted)){
			foreach ($posted as $key => $value) {
				$sanitized_value = sanitize_text_field($value);
				if(in_array($key, $secure_params)){
					$length = strlen($sanitized_value);
					$data[$key] = str_repeat("*", $length);
				}else{
					$data[$key] = $sanitized_value;
				}
			}
		}
		return $data;
	}

	function valitor_held_duration_notice(){
		echo sprintf( '<div class="notice notice-warning"><p>%s</p></div>', esc_html__( 'Please take attention that Valitor "Session Expired Timeout" setting is greater than Woocommerce Hold stock value. This may cause issues with cancelling orders by timeout early than customer pays for the order.', 'valitor_woocommerce' ) );
	}
}