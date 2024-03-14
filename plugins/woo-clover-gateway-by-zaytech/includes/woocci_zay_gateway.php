<?php
if ( ! defined( 'ABSPATH' ) ) {
	exit;
}

/**
 * Woocci_zay_gateway class.
 *
 * @extends WC_Payment_Gateway
 */
class Woocci_zay_gateway extends WC_Payment_Gateway  {
	/**
	 * The delay between retries.
	 *
	 * @var int
	 */
	public $retry_interval;
	/**
	 * The title.
	 *
	 * @var int
	 */
	public $title;
	/**
	 * The method_description.
	 *
	 * @var int
	 */
	public $method_description;
	/**
	 * The description.
	 *
	 * @var int
	 */
	public $description;

    /**
	 * Use or not the inline payment form.
	 *
	 * @var boolean
	 */
	public $use_cc_form;
	/**
	 * Show or not the Stress Address field on the payment form.
	 *
	 * @var boolean
	 */
	public $showStreetAddressField;
	/**
	 * The new order status for woocomerce orders when payments are accepted.
	 *
	 * @var boolean
	 */
	public $order_status;

	/**
	 * API access secret key
	 *
	 * @var string
	 */
	public $secret_key;

	/**
	 * Clover public key
	 *
	 * @var string
	 */
	public $pakmsKey;

	/**
	 * is Google recaptcha Enabled
	 *
	 * @var string
	 */
	public $isRecaptchaEnabled;

	/**
	 *  Google recaptcha Site Key
	 *
	 * @var string
	 */
	public $recaptchaSiteKey;
	/**
	 *  Google recaptcha Secret Key
	 *
	 * @var string
	 */
	public $recaptchaSecretKey;

	/*
	 * API handler
	 */
	public $api;


	/**
	 * Constructor
	 */
	public function __construct() {

        $this->retry_interval = 1;
		$this->id             = 'woocci_zaytech';
		$this->method_title   = __( 'Zaytech Clover Integration', 'zaytech_woocci' );
		$this->method_description = __(  'Zaytech Woocommerce integration for Clover works by adding a payment option on the checkout page. This allows payments to be processed by your Clover Merchant Account. All orders can either auto print to your Clover POS or you can print them manually. Make accepting credit card payments simple with the Woocommerce Clover payment gateway.','zaytech_woocci');
		$this->has_fields         = false;
		$this->supports = array(
			'products',
			'refunds',
			'tokenization',
			'add_payment_method',
		);

		// Load the form fields.
		$this->init_form_fields();

		// Load the settings.
        $this->init_settings();

		$this->secret_key               = $this->get_option( 'secret_key' );
		$this->title                    = $this->get_option( 'title' );
		$this->enabled                  = $this->get_option( 'enabled' );
        $this->description              = $this->get_option( 'description' );
        $this->use_cc_form              = 'yes' === $this->get_option( 'use_cc_form' );
        $this->showStreetAddressField   = 'yes' === $this->get_option( 'show_address_field' );
        $this->reduce_stock             = $this->get_option( 'reduce_stock' );
        $this->order_status             = $this->get_option( 'order_status' );
        $this->isRecaptchaEnabled       = 'yes' === $this->get_option( 'enable_recaptcha' );
        $this->recaptchaSiteKey         = $this->get_option( 'recaptcha_site_key' );
        $this->recaptchaSecretKey       = $this->get_option( 'recaptcha_secret_key' );


        $this->api = new Woocci_zaytech_api($this->secret_key);

        try {
            if ( $this->use_cc_form ) {
                $this->pakmsKey = $this->api->getPakmsKey();
                if(! $this->pakmsKey  ){
                    add_action( 'admin_notices', array( $this, 'zaytech_notice_key_not_correct' ));
                }
            }
        } catch (Woocci_Exception $e) {
            Woocci_Logger::log( $e->getMessage() );
        }

        $this->description  =  !empty( $this->description ) ? $this->description : ' ';
        $this->order_status =  isset( $this->order_status ) ? $this->order_status : 'default';
        $this->reduce_stock =  isset( $this->reduce_stock ) ? $this->reduce_stock : 'when_paid';

		// Hooks.
		add_action( 'wp_enqueue_scripts', array( $this, 'woocci_scripts' ) );
		add_action( 'woocommerce_update_options_payment_gateways_' . $this->id, array( $this, 'process_admin_options' ) );

    }

