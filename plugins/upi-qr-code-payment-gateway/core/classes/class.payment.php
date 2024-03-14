<?php
// add Gateway to woocommerce
add_filter( 'woocommerce_payment_gateways', 'dwu_woocommerce_payment_add_gateway_class' );
function dwu_woocommerce_payment_add_gateway_class( $gateways ) {
	$gateways[] = 'DWU_Payment_Gateway'; // class name
	return $gateways;
}

/*
 * The class itself, please note that it is inside plugins_loaded action hook
 */
add_action( 'plugins_loaded', 'dwu_payment_gateway_init' );
function dwu_payment_gateway_init() {
	// If the WooCommerce payment gateway class is not available nothing will return
	if ( ! class_exists( 'WC_Payment_Gateway' ) ) return;
	class DWU_Payment_Gateway extends WC_Payment_Gateway {
		/**
		 * Constructor for the gateway.
		**/
		public function __construct() {
			$this->id                 = 'dew-wc-upi';
			$this->icon               = apply_filters( 'dwu_custom_gateway_icon', DEW_WOO_UPI_IMAGES_URL . '/dwu-logo.png' );
			$this->has_fields         = true;
			$this->method_title       = __( 'UPI QR Code', 'dew-upi-qr-code' );
			$this->method_description = __( 'Allows customers to use UPI mobile app like Paytm, Google Pay, BHIM, PhonePe to pay to your bank account directly using UPI. All of the below fields are required. Merchant needs to manually checks the payment and mark it as complete on the Order edit page as automatic verification is not available in this payment method.', 'dew-upi-qr-code' );
			$this->order_button_text  = __( 'Proceed to Payment', 'dew-upi-qr-code' );
			// Method with all the options fields
			$this->dwu_init_form_fields();
			// Load the settings.
			$this->init_settings();
			// Define user set variables
			$this->title 				= $this->get_option( 'title' );
			$this->description 			= $this->get_option( 'description' );
			$this->instructions 		= $this->get_option( 'instructions', $this->description );
			$this->instructions_mobile 	= $this->get_option( 'instructions_mobile', $this->description );
			$this->confirm_message 		= $this->get_option( 'confirm_message' );
			$this->thank_you 			= $this->get_option( 'thank_you' );
			$this->payment_status 		= $this->get_option( 'payment_status', 'on-hold' );
			$this->name 				= $this->get_option( 'name' );
			$this->vpa 					= $this->get_option( 'vpa' );
			$this->pay_button 			= $this->get_option( 'pay_button' );
			$this->mcc                  = $this->get_option( 'mc_code' );
			$this->app_theme 			= $this->get_option( 'theme', 'light' );
			$this->upi_address 			= $this->get_option( 'upi_address', 'show_require' );
			$this->require_upi 			= $this->get_option( 'require_upi', 'yes' );
			$this->transaction_id 		= $this->get_option( 'transaction_id', 'hide' );
			$this->qrcode_mobile 		= $this->get_option( 'qrcode_mobile', 'yes' );
			$this->hide_on_mobile		= $this->get_option( 'hide_on_mobile', 'no' );
			$this->email_enabled 		= $this->get_option( 'email_enabled' );
			$this->email_subject 		= $this->get_option( 'email_subject' );
			$this->email_heading 		= $this->get_option( 'email_heading' );
			$this->additional_content 	= $this->get_option( 'additional_content' );
			$this->default_status 		= apply_filters( 'dwu_process_payment_order_status', 'pending' );

			// Actions
			add_action( 'woocommerce_update_options_payment_gateways_' . $this->id, array( $this, 'process_admin_options' ) );

			// We need custom JavaScript to obtain the transaction number
			add_action( 'wp_enqueue_scripts', array( $this, 'dwu_payment_scripts' ) );

			// thank you page output
			add_action( 'woocommerce_receipt_'.$this->id, array( $this, 'dwu_generate_qr_code' ), 4, 1 );

			// verify payment from redirection
			add_action( 'woocommerce_api_dwu-payment', array( $this, 'dwu_capture_payment' ) );

			// Customize on hold email template subject
			add_filter( 'woocommerce_email_subject_customer_on_hold_order', array( $this, 'dwu_email_subject_pending_order' ), 10, 3 );

			// Customize on hold email template heading
			add_filter( 'woocommerce_email_heading_customer_on_hold_order', array( $this, 'dwu_email_heading_pending_order' ), 10, 3 );

			// Customize on hold email template additional content
			add_filter( 'woocommerce_email_additional_content_customer_on_hold_order', array( $this, 'dwu_email_additional_content_pending_order' ), 10, 3 );

			// Customer Emails
			add_action( 'woocommerce_email_after_order_table', array( $this, 'dwu_email_instructions' ), 10, 4 );

			// add support for payment for on hold orders
			add_action( 'woocommerce_valid_order_statuses_for_payment', array( $this, 'dwu_on_hold_payment' ), 10, 2 );

			// change wc payment link if exists payment method is QR Code
			add_filter( 'woocommerce_get_checkout_payment_url', array( $this, 'dwu_custom_checkout_url' ), 10, 2 );
			
			// add custom text on thankyou page
			add_filter( 'woocommerce_thankyou_order_received_text', array( $this, 'dwu_order_received_text' ), 10, 2 );

			// disale upi payment gateway
			add_filter( 'woocommerce_available_payment_gateways', array( $this, 'dwu_disable_gateway' ), 10, 1 );

			if ( !$this->is_valid_for_use() ) {
				$this->enabled = 'no';
			}
		}

		/**
		 * Check if this gateway is enabled and available in the user's country.
		 *
		 * @return bool
		**/
		public function is_valid_for_use() {
			if ( in_array( get_woocommerce_currency(), apply_filters( 'dwu_supported_currencies', array( 'INR' ) ) ) ) {
				return true;
			}
			return false;
		}

		/**
		 * Admin Panel Options.
		 *
		 * @since 1.0.0
		**/
		public function admin_options() {
			if ( $this->is_valid_for_use() ) {
				parent::admin_options();
			} else {
				?>
				<div class="inline error">
					<p>
						<strong><?php esc_html_e( 'Gateway disabled', 'dew-upi-qr-code' ); ?></strong>: <?php _e( 'This plugin does not support your store currency. UPI Payment only supports Indian Currency. Contact developer for support.', 'dew-upi-qr-code' ); ?>
					</p>
				</div>
				<?php
			}
		}

		/**
		 * Initialize Gateway Settings Form Fields
		**/
		public function dwu_init_form_fields() {
			$this->form_fields = array(
				'enabled' => array(
					'title'       => __( 'Enable/Disable:', 'dew-upi-qr-code' ),
					'type'        => 'checkbox',
					'label'       => __( 'Enable UPI QR Code Payment Method', 'dew-upi-qr-code' ),
					'description' => __( 'Enable this if you want to collect payment via UPI QR Codes.', 'dew-upi-qr-code' ),
					'default'     => 'yes',
					'desc_tip'    => true,
				),
				'title' => array(
					'title'       => __( 'Title:', 'dew-upi-qr-code' ),
					'type'        => 'text',
					'description' => __( 'This controls the title for the payment method the customer sees during checkout.', 'dew-upi-qr-code' ),
					'default'     => __( 'Pay with UPI QR Code', 'dew-upi-qr-code' ),
					'desc_tip'    => false,
				),
				'description' => array(
					'title'       => __( 'Description:', 'dew-upi-qr-code' ),
					'type'        => 'textarea',
					'description' => __( 'Payment method description that the customer will see on your checkout.', 'dew-upi-qr-code' ),
					'default'     => __( 'It uses UPI apps like BHIM, Paytm, Google Pay, PhonePe or any Banking UPI app to make payment.', 'dew-upi-qr-code' ),
					'desc_tip'    => false,
				),
				'instructions' => array(
					'title'       => __( 'Instructions:', 'dew-upi-qr-code' ),
					'type'        => 'textarea',
					'description' => __( 'Instructions that will be added to the order pay popup on desktop devices.', 'dew-upi-qr-code' ),
					'default'     => __( 'Scan the QR Code with any UPI apps like BHIM, Paytm, Google Pay, PhonePe or any Banking UPI app to make payment for this order. After successful payment, enter the UPI Reference ID or Transaction Number and your UPI ID in the next screen and submit the form. We will manually verify this payment against your 12-digits UPI Reference ID or Transaction Number starts with 1 (e.g. 101422121258) and your UPI ID.', 'dew-upi-qr-code' ),
					'desc_tip'    => false,
				),
				'instructions_mobile' => array(
					'title'       => __( 'Mobile Instructions:', 'dew-upi-qr-code' ),
					'type'        => 'textarea',
					'description' => __( 'Instructions that will be added to the order pay popup on mobile devices.', 'dew-upi-qr-code' ),
					'default'     => __( 'Scan the QR Code with any UPI apps like BHIM, Paytm, Google Pay, PhonePe or any Banking UPI app to make payment for this order. After successful payment, enter the UPI Reference ID or Transaction Number and your UPI ID in the next screen and submit the form. We will manually verify this payment against your 12-digits UPI Reference ID or Transaction Number starts with 1 (e.g. 101422121258) and your UPI ID.', 'dew-upi-qr-code' ),
					'desc_tip'    => false,
				),
				'confirm_message' => array(
					'title'       => __( 'Confirm Message:', 'dew-upi-qr-code' ),
					'type'        => 'textarea',
					'description' => __( 'This displays a message to customer as payment processing text.', 'dew-upi-qr-code' ),
					'default'     => __( 'Click Confirm, only after amount deducted from your account. We will manually verify your transaction. Are you sure?', 'dew-upi-qr-code' ),
					'desc_tip'    => false,
				),
				'thank_you' => array(
					'title'       => __( 'Thank You Message:', 'dew-upi-qr-code' ),
					'type'        => 'textarea',
					'description' => __( 'This displays a message to customer after a successful payment is made.', 'dew-upi-qr-code' ),
					'default'     => __( 'Thank you for your payment. Your transaction has been completed, and your order has been successfully placed. Please check you Email inbox for details. Please check your bank account statement to view transaction details.', 'dew-upi-qr-code' ),
					'desc_tip'    => false,
				),
				'payment_status'  => array(
					'title'       => __( 'Payment Success Status:', 'dew-upi-qr-code' ),
					'type'        => 'select',
					'description' =>  __( 'Payment action on successful UPI Transaction ID submission.', 'dew-upi-qr-code' ),
					'desc_tip'    => false,
					'default'     => 'on-hold',
					'options'     => apply_filters( 'dwu_settings_order_statuses', array(
							'pending'      => __( 'Pending Payment', 'dew-upi-qr-code' ),
							'on-hold'      => __( 'On Hold', 'dew-upi-qr-code' ),
							'processing'   => __( 'Processing', 'dew-upi-qr-code' ),
							'completed'    => __( 'Completed', 'dew-upi-qr-code' )
						)
					)
				),
				'name' => array(
					'title'       => __( 'Your Store or Shop Name:', 'dew-upi-qr-code' ),
					'type'        => 'text',
					'description' => __( 'Please enter Your Store or Shop name. If you are a person, you can enter your name.', 'dew-upi-qr-code' ),
					'default'     => get_bloginfo( 'name' ),
			    	'desc_tip'    => false,
				),
				'vpa' => array(
					'title'       => __( 'Merchant UPI VPA ID:', 'dew-upi-qr-code' ),
					'type'        => 'email',
					'description' => __( 'Please enter Your Merchant UPI VPA (e.g. Q12345678@ybl) at which you want to collect payments. Receiver and Sender UPI ID can\'t be same. General User UPI VPA is not acceptable. To Generate Merchant UPI ID, you can use apps like PhonePe Business or Paytm for Business etc.', 'dew-upi-qr-code' ),
					'default'     => '',
					'desc_tip'    => false,
				),
				'pay_button' => array(
					'title'       => __( 'Pay Now Button Text:', 'dew-upi-qr-code' ),
					'type'        => 'text',
					'description' => __( 'Enter the text to show as the payment button.', 'dew-upi-qr-code' ),
					'default'     => __( 'Scan & Pay Now', 'dew-upi-qr-code' ),
					'desc_tip'    => false,
				),
				'mc_code'             => array(
			    	'title'       => __( 'Merchant Category Code:', 'dew-upi-qr-code' ),
			    	'type'        => 'text',
			    	'description' => sprintf( '%s <a href="https://www.citibank.com/tts/solutions/commercial-cards/assets/docs/govt/Merchant-Category-Codes.pdf" target="_blank">%s</a> or <a href="https://docs.checkout.com/resources/codes/merchant-category-codes" target="_blank">%s</a>', __( 'You can refer to these links to find out your MCC.', 'dew-upi-qr-code' ), 'Citi Bank', 'Checkout.com' ),
			    	'default'     => 5411,
			    	'desc_tip'    => false,
				),
				'theme' => array(
					'title'       => __( 'Popup Theme:', 'dew-upi-qr-code' ),
					'type'        => 'select',
					'description' =>  __( 'Select the QR Code Popup theme from here.', 'dew-upi-qr-code' ),
					'desc_tip'    => false,
					'default'     => 'light',
					'options'     => apply_filters( 'dwu_popup_themes', array(
						'light'     => __( 'Light Theme', 'dew-upi-qr-code' ),
						'dark'      => __( 'Dark Theme', 'dew-upi-qr-code' )
					) )
				),
				'upi_address' => array(
					'title'       => __( 'UPI Address (VPA):', 'dew-upi-qr-code' ),
					'type'        => 'select',
					'description' =>  __( 'If you want to collect UPI Address from customers, set it from here.', 'dew-upi-qr-code' ),
					'desc_tip'    => false,
					'default'     => 'show_handle',
					'options'     => array(
						'hide'           => __( 'Hide Field', 'dew-upi-qr-code' ),
						'show'           => __( 'Show Input Field', 'dew-upi-qr-code' ),
						'show_handle'    => __( 'Show Input Field & Handle', 'dew-upi-qr-code' ),
					)
				),
				'require_upi' => array(
					'title'       => __( 'Require UPI ID:', 'dew-upi-qr-code' ),
					'type'        => 'select',
					'description' =>  __( 'If you want to make UPI Address field required, set it from here.', 'dew-upi-qr-code' ),
					'desc_tip'    => false,
					'default'     => 'yes',
					'options'     => array(
						'yes'     => __( 'Require Field', 'dew-upi-qr-code' ),
						'no'      => __( 'Don\'t Require Field', 'dew-upi-qr-code' )
					)
				),
				'transaction_id' => array(
					'title'       => __( 'UPI Transaction ID:', 'dew-upi-qr-code' ),
					'type'        => 'select',
					'description' =>  __( 'If you want to collect UPI Transaction ID from customers, set it from here.', 'dew-upi-qr-code' ),
					'desc_tip'    => false,
					'default'     => 'hide',
					'options'     => array(
						'hide'          => __( 'Hide Field', 'dew-upi-qr-code' ),
						'show'          => __( 'Show Input Field', 'dew-upi-qr-code' ),
						'show_require'  => __( 'Show & Require Input Field', 'dew-upi-qr-code' )
					)
				),
				'qrcode_mobile' => array(
					'title'       => __( 'Mobile QR Code:', 'dew-upi-qr-code' ),
					'type'        => 'checkbox',
					'label'       => __( 'Show / Hide QR Code on Mobile Devices', 'dew-upi-qr-code' ),
					'description' => __( 'Enable this if you want to show UPI QR Code on mobile devices.', 'dew-upi-qr-code' ),
					'default'     => 'yes',
					'desc_tip'    => false,
				),
				'hide_on_mobile' => array(
					'title'       => __( 'Disable Gateway on Mobile:', 'dew-upi-qr-code' ),
					'type'        => 'checkbox',
					'label'       => __( 'Disable QR Code Payment Gateway on Mobile Devices', 'dew-upi-qr-code' ),
					'description' => __( 'Enable this if you want to disable QR Code Payment Gateway on Mobile Devices.', 'dew-upi-qr-code' ),
					'default'     => 'no',
					'desc_tip'    => false,
				),
				'email' => array(
					'title'       => __( 'Configure Email', 'dew-upi-qr-code' ),
					'type'        => 'title',
					'description' => '',
				),
				'email_enabled' => array(
					'title'       => __( 'Enable/Disable:', 'dew-upi-qr-code' ),
					'type'        => 'checkbox',
					'label'       => __( 'Enable Email Notification', 'dew-upi-qr-code' ),
					'description' => __( 'Enable this option if you want to send payment link to the customer via email after placing the successful order.', 'dew-upi-qr-code' ),
					'default'     => 'yes',
					'desc_tip'    => false,
				),
				'email_subject' => array(
					'title'       => __( 'Email Subject:', 'dew-upi-qr-code' ),
					'type'        => 'text',
					'desc_tip'    => false,
					'description' => sprintf( __( 'Available placeholders: %s', 'woocommerce' ), '<code>' . esc_html( '{site_title}, {site_address}, {order_date}, {order_number}' ) . '</code>' ),
					'default'     => __( '[{site_title}]: Payment pending #{order_number}', 'dew-upi-qr-code' ),
				),
				'email_heading' => array(
					'title'       => __( 'Email Heading:', 'dew-upi-qr-code' ),
					'type'        => 'text',
					'desc_tip'    => false,
					'description' => sprintf( __( 'Available placeholders: %s', 'woocommerce' ), '<code>' . esc_html( '{site_title}, {site_address}, {order_date}, {order_number}' ) . '</code>' ),
					'default'     => __( 'Thank you for your order', 'dew-upi-qr-code' ),
				),
				'additional_content' => array(
					'title'       => __( 'Email Body Text:', 'dew-upi-qr-code' ),
					'type'        => 'textarea',
					'description' => __( 'This text will be attached to the On Hold email template sent to customer. Use {upi_pay_link} to add the link of payment page.', 'dew-upi-qr-code' ),
					'default'     => __( 'Please complete the payment via UPI by going to this link: {upi_pay_link} (ignore if already done).', 'dew-upi-qr-code' ),
					'desc_tip'    => false,
				)
			);
		}

		/**
		 * Display the UPi Id field
		**/
		public function payment_fields() {
			// display description before the payment form
			if ( $this->description ) {
				// display the description with <p> tags
				echo wpautop( wp_kses_post( $this->description ) );
			}
			
			$dwu_handles = array_unique( apply_filters( 'dwu_upi_handle_list', array( '@airtel', '@airtelpaymentsbank', '@apb', '@apl','@allbank','@albk', '@allahabadbank', '@andb', '@axisgo', '@axis', '@axisbank', '@axisb', '@okaxis', '@abfspay', '@axl', '@barodampay', '@barodapay', '@boi', '@cnrb', '@csbpay', '@csbcash', '@centralbank', '@cbin', '@cboi', '@cub', '@dbs', '@dcb', '@dcbbank', '@denabank', '@equitas', '@federal', '@fbl', '@finobank', '@hdfcbank', '@payzapp', '@okhdfcbank', '@rajgovhdfcbank', '@hsbc', '@imobile', '@pockets', '@ezeepay', '@eazypay', '@idbi', '@idbibank', '@idfc', '@idfcbank', '@idfcnetc', '@cmsidfc', '@indianbank', '@indbank', '@indianbk', '@iob', '@indus', '@indusind', '@icici', '@myicici', '@okicici', '@ikwik', '@ibl', '@jkb', '@jsbp', '@kbl', '@karb', '@kbl052', '@kvb', '@karurvysyabank', '@kvbank', '@kotak', '@kaypay', '@kmb', '@kmbl', '@okbizaxis', '@obc', '@paytm', '@pingpay', '@psb', '@pnb', '@sib', '@srcb', '@sc', '@scmobile', '@scb', '@scbl', '@sbi', '@oksbi', '@syndicate', '@syndbank', '@synd', '@lvb', '@lvbank', '@rbl', '@tjsb', '@uco', '@unionbankofindia', '@unionbank', '@uboi', '@ubi', '@united', '@utbi', '@upi', '@vjb', '@vijb', '@vijayabank', '@ubi', '@yesbank', '@ybl', '@yesbankltd' ) ) );
			sort( $dwu_handles );

			$dwu_class = 'form-row-wide';
			$dwu_placeholder = apply_filters( 'dwu_upi_address_placeholder', 'abc123@paytm' );
			$dwu_required = '';
			$dwu_upi_address = ( isset( $_POST[ 'customer_dwu_address' ] ) ) ? sanitize_text_field( $_POST[ 'customer_dwu_address' ] ) : '';
			
			if ( $this->upi_address === 'show_handle' ) {
				$dwu_class = 'form-row-first';
				$dwu_placeholder = apply_filters( 'dwu_upi_address_placeholder', 'abc123' );
			}

			if ( $this->require_upi === 'yes' ) {
				$dwu_required = ' <span class="required">*</span>';
			}

			if ( in_array( $this->upi_address, array( 'show', 'show_handle' ) ) ) {
				echo '<fieldset id="' . esc_attr( $this->id ) . '-payment-form" class="wc-upi-form wc-payment-form" style="background:transparent;">';
	
				do_action( 'woocommerce_upi_form_start', $this->id );
	
				echo '<div class="form-row ' . $dwu_class . ' dwu-input"><label>' . __( 'UPI Address', 'dew-upi-qr-code' ) . $dwu_required . '</label>
						<input id="dwu-address" class="dwu-address" name="customer_dwu_address" type="text" autocomplete="off" placeholder="e.g. ' . $dwu_placeholder . '" value="' . $dwu_upi_address . '" style="width: 100%;height: 34px;min-height: 34px;">
					</div>';
				if ( $this->upi_address === 'show_handle' ) {
					echo '<div class="form-row form-row-last dwu-input"><label>' . __( 'UPI Handle', 'dew-upi-qr-code' ) . $dwu_required . '</label>
						<select id="dwu-handle" name="customer_dwu_handle" style="width: 100%;height: 34px;min-height: 34px;"><option selected disabled hidden value="">' . __( '-- Select --', 'dew-upi-qr-code' ) . '</option>';
						foreach ( $dwu_handles as $dwu_handle ) {
							echo '<option value="' . $dwu_handle . '">' . $dwu_handle . '</option>';
						}
					echo '</select></div>';
				}
	
				do_action( 'woocommerce_upi_form_end', $this->id );
	
				echo '<div class="clear"></div></fieldset>'; ?>
				<script type="text/javascript">
					(function($){
						if( $('#dwu-handle').length ) {
							$("#dwu-handle").selectize({
								create: <?php echo apply_filters( 'dwu_valid_order_status_for_note', 'false' ); ?>,
							});
						}
					})(jQuery);
				</script>
				<?php
			}
		}

		/**
		 * Validate UPI ID field
		**/
		public function validate_fields() {
			if ( empty( $_POST[ 'customer_dwu_address' ] ) && in_array( $this->upi_address, array( 'show', 'show_handle' ) ) && $this->require_upi === 'yes' ) {
				wc_add_notice( __( 'Please enter your <strong>UPI Address</strong>!', 'dew-upi-qr-code' ), 'error' );
				return false;
			}

			if ( empty( $_POST[ 'customer_dwu_handle' ] ) && $this->upi_address === 'show_handle' && $this->require_upi === 'yes' ) {
				wc_add_notice( __( 'Please select your <strong>UPI Handle</strong>!', 'dew-upi-qr-code' ), 'error' );
				return false;
			}

			$regex = "/^[_a-z0-9-]+(\.[_a-z0-9-]+)*@[a-z0-9-]+(\.[a-z0-9-]+)*$/i";
			if ( $this->upi_address === 'show_handle' ) {
				$regex = "/^[_a-z0-9-]+(\.[_a-z0-9-]+)*$/i";
			}
			if ( ! preg_match( $regex, sanitize_text_field( $_POST[ 'customer_dwu_address' ] ) ) && in_array( $this->upi_address, array( 'show', 'show_handle' ) ) && $this->require_upi === 'yes' ) {
				wc_add_notice( __( 'Please enter a <strong>valid UPI Address</strong>!', 'dew-upi-qr-code' ), 'error' );
				return false;
			}
			return true;
		}

		/**
		 * Custom CSS and JS
		**/
		public function dwu_payment_scripts() {
			// if our payment gateway is disabled, we do not have to enqueue JS too
			if( 'no' === $this->enabled ) {
				return;
			}

			$ver = DEW_WOO_UPI_VERSION;
			if( defined( 'DEW_WOO_UPI_ENABLE_DEBUG' ) ) {
				$ver = time();
			}
			
			if ( is_checkout() ) {
				wp_enqueue_style( 'dwu-selectize', DEW_WOO_UPI_URL . '/css/selectize.min.css', array(), $ver );
				wp_enqueue_script( 'dwu-selectize', DEW_WOO_UPI_URL . '/js/selectize.min.js', array( 'jquery' ), $ver, false );
			}
		
			wp_register_style( 'dwu-jquery-confirm', DEW_WOO_UPI_URL . '/css/jquery-confirm.min.css', array(), $ver );
			wp_register_style( 'dwu-qr-code', DEW_WOO_UPI_URL . '/css/upi.min.css', array( 'dwu-jquery-confirm' ), $ver );
			
			wp_register_script( 'dwu-qr-code', DEW_WOO_UPI_URL . '/js/easy.qrcode.min.js', array( 'jquery' ), $ver, true );
			wp_register_script( 'dwu-jquery-confirm', DEW_WOO_UPI_URL . '/js/jquery-confirm.min.js', array( 'jquery' ), $ver, true );
			wp_register_script( 'dwu', DEW_WOO_UPI_URL . '/js/upi.min.js', array( 'jquery', 'dwu-qr-code', 'dwu-jquery-confirm' ), $ver, true );
		}

		/**
		 * Process the payment and return the result
		 *
		 * @param int $order_id
		 * @return array
		**/
		public function process_payment( $order_id ) {
			$order = wc_get_order( $order_id );
			$dwu_upi_address = ! empty( $_POST[ 'customer_dwu_address' ] ) ? sanitize_text_field( $_POST[ 'customer_dwu_address' ] ) : '';
			$dwu_upi_address = ! empty( $_POST[ 'customer_dwu_handle' ] ) ? $dwu_upi_address . sanitize_text_field( $_POST[ 'customer_dwu_handle' ] ) : $dwu_upi_address;
			$message = __( 'Awaiting UPI Payment!', 'dew-upi-qr-code' );

			// Mark as pending (we're awaiting the payment)
			$order->update_status( $this->default_status );

			// update meta
			update_post_meta( $order->get_id(), '_dwu_order_paid', 'no' );

			if ( ! empty( $dwu_upi_address ) ) {
				update_post_meta( $order->get_id(), '_transaction_id', preg_replace( "/\s+/", "", $dwu_upi_address ) );
				$message .= '<br />' . sprintf( __( 'UPI ID: %s', 'dew-upi-qr-code' ), preg_replace( "/\s+/", "", $upi_address ) );
			}

			// add some order notes
			$order->add_order_note( apply_filters( 'dwu_process_payment_note', $message, $order ), false );

			if ( apply_filters( 'dwu_payment_empty_cart', false ) ) {
				// Empty cart
				WC()->cart->empty_cart();
			}

			do_action( 'dwu_after_payment_init', $order_id, $order );

			// check plugin settings
			if( 'yes' === $this->enabled && 'yes' === $this->email_enabled && $order->has_status( 'pending' ) ) {
				// Get an instance of the WC_Email_Customer_On_Hold_Order object
				$wc_email = WC()->mailer()->get_emails()['WC_Email_Customer_On_Hold_Order'];
				
				// Send "New Email" notification
				$wc_email->trigger( $order_id );
			}

			// Return redirect
			return array(
				'result' 	=> 'success',
				'redirect'	=> apply_filters( 'dwu_process_payment_redirect', $order->get_checkout_payment_url( true ), $order )
			);
		}

		/**
		 * Show UPI details as html output
		 *
		 * @param WC_Order $order_id Order id.
		 * @return string
		**/
		public function dwu_generate_qr_code( $order_id ) {
			// get order object from id
			$order = wc_get_order( $order_id );
			$total = apply_filters( 'dwu_order_total_amount', $order->get_total(), $order );

			// enqueue required css & js files
			wp_enqueue_style( 'dwu-jquery-confirm' );
			wp_enqueue_style( 'dwu-qr-code' );
			wp_enqueue_script( 'dwu-jquery-confirm' );
			wp_enqueue_script( 'dwu-qr-code' );
			wp_enqueue_script( 'dwu' );
			
			// add localize scripts
			wp_localize_script( 'dwu', 'dwu_params',
				array( 
					'ajaxurl'           => admin_url( 'admin-ajax.php' ),
					'order_id'          => $order_id,
					'order_amount'      => $total,
					'order_key'         => $order->get_order_key(),
					'order_number'      => htmlentities( $order->get_order_number() ),
					'confirm_message'   => $this->confirm_message,
					'transaction_text'  => apply_filters( 'dwu_transaction_id_text', __( 'Enter 12-digit Transaction/UTR/Reference ID:', 'dew-upi-qr-code' ) ),
					'processing_text'   => apply_filters( 'dwu_payment_processing_text', __( 'Do not close or refresh this window and Please wait while we are processing your request...', 'dew-upi-qr-code' ) ),
					'callback_url'      => add_query_arg( array( 'dwu-wc-api' => 'dwu-payment' ), trailingslashit( get_home_url() ) ),
					'payment_url'       => $order->get_checkout_payment_url(),
					'cancel_url'        => apply_filters( 'dwu_payment_cancel_url', wc_get_checkout_url(), $this->get_return_url( $order ), $order ),
					'payment_status'    => $this->payment_status,
					'transaction_id'    => $this->transaction_id,
					'app_theme'         => $this->app_theme,
					'mcc'               => $this->mcc ? $this->mcc : 5411,
					'tran_id_length'    => apply_filters( 'dwu_transaction_id_length', 12 ),
					'prevent_reload'    => apply_filters( 'dwu_enable_payment_reload', true ),
					'intent_interval'   => apply_filters( 'dwu_auto_open_interval', 1000 ),
					'btn_show_interval' => apply_filters( 'dwu_button_show_interval', 30000 ),
					'payee_vpa'         => htmlentities( strtolower( $this->vpa ) ),
					'payee_name'        => preg_replace('/[^\p{L}\p{N}\s]/u', '', $this->name ),
					'is_mobile'         => ( wp_is_mobile() ) ? 'yes' : 'no',
					'app_version'       => DEW_WOO_UPI_VERSION,
				)
			);

			// add html output on payment endpoint
			if ( 'yes' === $this->enabled && $order->needs_payment() === true && $order->has_status( $this->default_status ) && ! empty( $this->vpa ) ) { ?>
			    <section class="woo-upi-section">
					<div class="dwu-info">
						<h6 class="dwu-waiting-text"><?php _e( 'Please wait and don\'t press back or refresh this page while we are processing your payment.', 'dew-upi-qr-code' ); ?></h6>
						<button id="dwu-processing" class="btn button" disabled="disabled"><?php _e( 'Waiting for payment...', 'dew-upi-qr-code' ); ?></button>
						<?php do_action( 'dwu_after_before_title', $order ); ?>
						<div class="dwu-buttons" style="display: none;">
							<button id="dwu-confirm-payment" class="btn button" data-theme="<?php echo apply_filters( 'dwu_payment_dialog_theme', 'blue' ); ?>"><?php echo esc_html( apply_filters( 'dwu_payment_button_text', $this->pay_button ) ); ?></button>
							<?php if ( apply_filters( 'dwu_show_cancel_button', true ) ) { ?>
								<button id="dwu-cancel-payment" class="btn button"><?php _e( 'Cancel', 'dew-upi-qr-code' ); ?></button>
							<?php } ?>
						</div>
						<?php if ( apply_filters( 'dwu_show_choose_payment_method', true ) ) { ?>
							<div style="margin-top: 5px;"><span class="dwu-return-link"><?php _e( 'Choose another payment method', 'dew-upi-qr-code' ); ?></span></div>
						<?php } ?>
						<?php do_action( 'dwu_after_payment_buttons', $order ); ?>
				        <div id="js_qrcode">
							<?php if ( apply_filters( 'dwu_show_upi_id', true ) ) { ?>
								<div id="dwu-upi-id" class="dwu-upi-id" title="<?php _e( 'Please check the UPI ID again before making the payment.', 'dew-upi-qr-code' ); ?>"><?php _e( 'MERCHANT UPI ID:', 'dew-upi-qr-code' ); ?> <span id="dwu-upi-id-raw-<?php echo $this->app_theme; ?>"><?php echo htmlentities( strtoupper( $this->vpa ) ); ?></span></div>
							<?php } ?>
							<?php  if ( wp_is_mobile() && $this->qrcode_mobile === 'no' ) {
								$style = ' style="display: none;"';
							} ?>
							<div id="dwu-qrcode"<?php echo isset( $style ) ? $style : ''; ?>><?php do_action( 'dwu_after_qr_code', $order ); ?></div>
							<?php if ( apply_filters( 'dwu_show_order_total', true ) ) { ?>
								<div id="dwu-order-total" class="dwu-order-total"><?php _e( 'Amount to be Paid:', 'dwu-upi-qr-code' ); ?> <span id="dwu-order-total-amount-<?php echo $this->app_theme; ?>">₹<?php echo $total; ?></span></div>
							<?php } ?>
							<?php if ( wp_is_mobile() && apply_filters( 'dwu_show_direct_pay_button', true ) ) { ?>
								<?php if ( stripos( $_SERVER['HTTP_USER_AGENT'], "iPhone" ) === false ) { ?>
									<div class="jconfirm-buttons" style="padding-bottom: 5px;">
										<button type="button" id="upi-pay" class="btn btn-dark btn-upi-pay"><?php echo apply_filters( 'dwu_upi_direct_pay_text', __( 'Click here to pay using a UPI App', 'dwu-upi-qr-code' ) ); ?></button>
									</div>
								<?php } ?>
							<?php } ?>
							<?php if ( wp_is_mobile() && apply_filters( 'dwu_show_upi_id_copy_button', false ) ) { ?>
								<div class="jconfirm-buttons" style="padding-bottom: 5px;">
									<button type="button" id="upi-copy" class="btn btn-dark btn-upi-copy"><?php echo apply_filters( 'dwu_upi_copy_text', __( 'Click here to copy UPI ID', 'dwu-upi-qr-code' ) ); ?></button>
								</div>
							<?php } ?>
							<?php if ( wp_is_mobile() && apply_filters( 'dwu_show_download_qrcode_button', false ) ) { ?>
							    <div class="jconfirm-buttons" style="padding-bottom: 5px;">
					    	        <button type="button" id="upi-download" class="btn btn-dark btn-upi-download"><?php echo apply_filters( 'dwu_donwload_button_text', __( 'Download QR Code', 'dwu-upi-qr-code' ) ); ?></button>
					    	    </div>
							<?php } ?>
							<?php if ( apply_filters( 'dwu_show_upi_id', true ) && get_post_meta( $order->get_id(), '_transaction_id', true ) ) { ?>
								<div id="dwu-upi-payer-id" class="dwu-upi-id" title="<?php _e( 'You are paying the required order amount using this UPI ID.', 'dwu-upi-qr-code' ); ?>"><?php _e( 'Your UPI ID:', 'dwu-upi-qr-code' ); ?> <span id="dwu-upi-id-raw-<?php echo $this->app_theme; ?>"><?php echo htmlentities( strtoupper( get_post_meta( $order->get_id(), '_transaction_id', true ) ) ); ?></span></div>
							<?php } ?>
							<?php if ( apply_filters( 'dwu_show_description', true ) ) { ?>
								<div id="dwu-description" class="dwu-description">
									<?php if( wp_is_mobile() ) { 
										echo wptexturize( $this->instructions_mobile );
									} else {
										echo wptexturize( $this->instructions ); 
									} ?>
									<?php if ( apply_filters( 'dwu_show_upi_help_text', true ) ) { ?>
										<span class="dwu-help-text-<?php echo $this->app_theme; ?>"><?php printf( __( 'At the time of payment, please enter "<strong>ORDER ID %s</strong>" in UPI App Payment Screen as message for future reference.', 'dwu-upi-qr-code' ), $order_id ); ?></span>
									<?php } ?>
								</div>
							<?php } ?>
					    </div>
						<div id="payment-success-container" style="display: none;"></div>
					</div>
				</section><?php
			}
		}

		/**
		 * Process payment verification.
		**/
		public function dwu_capture_payment() {
			// get order id
			if ( ( 'POST' !== $_SERVER['REQUEST_METHOD'] ) || ! isset( $_GET['dwu-wc-api'] ) || ( 'dwu-payment' !== $_GET['dwu-wc-api'] ) ) {
				return;
			}
			// generate order
			$order_id = wc_get_order_id_by_order_key( sanitize_text_field( $_POST['wc_order_key'] ) );
			$order = wc_get_order( $order_id );
			// check if it an order
			if ( is_a( $order, 'WC_Order' ) ) {
				$order->update_status( apply_filters( 'dwu_capture_payment_order_status', $this->payment_status ) );
				// set upi id as trnsaction id
				if ( isset( $_POST['wc_transaction_id'] ) && !empty( $_POST['wc_transaction_id'] ) ) {
					update_post_meta( $order->get_id(), '_transaction_id', sanitize_text_field( $_POST['wc_transaction_id'] ) );
				}

				// reduce stock level
				wc_reduce_stock_levels( $order->get_id() );

				// check order if it actually needs payment
				if( in_array( $this->payment_status, apply_filters( 'dwu_valid_order_status_for_note', array( 'pending', 'on-hold' ) ) ) ) {
					// set order note
					$order->add_order_note( __( 'Payment primarily completed. Needs shop owner\'s verification.', 'dwu-upi-qr-code' ), false );
				}

				// update post meta
				update_post_meta( $order->get_id(), '_dwu_order_paid', 'yes' );

				// add custom actions 
				do_action( 'dwu_after_payment_verify', $order->get_id(), $order );

				// create redirect
				wp_safe_redirect( apply_filters( 'dwu_payment_redirect_url', $this->get_return_url( $order ), $order ) );
				exit;
			} else {
				// create redirect
				$title = __( 'Order can\'t be found against this Order ID. If the money debited from your account, please Contact with Site Administrator for further action.', 'dwu-upi-qr-code' );
                        
				wp_die( $title, get_bloginfo( 'name' ) );
				exit;
			}
		}

		/**
		 * Customize the WC emails template.
		 *
		 * @access public
		 * @param string $formated_subject
		 * @param WC_Order $order
		 * @param object $object
		**/
		public function dwu_email_subject_pending_order( $formated_subject, $order, $object ) {
			// We exit for 'order-accepted' custom order status
			if ( $this->id === $order->get_payment_method() && 'yes' === $this->enabled && $order->has_status( 'pending' ) ) {
				return $object->format_string( $this->email_subject );
			}
			return $formated_subject;
		}

		/**
		 * Customize the WC emails template.
		 *
		 * @access public
		 * @param string $formated_subject
		 * @param WC_Order $order
		 * @param object $object
		**/
		public function dwu_email_heading_pending_order( $formated_heading, $order, $object ) {
			// We exit for 'order-accepted' custom order status
			if ( $this->id === $order->get_payment_method() && 'yes' === $this->enabled && $order->has_status( 'pending' ) ) {
				return $object->format_string( $this->email_heading );
			}
			return $formated_heading;
		}
		/**
		 * Customize the WC emails template.
		 *
		 * @access public
		 * @param string $formated_subject
		 * @param WC_Order $order
		 * @param object $object
		**/
		public function dwu_email_additional_content_pending_order( $formated_additional_content, $order, $object ) {
			// We exit for 'order-accepted' custom order status
			if( $this->id === $order->get_payment_method() && 'yes' === $this->enabled && $order->has_status( 'pending' ) ) {
				return $object->format_string( str_replace( '{upi_pay_link}', $order->get_checkout_payment_url( true ), $this->additional_content ) );
			}
			return $formated_additional_content;
		}

		/**
		 * Custom order received text.
		 *
		 * @param string   $text Default text.
		 * @param WC_Order $order Order data.
		 * @return string
		**/
		public function dwu_order_received_text( $text, $order ) {
			if ( $this->id === $order->get_payment_method() && ! empty( $this->thank_you ) ) {
				return esc_html( $this->thank_you );
			}
			return $text;
		}

		/**
		 * Custom checkout URL.
		 *
		 * @param string   $url Default URL.
		 * @param WC_Order $order Order data.
		 * @return string
		**/
		public function dwu_custom_checkout_url( $url, $order ) {
			if ( $this->id === $order->get_payment_method() && ( ( $order->has_status( 'on-hold' ) && $this->default_status === 'on-hold' ) || ( $order->has_status( 'pending' ) && apply_filters( 'dwu_custom_checkout_url', false ) ) ) ) {
				return esc_url( remove_query_arg( 'pay_for_order', $url ) );
			}
			return $url;
		}

		/**
		 * Add content to the WC emails.
		 *
		 * @access public
		 * @param WC_Order $order
		 * @param bool     $sent_to_admin
		 * @param bool     $plain_text
		 * @param object   $email
		**/
		public function dwu_email_instructions( $order, $sent_to_admin, $plain_text, $email ) {
			// check upi gateway name
			if ( 'yes' === $this->enabled && 'yes' === $this->email_enabled && ! empty( $this->additional_content ) && ! $sent_to_admin && $this->id === $order->get_payment_method() && $order->has_status( 'on-hold' ) ) {
				echo wpautop( wptexturize( str_replace( '{dwu_upi_pay_link}', $order->get_checkout_payment_url( true ), $this->additional_content ) ) ) . PHP_EOL;
			}
		}

		/**
		 * Allows payment for orders with on-hold status.
		 *
		 * @param string   $statuses  Default status.
		 * @param WC_Order $order     Order data.
		 * @return string
		**/
		public function dwu_on_hold_payment( $statuses, $order ) {
			if ( $this->id === $order->get_payment_method() && $order->has_status( 'on-hold' ) && $order->get_meta( '_dwu_order_paid', true ) !== 'yes' && $this->default_status === 'on-hold' ) {
				$statuses[] = 'on-hold';
			}
			return $statuses;
		}

		/**
		 * Disable UPI from available payment gateways.
		 *
		 * @param string   $available_gateways  Available payment gateways.
		 * @return array
		**/
		public function dwu_disable_gateway( $available_gateways ) {
			if ( empty( $this->vpa ) || ( wp_is_mobile() && $this->hide_on_mobile === 'yes' ) ) {
				unset( $available_gateways['dew-wc-upi'] );
			}
			return $available_gateways;
		}
	}
}