	/**
	 * Checks if keys are set.
	 *
	 * @since 1.0.0
	 * @return bool
	 */
	public function are_keys_set() {
		if ( empty( $this->secret_key ) ) {
			return false;
		}
		return true;
	}

    public function elements_form() {
		?>
        <fieldset id="wc-<?php echo esc_attr( $this->id ); ?>-cc-form" class="wc-credit-card-form wc-payment-form" style="background:transparent;">
            <?php do_action( 'woocommerce_credit_card_form_start', $this->id ); ?>
                <input type="hidden" value="use_inline_form" />
                <div class="form-row form-row-wide moo-no-margin-buttom">
                    <label for="clover-card-element"><?php esc_html_e( 'Card Number', 'zaytech_woocci' ); ?> <span class="required">*</span></label>
                    <div class="clover-card-group">
                        <div id="clover-card-element" class="wc-clover-elements-field">
                            <!-- a Clover Element will be inserted here. -->
                        </div>
                    </div>
                </div>

                <div class="form-row form-row-first moo-no-margin-buttom">
                    <label for="clover-exp-element"><?php esc_html_e( 'Expiry Date', 'zaytech_woocci' ); ?> <span class="required">*</span></label>
                    <div id="clover-exp-element" class="wc-clover-elements-field">
                        <!-- a Clover Element will be inserted here. -->
                    </div>
                </div>

                <div class="form-row form-row-last moo-no-margin-buttom">
                    <label for="clover-cvc-element"><?php esc_html_e( 'Card Code (CVC)', 'zaytech_woocci' ); ?> <span class="required">*</span></label>
                    <div id="clover-cvc-element" class="wc-clover-elements-field">
                        <!-- a Clover Element will be inserted here. -->
                    </div>
                </div>
                <?php if ($this->showStreetAddressField){ ?>
                    <div class="form-row form-row-wide moo-no-margin-buttom">
                        <label for="clover-address-element"><?php esc_html_e( 'Street Address', 'zaytech_woocci' ); ?> <span class="required">*</span></label>
                        <div id="clover-address-element" class="wc-clover-elements-field">
                            <!-- a Clover Element will be inserted here. -->
                        </div>
                    </div>
                <?php } ?>
                <div class="form-row form-row-wide moo-no-margin-buttom">
                    <label for="clover-zip-element"><?php esc_html_e( 'Zip code', 'zaytech_woocci' ); ?> <span class="required">*</span></label>
                    <div id="clover-zip-element" class="wc-clover-elements-field">
                        <!-- a Clover Element will be inserted here. -->
                    </div>
                </div>
                <div class="clear"></div>

            <!-- Used to display form errors -->
            <div class="clover-errors" role="alert"></div>
            <!-- Used to display Clover footer -->
            <div class="clover-footer"></div>
            <br />
            <?php do_action( 'woocommerce_credit_card_form_end', $this->id ); ?>
            <div class="clear"></div>
        </fieldset>

        <?php
    }
    /**
     * Payment form on checkout page
     */

    public function payment_fields() {
        if( ! $this->are_keys_set() ){
            return;
        }
        $description = $this->get_description();

        if ( $this->use_cc_form ) {
            ob_start();
            echo '<div
			id="woocci-payment-data"
			data-currency="' . esc_attr( strtolower( get_woocommerce_currency() ) ) . '"
		>';


            $description = trim( $description );

            if ( $description ) {
                echo wpautop( wptexturize( $description ) );
            }

            $this->elements_form();

            echo '</div>';

            ob_end_flush();
        } else {
            if ( $description ) {
                echo wpautop( wptexturize( $description ) );
            }
        }
    }
	/**
	 * Initialise Gateway Settings Form Fields
	 */
	public function init_form_fields() {
		$this->form_fields = apply_filters('woocci_settings',array(
            'enabled'    => array(
                'title'       => __( 'Enable/Disable', 'zaytech_woocci' ),
                'label'       => __( 'Enable', 'zaytech_woocci' ),
                'type'        => 'checkbox',
				'default'     => 'yes',
				'description' => ''
            ),
            'secret_key' => array(
                'title'       => __( 'API Key', 'zaytech_woocci' ),
                'type'        => 'text',
               // 'description' => __( 'To get the correct Api-Key, make sure to install the App by Zaytech on Clover.', 'zaytech_woocci' ),
				'description' => __(  'Easily obtain your API Key by clicking <span style="color: blue;cursor: pointer" class="sooApiKeyLink" href="%1$s" target="_blank">here</span>.', 'zaytech_woocci'),
				'default'     => '',
				'class' => 'sooApiKeyField',
            ),
            'title'       => array(
                'title'       => __( 'Title', 'zaytech_woocci' ),
                'type'        => 'text',
                'description' => __( 'This controls the title which the user sees during checkout.', 'zaytech_woocci' ),
                'default'     => __( 'Pay with your Credit Card via Clover', 'zaytech_woocci' ),
                'desc_tip'    => true,
            ),
            'description'       => array(
                'title'       => __( 'Description', 'zaytech_woocci' ),
                'type'        => 'textarea',
                'description' => __( 'This controls the description which the user sees during checkout.', 'zaytech_woocci' ),
                'default'     => __( 'Secure Payments Powered by Clover \n\n Clover® trademark and logo are owned by Clover® Network, Inc., a Fiserv company', 'zaytech_woocci' ),
                'desc_tip'    => true,
            ),
            'use_cc_form'  => array(
                'title'       => __( 'Use Credit Card Form', 'zaytech_woocci' ),
                'label'       => __( 'Use Credit Card Form on Woo-Commerce Checkout Page', 'zaytech_woocci' ),
                'type'        => 'checkbox',
                'description' => __( 'By selecting this option, customers will remain on the Woo-Commerce Checkout page and use (iframe Hosted by Clover)', 'zaytech_woocci' ),
                'default'     => 'yes',
                'desc_tip'    => true,
            ),
            'show_address_field'  => array(
                'title'       => __( 'Show Street Address Field', 'zaytech_woocci' ),
                'label'       => __( 'Show The Street Address Field On Credit Card Form', 'zaytech_woocci' ),
                'type'        => 'checkbox',
                'description' => __( 'By selecting this option, customers will see the Sreet Address field', 'zaytech_woocci' ),
                'default'     => 'no',
                'desc_tip'    => true,
            ),
            'order_status'      => array(
                'title'       => __( 'Status For Paid Orders', 'zaytech_woocci' ),
                'label'       => __( 'The new status for paid orders', 'zaytech_woocci' ),
                'type'        => 'select',
                'description' => __( 'The new status for orders paid successfully', 'zaytech_woocci' ),
                'default'     => 'completed',
                'desc_tip'    => true,
                'options' => apply_filters( 'woocci_default_order_status',array( 'processing' => 'Processing', 'completed' => 'Completed', 'on_hold' => 'On hold', 'default' => 'Default'))
            ),
            'reduce_stock'      => array(
                'title'       => __( 'Reduce the stock', 'zaytech_woocci' ),
                'label'       => __( 'Reduce the Woo stock', 'zaytech_woocci' ),
                'type'        => 'select',
                'description' => __( 'When tell Woo to reduce the stock', 'zaytech_woocci' ),
                'default'     => 'when_paid',
                'desc_tip'    => true,
                'options' => apply_filters( 'woocci_default_reduce_stock',array( 'when_ordering' => 'When the order is placed', 'when_paid' => 'When the order is paid', 'never' => 'Never reduce the stock'))
            ),
            'enable_recaptcha'      => array(
                'title'       => __( 'Enable Google reCAPTCHA', 'zaytech_woocci' ),
                'label'       => __( 'Enable reCAPTCHA v3', 'zaytech_woocci' ),
                'type'        => 'select',
                'description' => __( 'Enable Google reCAPTCHA v3 to protect your website', 'zaytech_woocci' ),
                'default'     => 'no',
                'desc_tip'    => true,
				'options' => array(
					'yes' => __('Yes', 'zaytech_woocci'),
					'no' => __('No', 'zaytech_woocci'),
				),
            ),
			'recaptcha_site_key'       => array(
				'title'       => __( 'Site Key', 'zaytech_woocci' ),
				'type'        => 'text',
				'description' => __( 'Go to the Google reCaptcha Admin Console and generate a site key and secret key', 'zaytech_woocci' ),
				'default'     => ''
			),
			'recaptcha_secret_key'       => array(
				'title'       => __( 'Secret Key', 'zaytech_woocci' ),
				'type'        => 'text',
				'description' => __( '', 'zaytech_woocci' ),
				'default'     => '',
			),
            'logging'      => array(
                'title'       => __( 'Logging', 'zaytech_woocci' ),
                'label'       => __( 'Log debug messages', 'zaytech_woocci' ),
                'type'        => 'checkbox',
                'description' => __( 'Save debug messages to the WooCommerce System Status log.', 'zaytech_woocci' ),
                'default'     => 'no',
                'desc_tip'    => true,
            )

        ));

	}


    /**
     * Gets the locale with normalization that only Stripe accepts.
     *
     * @since 1.0.0
     * @return string $locale
     */
    public function get_locale() {
        $locale = get_locale();
        if ( 'NO' === substr( $locale, 3, 2 ) ) {
            $locale = 'no';
        } else {
            $locale = substr( get_locale(), 0, 2 );
        }

        return $locale;
    }

	/**
	 * Process the payment
	 * @return array
	 */
	public function process_payment( $order_id ) {
		try {
			global $woocommerce;

			//Google reCAPTCHA v3
			if ($this->isRecaptchaEnabled) {
				$googleRecaptchaToken = wc_clean($_POST["soo-recaptcha-token"]);
				if (!  $this->checkGoogleRecaptchaToken($googleRecaptchaToken) ){
					$reCaptchaErrorMessage = "Oops! It seems there was an issue with the reCAPTCHA. Don't worry, these things happen. Please try submitting the form again. We apologize for any inconvenience caused.";
					throw new Woocci_Exception( $reCaptchaErrorMessage );
				}
			}
			//Inline Form
			if($this->use_cc_form) {
				if (empty($_POST["clover-source"])) {
					throw new Woocci_Exception( "An error has occurred, please try again.");
				}
			}

			$order = new WC_Order( $order_id );
            $orderNote = 'WooCommerce (ID : '.$order->get_id().') | '. $order->get_billing_first_name() .' '.$order->get_billing_last_name();

			$cloverOrder = array (
                    "note"=>'SOO | ' . apply_filters( 'woocci_order_note',$orderNote, $order),
                    "payment_method"=>"scp2",
                    "amount"=>floatval($order->get_total()) * 100,
                    "tax_amount"=>floatval($order->get_total_tax()) * 100,
					"source"=>"woo",
                    "special_instructions"=>apply_filters( 'woocci_new_order_customer_note',$order->get_customer_note(), $order_id),
                    "currency"=>$order->get_currency()
            );

			if(isset($this->clover_service_charge)) {
                $cloverOrder["applyServiceCharge"] = true;
            }

            if(!empty($_POST["clover-source"])) {
                $cloverPaymentToken = wc_clean($_POST["clover-source"]);
                $cardString = wc_clean($_POST["clover-card"]);
                $card = json_decode(stripslashes($cardString),true);
                $cloverOrder["payment_method"] = "clover";
                $cloverOrder["token"] = $cloverPaymentToken;
                $cloverOrder["card"] = $card;
            }

            $cloverOrder["customer"]	= $this->prepareCustomerData($order);
            $cloverOrder["metainfo"]	= $this->prepareMetaInfoData($order);
			$cloverOrder["cart"]		= $this->prepareLineItemsData($order);
			$cloverOrder["discounts"]	= $this->prepareDiscountsData($order);

			//Add Shipping fee to the Order
			if( $order->has_shipping_address() ) {
			    $cloverOrder["delivery_amount"] = floatval($order->calculate_shipping())*100;
			    $cloverOrder["delivery_name"]= $order->get_shipping_method();
            }
			//Create the Order
			$orderCreated =$this->api->createOrder($cloverOrder);

			if(!$orderCreated){
                Woocci_Logger::log( 'Clover Order not created, response is : '. json_encode($orderCreated) );
                Woocci_Logger::log( 'If you are the site owner, check your WooCommerce Clover Payment Gateway by Zaytech Api Key');
				throw new Woocci_Exception( "An error has occurred, If you are the site owner, check your WooCommerce Clover Payment Gateway by Zaytech Api Key'");
			} else {
                if(isset($orderCreated['order_id'])){
                    Woocci_Logger::log( 'New Order has been created, Clover UUID : ' . $orderCreated['order_id'] . ", WOO ID : ". $order_id);
                    $order->add_order_note("Order created in Clover with the id : ".$orderCreated['order_id']);
					$order->add_meta_data( '_clover_uuid', $orderCreated['order_id'] );
                }

                if(isset($orderCreated['status'])) {

                    //Tell Woo to reduce the stock just after creating the Clover order
                    if($this->reduce_stock === "when_ordering") {
						Woocci_Logger::log( $order_id . " : The stock has been reduced " );
						wc_reduce_stock_levels( $order );
                    }

                    if($orderCreated['status'] === "redirect") {
                        Woocci_Logger::log( $order_id . " : The customer has been redirected to the payment page (". $orderCreated['payment_link'] .")" );
						$order->save();
                        return array(
                            'result' => 'success',
                            'redirect' => $orderCreated['payment_link']
                        );
                    }
                    // The order not paid successfully
                    if($orderCreated['status'] === "failed") {
                        $failureMessage = "Payment card was declined. Check card info or try another card.";
                        if(!empty($orderCreated['message'])){
                            $failureMessage = $orderCreated['message'];
                        }
                        Woocci_Logger::log( $order_id . " : New Clover Order has been paid using the inline form but the payment wasn't accepted. Clover reponse is (". $failureMessage .")" );
                        $order->update_status( 'failed' );
						throw new Woocci_Exception( $failureMessage);
					}
                    //The order has been paid
                    if($orderCreated['status'] === "success") {
                        Woocci_Logger::log( $order_id . ' : New Clover Order has been paid using inline form, the order id was : ' . $orderCreated['order_id'] );

                        // Mark Order as PAID
                        if (!empty($cloverOrder["card"]["last4"])){
							$order->add_meta_data( '_card_last4', $cloverOrder["card"]["last4"] );
                            $orderNote = 'The payment using the card **********'.$cloverOrder["card"]["last4"];
							if (!empty($cloverOrder["card"]["brand"])){
								$order->add_meta_data( '_card_brand', $cloverOrder["card"]["brand"] );
								$orderNote .= " (".$cloverOrder["card"]["brand"].")";
							}
                        } else {
                            $orderNote = 'The payment';
                        }

                        $orderNote .= ' has been accepted by Clover. Check the online receipt from <a href="' . WOOCCI_RECEIPT_URL . $orderCreated['order_id'] . '" target="_blank">here</a>';

						//Set Clover transaction ID to the Order
						if(! empty($orderCreated["charge_id"]) ){
							$order->set_transaction_id($orderCreated["charge_id"]);
						}

                        if($this->order_status === "default") {
                            $order->add_order_note($orderNote);
                            $order->payment_complete();
                        } else {
                            // Mark Order as PAID
                            $order->update_status($this->order_status, $orderNote);
                        }

                        //Check reduce stock settings them reduce it if when order is paid selected
                        if($this->reduce_stock === "when_paid") {
							Woocci_Logger::log( $order_id . " : The stock has been reduced " );
							wc_reduce_stock_levels( $order );
                        }

                        // Remove cart
                        $woocommerce->cart->empty_cart();

						//Save the order
						$order->save();

                        do_action( 'woocci_process_payment_success', $order );

                        return array(
                            'result' => 'success',
                            'redirect' => $this->get_return_url($order)
                        );
                    }

					Woocci_Logger::log( $order_id . " : Unknown order status". json_encode($orderCreated).")" );

				} else {
					do_action( 'woocci_process_payment_failed', $order );
					$order->update_status( 'failed' );
					throw new Woocci_Exception( "An error has occurred, please try again");
				}
            }
		} catch ( Woocci_Exception $e ) {
		    Woocci_Logger::log( $e->getMessage() );
           // wc_add_notice(  $e->getMessage() , 'error' );
			throw new Exception( $e->getMessage()  );
		}
	}
	public function process_refund( $order_id, $amount = null, $reason = '' ){
		try {
			if (is_null($amount) ) {
				return new WP_Error(
					'zaytech_woocci_error',
					sprintf(
						__('Please enter refund value', 'zaytech_woocci')
					)
				);
			}
			$order = wc_get_order($order_id);

			if (in_array($order->get_status(), array( 'refunded', 'failed' ), true) ) {
				return new WP_Error(
					'zaytech_woocci_error',
					sprintf(
						__('Please select a valid order to refund', 'zaytech_woocci')
					)
				);
			}

			if ($order->get_total() !== $amount ) {
				return new WP_Error(
					'zaytech_woocci_error',
					sprintf(
						__('Unable to process partial refunds', 'zaytech_woocci')
					)
				);
			}
			$cloverOrderUuid = $order->get_meta('_clover_uuid',true);
			$cloverTxId = $order->get_transaction_id();
			$payload = [
				"amount"=>(int)Round($amount * 100),
				"chargeId"=>!empty($cloverTxId) ? $cloverTxId : null,
				"externalReferenceId"=>$order_id,
			];
			$refundResponse = $this->api->refundOrder($cloverOrderUuid, $payload);
			if( !$refundResponse || empty($refundResponse['id'])) {
				if (!empty($refundResponse['error']['message'])){
					$errorMessage = $refundResponse['error']['message'];
					Woocci_Logger::log( 'Clover Order not refunded ( '. $errorMessage ." )" );
				} else {
					$errorMessage = "An error has occurred, try again";
					Woocci_Logger::log( 'Clover Order not refunded' );
				}
				return new WP_Error(
					'zaytech_woocci_error',
					sprintf(
						__($errorMessage, 'zaytech_woocci')
					)
				);
			}

			$refund_message = sprintf(__('Clover Order has been refunded %1$s - Refund ID: %2$s', 'zaytech_woocci'), $amount, $refundResponse['id']);
			$order->update_meta_data('_clover_refund_id', $refundResponse['id']);
			$order->add_order_note($refund_message);
			return true;

		} catch ( Exception $e ) {
			Woocci_Logger::log( $e->getMessage() );
			throw new Exception($e->getMessage());
		}

	}

    /**
     * scripts function.
     *
     * Outputs scripts
     *
     * @since 1.2.2
     * @version 1.2.2
     */
    public function woocci_scripts() {

        if ( ! is_product() && ! is_cart() && ! is_checkout() && ! isset( $_GET['pay_for_order'] ) && ! is_add_payment_method_page() && ! isset( $_GET['change_payment_method'] ) || ( is_order_received_page() ) ) { // wpcs: csrf ok.
            //return;
        }

        // If it is not enabled bail.
        if ( ! $this->enabled === 'yes' ) {
            return;
        }

        // If keys are not set bail.
        if ( ! $this->are_keys_set() ) {
            Woocci_Logger::log( 'Api Key is not set correctly.' );
            return;
        }

        // If no SSL bail.
        if ( ! is_ssl() ) {
            Woocci_Logger::log( 'Woocci live mode requires SSL.' );
            return;
        }
		$cloverSkd = (defined('WOOCCI_ENV') && (strtoupper(WOOCCI_ENV) === "DEV"))? 'https://checkout.sandbox.dev.clover.com/sdk.js' : 'https://checkout.clover.com/sdk.js';

		wp_register_script( 'woocci_clover', $cloverSkd, array(), null, false );
		wp_enqueue_script( 'woocci_clover' );

        wp_register_style( 'woocci_styles', plugins_url( 'assets/css/woocci-styles.min.css', WOOCCI_MAIN_FILE ), array(), WOOCCI_VERSION );
        wp_register_script( 'woocci_scripts', plugins_url( 'assets/js/woocci-scripts.min.js', WOOCCI_MAIN_FILE ), array( 'woocci_clover' ), WOOCCI_VERSION, true );

        $woocci_params = array(
            'key'                  => $this->pakmsKey,
            'inline_form'          => $this->use_cc_form,
            'show_street_address'  => $this->showStreetAddressField,
            'i18n_terms'           => __( 'Please accept the terms and conditions first', 'zaytech_woocci' ),
            'i18n_required_fields' => __( 'Please fill in required checkout fields first', 'zaytech_woocci' ),
            "locale"=>str_replace("_","-",get_locale()),
            "recaptcha_site_key"=>$this->isRecaptchaEnabled ? $this->recaptchaSiteKey : false,
        );

        // If we're on the pay page we need the address of the order.
        if ( isset( $_GET['pay_for_order'] ) && 'true' === $_GET['pay_for_order'] ) { // wpcs: csrf ok.
            $order_id = wc_get_order_id_by_order_key( urldecode( $_GET['key'] ) ); // wpcs: csrf ok, sanitization ok, xss ok.
            $order    = wc_get_order( $order_id );

            if ( is_a( $order, 'WC_Order' ) ) {
                $woocci_params['billing_first_name'] = $order->get_billing_first_name();
                $woocci_params['billing_last_name']  = $order->get_billing_last_name();
                $woocci_params['billing_address_1']  = $order->get_billing_address_1();
                $woocci_params['billing_address_2']  = $order->get_billing_address_2();
                $woocci_params['billing_state']      = $order->get_billing_state();
                $woocci_params['billing_city']       = $order->get_billing_city();
                $woocci_params['billing_postcode']   = $order->get_billing_postcode();
                $woocci_params['billing_country']    = $order->get_billing_country();
            }
        }

        $woocci_params['no_prepaid_card_msg']       = __( 'Sorry, we\'re not accepting prepaid cards at this time. Your credit card has not been charged. Please try with alternative payment method.', 'zaytech_woocci' );
        $woocci_params['no_sepa_owner_msg']         = __( 'Please enter your IBAN account name.', 'zaytech_woocci' );
        $woocci_params['no_sepa_iban_msg']          = __( 'Please enter your IBAN account number.', 'zaytech_woocci' );
        $woocci_params['payment_intent_error']      = __( 'We couldn\'t initiate the payment. Please try again.', 'zaytech_woocci' );
        $woocci_params['allow_prepaid_card']        = apply_filters( 'wc_stripe_allow_prepaid_card', true ) ? 'yes' : 'no';
        $woocci_params['is_checkout']               = ( is_checkout() && empty( $_GET['pay_for_order'] ) ) ? 'yes' : 'no'; // wpcs: csrf ok.
        $woocci_params['ajaxurl']                   = WC_AJAX::get_endpoint( '%%endpoint%%' );
        $woocci_params['woocci_nonce']              = wp_create_nonce( '_woocci_nonce' );
        $woocci_params['elements_options']          = apply_filters( 'woocci_elements_options', array() );
        $woocci_params['invalid_owner_name']        = __( 'Billing First Name and Last Name are required.', 'zaytech_woocci' );
        $woocci_params['is_change_payment_page']    = isset( $_GET['change_payment_method'] ) ? 'yes' : 'no'; // wpcs: csrf ok.
        $woocci_params['is_add_payment_page']       = is_wc_endpoint_url( 'add-payment-method' ) ? 'yes' : 'no';
        $woocci_params['is_pay_for_order_page']     = is_wc_endpoint_url( 'order-pay' ) ? 'yes' : 'no';
        $woocci_params['elements_styling']          = apply_filters( 'woocci_elements_styling', false );
        $woocci_params['elements_classes']          = apply_filters( 'wocci_elements_classes', false );

        // Merge localized messages to be used in JS.
        $woocci_params = array_merge( $woocci_params, Woocci_Helper::get_localized_messages() );
		wp_localize_script( 'woocci_scripts', 'woocci_params', apply_filters( 'woocci_params', $woocci_params ) );


		wp_enqueue_script( 'woocci_scripts' );
		wp_enqueue_style( 'woocci_styles' );

		if ($this->isRecaptchaEnabled){
			wp_register_script('WoocciGoogleRecaptcha',  '//www.google.com/recaptcha/api.js?render='.$this->recaptchaSiteKey,array(),false);
			wp_enqueue_script('WoocciGoogleRecaptcha');
		}

	}

    /**
     * Add Notice when the secret key isn't correct or when we aren't able to get the PAKMS Key.
     *
     * @since 1.2.6
     * @version 1.2.6
     */
    public function zaytech_notice_key_not_correct() {
        echo '<div class="error"><p><strong>' . sprintf( esc_html__("WooCommerce Clover Payment Gateway by Zaytech : We couldn't check the api key right now. Please double check it or re-install the app Smart Online Order on your Clover account", 'zaytech_woocci' ) ) . '</strong></p></div>';

    }

	private function checkGoogleRecaptchaToken($googleRecaptchaToken) {
		$args = array(
			'method'    => 'POST',
			'body'=>array(
				'secret'   => $this->recaptchaSecretKey,
				'response' => $googleRecaptchaToken
			)
		);
		$gcaptcha = wp_remote_post( WOOCCI_G_RECAPTCHA_URL, $args );

		if ( is_wp_error( $gcaptcha ) ) {
			Woocci_Logger::log( 'Failed to check Google reCAPTCHA v3 : '. json_encode($gcaptcha->get_error_message()) );
			return false;
		}
		$gcaptchaBody = wp_remote_retrieve_body( $gcaptcha );
		if ( empty( $gcaptchaBody ) ) {
			Woocci_Logger::log( 'Failed to check Google reCAPTCHA v3 : Empty response from Google ');
			return false;
		}
		$result = json_decode( $gcaptchaBody );
		if ( empty( $result ) ) {
			Woocci_Logger::log( 'Failed to check Google reCAPTCHA v3 : Empty response from Google ');
			return false;
		}
		if ( ! isset( $result->success ) ) {
			Woocci_Logger::log( 'Failed to check Google reCAPTCHA v3 :  '. json_encode($result));
			return false;
		}
		if ($result->success){
			return true;
		} else {
			Woocci_Logger::log( 'Failed to check Google reCAPTCHA v3 :  '. json_encode($result));
			return false;
		}
	}

	private function prepareCustomerData($order) {
		return array(
			"name"=>$order->get_billing_first_name() .' '.$order->get_billing_last_name(),
			"phone"=>$order->get_billing_phone(),
			"email"=>$order->get_billing_email(),
			"address"=> array(
				"address1"=>$order->get_billing_address_1(),
				"address2"=>$order->get_billing_address_2(),
				"zip"=>$order->get_billing_postcode(),
				"city"=>$order->get_billing_city(),
				"state"=>$order->get_billing_state(),
				"country"=>$order->get_billing_country()
			)
		);
	}

	private function prepareLineItemsData($order) {
		$line_items = array();

		foreach ($order->get_items() as $item_id => $item_line) {
			// Get an instance of corresponding the WC_Product object
			$product = $item_line->get_product();
			$itemName = apply_filters( 'woocci_orderline_item_name', $product->get_name(), $item_line);
			if ($item_line->get_quantity() > 1){
				$itemName = $itemName . ' x ' . $item_line->get_quantity();
			}
			$line_items[] = array(
				"name" => $itemName,
				"price" => floatval($item_line->get_subtotal()) * 100,
				"qty" => 1,
				"sku" => $product->get_sku(),
				"note" => apply_filters('woocci_line_item_note', "", $item_line)
			);
		}

		return array("items"=>$line_items);
	}

	private function prepareMetaInfoData($order) {
		$successLink = $this->get_return_url($order);
		$callback_url = get_rest_url() . "woocci/v1/check_order";;
		$cancelLink = wc_get_checkout_url();
		return array(
			array(
				"name"  => "success_link",
				"value" => $successLink,
			),
			array(
				"name"  => "cancel_link",
				"value" => $cancelLink,
			),
			array(
				"name"  => "woo_response",
				"value" => $callback_url,
			),
			array(
				"name"  => "source",
				"value" => "woocommerce",
			),
			array(
				"name"  => "orderWebRef",
				"value" => $order->get_id(),
			),
			array(
				"name"  => "tips_enabled",
				"value" => apply_filters( 'woocci_apply_tips',"no"),
			),
		);
	}

	private function prepareDiscountsData($order) {
		$cloverDiscounts = [];
		if($order->get_total_discount() > 0) {
			foreach( $order->get_coupon_codes() as $coupon_code ){
				$c = new WC_Coupon($coupon_code);
				if($c->get_discount_type() === "percent"){
					$cloverDiscounts[] =  array(
						"value"=>floatval($c->get_amount()),
						"name"=>($c->get_description() === "") ? $coupon_code : $c->get_description(),
						"type"=>"PERCENTAGE"
					);
				} else {
					$cloverDiscounts[] =  array(
						"value"=>floatval($c->get_amount())*100,
						"name"=>($c->get_description() === "") ? $coupon_code : $c->get_description(),
						"type"=>"AMOUNT"
					);
				}
			}
		}
		return $cloverDiscounts;
	}

}
