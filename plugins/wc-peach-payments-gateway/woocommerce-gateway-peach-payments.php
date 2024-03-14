<?php
/*
 * Plugin Name: WooCommerce Peach Payments Gateway
 * Plugin URI: http://woothemes.com/products/peach-payments/
 * Description: A payment gateway for <a href="https://www.peachpayments.com/">Peach Payments</a>.
 * Author: Peach Payments
 * Text Domain: woocommerce-gateway-peach-payments
 * Author URI: https://peachpayments.com
 * Version: 3.2.3
 * Requires at least: 4.7
 * Tested up to: 6.3.1
 */
 
if ( ! defined( 'ABSPATH' ) ) {
	exit;
}

if( ! function_exists('get_plugin_data') ){
	require_once( ABSPATH . 'wp-admin/includes/plugin.php' );
}

$plugin_data = get_plugin_data( __FILE__ );

$version = explode('.', phpversion());
define( 'WC_PEACH_PHP', $version[0]);
define( 'WC_PEACH_VER', $plugin_data['Version'] );
define( 'PMPRO_PEACH_API_VERSION', $plugin_data['Version']);
define( 'WC_PEACH_MIN_WC_VER', '5.7' );
define( 'WC_PEACH_PLUGIN_URL', untrailingslashit( plugins_url( basename( plugin_dir_path( __FILE__ ) ), basename( __FILE__ ) ) ) );
define( 'WC_PEACH_PLUGIN_PATH', untrailingslashit( plugin_dir_path( __FILE__ ) ) );
define( 'WC_PEACH_SITE_URL', get_site_url().'/' );
define( 'WC_PEACH_README_URL', 'https://plugins.svn.wordpress.org/wc-peach-payments-gateway/trunk/README.txt' );
define( 'PEACH_FILE', 'wc-peach-payments-gateway/woocommerce-gateway-peach-payments.php' );

//Express Checkout for WC Integration
if ( is_plugin_active( 'express-checkout-for-woocommerce/express-checkout-for-woocommerce.php' ) ) {
	if(!isset($_COOKIE['PeachExpressCheckoutPlugin']) || $_COOKIE['PeachExpressCheckoutPlugin'] == ''){
		setcookie('PeachExpressCheckoutPlugin', 'dontsave', time() + (86400 * 30), "/");
	}
}
if(!isset($_COOKIE['PeachManualCheckout']) || $_COOKIE['PeachManualCheckout'] == ''){
	setcookie('PeachManualCheckout', 'dontsave', time() + (86400 * 30), "/");
}

add_action( 'plugins_loaded', 'woocommerce_gateway_peach_init' );

//Paid Membership Pro Integration
if ( is_plugin_active( 'paid-memberships-pro/paid-memberships-pro.php' ) ) {
	//require_once(WC_PEACH_PLUGIN_PATH . '/classes/peach-pmprogateway.php');
}

if ( is_plugin_active( 'wp-graphql/wp-graphql.php' ) ) {
	//add_action( 'graphql_register_types', 'peach_extend_wpgraphql_schema');
}

function woocommerce_gateway_peach() {
	
	add_filter( 'woocommerce_payment_gateways', 'peachpayments_add_gateway_class' );
	function peachpayments_add_gateway_class( $gateways ) {
		$gateways[] = 'WC_Peach_Payments';
		return $gateways;
	}
	
	class WC_Peach_Payments extends WC_Payment_Gateway {

		public function __construct() {
			require_once ( WC_PEACH_PLUGIN_PATH . '/classes/pluginSupport.php');
			require_once ( WC_PEACH_PLUGIN_PATH . '/classes/embeddedCheckout.php');
			
			$this->peach_statusses = wc_get_order_statuses();
			
			$this->id = 'peach-payments';
			$this->icon = WC_PEACH_PLUGIN_URL .'/assets/images/Peach_Payments_Primary_logo.png';
			$this->has_fields = true;
			$this->method_title = 'Peach Payments';
			
			if(null !== $this->get_option( 'title' ) && $this->get_option( 'title' ) != ''){
				$this->method_title = $this->get_option( 'title' );
			}
			
			$this->method_description = 'Take payments via card or checkout.';
		
			$this->supports = array(
			'subscriptions',
			'products',
			'refunds',
			'subscription_cancellation',
			'subscription_reactivation',
			'subscription_suspension',
			'subscription_amount_changes',
			'subscription_payment_method_change',
			'subscription_payment_method_change_admin',
			'subscription_date_changes',
			'multiple_subscriptions',
			'pre-orders'
			);
		
			$this->init_form_fields();
			$this->init_settings();
			$this->title = $this->get_option( 'title' );
			$this->description = $this->get_option( 'description' );
			$this->card_storage = $this->get_option('card_storage');
			$this->embed_payments = $this->get_option('embed_payments');
			$this->embed_clientid = $this->get_option('embed_clientid');
			$this->embed_clientsecret = $this->get_option('embed_clientsecret');
			$this->embed_merchantid = $this->get_option('embed_merchantid');
			$this->enabled = $this->get_option( 'enabled' );
			$this->checkout_methods = $this->get_option( 'checkout_methods' );
			$this->checkout_methods_select = $this->get_option( 'checkout_methods_select' );
			$this->consolidated_label = $this->get_option( 'consolidated_label' );
			$this->consolidated_label_logos = $this->get_option( 'consolidated_label_logos' );
			$this->transactionmode = $this->get_option( 'transaction_mode' );
			$this->secrettoken = $this->get_option( 'secret' );
			
			if($this->transactionmode == 'INTEGRATOR_TEST'){
				$this->process_checkout_url = 'https://eu-test.oppwa.com';
				$this->request_checkout_url = 'https://testsecure.peachpayments.com/checkout';
				$this->request_status_url = 'https://testapi.peachpayments.com/v1/checkout/status';
				$this->request_pre_status_url = 'https://eu-test.oppwa.com/v1/payments';
				$this->request_refund_url = 'https://testapi.peachpayments.com/v1/checkout/refund';
				$this->ssl_verifypeer = false;
				$this->success_code = '000.100.110';
			}else{
				$this->process_checkout_url = 'https://eu-prod.oppwa.com';
				$this->request_checkout_url = 'https://secure.peachpayments.com/checkout';
				$this->request_status_url = 'https://api.peachpayments.com/v1/checkout/status';
				$this->request_pre_status_url = 'https://eu-prod.oppwa.com/v1/payments';
				$this->request_refund_url = 'https://api.peachpayments.com/v1/checkout/refund';
				$this->ssl_verifypeer = true;
				$this->success_code = '000.000.000';
			}
			
			$this->completestatus = $this->get_option( 'auto_complete' );
			$this->accesstoken = $this->get_option( 'access_token' );
			$this->secureid = $this->get_option( 'channel_3ds' );
			$this->recurringid = $this->get_option( 'channel' );
			$this->subscribeProds = false;
			$this->mixedBasket = false;
			$this->orderids = $this->get_option( 'orderids' );
			$this->checkout_page_url = wc_get_checkout_url();
			$this->order_received_page_url = $this->checkout_page_url.get_option('woocommerce_checkout_order_received_endpoint');
			$this->order_pay_page_url = $this->checkout_page_url.get_option('woocommerce_checkout_pay_endpoint');
			
			$this->card_webhook_key = $this->get_option( 'card_webhook_key' );
			
			$this->peach_order_status = $this->get_option( 'peach_order_status' );
			
			$this->logger_info_settings = array(
				'transactionmode' => $this->transactionmode,
				'secrettoken' => $this->secrettoken,
				'accesstoken' => $this->accesstoken,
				'secureid' => $this->secureid,
				'recurringid' => $this->recurringid,
				'card_webhook_key' => $this->card_webhook_key,
				'completestatus' => $this->completestatus,
				'peach_order_status' => $this->peach_order_status
			);
			
			add_action( 'woocommerce_after_checkout_validation', array( $this, 'peach_validate_checkout' ), 10, 2);
			
			add_action( 'woocommerce_scheduled_subscription_payment_' . $this->id, array( $this, 'scheduled_subscription_payment' ), 10, 2  );
			add_action( 'woocommerce_scheduled_subscription_trial_end', array( $this, 'peach_trial_end' ), 10, 1 ); 
			add_action( 'woocommerce_thankyou', array( $this, 'peach_thankyou' ), 10, 1 ); 
			add_action( 'woocommerce_update_options_payment_gateways_' . $this->id, array( $this, 'process_admin_options' ) );
			add_action( 'woocommerce_receipt_' . $this->id, array( $this, 'receipt_page' ) );
			add_action( 'woocommerce_api_' . strtolower( get_class( $this ) ), array( $this, 'process_payment' ) );
			
			add_action( 'woocommerce_api_wc_payon_webhook_peach_payments', array( $this, 'wc_payon_webhook_peach_payments_handler' ) );
			add_action( 'woocommerce_api_wc_switch_webhook_peach_payments', array( $this, 'switch_payment_webhook_response' ) );
			add_action( 'woocommerce_api_wc_switch_peach_payments', array( $this, 'switch_payment_response' ) );
			
			add_action( 'woocommerce_api_wc_peach_payments', array( $this, 'process_payment_status' ) );
			
			add_action( 'in_plugin_update_message-' . PEACH_FILE, array( $this, 'peach_in_plugin_update_message' ) );
			
			if(!empty($this->checkout_methods_select)){
				if(!in_array('card',$this->checkout_methods_select)){
					setcookie('PeachManualCheckout', 'other', time() + (86400 * 30), "/");						
				}else{
					if(isset($_COOKIE['PeachManualCheckout']) && $_COOKIE['PeachManualCheckout'] == 'other'){
						if(!in_array('hosted',$this->checkout_methods_select)){
							setcookie('PeachManualCheckout', 'dontsave', time() + (86400 * 30), "/");
						}
					}
				}
			}
			
			if($this->embed_payments == 'yes'){
				if($this->transactionmode == 'INTEGRATOR_TEST'){
					wp_enqueue_script('peach_embed_checkout_test_js','https://sandbox-checkout.peachpayments.com/js/checkout.js');
				}else{
					wp_enqueue_script('peach_embed_checkout_live_js','https://checkout.peachpayments.com/js/checkout.js');
				}
			}
				
		}
	
		//Plugin options
		public function init_form_fields(){
			$this->form_fields = array(
				'enabled' => array(
					'title'       => 'Enable/Disable',
					'label'       => 'Enable Peach Payments Gateway',
					'type'        => 'checkbox',
					'description' => '',
					'default'     => 'no'
				),
				'title' => array(
					'title'       => 'Title',
					'type'        => 'text',
					'description' => 'This controls the title which the user sees during checkout.',
					'default'     => 'Peach Payments',
					'desc_tip'    => true,
					'required'    => true,
				),
				'description' => array(
					'title'       => 'Description',
					'type'        => 'textarea',
					'description' => 'This controls the description which the user sees during checkout.',
					'default'     => 'Pay with your credit card via our super-cool payment gateway.',
				),
				'checkout_methods'            => array(
					'title'       => __( 'Payment Methods', 'woocommerce-gateway-peach-payments' ),
					'type'        => 'multiselect',
					'description' => __( 'This option were disabled in version 3.1.8 of this plugin.' ),
					'options'     => array(
						'VISA'   => 'VISA',
						'MASTER' => 'Mastercard',
						'CAPITEC' => 'Capitec Pay',
						'AMEX'   => 'American Express',
						'DINERS' => 'Diners Club',
						'EFTSECURE'   => 'EFT Secure',
						'MOBICRED' => 'Mobicred',
						'1VOUCHER' => '1Voucher',
						'SCANTOPAY'   => 'Scan to Pay',
						'APPLE'   => 'ApplePay',
						'PAYPAL'   => 'PayPal',
						'MPESA'   => 'MPESA',
						'PAYFLEX'   => 'Payflex',
						'ZEROPAY'   => 'ZeroPay',	
						'INSTANTEFT BY PEACH' => 'InstantEFT',
						'BLINKBYEMTEL' => 'Blink by EMTEL',
						'MCBJUICE' => 'MCB Juice'
					),
					'default'     => array('VISA','MASTER', 'CAPITEC', 'EFTSECURE', 'MOBICRED', 'SCANTOPAY'),
					'class'       => 'chosen_select checkout_methods',
					'css'         => 'width: 450px;',
				),
				'checkout_methods_select'            => array(
					'title'       => __( 'Checkout Options', 'woocommerce-gateway-peach-payments' ),
					'type'        => 'multiselect',
					'description' => __( 'Which payment options should display on the front-end? Hold down "CTRL" key to select multiples.' ),
					'options'     => array(
						'card'   => 'Card Payments',
						'hosted' => 'Consolidated Payments'
					),
					'default'     => array('card','hosted'),
					'class'       => 'chosen_select checkout_methods_select',
					'css'         => 'width: 450px;',
					'required'    => true,
				),
				'consolidated_label' => array(
					'title'       => __('Consolidated Payments Label'),
					'type'        => 'text',
					'description' => __( 'Front-end display label for consolidated payments.' ),
					'default'     => __( 'More payment types' ),
				),
				'consolidated_label_logos'            => array(
					'title'       => __( 'Consolidated Payments Logos', 'woocommerce-gateway-peach-payments' ),
					'type'        => 'multiselect',
					'description' => __( 'Which logos should display on front-end for consolidated payments option.' ),
					'options'     => array(
						'VISA'   => 'VISA',
						'MASTER' => 'Mastercard',
						'CAPITECPAY' => 'Capitec Pay',
						'AMEX'   => 'American Express',
						'DINERS' => 'Diners Club',
						'EFTSECURE'   => 'EFT Secure',
						'MOBICRED' => 'Mobicred',
						'1VOUCHER' => '1Voucher',
						'SCANTOPAY'   => 'Scan to Pay',
						'APPLE'   => 'ApplePay',
						'PAYPAL'   => 'PayPal',
						'MPESA'   => 'MPESA',
						'PAYFLEX'   => 'Payflex',
						'ZEROPAY'   => 'ZeroPay',
						'INSTANTEFT' => 'InstantEFT',
						'BLINKBYEMTEL' => 'Blink by EMTEL',
						'MCBJUICE' => 'MCB Juice'
					),
					'default'     => array('VISA','MASTER', 'CAPITEC', 'EFTSECURE'),
					'class'       => 'chosen_select consolidated_label_logos',
					'css'         => 'width: 450px;',
				),
				'embed_payments' => array(
					'title'       => 'Enable Embedded Checkout',
					'label'       => 'Only supports <a href="https://developer.peachpayments.com/docs/checkout-embedded#known-limitations" target="_blank" rel="nofollow">certain payment methods</a>.',
					'type'        => 'checkbox',
					'description' => 'Embedded Checkout enables the Peach Payments hosted payments page to load within your website without any redirects.',
					'default'     => 'no',
					'desc_tip'    => true,
				),
				'embed_clientid' => array(
					'title'       => __( 'Client ID', 'woocommerce-gateway-peach-payments' ),
					'type'        => 'text',
					'description' => 'This can be found in the <a href="https://dashboard.peachpayments.com/" target="_blank" rel="nofollow">Peach Payments dashboard</a> under Checkout > Embedded Checkout.'
				),
				'embed_clientsecret' => array(
					'title'       => __( 'Client Secret', 'woocommerce-gateway-peach-payments' ),
					'type'        => 'text',
					'description' => 'This can be found in the <a href="https://dashboard.peachpayments.com/" target="_blank" rel="nofollow">Peach Payments dashboard</a> under Checkout > Embedded Checkout.'
				),
				'embed_merchantid' => array(
					'title'       => __( 'Merchant ID', 'woocommerce-gateway-peach-payments' ),
					'type'        => 'text',
					'description' => 'This can be found in the <a href="https://dashboard.peachpayments.com/" target="_blank" rel="nofollow">Peach Payments dashboard</a> under Checkout > Embedded Checkout.'
				),
				'card_storage' => array(
					'title'       => 'Card Storage',
					'label'       => 'Enable Card Storage',
					'type'        => 'checkbox',
					'description' => 'Allow customers to store cards against their account.',
					'default'     => 'no',
					'desc_tip'    => true,
				),
				'orderids' => array(
					'title'       => 'Order IDs',
					'label'       => 'Always use WooCommerce order IDs',
					'type'        => 'checkbox',
					'description' => 'Overwrite any custom generated order IDs by third party plugins e.g. sequentional order IDs.',
					'default'     => 'yes',
					'desc_tip'    => true,
				),
				'auto_complete'     => array(
					'title'       => __( 'Auto Complete', 'woocommerce-gateway-peach-payments' ),
					'label'       => __( 'Enable Auto Complete for Virtual/Downloadable products.', 'woocommerce-gateway-peach-payments' ),
					'type'        => 'checkbox',
					'description' => __( 'Allow Peach Payments to update order status for successfull payments of Virtual/Downloadable products to "Completed".' ),
					'default'     => 'no',
				),
				'transaction_mode' => array(
					'title'       => __( 'Transaction Mode', 'woocommerce-gateway-peach-payments' ),
					'type'        => 'select',
					'description' => __( 'Set your gateway to LIVE when you are ready.', 'woocommerce-gateway-peach-payments' ),
					'default'     => 'INTEGRATOR_TEST',
					'options'     => array(
						'INTEGRATOR_TEST' => 'Integrator Test',
						'CONNECTOR_TEST'  => 'Connector Test',
						'LIVE'            => 'Live',
					),
				),
				'peach_order_status' => array(
					'title'       => __( 'Order Status', 'woocommerce-gateway-peach-payments' ),
					'type'        => 'select',
					'description' => __( 'Choose what the order successfull status should be.', 'woocommerce-gateway-peach-payments' ),
					'default'     => 'wc-processing',
					'options'     => $this->peach_statusses,
				),
				'access_token' => array(
					'title'       => 'Access Token',
					'type'        => 'text',
					'description' => 'This is the key generated within the Peach Payments Console under Development > Access Token.'
				),
				'secret' => array(
					'title'       => 'Secret Token',
					'type'        => 'text',
					'description' => 'This is the key generated within the Peach Payments Dashboard (Only if non-card payment method types have been enabled)'
				),
				'channel_3ds' => array(
					'title'       => '3DSecure Entity ID',
					'type'        => 'text',
					'description' => 'The Entity ID that you received from Peach Payments.'
				),
				'channel'          => array(
					'title'       => __( 'Recurring Entity ID', 'woocommerce-gateway-peach-payments' ),
					'type'        => 'text',
					'description' => __( 'This field is only required if you want to receive recurring payments. You will receive this from Peach Payments.', 'woocommerce-gateway-peach-payments' ),
					'default'     => '',
				),
				'card_webhook_key' => array(
					'title'       => 'Card Webhook Decryption key',
					'type'        => 'text',
					'description' => 'You’ll receive this key from Peach Payments after your webhook is enabled.<br>To enable the webhook, please email <a href="mailto:support@peachpayments.com">support@peachpayments.com</a> to set up <a href="'.WC_PEACH_SITE_URL.'" target="_blank" rel="nofollow">'.WC_PEACH_SITE_URL.'</a> on your account.'
				)
			);
	
		}
			
		//Back-end output
		public function admin_options() {
			$cardSyncBtnTxt = 'Sync users saved cards';
			$rollbackBtnTxt = 'Rollback to Version 3.1.7';
			$action = 'peach_core_version_rollback';
			$url = add_query_arg( array(
				'action'  => $action,
				'nonce'   => wp_create_nonce( $action ),
			), admin_url( 'admin-ajax.php' ) );
		?>
			<img name="Peach Payment Gateway" src="<?php echo WC_PEACH_PLUGIN_URL.'/assets/images/Peach_Payments_Primary_logo.png';?>" width="100" alt="Peach Payment Gateway" class="back-title"/>
			<table class="form-table">
			<?php $this->generate_settings_html(); ?>
			</table>
            <?php
			$btn_txt = '<button type="button" class="peach-version-rollback">'.$rollbackBtnTxt.'</button>';
			?>
			<div class="peach-rollback">
				<?php echo $btn_txt; ?>
			</div>
			<div class="peach-core-modal-overlay">
				<div class="peach-core-modal" style="margin-top: -157.344px;">
					<div class="peach-core-modal-header">
						<h3 class="peach-core-modal-title">Version Rollback</h3>
						<a href="#" class="peach-core-modal-close" data-et-core-modal="close"></a>
					</div>
					<div id="peach-core-version-rollback-modal-content">
						<div class="peach-core-modal-content">
							<p>You'll be rolled back to <strong>Version 3.1.7</strong> from the current <strong>Version <?php echo WC_PEACH_VER; ?></strong>.</p>
							<p>Rolling back will reinstall the previous version of Peach Payments Gateway.</p>
							<p><strong>Note:</strong> older versions of the plugin could possibly not be fully compatible with the latest versions of <strong>WordPress</strong> or <strong>WooCommerce</strong>. You will be able to update to the latest version at any time.</p>
							<p><strong>Make sure you have a full site backup before proceeding.</strong></p>
						</div>
						<a class="peach-button peach-version-rollback-confirm" href="<?php echo $url; ?>"><?php echo $rollbackBtnTxt; ?></a>
					</div>
				</div>
			</div>
		<?php
		}
			
		//Payment Methods
		public function payment_fields() {
			$order_id = '';
			$payOption = '';
			if(isset($_GET['key'])){
				$order_id = wc_get_order_id_by_order_key($_GET['key']);
				$order = wc_get_order( $order_id );
				$payOption = $order->get_meta('_billing_peach');
			}
			
			$logger = wc_get_logger();
			$logger_info = array();
			
			$signup_checkout = get_option('woocommerce_enable_signup_and_login_from_checkout');
			$signup_checkout_subscribe = get_option('woocommerce_enable_signup_from_checkout_for_subscriptions');
			$signup = false;
			
			if($signup_checkout == 'yes' || $signup_checkout_subscribe == 'yes'){
				$signup = true;
			}
			
			$subscribe_test = $this->check_subscriptions();
			$hasCardStoragePaymentEnabled = false;
			
			if(($this->enabled == 'yes') && (!empty($this->checkout_methods_select))){
			$enabled = false;
			$enabledTxt = '';
			
			$methodsCnt = 0;
			
			if($this->description && $this->description != ''){
				echo '<p>'.$this->description.'</p>';
			}
			
			?>
			<fieldset>
				<p class="form-row form-row-wide">  
					<?php
					if(in_array('card',$this->checkout_methods_select)){ 
						if(!empty($this->checkout_methods_select)){
						if($payOption == '' || $payOption == 'dontsave'){
							$payOption
						?>
							  <?php if(!$subscribe_test[0] || $signup){ //Subscription Products Found?>
                              	  <?php if(!$subscribe_test[0]){ ?>
									  <?php
                                      if(!$enabled){
                                        $enabled = true;
                                        $enabledTxt = 'checked';
                                      }
                                      ?>
                                      <div class="peachpayopt card" style="padding:5px 0;">   
                                      <input type="radio" id="dontsave" name="peach_payment_id" onclick="getValue('dontsave')" style="width:auto;" value="dontsave" <?php if ( !($hasCardStoragePaymentEnabled  )){ echo $enabledTxt;} ?> /> <label style="display:inline;" for="dontsave"><?php esc_html_e( 'Pay with Card', 'woocommerce-gateway-peach-payments' ); ?></label></div>
                                      <?php 
                                      $methodsCnt++; 
							  		} //subscribe_test ?>
                        	<?php } //Subscription Products Found?>
						<?php
						}
						}
					?>
					<?php
					$enabledTxt = '';
					?>          
					<?php  if( ($signup || is_user_logged_in()) && $this->card_storage == 'yes' && $payOption == ''){ 
								$hasCardStoragePaymentEnabled=true; ?>
								<?php
								if ( is_user_logged_in() ) {
									$user_id = get_current_user_id();
									$user_cards = get_user_meta( $user_id, 'my-cards', true );
									$myOldCards = get_user_meta( $user_id, '_peach_payment_id', false);
									
									if(isset($user_cards) && is_array($user_cards) && !empty($user_cards)){
										$checkCardArray = true;
										if(!$enabled){
											$enabled = true;
											$enabledTxt = 'checked';
										  }
									}else if(isset($myOldCards) && is_array($myOldCards) && !empty($myOldCards)){
										$checkCardArray = true;
										if(!$enabled){
											$enabled = true;
											$enabledTxt = 'checked';
										  }
									}else{
										$checkCardArray = false;
									}
									
									if($checkCardArray):
									?>
									<div class="peachpayopt card" style="padding:5px 0;"><input type="radio" id="savedcards" name="peach_payment_id" style="width:auto;" onclick="getValue('savedcards')" value="savedcards" <?php echo $enabledTxt; ?> /> <label style="display:inline;" for="savedcards"><?php esc_html_e( 'Pay with Saved Cards', 'woocommerce-gateway-peach-payments' ); ?></label></div>                                  
									<?php
									endif;
								}

								$enabledTxt = '';
							
								if(!$enabled){
									$enabled = true;
									$enabledTxt = 'checked';
								  }
								  $methodsCnt++;
								?>
	
								<div class="peachpayopt card" style="padding:5px 0;"><input type="radio" id="saveinfo" name="peach_payment_id" style="width:auto;" onclick="getValue('saveinfo')" value="saveinfo" <?php echo $enabledTxt; ?> /> <label style="display:inline;" for="saveinfo"><?php esc_html_e( 'Pay and save New Card', 'woocommerce-gateway-peach-payments' ); ?></label></div>
							<?php 
							}?>
							<?php
							$enabledTxt = '';
							?> 
	
				 <?php
					}//end 'if' for card options selected
				 if(in_array('hosted',$this->checkout_methods_select) && $subscribe_test[0] == false){ 
				 if($payOption == '' || $payOption == 'other'){     
								$payIcons = '';
								$payIconsPop = array();
								$payIconsPopCont = '';
								$consolidated_label = $this->consolidated_label;
								foreach($this->consolidated_label_logos as $index => $value){
									
									$methodName = ucwords(strtolower($value));
									
									if($value == 'APPLE'){
										$methodName = 'Apple Pay';
									}else if($value == 'SCANTOPAY'){
										$methodName = 'Scan to Pay';
									}else if($value == '1VOUCHER'){
										$methodName = '1Voucher';
									}else if($value == 'EFTSECURE'){
										$methodName = 'EFT Secure';
									}else if($value == 'INSTANTEFT'){
										$methodName = 'InstantEFT';
									}else if($value == 'MASTER'){
										$methodName = 'Mastercard';
									}else if($value == 'ZEROPAY'){
										$methodName = 'ZeroPay';
									}else if($value == 'BLINKBYEMTEL'){
										$methodName = 'Blink by EMTEL';
									}else if($value == 'CAPITEC'){
										$methodName = 'Capitec Pay';
									}else if($value == 'MCBJUICE'){
										$methodName = 'MCB Juice';
									}
									
									if($index < 4){
										$payIcons .= '<div class="peach-method"><img name="" src="'.WC_PEACH_PLUGIN_URL.'/assets/images/'.$value.'.png" width="38" height="20" alt="" /><div class="peach-method-tooltip">'.$methodName.'</div></div>';
									}else{
										$payIconsPop[] = $methodName;
									}
								}
								
								if(!empty($payIconsPop)){
									$payPopTxtCnt = 1;
									$payPopTxt = '';
									foreach($payIconsPop as $index => $payIconsMore){
										if($payPopTxtCnt == 1){
											$payPopTxt .= $payIconsMore;
										}else if($payPopTxtCnt < count($payIconsPop)){
											$payPopTxt .= ', '.$payIconsMore;
										}else{
											$payPopTxt .= ' and '.$payIconsMore;	
										}
										$payPopTxtCnt++;
									}
									
									$payIconsPopCont = '
									<div class="peachpopcont">
										<span class="peachpop">
										'.$payPopTxt.'
										</span>
										+'.count($payIconsPop).' '.esc_attr_e('more', 'woocommerce-gateway-peach-payments').'
									</div>
									';
								}

								  if(!$enabled){
									$enabled = true;
									$enabledTxt = 'checked';
								  }
								  $methodsCnt++;
							  ?>
								   <div class="peachpayopt peach" style="padding:5px 0;"> 
								  <input type="radio" id="ApplePay" name="peach_payment_id" onclick="getValue('other')" style="width:auto;" value="other" <?php echo $enabledTxt; ?> /> <label style="display:inline;" for="ApplePay"><?php echo $consolidated_label; ?></label><div style="width:100%; padding-left:20px; margin:5px 0 10px 0;"><?php echo $payIcons.' '.$payIconsPopCont; ?></div></div>
				<?php 
				} }?>          
				<div class="clear"></div>
				</p>
			</fieldset>
            <?php
			if($methodsCnt == 0){
				echo 'No payment methods available.';
			}
			?>
	
			 <?php }else{
				echo '
				<fieldset>
					<p>
					No payment methods available.
					</p>
					<div class="clear"></div>
				</fieldset>
				'; 
			} 
			
			?>
				 
		<?php
			if(!empty($logger_info)){
				$logger->info( "\n".print_r($logger_info, true)."\n\n", array( 'source' => 'peach-payment-fields' ) );
			}
				 
		}
			
		//Check cart for subscription products
		public function check_subscriptions(){
			$found = false;
			$subsrb_cnt = 0;
			$prod_cnt = 0;
			$mixed = false;
			if(WC()->cart){
				foreach ( WC()->cart->get_cart() as $cart_item_key => $cart_item ) {
					if(isset($cart_item['wcsatt_data'])){
						$subscription_scheme = $cart_item['wcsatt_data']['active_subscription_scheme'];
					}
					$product = $cart_item['data'];
					$product_id = $cart_item['product_id'];
					$metas = get_post_meta($product_id);
					foreach($metas as $key => $value){
						if($key == '_subscription_price'){
							$subsrb_cnt++;
							$found = true;
						}
					}
					
					$prod_cnt++;
					
					if(isset($subscription_scheme) && $subscription_scheme != ''){
						$found = true;
					}
				}
				
				if($prod_cnt > $subsrb_cnt && $subsrb_cnt != 0){
					$mixed = true;
				}
			}
			
			return array($found, $mixed);
			
		}
			
		//Processing the payments here
		public function process_payment( $order_id ) {
			global $woocommerce;
			$logger = wc_get_logger();
			$logger_info['settings'] = $this->logger_info_settings;
			
			$bearerOrderID = $order_id;
			if((!isset($bearerOrderID) || $bearerOrderID == '') && isset($_COOKIE['PeachOrderID'])){
				$orderID = $_COOKIE['PeachOrderID'];
				if($this->orderids != 'yes'){
					$plugin_support = new pluginSupport();
					$bearerOrderID = $plugin_support->sequentialNumbers($orderID, 1);
				}else{
					$bearerOrderID = $_COOKIE['PeachOrderID'];
				}
			}
			
			$order = wc_get_order($bearerOrderID);
			
			if(isset($_GET['id']) && isset($_GET['resourcePath'])){
				
				$id = urldecode($_GET['id']);
				$resourcePath = urldecode($_GET['resourcePath']);
			
				$url = $this->process_checkout_url.''.$resourcePath;
				$url .= "?entityId=".$this->secureid;
				
				$auth_bearer = get_post_meta( $bearerOrderID , 'payment_auth_bearer', true );
				//$this->accesstoken = 'OGFjN2E0Yzg3ZWJlMzNmOTAxN2ViZmEyZmY5ODA0MTF8NHdLOXJ==peach==';
				
				if($auth_bearer == $this->accesstoken){
				
					$ch = curl_init();
					curl_setopt($ch, CURLOPT_URL, $url);
					curl_setopt($ch, CURLOPT_HTTPHEADER, array(
								   'Authorization:Bearer '. $this->accesstoken));
					curl_setopt($ch, CURLOPT_CUSTOMREQUEST, 'GET');
					curl_setopt($ch, CURLOPT_SSL_VERIFYPEER, $this->ssl_verifypeer);
					curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
					curl_setopt($ch, CURLOPT_FAILONERROR, true);
					
					$responseData = curl_exec($ch);
					$resultCode = '';
					$curlError = '';
					
					if(curl_errno($ch)) {
						$curlError = curl_error($ch);
						wc_add_notice($curlError, 'error' );
						return;
					}else{
						$response = json_decode($responseData);
						$resultCode = $response->result->code;
						$resultDescription = $response->result->description;
					}
					curl_close($ch);
				
					$paymentBrand = '';
					$paymentType = '';
					if(isset($response->paymentBrand)){
						$paymentBrand = $response->paymentBrand;
					}
					if(isset($response->paymentType)){
						$paymentType = $response->paymentType;
					}
					
					if($resultCode == $this->success_code && $curlError == ''){
						
						$orderID = $response->merchantTransactionId;
						
						$seqOrderID = $orderID;
						if($this->orderids != 'yes'){
							$plugin_support = new pluginSupport();
							$seqOrderID = $plugin_support->sequentialNumbers($orderID, 1);
						}
						
						$orderNew = wc_get_order( $seqOrderID );
						
						if($orderNew->get_status() != 'completed' || $orderNew->get_status() != $this->peach_order_status){
							$force_complete = $this->check_orders_products($orderNew);
							if($force_complete && $this->completestatus == 'yes'){
								$orderNew->add_order_note( 'Peach Payment Successfull.',0,false);
								$orderNew->update_status('completed', __( 'Paid via Peach Payments', 'woocommerce' ));
							}else{
								$orderNew->add_order_note( 'Peach Payment Successfull.',0,false);
								$orderNew->update_status($this->peach_order_status, __( 'Order being processed.', 'woocommerce' ));
							}
						}
						
						$orderNew->save();
						
						$woocommerce->cart->empty_cart();
						
						add_post_meta( $seqOrderID, 'payment_order_id', $response->id );
						update_post_meta($seqOrderID, "_checkout_payment_option", $paymentBrand);
						
						if ( is_user_logged_in() ) {
							if(isset($response->registrationId) && $response->registrationId != ''){
								add_post_meta( $seqOrderID, 'payment_registration_id', $response->registrationId );
								
								$newCard = array(
									'id' => $response->registrationId,
									'num' => 'xxxx-'.$response->card->last4Digits,
									'holder' => $response->card->holder,
									'brand' => $response->paymentBrand,
									'exp_year' => $response->card->expiryYear,
									'exp_month' => $response->card->expiryMonth
								);
								
								$found = $this->card_search($newCard, $checkOld = true);
								
								$myCards = get_user_meta( get_current_user_id(), 'my-cards', true);

								if(isset($myCards) && !$found){
									if($myCards == ''){
										update_user_meta( get_current_user_id(), 'my-cards', array($newCard));
									}else{
										$myCards[] = $newCard;
										update_user_meta( get_current_user_id(), 'my-cards', $myCards);
									}
								}else if(!$found){
									add_user_meta( get_current_user_id(), 'my-cards', array($newCard));
								}
							}
						}
						
						wp_safe_redirect($this->order_received_page_url.'/'.$orderNew->get_id().'/?key='.$orderNew->get_order_key() );
						exit;
						
					}else{
						$orderID = $bearerOrderID;
						
						$logger_info['errors'] = array(
							'Order' => $orderID,
							'Payment' => 'Card Widget',
							'Response' => $response,
						);
						
						$logger->info( "\n".print_r($logger_info, true)."\n\n", array( 'source' => 'peach-process-payment' ) );
						
						$orderNew = wc_get_order( $orderID );
						$orderNew->add_order_note( 'Peach Error #'.$resultCode.'. '.$resultDescription,0,false );
						wc_add_notice(  'Please try again. '.$resultDescription, 'error' );
						wp_safe_redirect( $this->order_pay_page_url.'/'.$orderNew->get_id().'/?key='.$orderNew->get_order_key() );
						exit;
					}
				}else{
					if(isset($_COOKIE['PeachOrderID'])){
						$status = $this->checkStatusPre($_COOKIE['PeachOrderID']);
						setcookie('PeachOrderID', '', time() - 3600);
					}
					if(isset($status) && $status[0] == $this->success_code){
						$orderNew = wc_get_order( $status[1] );
						
						if($orderNew->get_status() != 'completed' || $orderNew->get_status() != $this->peach_order_status){
							$force_complete = $this->check_orders_products($orderNew);
							if($force_complete && $this->completestatus == 'yes'){
								$orderNew->add_order_note( 'Peach Payment Successfull.',0,false);
								$orderNew->update_status('completed', __( 'Paid via Peach Payments', 'woocommerce' ));
							}else{
								$orderNew->add_order_note( 'Peach Payment Successfull.',0,false);
								$orderNew->update_status($this->peach_order_status, __( 'Order being processed.', 'woocommerce' ));
							}
						}
						
						$orderNew->save();
						$woocommerce->cart->empty_cart();
						
						update_post_meta( $status[1], 'payment_order_id', $status[2] );
						update_post_meta( $status[1], "_checkout_payment_option", $status[3]);
						
						wp_safe_redirect($this->order_received_page_url.'/'.$orderNew->get_id().'/?key='.$orderNew->get_order_key() );
						exit;
					}else{
						$logger_info['errors'] = array(
							'Order' => $bearerOrderID,
							'Payment' => 'Card Widget',
							'Response' => 'Order Failed',
						);
						
						$logger->info( "\n".print_r($logger_info, true)."\n\n", array( 'source' => 'peach-process-payment' ) );
						
						if(isset($status)){
							$orderNew = wc_get_order( $status[1] );
							$orderNew->add_order_note( 'Processing Error = ['.$status[0].'] Peach Authorization Keys Conflict.',0,false );
						}else{
						}
						wc_add_notice(  'There has been an conflict in authorization details.', 'error' );
						wp_safe_redirect( $this->checkout_page_url );
						exit;
					}
				}
				
			}else if (isset($_POST)){
				if(isset($_POST['result_code'])){
					$orderID = $_POST['merchantTransactionId'];
					$paymentBrand = '';
					$paymentType = '';
					if(isset($_POST['paymentBrand'])){
						$paymentBrand = $_POST['paymentBrand'];
					}
					if(isset($_POST['paymentType'])){
						$paymentType = $_POST['paymentType'];
					}
					$seqOrderID = $orderID;
					if($this->orderids != 'yes'){
						$plugin_support = new pluginSupport();
						$seqOrderID = $plugin_support->sequentialNumbers($orderID, 1);
					}
					
					$orderNew = wc_get_order( $seqOrderID );
						
					if($_POST['result_code'] == $this->success_code){
						
						if($orderNew->get_status() != 'completed' || $orderNew->get_status() != $this->peach_order_status){
							$force_complete = $this->check_orders_products($orderNew);
							if($force_complete && $this->completestatus == 'yes'){
								$orderNew->add_order_note( 'Peach Payment Successfull.',0,false);
								$orderNew->update_status('completed', __( 'Paid via Peach Payments: '.$_POST['paymentBrand'], 'woocommerce' ));
							}else{
								$orderNew->add_order_note( 'Peach Payment Successfull.',0,false);
								$orderNew->update_status($this->peach_order_status, __( 'Order being processed by Peach Payments ('.$_POST['paymentBrand'].').', 'woocommerce' ));
							}
						}
						
						$orderNew->save();
						
						$woocommerce->cart->empty_cart();
						
						add_post_meta( $seqOrderID, 'payment_order_id', $_POST['id'] );
						update_post_meta($seqOrderID, "_checkout_payment_option", $paymentBrand);
						
						//For Event Analytics
						$analyticsPageViewData = array("pp_page_title"=>'CardPayment');	
						wp_enqueue_script('pp_google_anlaytics_page_view',WC_PEACH_PLUGIN_URL.'/assets/js/analytics_page_view.js');
						wp_localize_script( "pp_google_anlaytics_page_view", "merchant", $analyticsPageViewData );
							
						$analyticsData = array("siteurl"=>site_url(),
											   "transaction_id"=>$seqOrderID,
											   "amount"=>$orderNew->get_total()
										);
						wp_enqueue_script('peachpaymentGoogleTagCheckoutPaymentStartJs',WC_PEACH_PLUGIN_URL.'/assets/js/pp_payon_payment.js');
						wp_localize_script( "peachpaymentGoogleTagCheckoutPaymentStartJs", "merchant", $analyticsData );
						
						wp_safe_redirect( $this->order_received_page_url.'/'.$orderNew->get_id().'/?key='.$orderNew->get_order_key() );
						exit;
					}else{
						$orderNew->add_order_note( 'Peach Order Status - ['.$_POST['result_code'].']',0,false);
						wc_add_notice('Please try again. ['.$_POST['result_code'].']', 'error' );
						$cart_page_url = function_exists( 'wc_get_cart_url' ) ? wc_get_cart_url() : $woocommerce->cart->get_cart_url();
						$logger_info['errors'] = array(
							'Order' => $orderNew->get_id(),
							'Payment' => 'Hosted Widgets',
							'Response' => $_POST,
						);
						
						$logger->info( "\n".print_r($logger_info, true)."\n\n", array( 'source' => 'peach-process-payment' ) );

						wp_safe_redirect( $cart_page_url );
						exit;
					}
				}else{
				   return array(
					  'result'   => 'success',
					  'redirect' => $order->get_checkout_payment_url( true )
				   );	
				}
			}else{	
				return array(
				  'result'   => 'success',
				  'redirect' => $order->get_checkout_payment_url( true )
				);
			}
					
		}
		
		public function validate_title_field( $key, $value ) {
		
			if ( $value == '' ) {
				WC_Admin_Settings::add_error( 'Please enter a title for the Peach Payment Gateway.' );
				$value = ''; // empty it because it is not correct
			}
		
			return $value;
		}
		
		public function validate_checkout_methods_select_field( $key, $value ) {
		
			if ( empty($value) || $value == '') {
				WC_Admin_Settings::add_error( 'Please choose at least one checkout option!' );
				$value = '';
			}
		
			return $value;
		}
		
		public function validate_embed_clientid_field( $key, $value ) {
			
			if($this->get_option('embed_payments') == 'yes' && $value == ''){
				WC_Admin_Settings::add_error( 'Embed Client ID is required.' );
				$value = ''; // empty it because it is not correct
			}
		
			return $value;
		}
		
		public function validate_embed_clientsecret_field( $key, $value ) {
			
			if($this->get_option('embed_payments') == 'yes' && $value == ''){
				WC_Admin_Settings::add_error( 'Embed Client Secret is required.' );
				$value = ''; // empty it because it is not correct
			}
		
			return $value;
		}
		
		public function validate_embed_merchantid_field( $key, $value ) {
			
			if($this->get_option('embed_payments') == 'yes' && $value == ''){
				WC_Admin_Settings::add_error( 'Merchant ID is required.' );
				$value = ''; // empty it because it is not correct
			}
		
			return $value;
		}
		
		public function process_admin_options(){
			parent::process_admin_options();
			$analyticsData = array("pp_page_title"=>'ConfigurationForm');
			wp_enqueue_script('pp_google_anlaytics_admin_configuration',WC_PEACH_PLUGIN_URL.'/assets/js/pp_admin_success.js');
			wp_localize_script( "pp_google_anlaytics_admin_configuration", "merchant", $analyticsData );
		}
		
		public function peach_trial_end($subscription_id){
			
		}
		
		function peach_thankyou($order_id){
			$logger = wc_get_logger();
			$logger_info = array();
			$logger_info['settings'] = $this->logger_info_settings;
			
			$order = wc_get_order( $order_id );
			$is_order = $this->validate_order($order);
			
			if($is_order){
				$eventType="order_received";
				$current_order_status = $order->get_status();
				$ppGetPaymentMethod = '';
				
				$analyticsPageViewData = array("pp_page_title"=>'OrderReceived',);						
				wp_enqueue_script('pp_google_anlaytics_page_view',WC_PEACH_PLUGIN_URL.'/assets/js/analytics_page_view.js');
				wp_localize_script( "pp_google_anlaytics_page_view", "merchant", $analyticsPageViewData );
				
				$analyticsData = array("siteurl"=>site_url(),
									   "transaction_id"=> $order_id ,
									   "payment_method"=>$ppGetPaymentMethod,
									   "payment_status"=>$current_order_status,
									   "amount"=>$order->get_total(),
									   "event_type"=>$eventType
				);
				wp_enqueue_script('ppEventCompletePayment',WC_PEACH_PLUGIN_URL.'/assets/js/pp_event_complete_payment.js');
				wp_localize_script( "ppEventCompletePayment", "merchant", $analyticsData );
			}else{
				$logger_info['errors'] = 'Order not found for Analytics Data.';
				$logger->info( "\n".print_r($logger_info, true)."\n\n", array( 'source' => 'peach-peach-thankyou' ) );
			}
		}
			
		//WC Subscriptions recurring payments
		function scheduled_subscription_payment( $amount_to_charge, $renewal_order ) {
			
			if ( wcs_order_contains_renewal( $renewal_order->get_id() ) ) {
				$this->process_subscription_payment( $amount_to_charge, $renewal_order, true, false );
			}
		}
			
		function process_subscription_payment( $amount, $renewal_order, $retry = true, $previous_error = false ) {
			$logger = wc_get_logger();
			$logger_info = array();
			$logger_info['settings'] = $this->logger_info_settings;
			
			$order_id = $renewal_order->get_id();
			
			if ( wcs_order_contains_renewal( $order_id ) ) {
    			$parent_order_id = WC_Subscriptions_Renewal_Order::get_parent_order_id( $order_id );
    		}else{
    			$parent_order_id = $order_id;
    		}

			$parent_order = wc_get_order( $parent_order_id );
			
			$id = get_post_meta( $parent_order_id , '_peach_subscription_payment_method', true );
			$plgvs = 'V2';
			if(!isset($id) || $id == ''){
				$id = get_post_meta( $parent_order_id, 'payment_registration_id', true );
				$plgvs = 'V3';
			}

			$result = '';
			
			if(isset($this->recurringid) && $this->recurringid != ''){
				$url = $this->process_checkout_url."/v1/registrations/".$id."/payments";
				$data = "entityId=" .$this->recurringid.
							"&amount=" .$amount.
							"&currency=" .$renewal_order->get_currency().
							"&paymentType=DB" .
							"&standingInstruction.mode=REPEATED" .
							"&standingInstruction.type=RECURRING" .
							"&standingInstruction.source=MIT";
			
				$ch = curl_init();
				curl_setopt($ch, CURLOPT_URL, $url);
				curl_setopt($ch, CURLOPT_HTTPHEADER, array(
							   'Authorization:Bearer '. $this->accesstoken));
				curl_setopt($ch, CURLOPT_POST, 1);
				curl_setopt($ch, CURLOPT_POSTFIELDS, $data);
				curl_setopt($ch, CURLOPT_SSL_VERIFYPEER, $this->ssl_verifypeer);
				curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
				curl_setopt($ch, CURLOPT_FAILONERROR, true);
				
				$responseData = curl_exec($ch);
				$response = '';
				if(curl_errno($ch)) {
					$response = curl_error($ch);
					if($url === ''){
						$response = 'URL not set.';
					}
				}else{
					$response = json_decode($responseData);
					$resultCode = $response->result->code;
				}
				
				curl_close($ch);
			
				if ( $resultCode == $this->success_code) {
					add_post_meta( $order_id, 'payment_order_id', $response->id );
					$parent_order->add_order_note('Peach re-curring order [#'.$parent_order_id.'] payment accepted.',0,false);
					WC_Subscriptions_Manager::process_subscription_payments_on_order($parent_order);
				}else if($resultCode == '000.200.000' || $resultCode == '000.200.100'){}else {
										
					$logger_info['errors'] = array(
						'Order Type' => 'Recurring',
						'Order ID' => $order_id,
						'Order Parent ID' => $parent_order_id,
						'Response Code' => $resultCode,
						'Response' => $response,
						'Data' => $data,
						'URL' => $url,
						'ID' => $id
					);
					$logger->info( "\n".print_r($logger_info, true)."\n\n", array( 'source' => 'peach-process-subscription-payment' ) );
					$parent_order->add_order_note('Peach re-curring order [#'.$parent_order_id.'] payment denied with code #'.$resultCode.'.',0,false);
					WC_Subscriptions_Manager::process_subscription_payment_failure_on_order($parent_order);
				}
			}else{
				$logger_info['errors'] = array(
					'Order Type' => 'Recurring',
					'Order ID' => $order_id,
					'Order Parent ID' => $parent_order_id,
					'Response' => 'Recurring ID not set in Settings',
				);
				$logger->info( "\n".print_r($logger_info, true)."\n\n", array( 'source' => 'peach-process-subscription-payment' ) );
				
				$parent_order->add_order_note('Peach re-curring order [#'.$order_id.'] payment denied. Recurring ID not set in Settings',0,false);
				WC_Subscriptions_Manager::process_subscription_payment_failure_on_order($parent_order);
			}
		}
			
		function receipt_page( $order_id ) {
			
			global $woocommerce;
			
			$logger = wc_get_logger();
			$logger_info = array();
			$logger_info['settings'] = $this->logger_info_settings;
			$logger_info['urls'] = array(
				'process_checkout' => $this->process_checkout_url,
				'request_checkout' => $this->request_checkout_url,
				'request_status' => $this->request_status_url,
				'request_pre_status' => $this->request_pre_status_url,
				'request_refund' => $this->request_refund_url,
				'checkout_page' => $this->checkout_page_url,
				'order_received_page' => $this->order_received_page_url,
				'order_pay_page' => $this->order_pay_page_url,
				'shopperResultUrl' => WC_PEACH_SITE_URL.'?wc-api=WC_Peach_Payments'
			);
			
			$order = wc_get_order( $order_id );
			
			$seqOrderID = $order_id;
			
			if($this->orderids != 'yes'){
				$plugin_support = new pluginSupport();
				$seqOrderID = $plugin_support->sequentialNumbers($order, 0);
			}
			
			setcookie('PeachOrderID', $seqOrderID, time() + (86400 * 30), "/");
			$bearerCheck = get_post_meta( $seqOrderID , 'payment_auth_bearer', true );
			if(!$bearerCheck || $bearerCheck == ''){
				add_post_meta( $order_id, 'payment_auth_bearer', $this->accesstoken );
			}
			
			$url = $this->process_checkout_url."/v1/checkouts";
			$paymentType = 'DB';
			$shopperResultUrl = WC_PEACH_SITE_URL.'?wc-api=WC_Peach_Payments';
			$myCards = '';
			$myOldCards = '';
			$user_id = '';
			$nonce = wp_create_nonce( $order->get_order_key().'_'.time() );
			
			if(isset($_COOKIE['PeachExpressCheckoutPlugin']) && $_COOKIE['PeachExpressCheckoutPlugin'] !== ''){
				$payOption = $_COOKIE['PeachExpressCheckoutPlugin'];
				$order->update_meta_data( '_billing_peach', $payOption );
				setcookie('PeachExpressCheckoutPlugin', '', time() + (86400 * 30), "/");
			}else if(isset($_COOKIE['PeachManualCheckout']) && $_COOKIE['PeachManualCheckout'] !== ''){
				$payOption = $_COOKIE['PeachManualCheckout'];
				$order->update_meta_data( '_billing_peach', $_COOKIE['PeachManualCheckout'] );
				setcookie('PeachManualCheckout', '', time() + (86400 * 30), "/");
			}else if(null !== $order->get_meta('_billing_peach') && $order->get_meta('_billing_peach') != ''){
				$payOption = $order->get_meta('_billing_peach');
			}else{
				$order->add_order_note( 'Peach Error. Checkout could not detect selected payment method',0,false);
				wc_add_notice(  'Error - Checkout could not detect your selected payment option.', 'error' );
				wp_safe_redirect($this->checkout_page_url);
				exit;
			}
			
			if($payOption && $payOption != ''){
				$logger_info['payOption'] = $payOption;
			}
			
			//New 3D Secure Rule. Address can't exceed 50 chars
			$billing_address = substr($order->get_billing_address_1(),0,50);
			$billing_address = str_replace('&', ' ',$billing_address);
			$billing_address = str_replace('.', '',$billing_address);
			
			$sigArray = array(
				'amount' => $order->get_total(),
				'authentication.entityId' => $this->secureid,
				'billing.city' => $order->get_billing_city(),
				'billing.country' => $order->get_billing_country(),
				'billing.postcode' => $order->get_billing_postcode(),
				'billing.street1' => $billing_address,
				'currency' => $order->get_currency(),
				'customer.email' => $order->get_billing_email(),
				'customer.givenName' => str_replace(' ', '', $order->get_billing_first_name()),
				'customer.ip' => $_SERVER['REMOTE_ADDR'],
				'customer.mobile' => $order->get_billing_phone(),
				'customer.surname' => str_replace(' ', '', $order->get_billing_last_name()),
				'merchantTransactionId' => $seqOrderID,
				'nonce' => $nonce,
				'originator' => WC_PEACH_VER,
				'paymentType' => $paymentType,
				'plugin' => 'Woocommerce',
				'shopperResultUrl' => $shopperResultUrl
			);
			
			$sig_string = '';
			$hostedFields = '';
			foreach($sigArray as $key => $value){
				if(isset($key) && $value != ''){
					$sig_string .= $key.$value;
					$hostedFields .= '
					<input type="hidden" name="'.$key.'" value="'.$value.'" />
					';
				}
			}
			
			$secret = $this->secrettoken;
			$signature = hash_hmac('sha256', $sig_string, $secret);
			
			if ( is_user_logged_in() ) {
				$user_id = get_current_user_id();
				$myCards = get_user_meta( $user_id, 'my-cards', true );
				$myOldCards = get_user_meta( $user_id, '_peach_payment_id', false);
			}
			
			//$subscribe_test = $this->check_subscriptions();
			$subscribe_test = array(false, false);
			
			//First Check for Mixed Basked
			if($subscribe_test[1]){
				$logger_info['errors'] = array(
					'Order ID' => $seqOrderID,
					'Response' => 'Mixed baskets detected.',
				);
				$logger->info( "\n".print_r($logger_info, true)."\n\n", array( 'source' => 'peach-receipt-page' ) );
				$order->add_order_note( 'Peach Error. Mixed baskets detected.',0,false);
				wc_add_notice(  'Peach Payments can\'t process mixed baskets.', 'error' );
				wp_safe_redirect($this->checkout_page_url);
				exit;
			}else{
				
				if($payOption == 'other'){
					//Google Analytics
					$items = $order->get_items();		
					$getBasketArray = $this->ppBasketValues($items,$order_id);		
					$analyticsData = array("siteurl"=>site_url(),
										   "transaction_id"=>$seqOrderID,
										   "pp_version"=>WC_PEACH_VER,
										   "wc_version"=>WC()->version,
										   "wp_version"=>get_bloginfo( 'version' ),
										   "amount"=>$order->get_total(),
										   "checkoutPaymentMethod"=>$payOption,
										   "basket"=>$getBasketArray);
			
					wp_enqueue_script('pp_event_switch_paymentjs',WC_PEACH_PLUGIN_URL.'/assets/js/pp_event_switch_payment.js');
					wp_localize_script( "pp_event_switch_paymentjs", "merchant", $analyticsData );
					
					if($this->embed_payments != 'yes'){
						echo '
						<form name="Checkout" action="'.$this->request_checkout_url.'" method="POST" accept-charset="utf-8" id="peach-other-payments">
							'.$hostedFields.'
							<input type="hidden" name="signature" value="'.$signature.'" />
							<input type="submit" value="Continue to Payment Method" class="peach-payment-btn" name="btnSubmit" />
						</form>
						<div class="modal-content">
							<div class="modal-header">
								<h2>Peach Payments</h2>
							</div>
							<div class="modal-body">
								<p>We are redirecting you to your payment options.</p>
								<p>Please do not interupt this process.</p>
							</div>
							<div class="modal-footer">
								<img name="" src="'.WC_PEACH_PLUGIN_URL.'/assets/images/Peach_Payments_Primary_logo_modal.png" width="38" height="20" alt="" />
							</div>
						</div>
						';
						echo '
						<script>
							setTimeout(
							function() 
							{
							jQuery( "#peach-other-payments" ).submit();
							}, 500);
						</script>';
					}else{
						$embed_errors = false;
						$embed_keys = false;
						$embed = new embeddedCheckout();
						if($this->embed_clientid == '' || $this->embed_clientsecret == '' || $this->embed_merchantid == ''){
							$embed_errors = true;
							$logger_info['errors'] = array(
								'order' => $order_id,
								'embed_token' => 'error',
							);
						}else{
							$embed_keys = true;
							$embed_token = $embed->get_access_token($this->transactionmode, $this->embed_clientid, $this->embed_clientsecret, $this->embed_merchantid, 'auth');
							if($embed_token != 'error'){
								$embed_checkout_instance = $embed->embed_checkout_instance($this->transactionmode, 'checkout', $embed_token, $order_id, $order, $this->secureid);
							}else{
								$embed_errors = true;
								$logger_info['errors'] = array(
									'order' => $order_id,
									'embed_token' => 'token error',
								);
							}
						}
						if($embed_checkout_instance != 'error'){
							$embed_js = $embed->get_embed_urls($this->transactionmode, 'embed');
							$input = '
							<div class="peach_embed_container">
								<div id="peach-embed-form"></div>
							</div>
							<script>
								console.log("Did this work?");
								setTimeout(
								function() 
								{
									console.log("Start Embedding");
									const checkout = Checkout.initiate({
										key: "'.$this->secureid.'",
										checkoutId: "'.$embed_checkout_instance.'",
										options: {
											theme: {
											  brand: {
												primary: "#EC5228",
											  },
											  cards: {
												background: "#EEEEEE",
												backgroundHover: "#FBDCD4",
											  }
											},
										  },
									});
									checkout.render("#peach-embed-form");
									console.log("Finish Embedding");
								}, 500);
							</script>
							';
						}else{
							$embed_errors = true;
							$logger_info['errors'] = array(
								'order' => $order_id,
								'checkout_instance' => 'embed error',
							);
						}
						
						if($embed_errors){
							$embed_order_note = 'Peach Embedded Checkout Error.';
							if(!$embed_keys){
								$embed_order_note = 'Peach Embedded Missing Account Keys.';
							}
							
							$logger->info( "\n".print_r($logger_info, true)."\n\n", array( 'source' => 'peach-embedded-checkout' ) );
							$order->add_order_note($embed_order_note,0,false);
							wc_add_notice(  'Peach Payments can\'t process the embedded checkout at the moment.', 'error' );
							wp_safe_redirect($this->checkout_page_url);
							exit;
						}else{
							echo $input;
						}
					}
					
				}else if($payOption == 'dontsave' || $payOption == 'saveinfo' || $payOption == 'savedcards'){
					//Google Analytics
					$items = $order->get_items();		
					$getBasketArray = $this->ppBasketValues($items,$order_id);
					$analyticsData = array("siteurl" => site_url(),
										   "transaction_id" => $seqOrderID,
										   "pp_version" => WC_PEACH_VER,
										   "wc_version" => WC()->version,
										   "wp_version" => get_bloginfo( 'version' ),
											"amount" => $order->get_total(),
											"pp_mode" => $this->transactionmode,							
											'basket' => $getBasketArray,
											"payment_method" => $payOption,
											);
					wp_enqueue_script('peachpaymentGoogleTagPaymentStartJs',WC_PEACH_PLUGIN_URL.'/assets/js/pp_invoking_plugin.js');
					wp_localize_script( "peachpaymentGoogleTagPaymentStartJs", "merchant", $analyticsData );
					
					//New 3D Secure Rule. Address can't exceed 50 chars
					$billing_address = substr($order->get_billing_address_1(),0,50);
					$billing_address = str_replace('&', ' ',$billing_address);
					$billing_address = str_replace('.', '',$billing_address);
					
					$data = "entityId=". $this->secureid .
							"&amount=" .$order->get_total().
							"&currency=" .$order->get_currency().
							"&customParameters[SHOPPER_pluginVersion]=".WC_PEACH_VER.
							"&customer.givenName=" .$order->get_billing_first_name().
							"&customer.surname=" .$order->get_billing_last_name().
							"&customer.ip=" .$order->get_customer_ip_address().
							"&customer.email=" .$order->get_billing_email().
							"&customer.phone=" .$order->get_billing_phone().
							"&billing.street1=" .$billing_address.
							"&billing.postcode=" .$order->get_billing_postcode().
							"&billing.city=" .$order->get_billing_city().
							"&billing.country=" .$order->get_billing_country();
							
					if($payOption == 'saveinfo'){
						$data .= "&createRegistration=true";
						$data .= "&paymentType=" .$paymentType;
						$data .= "&merchantTransactionId=" .$seqOrderID;
						
						$check_subscribe = $this->check_subscriptions();
						
						if($check_subscribe[0]){ //Subscuption Products Found
							$subscriptions = wcs_get_subscriptions_for_order( $order_id);
							$subscr_info = $this->getSubscriptionInfo($subscriptions);
						
							$data .= 
							"&standingInstruction.source=CIT" .
							"&standingInstruction.mode=INITIAL" .
							"&standingInstruction.type=RECURRING".
							"&standingInstruction.expiry=". $subscr_info[0] .
							"&standingInstruction.frequency=". $subscr_info[1];
						}else{
							$data .= 
							"&standingInstruction.source=CIT" .
							"&standingInstruction.mode=INITIAL" .
							"&standingInstruction.type=UNSCHEDULED";
						}
					}
					
					if($payOption == 'savedcards'){
						$data .= "&paymentType=" .$paymentType;
						$data .= "&merchantTransactionId=" .$seqOrderID;
						$cardCnt = 0;
						
						if(is_array($myCards) && !empty($myCards)){
							foreach ($myCards as $index => $card){
								$data .= "&registrations[".$cardCnt."].id=" . $card['id'];
								$cardCnt++;
							};
						}
						if(is_array($myOldCards) && !empty($myOldCards)){
							foreach ($myOldCards as $index => $OldCard){
								$data .= "&registrations[".$cardCnt."].id=" . $OldCard['payment_id'];
								$cardCnt++;
							};
						}
						
						$check_subscribe = $this->check_subscriptions();
						
						if($check_subscribe[0]){ //Subscuption Products Found
							$subscriptions = wcs_get_subscriptions_for_order( $order_id);
							$subscr_info = $this->getSubscriptionInfo($subscriptions);
							$data .= 
							"&standingInstruction.source=CIT" .
							"&standingInstruction.mode=REPEATED" .
							"&standingInstruction.type=RECURRING".
							"&standingInstruction.expiry=". $subscr_info[0] .
							"&standingInstruction.frequency=". $subscr_info[1];
						}else{
							$data .= 
							"&standingInstruction.source=CIT" .
							"&standingInstruction.mode=REPEATED" .
							"&standingInstruction.type=UNSCHEDULED";
						}
					}
					
					if($payOption == 'dontsave'){
						$data .= "&paymentType=" .$paymentType;
						$data .= "&merchantTransactionId=" .$seqOrderID;
					}
					
					$check_subscribe = $this->check_subscriptions();
					
					if($check_subscribe[0]){
						$analyticsData = array("siteurl"=>site_url(),"transaction_id"=>$seqOrderID);
						wp_enqueue_script('ppSubJs',WC_PEACH_PLUGIN_URL.'/assets/js/pp_sub_type.js');
						wp_localize_script( "ppSubJs", "merchant", $analyticsData );
					}
				
					$ch = curl_init();
					curl_setopt($ch, CURLOPT_URL, $url);
					curl_setopt($ch, CURLOPT_HTTPHEADER, array(
								   'Authorization:Bearer '. $this->accesstoken));
					curl_setopt($ch, CURLOPT_POST, 1);
					curl_setopt($ch, CURLOPT_POSTFIELDS, $data);
					curl_setopt($ch, CURLOPT_SSL_VERIFYPEER, $this->ssl_verifypeer);
					curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
					curl_setopt($ch, CURLOPT_FAILONERROR, true);
					$responseData = curl_exec($ch);
					
					if(curl_errno($ch)) {
						$curlError = curl_error($ch);
						if($url === ''){
							$curlError = 'URL not set.';
						}
					}else{
						$responseData = json_decode($responseData);
						$responseCode = $responseData->result->code;
					}
					curl_close($ch);
					
					if(isset($responseData->id)){
						$responseID = $responseData->id;
						
						$formClass = '';
						
						echo '<script src="'.$this->process_checkout_url.'/v1/paymentWidgets.js?checkoutId='.$responseID.'"></script>';
						
						if($payOption == 'dontsave'){
							echo "<script>
							var wpwlOptions = {
								style: 'plain',
								disableCardExpiryDateValidation: true,
								iframeStyles: {
									'card-number-placeholder': {
										'font-size': '17px'
									},
									'cvv-placeholder': {
										'font-size': '17px'
									}
								}
							}
							function validateExpiry(e){
								var currentYear = new Date().getFullYear();
								
								var expiry = jQuery('.wpwl-control-expiry').val();
								expiry = expiry.replace(/\s/g, '');
							  
								var currentDate = new Date();
								var inputYear = parseInt(expiry.substr(3, 2), 10) + 2000;
								var inputMonth = parseInt(expiry.substr(0, 2), 10) - 1;
			
								var expiryDate = new Date(inputYear, inputMonth, 1);
			
								if (expiryDate < currentDate) {
									if(!jQuery('.wpwl-control-expiry').hasClass('error')){
										jQuery('.wpwl-control-expiry').addClass('error').after('<div class=\"wpwl-hint wpwl-hint-cardHolderError\">Please note: Expiry date is in the past or not valid.</div>');
									}
								} else {
									jQuery('.wpwl-control-expiry').removeClass('error');
									jQuery('.wpwl-hint.wpwl-hint-cardHolderError').remove();
								}
							}
							</script>";
						}
						
						if($payOption == 'saveinfo'){
							echo "<script>
							var wpwlOptions = {
								style: 'plain',
								disableCardExpiryDateValidation: true,
								iframeStyles: {
									'card-number-placeholder': {
										'font-size': '17px'
									},
									'cvv-placeholder': {
										'font-size': '17px'
									}
								},
								  onBeforeSubmitCard: function(e){
									return validateExpiry(e);
								  }
							}
							function validateExpiry(e){
								var currentYear = new Date().getFullYear();
								
								var expiry = jQuery('.wpwl-control-expiry').val();
								expiry = expiry.replace(/\s/g, '');
							  
								var currentDate = new Date();
								var inputYear = parseInt(expiry.substr(3, 2), 10) + 2000;
								var inputMonth = parseInt(expiry.substr(0, 2), 10) - 1;
			
								var expiryDate = new Date(inputYear, inputMonth, 1);
			
								if (expiryDate < currentDate) {
									jQuery('.wpwl-control-expiry').addClass('wpwl-has-error').after('<div class=\"wpwl-hint wpwl-hint-cardHolderError\">Expiry date is in the past.</div>');
									return false;
								} else {
									return true;
								}
							}
							</script>";
							echo '
							<style>
							#wpwl-registrations {display:none !important;}
							</style>
							';
						}
						
						if($payOption == 'savedcards'){
							echo '<script>
							var wpwlOptions = {registrations: {requireCvv: false, hideInitialPaymentForms: true}, disableCardExpiryDateValidation: true};
							</script>';
							echo '
							<style>
							[data-action="show-initial-forms"] {display:none !important;}
							</style>
							';
						}
						
						$brands = 'VISA MASTER AMEX DINERS';
						
						echo '<form action="'.WC_PEACH_SITE_URL.'?wc-api=WC_Peach_Payments" class="paymentWidgets'.$formClass.'" data-brands="'.$brands.'"></form>';
					}else{
						if(isset($responseData->result->description) && isset($responseData->result->code)){
							$logger_info['errors'] = array(
								'Order ID' => $order_id,
								'Response Code' => $responseCode,
								'Response' => (array)$responseData,
							);
							wc_add_notice(  "Error [".$responseCode."] - ".$responseData->result->description.".", 'error' );
							$order->add_order_note( 'Peach Error ['.$responseCode.'] - '.$responseData->result->description.'.',0,false);
						}else if(isset($curlError)){
							$logger_info['errors'] = array(
								'Order ID' => $order_id,
								'Response' => 'Error [Curl] '.$curlError
							);
							$order->add_order_note( 'Peach Error [Curl] - '.$curlError,0,false);
							wc_add_notice(  'Error [Curl] - '.$curlError, 'error' );
						}else{
							$logger_info['errors'] = array(
								'Order ID' => $order_id,
								'Response' => (array)$responseData,
							);
							$order->add_order_note( 'Peach Error [Unknown] - Please contact Peach Payments.',0,false);
							wc_add_notice(  'Error [Unknown] - Please contact Peach Payments.', 'error' );
						}
						$logger->info( "\n".print_r($logger_info, true)."\n\n", array( 'source' => 'peach-receipt-page' ) );
						wp_safe_redirect($this->checkout_page_url);
						exit;
					}
				}
			}
			
		}
		
		public function getSubscriptionInfo($subscriptions){
			$date_end = '9999-12-31';
			$frequency = '0001';
			foreach($subscriptions as $subscription){
				$date = $subscription->get_date( 'end');
				$period = $subscription->get_billing_period();
				$frequency = $subscription->get_billing_interval();
				if($date != '0'){
					$date_end = date("Y-m-d", strtotime($date) );
				}
				if($period == 'year'){
					$frequency = $frequency * 365;
				}elseif($period == 'month'){
					$frequency = $frequency * 31;
				}elseif($period == 'week'){
					$frequency = $frequency * 7;
				}
				
				$frequency = str_pad( $frequency, 4, "0", STR_PAD_LEFT );
			}
			
			return array($date_end, $frequency);
		}
		
		public function ppBasketValues($items,$order_id){
			foreach($items as $item_id => $item) {
				$_product =  $item->get_product(); 
				$ppItems[]=array('sku'		=>$_product->get_sku(),
							   'name'		=>$_product->get_name(),
							   'price'		=>$item->get_total(),
							   'quantity'	=>$item->get_quantity(),
							   'product_type'=>$_product->get_type()
							   );
			}
			foreach ($ppItems as &$item) {
				$getArrayDataForamtted[]= $this->getItemTest($item);
			}
			return $getArrayDataForamtted;
		}
		
		function ppProductType($order_id,$product_id){
			global $woocommerce;
			$order = wc_get_order( $order_id );
			$ppProductFlag=false;
				if(class_exists( 'WC_Pre_Orders_Order' ) && (WC_Pre_Orders_Order::order_contains_pre_order( $order_id )) ){
					if(WC_Pre_Orders_Order::order_will_be_charged_upon_release($order)){
						$product_type="pre-order on release";
					}else{
						$product_type="pre-order upfront";
					}
					$ppProductFlag=true;
				}
				if(function_exists( 'wcs_order_contains_subscription' ) && (wcs_order_contains_subscription( $order_id ) || wcs_order_contains_renewal( $order_id )) ){
					if(wcs_order_contains_subscription( $order_id )){
						$product_type="subscription";
					}else{
						$product_type=" pending renewal order";
					}
					$ppProductFlag=true;
				}
				if(!$ppProductFlag){
					$product_type="once-off";
				}
			return $product_type;
		}
		
		function getItemTest($item) {
			return <<<HTML
			{  
			'name': '{$item['name']}',
			'sku': '{$item['sku']}',  
			'price': '{$item['price']}',
			'quantity': '{$item['quantity']}',
			'product_type':'{$item['product_type']}',
			}
			HTML;
		}
			
		//Process refunds
		public function process_refund( $order_id, $amount = null, $reason = '' ) {
			$order = wc_get_order( $order_id );
			$seqOrderID = $order_id;
			
			if($this->orderids != 'yes'){
				$plugin_support = new pluginSupport();
				$seqOrderID = $plugin_support->sequentialNumbers($order, 0);
			}
			
			$id = get_post_meta( $order_id, 'payment_order_id', true );
			
			$status = $this->checkStatus($seqOrderID);
			if($status == $this->success_code){
				return $this->checkoutRefund($id, $amount, $order, $seqOrderID );
			}else{
				return $this->cardRefund($id, $amount, $order, $seqOrderID);
			}
			
		}
		
		public function checkStatusPre($id) {
			$logger = wc_get_logger();
			$logger_info = array();
			$logger_info['settings'] = $this->logger_info_settings;

			$url = $this->request_pre_status_url;
			
			$orderID = $seqOrderID = $id;
			if($this->orderids != 'yes'){
				$plugin_support = new pluginSupport();
				$seqOrderID = $plugin_support->sequentialNumbers($orderID, 1);
			}
			
			$auth_bearer = get_post_meta( $seqOrderID , 'payment_auth_bearer', true );
			if(!isset($auth_bearer) || $auth_bearer == ''){
				$auth_bearer = $this->accesstoken;
			}
			
			$url .= "?entityId=".$this->secureid."&merchantTransactionId=".$id;
			
			$logger_info['url'] = $url;
			
			$ch = curl_init();
			curl_setopt($ch, CURLOPT_URL, $url);
			curl_setopt($ch, CURLOPT_HTTPHEADER, array(
						   'Authorization:Bearer '. $auth_bearer));
			curl_setopt($ch, CURLOPT_CUSTOMREQUEST, 'GET');
			curl_setopt($ch, CURLOPT_SSL_VERIFYPEER, $this->ssl_verifypeer);
			curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
			curl_setopt($ch, CURLOPT_FAILONERROR, true);
			
			$responseData = curl_exec($ch);
			$response = json_decode($responseData);
			
			if(curl_errno($ch)) {
				$curlError = curl_error($ch);
				return array($curlError, $seqOrderID, '', '');
			}
			curl_close($ch);
			
			if(isset($response->result->code)){
				return array($response->result->code, $seqOrderID, $response->payments[0]->id, $response->payments[0]->paymentBrand);
			}else{
				$logger_info['error'] = array(
					'Order' => $seqOrderID,
					'Auth Bearer' => $auth_bearer,
					'Response Code' => 'No Code',
					'Response' => $response,
				);
				$logger->info( "\n".print_r($logger_info, true)."\n\n", array( 'source' => 'peach-checkstatuspre' ) );
				return array('000', $seqOrderID, '', '');
			}
		}
		
		public function checkStatus($id) {
			$logger = wc_get_logger();
			$logger_info = array();
			$logger_info['settings'] = $this->logger_info_settings;
			
			$url = $this->request_status_url;
			
			$sig_string = 'authentication.entityId'.$this->secureid.'merchantTransactionId'.$id;
			$secret = $this->secrettoken;
			$signature = hash_hmac('sha256', $sig_string, $secret);
			
			$url .= "?authentication.entityId=".$this->secureid."&merchantTransactionId=".$id."&signature=".$signature;
			
			$logger_info['url'] = $url;
			
			$ch = curl_init();
			curl_setopt($ch, CURLOPT_URL, $url);
			curl_setopt($ch, CURLOPT_HTTPHEADER, array(
						   'Authorization:Bearer '. $this->accesstoken));
			curl_setopt($ch, CURLOPT_CUSTOMREQUEST, 'GET');
			curl_setopt($ch, CURLOPT_SSL_VERIFYPEER, $this->ssl_verifypeer);
			curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
			curl_setopt($ch, CURLOPT_FAILONERROR, true);
			
			$responseData = curl_exec($ch);
			$response = json_decode($responseData);
			if(curl_errno($ch)) {
				$curlError = curl_error($ch);
				return $curlError;
			}
			curl_close($ch);
			
			if(isset($response->merchantTransactionId)){
				return $this->success_code;
			}else{
				$logger_info['error'] = array(
					'Order' => $id,
					'Response Code' => 'No Code',
					'Response' => $response,
				);
				$logger->info( "\n".print_r($logger_info, true)."\n\n", array( 'source' => 'peach-checkstatus' ) );
				
				return '000';
			}
		}
		
		public function cardRefund($id, $amount, $order, $seqOrderID){
			$logger = wc_get_logger();
			$logger_info = array();
			$logger_info['settings'] = $this->logger_info_settings;

			$url = $this->process_checkout_url."/v1/payments/".$id;
			$logger_info['url'] = $url;
			
			$data = "entityId=" . $this->secureid .
						"&amount=" . $amount .
						"&currency=" . $order->get_currency() .
						"&paymentType=RF";
		
			$ch = curl_init();
			curl_setopt($ch, CURLOPT_URL, $url);
			curl_setopt($ch, CURLOPT_HTTPHEADER, array(
						   'Authorization:Bearer '.$this->accesstoken));
			curl_setopt($ch, CURLOPT_POST, 1);
			curl_setopt($ch, CURLOPT_POSTFIELDS, $data);
			curl_setopt($ch, CURLOPT_SSL_VERIFYPEER, $this->ssl_verifypeer);
			curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
			curl_setopt($ch, CURLOPT_FAILONERROR, true);
			
			$responseData = curl_exec($ch);
			if(curl_errno($ch)) {
				$curlError = curl_error($ch);
				$logger_info['error'] = array(
					'Order' => $seqOrderID,
					'Response Code' => 'CURL',
					'Response' => $curlError,
				);
				$logger->info( "\n".print_r($logger_info, true)."\n\n", array( 'source' => 'peach-cardrefund' ) );
				$order->add_order_note('Peach Refund Failed. '.$curlError,0,false);
				return $curlError;
			}
			curl_close($ch);
			
			$responseData = json_decode($responseData);
			$responseCode = $responseData->result->code;
			
			if($responseCode == $this->success_code){
				$order->add_order_note('Peach #'.$seqOrderID.' [Card] Refunded: '.$order->get_currency().$amount,0,false);
				return true;
			}else{
				$logger_info['error'] = array(
					'Order' => $seqOrderID,
					'Response Code' => $responseCode,
					'Response' => $responseData,
				);
				$logger->info( "\n".print_r($logger_info, true)."\n\n", array( 'source' => 'peach-cardrefund' ) );
				$order->add_order_note('Peach #'.$seqOrderID.' [Card] Refund Failed. '.$responseData->result->description,0,false);
				return false;
			}
		}
		
		public function checkoutRefund($id, $amount, $order, $seqOrderID){
			$logger = wc_get_logger();
			$logger_info = array();
			$logger_info['settings'] = $this->logger_info_settings;

			$amount = $amount;
			$currency = $order->get_currency();
			$paymentType = 'RF';
						
			$sig_string = 'amount'.$amount.'authentication.entityId'.$secureid.'currency'.$currency.'id'.$id.'paymentType'.$paymentType;
			$secret = $this->secrettoken;
			$signature = hash_hmac('sha256', $sig_string, $secret);
			
			$url = $this->request_refund_url;
			$data = "authentication.entityId=" .$this->secureid.
						"&amount=" .$amount.
						"&currency=" .$currency.
						"&paymentType=" .$paymentType.
						"&id=" .$id.
						"&signature=" .$signature;
			
			$ch = curl_init();
			curl_setopt($ch, CURLOPT_URL, $url);
			curl_setopt($ch, CURLOPT_HTTPHEADER, array(
						   'Authorization:Bearer '. $this->accesstoken));
			curl_setopt($ch, CURLOPT_POST, 1);
			curl_setopt($ch, CURLOPT_POSTFIELDS, $data);
			curl_setopt($ch, CURLOPT_SSL_VERIFYPEER, $this->ssl_verifypeer);
			curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
			curl_setopt($ch, CURLOPT_FAILONERROR, true);
			
			if(curl_errno($ch)) {
				$curlError = curl_error($ch);
				$order->add_order_note('Peach Refund Failed. '.$curlError,0,false);
				return $curlError;
			}
			curl_close($ch);
			
			$responseData = json_decode($responseData);
			$responseCode = $responseData->result->code;
			
			if($responseCode == $this->success_code){
				$order->add_order_note('Peach #'.$seqOrderID.' [Checkout] Refunded: '.$order->get_currency().$amount,0,false);
				return true;
			}else{
				$logger_info['errors'] = array(
					'Order ID' => $seqOrderID,
					'Response Code' => $responseCode,
					'Response' => $responseData,
				);
				$logger->info( "\n".print_r($logger_info, true)."\n\n", array( 'source' => 'peach-checkoutrefund' ) );
				$order->add_order_note('Peach #'.$seqOrderID.' [Checkout] Refund Failed. '.$responseData->result->description,0,false);
				return false;
			}
		}
			
		//Checks the order for virtual or downloadable products
		public function check_orders_products( $order = false ) {
			$force_complete = false;
			$mixed_products = false;
	
			if ( false !== $order && count( $order->get_items() ) > 0 ) {
				foreach ( $order->get_items() as $item ) {
					$_product = $this->get_item_product( $item, $order );
					if ( $_product ) {
						if ( $_product->is_downloadable() || $_product->is_virtual() ) {
							$force_complete = true;
						} else {
							$mixed_products = true;
						}
					}
				}
			}
			if ( true === $mixed_products ) {
				$force_complete = false;
			}
			
			return $force_complete;
		}
		
		public function get_item_product( $item = false, $order = false ) {
			$return = 0;
			if ( false !== $item ) {
				if ( defined( 'WC_VERSION' ) && WC_VERSION >= 3.0 ) {
					$return = $item->get_product();
				} else {
					$return = $order->get_product_from_item( $item );
				}
			}
			return $return;
		}
		
		//Hosted Payment Methods Webhook
		public function switch_payment_response() {
			$logger = wc_get_logger();
			$logger_info = array();
			$logger_info['settings'] = $this->logger_info_settings;
			
			global $woocommerce;

			$order_id = '';
			
			if(isset($_POST)){
				$order_id = $_POST['merchantTransactionId'];
			}		
			
			$seqOrderID = $order_id;
			$order = wc_get_order( $seqOrderID );
			
			if($this->orderids != 'yes'){
				$plugin_support = new pluginSupport();
				$seqOrderID = $plugin_support->sequentialNumbers($order, 0);
			}
			
			if ( false !== $order ) {
				$current_order_status = $order->get_status();
				$force_complete = false;
	
				if ( ('complete' !== $current_order_status) && ($this->peach_order_status !== $current_order_status)  && ('pre-ordered' !== $current_order_status) ) {
						$this->pp_handle_switch_request( stripslashes_deep( $_POST ) );
						
						wp_safe_redirect( $this->get_return_url( $order ) );
								exit;
				}
				$resultCode =  esc_html($_POST['result_code']);
	
				if ( !empty($resultCode)) {
					$logger_info['errors'] = array(
						'SwitchStep' => '1/4',
						'OrderID' => $order_id,
						'ResponseCode' => $resultCode,
						'Response' => $_POST,
					);
					$logger->info( "\n".print_r($logger_info, true)."\n\n", array( 'source' => 'peach-switch-payment-response' ) );
					$order->add_order_note( 'Peach Switch Webhook Error['.$resultCode.']. Step 1/4.',0,false);
				
					wp_safe_redirect( $this->get_return_url( $order ) );
								exit;
	
				}
			}else{
				$logger_info['errors'] = array(
					'SwitchStep' => '1/4',
					'OrderID' => $order_id,
					'Response' => 'Couldn\'t retrieve order.',
				);
				$logger->info( "\n".print_r($logger_info, true)."\n\n", array( 'source' => 'peach-switch-payment-response' ) );
			}
	
		}
		
		public function switch_payment_webhook_response() {
			$logger = wc_get_logger();
			$logger_info = array();
			$logger_info['settings'] = $this->logger_info_settings;

			global $woocommerce;
			$order_id = $seqOrderID = '';
			
			if(isset($_POST)){
				$raw_id = $_POST['merchantTransactionId'];
				if (str_contains($raw_id, 'Checkout_')) { 
					$raw_id = str_replace('Checkout_', '', $raw_id);
				}
				$order_id = $seqOrderID = $raw_id;
			}
			
			if($this->orderids != 'yes'){
				$plugin_support = new pluginSupport();
				$seqOrderID = $plugin_support->sequentialNumbers($order_id, 1);
			}
					
			$order = wc_get_order( $seqOrderID );
	
			if ( false !== $order &&  $order_id != '') {
				$current_order_status = $order->get_status();
				$force_complete = false;
	
				if ($this->peach_order_status !== $current_order_status) {
					$this->pp_handle_switch_webhook_request( stripslashes_deep( $_POST ) );		
				}
			}else{
				$logger_info['errors'] = array(
					'OrderID' => $order_id,
					'Response' => 'Could not retrive order information.',
				);
				$logger->info( "\n".print_r($logger_info, true)."\n\n", array( 'source' => 'peach-switch-payment-webhook-response' ) );
				$peachpayment_error  = true;
				$peachpayment_error_message = 'Could not retrieve order information.';
			}
	
		}
		
		public function pp_handle_switch_request( $data ) {
			$logger = wc_get_logger();
			$logger_info = array();
			$logger_info['settings'] = $this->logger_info_settings;
			
			$peachpayment_error  = false;
			$peachpayment_done   = false;
			
			if ( false === $data ) {
				$peachpayment_error  = true;
				$peachpayment_error_message = 'Bad access of page';
				$logger_info['errors'] = array(
					'Step' => '1/4',
					'Response' => 'Bad access of page',
				);
			}
			
			$order_id = $data['merchantTransactionId'];
			
			$seqOrderID = $order_id;
			if($this->orderids != 'yes'){
				$plugin_support = new pluginSupport();
				$seqOrderID = $plugin_support->sequentialNumbers($order_id, 1);
			}
			
			$order          = wc_get_order( $seqOrderID );
			$original_order = $order;
			$debug_email    = 'fhagen@semantica.co.za';
			$vendor_name    = get_bloginfo( 'name', 'display' );
			$vendor_url     = home_url( '/' );
	
			// Verify security signature
			if ( ! $peachpayment_error && ! $peachpayment_done ) {
	
				
				// If signature different, log for debugging
				if ( ! $this->pp_validate_signature( $data ) ) {
					$peachpayment_error         = true;
					$logger_info['errors'] = array(
						'Step' => '2/4',
						'Response' => 'Security signature mismatch',
					);
					$peachpayment_error_message = 'Security signature mismatch';
				}
			}
			// Get internal order and verify it hasn't already been processed
			if ( ! $peachpayment_error && ! $peachpayment_done ) {
				
	
				// Check if order has already been processed
				if ( ($this->peach_order_status === self::get_order_prop( $order, 'status' )) || ('completed' === self::get_order_prop( $order, 'status' ) )) {
					$peachpayment_done = true;
				}
			}
			
			// If an error occurred
			if ( $peachpayment_error ) {

			} elseif ( ! $peachpayment_done ) {
	
	
				$resultCode =  esc_html($data['result_code']);
				if ($resultCode == $this->success_code ) {
				
					$this->handle_switch_payment_complete( $data, $order );
	
				}else if($resultCode == '000.200.000' || $resultCode == '000.200.100'){
					
				} else{
					$logger_info['errors'] = array(
						'Step' => '3/4',
						'OrderID' => $seqOrderID,
						'ResponseCode' => $resultCode,
						'Response' => $data,
					);
					$logger->info( "\n".print_r($logger_info, true)."\n\n", array( 'source' => 'peach-pp-handle-switch-request' ) );
					$this->handle_switch_payment_failed( $data, $order );
				}
			} // End if().
	
		}
		
		public function pp_validate_signature( $data ) {
			assert(count($data) !== 0, 'Error: Sign data can not be empty');
			assert(function_exists('hash_hmac'), 'Error: hash_hmac function does not exist');

			$tmp = [];
			foreach ($data as $key => $datum) {           
				$tmp[str_replace('_', '.', $key)] = $datum;
			}
	
			ksort($tmp, SORT_STRING);
	
			$peachPaymentsignDataRaw = '';
			foreach ($tmp as $key => $datum) {
				if ($key !== 'signature') {                
					$peachPaymentsignDataRaw .= $key . $datum;
				}
			}
		 
			$peachPaymentsignData = hash_hmac('sha256', $peachPaymentsignDataRaw, $this->secrettoken);	    
			$result = $data['signature'] === $peachPaymentsignData;
			return $result;
		}
		
		public static function get_order_prop( $order, $prop ) {
			switch ( $prop ) {
				case 'order_total':
					$getter = array( $order, 'get_total' );
					break;
				default:
					$getter = array( $order, 'get_' . $prop );
					break;
			}
	
			return is_callable( $getter ) ? call_user_func( $getter ) : $order->{ $prop };
		}
		
		public function handle_switch_payment_failed( $data, $order ) {
			if($order->get_status() != 'completed' || $order->get_status() != $this->peach_order_status){
				$order->add_order_note( 'Peach Payment via Switch Webhook Successfull.',0,false);
				$order->update_status('failed', sprintf(__('Switch Payment Failed: Payment Response is "%s" - Peach Payments.', 'woocommerce-gateway-peach-payments'), woocommerce_clean($data['result_description'])  ) );
			}
				wp_safe_redirect( $this->get_return_url( $order ) );
				exit;	
		}
		
		public function pp_handle_switch_webhook_request( $data ) {
			$logger = wc_get_logger();
			$logger_info = array();
			$logger_info['settings'] = $this->logger_info_settings;
	
			$peachpayment_error  = false;
			$peachpayment_done   = false;
			
			if ( false === $data ) {
				$logger_info['errors'] = array(
					'Step' => '1/4',
					'Response' => 'Bad access of page',
				);
					
				$peachpayment_error  = true;
				$peachpayment_error_message = __( 'Error: Bad access of page', 'woocommerce-gateway-peach-payment' );
			}
			
			$raw_id = $data['merchantTransactionId'];
			$embed_checkout = false;
			if (str_contains($raw_id, 'Checkout_')) {
				$embed_checkout = true; 
				$raw_id = str_replace('Checkout_', '', $raw_id);
			}
			
			$order_id       =  $raw_id;
			
			$debug_email    = 'fhagen@semantica.co.za';
			$vendor_name    = get_bloginfo( 'name', 'display' );
			$vendor_url     = home_url( '/' );
			
			$seqOrderID = $order_id;
			if($this->orderids != 'yes'){
				$plugin_support = new pluginSupport();
				$seqOrderID = $plugin_support->sequentialNumbers($order_id, 1);
			}
			
			$order          = wc_get_order( $seqOrderID );
			$original_order = $order;
	
			// Verify security signature
			if ( ! $peachpayment_error && ! $peachpayment_done ) {
	
				
				// If signature different, log for debugging
				if ( ! $this->pp_validate_signature( $data ) ) {
					$logger_info['errors'] = array(
						'Step' => '2/4',
						'Response' => 'Security signature mismatch',
					);
					$peachpayment_error         = true;
					$peachpayment_error_message = __( 'Security signature mismatch', 'woocommerce-gateway-peach-payment' );
				}
			}
			
			// Get internal order and verify it hasn't already been processed
			if ( ! $peachpayment_error && ! $peachpayment_done ) {
				
	
				// Check if order has already been processed
				if ( ($this->peach_order_status === self::get_order_prop( $order, 'status' )) || ('completed' === self::get_order_prop( $order, 'status' ) )) {
					$peachpayment_done = true;
				}
			}

			// If an error occurred
			if ( $peachpayment_error ) {

			} elseif ( ! $peachpayment_done ) {
				$resultCode =  esc_html($data['result_code']);
				$status = $order->get_status();
				if($status != $this->peach_order_status || $status != 'completed' || $status != 'on-hold' || $status != 'refunded'){
					if ($resultCode == $this->success_code) {
						$order->add_order_note( 'Peach Payment via Switch Webhook Successfull.',0,false);
						$order->update_status($this->peach_order_status, __( 'Peach Switch Webhook ['.$data['result_code'].']:'.woocommerce_clean($data['result_description']).'. ', 'woocommerce' ));
					}else if($resultCode == '000.200.000' || $resultCode == '000.200.100'){

					}else{
						if(!$embed_checkout){
							$logger_info['errors'] = array(
								'Step' => '3/4',
								'OrderID' => $seqOrderID,
								'Response' => $data,
							);
							$logger->info( "\n".print_r($logger_info, true)."\n\n", array( 'source' => 'peach-pp-handle-switch-webhook-request' ) );
							$order->update_status('failed', __( 'Peach Switch Webhook ['.$data['result_code'].']:'.woocommerce_clean($data['result_description']).'. ', 'woocommerce' ));
						}
						
						if($embed_checkout){
							if($resultCode == '100.396.101'){
								$logger_info['errors'] = array(
									'Step' => '3/4',
									'OrderID' => $seqOrderID,
									'Response' => $data,
									'Embeded' => 'cancelled by user',
									'URL' => $order->get_cancel_order_url_raw(),
								);
								$logger->info( "\n".print_r($logger_info, true)."\n\n", array( 'source' => 'peach-pp-handle-switch-webhook-request' ) );
								$order->update_status('cancelled', __( 'Peach Switch Webhook ['.$data['result_code'].']:'.woocommerce_clean($data['result_description']).'. ', 'woocommerce' ));
								wp_redirect($order->get_cancel_order_url_raw());
								exit;
							}else{
								$logger_info['errors'] = array(
									'Step' => '3/4',
									'OrderID' => $seqOrderID,
									'Response' => $data,
									'Embeded' => 'payment error',
									'URL' => $order->get_checkout_payment_url(),
								);
								$logger->info( "\n".print_r($logger_info, true)."\n\n", array( 'source' => 'peach-pp-handle-switch-webhook-request' ) );
								$order->update_status('failed', __( 'Peach Switch Webhook ['.$data['result_code'].']:'.woocommerce_clean($data['result_description']).'. ', 'woocommerce' ));
								wp_redirect($order->get_checkout_payment_url());
								exit;
							}
						}
					}
				}
				status_header( 200 );
				
			} // End if()
	
		}
		
		public function handle_switch_payment_complete( $data, $order ) {
			
			if($order->get_status() != 'completed' || $order->get_status() != $this->peach_order_status){
				$order->add_order_note( 'Peach Payment via Switch Webhook Successfull.',0,false);
				$order->update_status($this->peach_order_status, __( 'Peach Switch Webhook:'.woocommerce_clean($data['result_description']).'. ', 'woocommerce' ));
			}
					
		}
		
		public function peach_validate_checkout( $fields, $errors ){

			$subscribe_test = array(false, false);
			
			$creatAccount = false;
			
			$creatAccountOpt = get_option('woocommerce_enable_signup_and_login_from_checkout');
			$creatAccountOptSubscribe = get_option('woocommerce_enable_signup_from_checkout_for_subscriptions');
			
			if(null !== get_option('woocommerce_enable_signup_and_login_from_checkout') && $creatAccountOpt == 'yes'){
				$creatAccount = true;
			}else if(null !== get_option('woocommerce_enable_signup_from_checkout_for_subscriptions') && $creatAccountOptSubscribe == 'yes'){
				$creatAccount = true;
			}
				
			if($subscribe_test[1] == '1'){
				$errors->add( 'validation', 'You have subscription products with normal products in your card. Peach Payments cannot process mixed baskets at this stage.' );
			}else if($subscribe_test[0] == '1' && !is_user_logged_in() && !$creatAccount){
				$errors->add( 'validation', 'Please login first or create an account in order to purchase subscription products.' );
			}
		}
		
		public function process_payment_status() {

		}
	
		//Payon Webhook Response
		public function wc_payon_webhook_peach_payments_handler(){
			$logger = wc_get_logger();
			$logger_info = array();
			$logger_info['settings'] = $this->logger_info_settings;
			
			$jsonString = file_get_contents('php://input');
			
			$jsonObj = json_decode($jsonString, true);
			$headers = apache_request_headers();
			
			foreach ($headers as $header => $value) {
				$header = strtolower($header);
				if($header=='x-initialization-vector'){
						$headerVector=$value;
				}
				if($header=='x-authentication-tag'){
						$headerTag=$value;
				}	    
			} 
			
			if(SODIUM_LIBRARY_VERSION){
				$key = hex2bin($this->card_webhook_key);
				$iv = hex2bin($headerVector);
				$auth_tag = hex2bin($headerTag);
				$cipher_text = hex2bin($jsonObj['encryptedBody']);
				
				$result = openssl_decrypt($cipher_text, "aes-256-gcm", $key, OPENSSL_RAW_DATA, $iv, $auth_tag);
			}else{
				$key = hex2bin($this->card_webhook_key);
				$iv = hex2bin($headerVector);
				$auth_tag = hex2bin($headerTag);
				$cipher_text = hex2bin($jsonObj['encryptedBody']);
				
				$result = openssl_decrypt($cipher_text, "aes-256-gcm", $key, OPENSSL_RAW_DATA, $iv, $auth_tag);
	
			}
			$resultArray=json_decode($result);
	
			global $woocommerce;

			$parsed_response=$resultArray->payload;

			$order_id = $parsed_response->merchantTransactionId;
			
			$seqOrderID = $order_id;
			if($this->orderids != 'yes'){
				$plugin_support = new pluginSupport();
				$seqOrderID = $plugin_support->sequentialNumbers($order_id, 1);
			}
			
			$order    = wc_get_order( $seqOrderID );
			$resultType=esc_html($resultArray->type);
			if($resultType=='PAYMENT'){
				$statusCode = $this->handle_payon_all_payment($parsed_response,$order);
				if($statusCode){
					status_header( 200 );
				}else{
					status_header( 200 );
				}
			} 	

		}
		
		public function handle_payon_all_payment($parsed_response,$order){
			if ( false !== $order ) {
				$current_order_status = $order->get_status();
				$force_complete = false;
				if($order->get_status() != 'completed' || $order->get_status() != $this->peach_order_status){
					 
					if ( $parsed_response->paymentType  == 'DB' || $parsed_response->paymentType  == 'PA' ) {
						
						$order_id = $parsed_response->merchantTransactionId;
						
						$seqOrderID = $order_id;
						if($this->orderids != 'yes'){
							$plugin_support = new pluginSupport();
							$seqOrderID = $plugin_support->sequentialNumbers($order_id, 1);
						}
							
						$order = wc_get_order( $seqOrderID );
						
						if ( preg_match('/^(000\.400\.0[^3]|000\.400\.100)/',$parsed_response->result->code) || preg_match('/^(000\.000\.|000\.100\.1|000\.[36])/',$parsed_response->result->code)) {
							
							//FH Update 20231219
							if(isset($parsed_response->registrationId) && $parsed_response->registrationId != ''){
								add_post_meta( $seqOrderID, 'payment_registration_id', $parsed_response->registrationId );
							}
							
							$order->add_order_note( 'Peach Payment via Payon Webhook Successfull.',0,false);
							$order->update_status($this->peach_order_status, __( 'Peach Payon Webhook:'.$parsed_response->result->description.'. ', 'woocommerce' ));
							return true;
							
						} 
						else {
							if($order->get_status() == 'completed' || $order->get_status() == $this->peach_order_status){
								return true;
							}else{
								return false;
							}
						}
					
					}
				}
			}
		}
		
		//Show plugin changes
		function peach_in_plugin_update_message() {
			$response = wp_remote_request(WC_PEACH_README_URL);
	
			if ( is_wp_error( $response ) || $response['response']['code'] != 200 )
				return;
	
			$matches = null;
			$regexp = '~==\s*Changelog\s*==\s*=\s*[0-9.]+\s*=(.*)(=\s*' . preg_quote( '2.9.9' ) . '\s*=|$)~Uis';
	
			$body = $response['body'];
			if ( !preg_match( $regexp, $body, $matches ) )
				return;
	
			$changelog = (array) preg_split( '~[\r\n]+~', trim( $matches[1] ) );
	
			echo '
			<div style="color: #f00;">' . __( 'Take a minute to update, here\'s why:', 'w3-total-cache' ) . '</div>
			<div style="font-weight: normal;height:250px;overflow:auto">
				<ul style="list-style: disc; margin-left: 20px;margin-top:0;">';
				foreach ( $changelog as $index => $line ) {
					if ( preg_match( '~^\s*\*\s*~', $line ) ) {
						$line = preg_replace( '~^\s*\*\s*~', '', htmlspecialchars( $line ) );
						echo '<li style="width: 50%; margin: 0; float: left;">' . $line . '</li>';
					}
				}
				echo '
				</ul>
			</div>
			<div style="clear: left;"></div>';
		}
		
		public static function card_search($newCard, $checkOld){
			$found = false;
			$myCards = get_user_meta( get_current_user_id(), 'my-cards', true);
			$myOldCards = get_user_meta( get_current_user_id(), '_peach_payment_id', false);
			
			if(isset($myCards) && is_array($myCards)){
				foreach($myCards as $card){
					if(!$found){
						if($newCard['num'] == $card['num']){
							if(isset($card['exp_year']) && $card['exp_year'] != ''){
								if($card['exp_year'] == $newCard['exp_year']){
									if(isset($card['exp_month']) && $card['exp_month'] != ''){
										if($card['exp_month'] == $newCard['exp_month']){
											$found = true;
										}
									}
								}
							}
						}
					}
				}
			}
			
			if($checkOld && !$found){
				if(isset($myOldCards) && is_array($myOldCards)){
					foreach($myOldCards as $card){
						$num = str_replace("xxxx-", "", $newCard['num']);
						if(!$found){
							if($num == $card['active_card'] && $newCard['exp_year'] == $card['exp_year'] && $newCard['exp_month'] == $card['exp_month']){
								$found = true;
							}
						}
					}
				}
			}
			
			return $found;
		}
		
		public function validate_order($order) {
			$result = false;
			if(is_object($order)){
				$order_id = $order->get_id();
				if(isset($order_id) && $order_id != ''){
					$result = true;
				}
			}
			
			return $result;
		}

	}

}


function woocommerce_gateway_peach_init() {
	
	//CleanTalk Plugin Compatibility
	if(null !== get_option('cleantalk_settings')){
		$cleanTalk = get_option('cleantalk_settings');
		
		if(isset($cleanTalk['exclusions__urls'])){
			$cleanTalk['exclusions__urls'] = '(\/order-pay\/)';
		}
		if(isset($cleanTalk['exclusions__urls__use_regexp'])){
			$cleanTalk['exclusions__urls__use_regexp'] = 1;
		}
		update_option('cleantalk_settings', $cleanTalk);
	}
	
	if ( class_exists( 'WooCommerce' ) ) {
	
		load_plugin_textdomain( 'woocommerce-gateway-peach-payments', false, trailingslashit( dirname( plugin_basename( __FILE__ ) ) ) );
		
		add_action( 'admin_enqueue_scripts', 'peach_enqueue_admin_scripts' );
		add_action( 'wp_enqueue_scripts', 'peach_required_scripts' );
		
		
		$ssl = is_ssl();
		if(!$ssl){
			add_action( 'admin_notices', 'woocommerce_peach_wc_ssl' );
			return;
		}
		
		if ( version_compare( WC_VERSION, WC_PEACH_MIN_WC_VER, '<' ) ) {
			add_action( 'admin_notices', 'woocommerce_peach_wc_not_supported' );
			return;
		}
		
		if( class_exists( 'WC_Sequential_Order_Numbers_Pro_Loader' ) && class_exists( 'WC_Sequential_Order_Numbers_Loader' )){
			add_action( 'admin_notices', 'woocommerce_peach_sequential' );
			return;
		}
		
		woocommerce_gateway_peach();
	
	}else{
		add_action( 'admin_notices', 'woocommerce_peach_missing_wc_notice' );
		return;
	}
	
}

function get_the_user_ip() {
	if ( ! empty( $_SERVER['HTTP_CLIENT_IP'] ) ) {
		$ip = $_SERVER['HTTP_CLIENT_IP'];
	} elseif ( ! empty( $_SERVER['HTTP_X_FORWARDED_FOR'] ) ) {
		$ip = $_SERVER['HTTP_X_FORWARDED_FOR'];
	} else {
		$ip = $_SERVER['REMOTE_ADDR'];
	}
	return apply_filters( 'wpb_get_ip', $ip );
}

//SSL Check
function woocommerce_peach_wc_ssl() {
	echo '<div class="error"><p><strong>' . esc_html__( 'Peach Payments has detected that there are no valid SSL Certificate installed on your website. This payment gateway might not function optimal without it!') . '</strong></p></div>';
}

//WC Required Notice
function woocommerce_peach_missing_wc_notice() {
	echo '<div class="error"><p><strong>' . sprintf( esc_html__( 'Peach Payments requires WooCommerce to be installed and active. You can download %s here.', 'woocommerce-gateway-peach-payments' ), '<a href="https://woocommerce.com/" target="_blank">WooCommerce</a>' ) . '</strong></p></div>';
}

//WC Supported Notice
function woocommerce_peach_wc_not_supported() {
	echo '<div class="error"><p><strong>' . sprintf( esc_html__( 'Peach Payments requires WooCommerce %1$s or greater to be installed and active. WooCommerce %2$s is no longer supported.', 'woocommerce-gateway-peach-payments' ), WC_PEACH_MIN_WC_VER, WC_VERSION ) . '</strong></p></div>';
}

//Sequential Order Numbers Notice
function woocommerce_peach_sequential() {
	echo '<div class="error"><p><strong>' . esc_html__( 'You have two or more Sequential Order Numbers plugins installed. Please enable only one for Peach Payments to processs order numbers correctly.' ) . '</strong></p></div>';
}

//Add link to setting page 
if ( ! function_exists( 'wc_peach_settings_link' ) ) {
	add_filter( 'plugin_action_links_' . plugin_basename(__FILE__), 'wc_peach_settings_link' );
	function wc_peach_settings_link($links) { 
		$settings_link = '<a href="admin.php?page=wc-settings&tab=checkout&section=peach-payments">Settings</a>'; 
		array_unshift($links, $settings_link); 
		return $links; 
	}
}

//Load Admin Scripts
function peach_enqueue_admin_scripts( $hook ) {
    if ( 'woocommerce_page_wc-settings' == $hook && (isset($_GET['section']) && $_GET['section'] == 'peach-payments')) {
        wp_enqueue_script( 'peach-admin-js', plugin_dir_url( __FILE__ ) . '/assets/js/admin-peach.js', array(), '1.0' );
		wp_enqueue_script( 'peach-font-js', 'https://kit.fontawesome.com/4bec4fe625.js');
		wp_enqueue_style( 'peach-admin-css', plugin_dir_url( __FILE__ ) . '/assets/css/admin-peach.css');
		wp_localize_script( 'peach-admin-js', 'peach_plugin', array( 'peach_plugin_url' => WC_PEACH_PLUGIN_URL, 'ajax_url' => admin_url( 'admin-ajax.php' )  ) );
		
		//Include Google Analytics
		wp_enqueue_script('pp_google_anlaytics_external', 'https://www.googletagmanager.com/gtag/js?id=UA-36515646-5');
		wp_enqueue_script('pp_google_anlaytics',WC_PEACH_PLUGIN_URL.'/assets/js/analytics.js');
		
		if( isset($_GET['section']) && (sanitize_text_field($_GET['section'])=='peach-payments' )){
			$analyticsData = array("pp_page_title"=>'ConfigurationForm');
			wp_enqueue_script('pp_google_anlaytics_page_view',WC_PEACH_PLUGIN_URL.'/assets/js/analytics_page_view.js');
			wp_localize_script( "pp_google_anlaytics_page_view", "merchant", $analyticsData );
		}
    }
}

//Load Front-end Scripts
function peach_required_scripts() { 
    wp_register_style( 'peach_front_css', WC_PEACH_PLUGIN_URL.'/assets/css/front-peach.css');
    wp_enqueue_style( 'peach_front_css' );
	
	wp_register_script('peach_front_js', WC_PEACH_PLUGIN_URL.'/assets/js/front-peach.js',array('jquery'),'', true);
    wp_enqueue_script('peach_front_js');
	
	wp_enqueue_script('pp_google_anlaytics_external', 'https://www.googletagmanager.com/gtag/js?id=UA-36515646-5');
	wp_enqueue_script('pp_google_anlaytics',WC_PEACH_PLUGIN_URL.'/assets/js/analytics.js');
	
	wp_localize_script('peach_front_js', 'peach_ajax_object', array( 'ajax_url' => admin_url( 'admin-ajax.php' ), 'ajax_nonce' => wp_create_nonce('ajax-nonce') ) );
	
	//Include Google Analytics
	if ( is_checkout() && !(is_wc_endpoint_url()))  {
		$analyticsData = array("pp_page_title"=>'CartCheckout');			
		wp_enqueue_script('pp_google_anlaytics_page_view',WC_PEACH_PLUGIN_URL.'/assets/js/analytics_page_view.js');
		wp_localize_script( "pp_google_anlaytics_page_view", "merchant", $analyticsData );
	}
	
}

// Our hooked in function – $fields is passed via the filter!
function peach_override_checkout_fields( $fields ) {
     $fields['billing']['billing_peach'] = array(
	 	'type' => 'hidden',
		'required'  => false,
		'class'     => array('form-row-wide'),
		'value'     => 'starter',
		'clear'     => true
     );

     return $fields;
}
add_filter( 'woocommerce_checkout_fields' , 'peach_override_checkout_fields', 9999 ); //express checkout uses priority 99 

function peach_display_order_data_in_admin( $order ){  ?>
	<?php
	$available_gateways = WC()->payment_gateways->get_available_payment_gateways();
	if ( isset( $available_gateways['peach-payments'] ) ) {
		$peachOptions = get_option('woocommerce_peach-payments_settings');
		
		if(isset($peachOptions['checkout_methods_select']) && $peachOptions['checkout_methods_select'] != ''){
			$checkout_methods_select = $peachOptions['checkout_methods_select'];
		}else{
			$checkout_methods_select = array('card','hosted');
		}
		
		if(isset($peachOptions['consolidated_label']) && $peachOptions['consolidated_label'] != ''){
			$hosted_label = $peachOptions['consolidated_label'];
		}else{
			$hosted_label = 'More payment types';
		}

		$value = get_post_meta($order->get_id(), '_billing_peach', true );
		
		//Older version compatability
		if($value != 'saveinfo' && $value != 'savedcards'){
			$default = '';
			foreach($checkout_methods_select as $key => $term){
				if($term == 'hosted'){
					$options['other'] = $hosted_label;
				}else{
					$options['dontsave'] = 'Credit Card';
				}
				if($key == 0){
					$default = $term;
				}
			}
			if (function_exists('woocommerce_wp_select')) {
			?>
                <p class="form-field form-field-wide">
                    <h4><?php _e( 'Peach Payment Options', 'woocommerce' ); ?></h4>
                    <div class="edit_billing_peach">
                        <?php 
                        woocommerce_wp_select( array(
                            'id'      => '_billing_peach',
                            'label'   => __( 'Payment Method', 'woocommerce' ),
                            'options' =>  $options,
                            'value'   => $value,
                            'default' => $default
                        ) );
                        ?>
                    </div>
                </p>
			<?php
			}
		}
	}
}
add_action( 'woocommerce_admin_order_data_after_order_details', 'peach_display_order_data_in_admin' );

function peach_save_extra_details( $post_id, $post ){
    update_post_meta( $post_id, '_billing_peach', wc_clean( $_POST[ '_billing_peach' ] ) );
}
add_action( 'woocommerce_process_shop_order_meta', 'peach_save_extra_details', 45, 2 );

//New My Cards tab on Account Section
function peach_add_cards_support_endpoint() {
    add_rewrite_endpoint( 'my-cards', EP_ROOT | EP_PAGES );
	add_rewrite_endpoint( 'add-card', EP_ROOT | EP_PAGES );
	add_rewrite_endpoint( 'update-subscription', EP_ROOT | EP_PAGES );
} 
add_action( 'init', 'peach_add_cards_support_endpoint' );
  
function peach_cards_support_query_vars( $vars ) {
    $vars[] = 'my-cards';
	$vars[] = 'add-card';
	$vars[] = 'update-subscription';
    return $vars;
}  
add_filter( 'query_vars', 'peach_cards_support_query_vars', 0 );
  
function peach_add_cards_support_link_my_account( $items ) {
    $items['my-cards'] = 'My Cards';
    return $items;
} 
add_filter( 'woocommerce_account_menu_items', 'peach_add_cards_support_link_my_account' );
  
function peach_cards_content() {

	echo '<h2>My Cards</h2>';
	
	$myCards = get_user_meta( get_current_user_id(), 'my-cards', true);
	$myOldCards = get_user_meta( get_current_user_id(), '_peach_payment_id', false);
	
	$combinedCards = generateCards('show', $myCards, $myOldCards, '');
	
	if(!empty($combinedCards)){ 
		$add_cart_url = get_permalink( get_option('woocommerce_myaccount_page_id') ).'add-card/';
		wc_print_notices();
		echo '
		<form method="post" action="'.get_permalink( get_option('woocommerce_myaccount_page_id') ).'my-cards/">
		<table class="woocommerce-MyAccount-orders shop_table shop_table_responsive my_account_orders account-orders-table">
			<thead>
			<tr class="peachCards">
			<th class="woocommerce-orders-table__header"><span class="nobr">Number</span></th>
			<th class="woocommerce-orders-table__header"><span class="nobr">Expiry Date</span></th>
			<th class="woocommerce-orders-table__header"><span class="nobr">Brand</span></th>
			<th class="woocommerce-orders-table__header woocommerce-orders-table__header-order-actions">&nbsp;</th>
			</tr>
			</thead>
			
			<tbody>';
			foreach($combinedCards as $card){
				
				if($card != ''){
					$brand = $card['brand'];
					echo '
					<tr id="'.$card['id'].'" class="woocommerce-orders-table__row peachCards">
							<td class="woocommerce-orders-table__cell">'.$card['num'].'</td>
							<td class="woocommerce-orders-table__cell">'.$card['exp_year'].'/'.$card['exp_month'].'</td>
							<td class="woocommerce-orders-table__cell">'.$brand.'</td>
							<td class="woocommerce-orders-table__cell"><input name="peach_remove_card" class="button" type="button" value="Delete Card" data-id="'.$card['id'].'" data-ver="'.$card['ver'].'"/></td>
					</tr>';
				}
			}
			echo '
			</tbody>
			
		</table>
		</form>
		<div class="peach-add-card-cont"><a class="woocommerce-button woocommerce-Button button peach-add-card" href="'.$add_cart_url.'">'.__('Add Card','woocommerce-gateway-peach-payments').'</a></div>
		';
	}else{
		wc_print_notices();
		echo '<p>No saved cards found.</p>';
	};
}
  
add_action( 'woocommerce_account_my-cards_endpoint', 'peach_cards_content' );

function peach_add_card_content() {
	$logger = wc_get_logger();
	$logger_info = array();
			
	$add_cart_url = get_permalink( get_option('woocommerce_myaccount_page_id') ).'add-card/';
	$cart_url = get_permalink( get_option('woocommerce_myaccount_page_id') ).'my-cards/';
	$peachOptions = get_option('woocommerce_peach-payments_settings');
	$secureid = $peachOptions['channel_3ds']; //secret
	$accesstoken = $peachOptions['access_token'];
	$mode = $peachOptions['transaction_mode'];
	$checkout_methods = $peachOptions['checkout_methods'];
	$consolidated_label_logos = $peachOptions['consolidated_label_logos'];
	$ssl_verifypeer = true;
	$process_checkout_url = 'https://eu-prod.oppwa.com';
	$successcode = '000.000.000';
	
	if($mode == 'INTEGRATOR_TEST'){
		$ssl_verifypeer = false;
		$process_checkout_url = 'https://eu-test.oppwa.com';
		$successcode = '000.100.110';
	}
	
	$current_user = wp_get_current_user();
		
	if(isset($_GET['id']) && isset($_GET['resourcePath'])){
		$id = urldecode($_GET['id']);
		$resourcePath = urldecode($_GET['resourcePath']);
	
		$url = $process_checkout_url.''.$resourcePath;
		$url .= "?entityId=".$secureid;
	
		$ch = curl_init();
		curl_setopt($ch, CURLOPT_URL, $url);
		curl_setopt($ch, CURLOPT_HTTPHEADER, array(
					   'Authorization:Bearer '. $accesstoken));
		curl_setopt($ch, CURLOPT_CUSTOMREQUEST, 'GET');
		curl_setopt($ch, CURLOPT_SSL_VERIFYPEER, $ssl_verifypeer);
		curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
		curl_setopt($ch, CURLOPT_FAILONERROR, true);
		
		$responseData = curl_exec($ch);
		$resultCode = '';
		$curlError = '';
		
		if(curl_errno($ch)) {
			$curlError = curl_error($ch);
			wc_add_notice($curlError, 'error' );
			return;
		}else{
			$response = json_decode($responseData);
			$resultCode = $response->result->code;
			$resultDescription = $response->result->description;
		}
		curl_close($ch);
		
		if($resultCode == $successcode && $curlError == ''){
			
			if ( is_user_logged_in() ) {
				if(isset($response->registrationId) && $response->registrationId != ''){
					
					$newCard = array(
						'id' => $response->registrationId,
						'num' => 'xxxx-'.$response->card->last4Digits,
						'holder' => $response->card->holder,
						'brand' => $response->paymentBrand,
						'exp_year' => $response->card->expiryYear,
						'exp_month' => $response->card->expiryMonth
					);
					
					$found = WC_Peach_Payments::card_search($newCard, $checkOld = true);
					
					$myCards = get_user_meta( get_current_user_id(), 'my-cards', true);

					if(isset($myCards) && !$found){
						if($myCards == ''){
							update_user_meta( get_current_user_id(), 'my-cards', array($newCard));
						}else{
							$myCards[] = $newCard;
							update_user_meta( get_current_user_id(), 'my-cards', $myCards);
						}
					}else if(!$found){
						add_user_meta( get_current_user_id(), 'my-cards', array($newCard));
					}
				}
			}
			
			wp_safe_redirect($cart_url);
			exit;
			
		}else{
			
			$logger_info['errors'] = array(
				'Action' => 'Trying to add addional card [Response Result]',
				'Response' => $response,
			);
			
			$logger->info( "\n".print_r($logger_info, true)."\n\n", array( 'source' => 'peach-add-additionasl-cards' ) );
			wp_safe_redirect($add_cart_url);
			exit;
		}
	}else{
		$url = $process_checkout_url."/v1/checkouts";
		
		$data = "entityId=". $secureid .
		"&amount=1.00" .
		"&currency=ZAR" .
		"&customer.givenName=" .$current_user->user_firstname .
		"&customer.surname=" .$current_user->user_lastname .
		"&customer.email=" .$current_user->user_email .
		"&createRegistration=true".
		"&paymentType=PA".
		"&standingInstruction.source=CIT" .
		"&standingInstruction.mode=INITIAL" .
		"&standingInstruction.type=UNSCHEDULED";
		
		$ch = curl_init();
		curl_setopt($ch, CURLOPT_URL, $url);
		curl_setopt($ch, CURLOPT_HTTPHEADER, array(
					   'Authorization:Bearer '. $accesstoken));
		curl_setopt($ch, CURLOPT_POST, 1);
		curl_setopt($ch, CURLOPT_POSTFIELDS, $data);
		curl_setopt($ch, CURLOPT_SSL_VERIFYPEER, $ssl_verifypeer);
		curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
		curl_setopt($ch, CURLOPT_FAILONERROR, true);
		$responseData = curl_exec($ch);
		
		if(curl_errno($ch)) {
			$curlError = curl_error($ch);
		}else{
			$responseData = json_decode($responseData);
			$responseCode = $responseData->result->code;
		}
		curl_close($ch);
		
		if(isset($responseData->id)){
			$responseID = $responseData->id;
			
			$formClass = '';
			
			echo '<script src="'.$process_checkout_url.'/v1/paymentWidgets.js?checkoutId='.$responseID.'"></script>';
			
			echo "<script>
			var wpwlOptions = {
				style: 'plain',
				disableCardExpiryDateValidation: true,
				iframeStyles: {
					'card-number-placeholder': {
						'font-size': '17px'
					},
					'cvv-placeholder': {
						'font-size': '17px'
					}
				},
				onReady: function() {
				  jQuery('.wpwl-button-pay').html('Add Card');  
				}
			}
			</script>";
			echo '
			<style>
			#wpwl-registrations {display:none !important;}
			</style>
			';
			
			$brands = 'VISA MASTER AMEX DINERS';
			
			echo '<form action="'.$add_cart_url.'" class="paymentWidgets'.$formClass.'" data-brands="'.$brands.'"></form>';
		}else{
			if(isset($responseData->result->description) && isset($responseData->result->code)){
				$logger_info['errors'] = array(
					'Action' => 'Trying to add addional card',
					'Response Code' => $responseCode,
					'Response' => (array)$responseData,
				);
			}else if(isset($curlError)){
				$logger_info['errors'] = array(
					'Action' => 'Trying to add addional card',
					'Response' => 'Error [Curl] '.$curlError
				);
			}else{
				$logger_info['errors'] = array(
					'Action' => 'Trying to add addional card',
					'Response' => (array)$responseData,
				);
			}
			$logger->info( "\n".print_r($logger_info, true)."\n\n", array( 'source' => 'peach-add-additionasl-cards' ) );
			wp_safe_redirect($add_cart_url);
			exit;
		}
	}
}
add_action( 'woocommerce_account_add-card_endpoint', 'peach_add_card_content' );

function peach_update_subscription_content() {
	if(isset($_GET['sub']) && $_GET['sub'] != ''){
		$order = wc_get_order($_GET['sub']);
		$id = $_GET['sub'];
		
		$parent_order_id = $order->get_parent_id();
		
		$old_reg_id = '';
		if($parent_order_id && $parent_order_id != ''){
			$old_reg_id = get_post_meta($parent_order_id, 'payment_registration_id', true);
		}
		
		if($order){

			$myCards = get_user_meta( get_current_user_id(), 'my-cards', true);
			if(!empty($myCards)){
				$select = '<select name="peach-cards" id="peach-cards">';
				$options = '';
				foreach($myCards as $card){
					if($old_reg_id != $card['id']){
						$options .= '
						<option value="'.$card['id'].'">'.$card['num'].' ('.$card['exp_year'].'/'.$card['exp_month'].')</option>
						';
					}
				}
				$select .= $options.'</select>';
				
				echo '<div class="update-card-result"></div><h2>Update Credit Card for Subscription: #'.$id .'</h2><p>Select new card:<br>'.$select.'</p><div class="peach-add-card-cont"><a class="woocommerce-button woocommerce-Button button peach-update-card" href="" data-id="'.$parent_order_id.'">'.__('Update Card','woocommerce-gateway-peach-payments').'</a></div>';
			}else{
				echo '<p>No saved cards found.</p>';
			}
		}else{
			echo '<p>Subscription not found.</p>';
		}
	}else{
		echo '<p>Subscription not found.</p>';
	}
}
add_action( 'woocommerce_account_update-subscription_endpoint', 'peach_update_subscription_content' );

function updateMyCards($cards, $accesstoken, $secureid, $transaction_mode, $process_checkout_url, $ssl_verifypeer, $success_code){

	$error = false;
			
	foreach ($cards as $card){
			$url = $process_checkout_url."/v1/registrations/".$card;
			$url .= "?entityId=".$secureid;
		
			$ch = curl_init();
			curl_setopt($ch, CURLOPT_URL, $url);
			curl_setopt($ch, CURLOPT_HTTPHEADER, array(
						   'Authorization:Bearer '.$accesstoken));
			curl_setopt($ch, CURLOPT_CUSTOMREQUEST, 'DELETE');
			curl_setopt($ch, CURLOPT_SSL_VERIFYPEER, $ssl_verifypeer);
			curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
			curl_setopt($ch, CURLOPT_FAILONERROR, true);
			
			$responseData = curl_exec($ch);
			if(curl_errno($ch)) {
				$curlError = curl_error($ch);
				$responseData = $curlError;
			}else{
				$responseData = json_decode($responseData);
				$resultCode = $responseData->result->code;
			}
			curl_close($ch);
			
			if(isset($resultCode)){
				if($resultCode == $success_code || $resultCode == '100.350.101'){
					$myCards = get_user_meta( get_current_user_id(), 'my-cards', true);
					if(isset($myCards) && is_array($myCards)){
						$newSet = removeCard($myCards, "id", $card);
						update_user_meta( get_current_user_id(), 'my-cards', $newSet);
					}
				}else{
					$error = true;
				}
			}else{
				$error = true;
			}
	}
	
	return $error;
}

function peachCardUpdate_funct(){
	$cardRemove = $_REQUEST['card'];
	
	$card_removed = peachRemoveCardAPI($cardRemove);

	$myCards = get_user_meta( get_current_user_id(), 'my-cards', true);
	$myOldCards = get_user_meta( get_current_user_id(), '_peach_payment_id', false);
	
	$newCards = generateCards('delete', $myCards, $myOldCards, $cardRemove);
	
	update_user_meta( get_current_user_id(), 'my-cards', $newCards);
	
	echo 'success';
	
	die();
}
add_action('wp_ajax_nopriv_peachCardUpdate', 'peachCardUpdate_funct');
add_action('wp_ajax_peachCardUpdate', 'peachCardUpdate_funct');

function generateCards($action, $myCards, $myOldCards, $cardRemove){
	$combinedCards = array();
	$cardCheck = array();
	$oldCards = array();
	
	if(is_array($myCards) && count($myCards) > 0){
		foreach($myCards as $card){
				
			$brand = '--';
			$exp_year = '--';
			$exp_month = '--';
			if(isset($card['brand'])){
				$brand = $card['brand'];
				$exp_year = $card['exp_year'];
				$exp_month = $card['exp_month'];
			}
				
			if(!in_array($card['id'].''.$exp_year.''.$exp_month, $cardCheck)){
				if($action == 'show' || ($action == 'delete' && $card['id'] != $cardRemove)){
					$combinedCards[] = array(
						'id' => $card['id'],
						'num' => $card['num'],
						'brand' => $brand,
						'exp_year' => $exp_year,
						'exp_month' => $exp_month,
						'ver' => 'new'
					);
					$cardCheck[] = $card['num'].''.$exp_year.''.$exp_month;
				}
			}
		}
	}

	if(is_array($myOldCards) && count($myOldCards) > 0){
		foreach($myOldCards as $cardOld){
			if(!in_array('xxxx-'.$cardOld['active_card'].''.$cardOld['exp_year'].''.$cardOld['exp_month'], $cardCheck)){
				if($action == 'show' || ($action == 'delete' && $cardOld['payment_id'] != $cardRemove)){
					$combinedCards[] = array(
						'id' => $cardOld['payment_id'],
						'num' => 'xxxx-'.$cardOld['active_card'],
						'brand' => $cardOld['brand'],
						'exp_year' => $cardOld['exp_year'],
						'exp_month' => $cardOld['exp_month'],
						'ver' => 'old'
					);
					
					$oldCards[] = array(
						'payment_id' => $cardOld['payment_id'],
						'active_card' => $cardOld['active_card'],
						'brand' => $cardOld['brand'],
						'exp_year' => $cardOld['exp_year'],
						'exp_month' => $cardOld['exp_month']
					);
					
					$cardCheck[] = 'xxxx-'.$cardOld['active_card'].''.$cardOld['exp_year'].''.$cardOld['exp_month'];
				}
			}
		}
	}
	
	if(!empty($oldCards)){
		update_user_meta( get_current_user_id(), '_peach_payment_id', $oldCards);
	}else{
		delete_user_meta( get_current_user_id(), '_peach_payment_id');
	}
	
	return $combinedCards;
}

function peachCardUpdateOrder_funct(){
	$cardID = $_REQUEST['cardID'];
	$orderID = $_REQUEST['orderID'];
	
	$new_reg_id = update_post_meta($orderID, 'payment_registration_id', $cardID);
	
	echo $new_reg_id;
	die();
}
add_action('wp_ajax_nopriv_peachCardUpdateOrder', 'peachCardUpdateOrder_funct');
add_action('wp_ajax_peachCardUpdateOrder', 'peachCardUpdateOrder_funct');

function peachRequestCardAPI($card){
	$peachOptions = get_option('woocommerce_peach-payments_settings');
	
	if(!isset($peachOptions) || $peachOptions == ''){
		return array(0, 'no options');
	}
	
	$secureid = $peachOptions['channel_3ds']; //secret
	$accesstoken = $peachOptions['access_token'];
	$mode = $peachOptions['transactionmode'];
	$ssl_verifypeer = true;
	$process_checkout_url = 'https://eu-prod.oppwa.com';
	$successcode = '000.000.000';
	
	if($mode == 'INTEGRATOR_TEST'){
		$ssl_verifypeer = false;
		$process_checkout_url = 'https://eu-test.oppwa.com';
		$successcode = '000.100.110';
	}

	$url = $process_checkout_url."/v1/registrations/".$card;
	$url .= "?entityId=".$secureid;

	$ch = curl_init();
	curl_setopt($ch, CURLOPT_URL, $url);
	curl_setopt($ch, CURLOPT_HTTPHEADER, array(
				   'Authorization:Bearer '.$accesstoken));
	curl_setopt($ch, CURLOPT_CUSTOMREQUEST, 'GET');
	curl_setopt($ch, CURLOPT_SSL_VERIFYPEER, $ssl_verifypeer);
	curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
	curl_setopt($ch, CURLOPT_FAILONERROR, true);
	
	$responseData = curl_exec($ch);
	if(curl_errno($ch)) {
		return array(0, 'curl error');
	}
	curl_close($ch);
	
	$responseData = json_decode($responseData);
	
	if($resultCode == $successcode || $resultCode == '100.350.101'){
		return array(1, $responseData);
	}else{
		return array(0, $responseData);
	}
}

function peachRemoveCardAPI($card){
	
	$peachOptions = get_option('woocommerce_peach-payments_settings');
	$logger_info_settings = array(
		'transactionmode' => $peachOptions['transaction_mode'],
		'secrettoken' => $peachOptions['secret'],
		'accesstoken' => $peachOptions['access_token'],
		'secureid' => $peachOptions['channel_3ds'],
		'recurringid' => $peachOptions['channel'],
		'card_webhook_key' => $peachOptions['card_webhook_key'],
		'completestatus' => $peachOptions['auto_complete'],
		'peach_order_status' => $peachOptions['peach_order_status']
	);
	
	if(!isset($peachOptions) || $peachOptions == ''){
		return array(0, 'no options');
	}
	
	$logger = wc_get_logger();
	$logger_info = array();
	$logger_info['settings'] = $logger_info_settings;
	
	$secureid = $peachOptions['channel_3ds']; //secret
	$accesstoken = $peachOptions['access_token'];
	$mode = $peachOptions['transactionmode'];
	$ssl_verifypeer = true;
	$process_checkout_url = 'https://eu-prod.oppwa.com';
	$successcode = '000.000.000';
	
	if($mode == 'INTEGRATOR_TEST'){
		$ssl_verifypeer = false;
		$process_checkout_url = 'https://eu-test.oppwa.com';
		$successcode = '000.100.110';
	}

	$url = $process_checkout_url."/v1/registrations/".$card;
	$url .= "?entityId=".$secureid;

	$ch = curl_init();
	curl_setopt($ch, CURLOPT_URL, $url);
	curl_setopt($ch, CURLOPT_HTTPHEADER, array(
				   'Authorization:Bearer '.$accesstoken));
	curl_setopt($ch, CURLOPT_CUSTOMREQUEST, 'DELETE');
	curl_setopt($ch, CURLOPT_SSL_VERIFYPEER, $ssl_verifypeer);
	curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
	curl_setopt($ch, CURLOPT_FAILONERROR, true);
	
	$responseData = curl_exec($ch);
	if(curl_errno($ch)) {
		$logger_info['error'] = array(
			'code' => 'CURL',
			'Response' => curl_errno($ch),
			'URL' => $url
		);
		$logger->info( "\n".print_r($logger_info, true)."\n\n", array( 'source' => 'peach-peachremovecardapi' ) );
		return array(0, 'Curl Error: '.$url);
	}
	curl_close($ch);
	
	$responseData = json_decode($responseData);
	$resultCode = $responseData->result->code;
	
	if($resultCode == $successcode || $resultCode == '100.350.101'){
		return array(1, $resultCode);
	}else{
		$logger_info['error'] = array(
			'code' => $resultCode,
			'Response' => $responseData,
			'URL' => $url
		);
		$logger->info( "\n".print_r($logger_info, true)."\n\n", array( 'source' => 'peach-peachremovecardapi' ) );
		return array(0, $resultCode);
	}
}

function removeCard($array, $key, $value){
	foreach($array as $subKey => $subArray){
		if(isset($subArray[$key]) && ($value != '' || $value != NULL)){
			if($subArray[$key] == $value){
				unset($array[$subKey]);
			}
		}
	}
	return $array;
}

function peach_extend_wpgraphql_schema(){
	
	// Add saved cards to graphql
	register_graphql_object_type('PeachPaymentsSavedCard', array(
		'description' => __('Describe the Type and what it represents', 'wp-graphql'),
		'fields' => array(
			'payment_id' => array(
				'type' => 'String',
				'description' => __('Card registration ID', 'wp-graphql'),
			),
			'active_card' => array(
				'type' => 'String',
				'description' => __('Last 4 digits', 'wp-graphql'),
			),
			'exp_month' => array(
				'type' => 'String',
				'description' => __('Expiry Month', 'wp-graphql'),
			),
			'exp_year' => array(
				'type' => 'String',
				'description' => __('Expiry Year', 'wp-graphql'),
			),
			'brand' => array(
				'type' => 'string',
				'description' => __('Card brand', 'wp-graphql'),
			),
		),
	));

	register_graphql_field( 'Customer', 'peachPaymentsSavedCards', [
		'type' => [ 'list_of' => 'PeachPaymentsSavedCard' ],
		'description' => __( 'Saved cards from Peach Payments', 'wp-graphql' ),
		'resolve' => function( $customer ) {
			if(isset($userID)){
				$credit_cards = get_user_meta( $userID, '_peach_payment_id', false );
			}else{
				$credit_cards = array();
			}
			return $credit_cards;
		}
	]);
}

function peach_core_version_rollback(){
	if (!wp_verify_nonce($_GET['nonce'], 'peach_core_version_rollback')) {
        die ( 'Unauthorized');
    }
	
	require_once( ABSPATH . 'wp-admin/includes/class-wp-upgrader.php' );
	$upgrader = new Plugin_Upgrader();
	
	$url = WC_PEACH_PLUGIN_PATH . '/assets/wc-peach-payments-gateway.3.1.7.zip';
	$args = array(
		'clear_update_cache' => true,
		'overwrite_package' => true
	);
	
	$result = $upgrader->install($url, $args);
	echo $result;
	die();
}
add_action( 'wp_ajax_peach_core_version_rollback', 'peach_core_version_rollback' );

//Ajax Function to update old cards
function peach_card_sync_funct(){

	echo 'Synchronization Done';
 
	die();
}
add_action('wp_ajax_peach_card_sync', 'peach_card_sync_funct');

//Set a minimum order amount for checkout
function wc_minimum_order_amount() {
    // Set this variable to specify a minimum order value
    $minimum = 1;

    if ( WC()->cart->subtotal < $minimum ) {

        if( is_cart() ) {

            wc_print_notice( 
                sprintf( 'Your current cart total is %s — you must have an cart with a minimum of %s to place your order.' , 
                    wc_price( WC()->cart->subtotal ), 
                    wc_price( $minimum )
                ), 'error' 
            );

        } else {

            wc_add_notice( 
                sprintf( 'Your current cart total is %s — you must have an cart with a minimum of %s to place your order.' , 
                    wc_price( WC()->cart->subtotal ), 
                    wc_price( $minimum )
                ), 'error' 
            );

        }
    }
}
add_action( 'woocommerce_checkout_process', 'wc_minimum_order_amount' );

function mp_checks_funct(){
	global $wpdb;
	$date = date_i18n( "Y-m-d", strtotime( "+1 day", current_time( 'timestamp' ) ) );
	$sqlQuery = "SELECT *
				 FROM $wpdb->pmpro_memberships_users
				 WHERE status = 'active'
					AND enddate > '".$date."'
					OR enddate = '0000-00-00 00:00:00'";
	$updates  = $wpdb->get_results( $sqlQuery );
	
	echo '<pre>'.print_r($updates, true).'</pre>';
}
add_shortcode('mp_checks', 'mp_checks_funct');


function peach_update_sub_card( $actions, $subscription ) {

    $buttonText = 'Update Card';
    $buttonURL = get_permalink( get_option('woocommerce_myaccount_page_id') ).'update-subscription/';

    $new_actions = array(
        'peach_sub_card_update' => array(
            'url' => $buttonURL . '?sub=' . $subscription->get_id(),
            'name' => $buttonText,
        ),
    );

    $actions = array_merge( $actions, $new_actions ); 

    return $actions; 
}
add_filter( 'wcs_view_subscription_actions', 'peach_update_sub_card', 10, 2 );

add_action( 'wp_loaded','peach_flush_urls' );
function peach_flush_urls() {
	
	if( ! $page = get_page_by_path('my-account/update-subscription') ){
		flush_rewrite_rules();
	}
	
}

//Registers WooCommerce Blocks integration.
function woocommerce_gateway_peach_woocommerce_block_support() {
	if ( class_exists( 'Automattic\WooCommerce\Blocks\Payments\Integrations\AbstractPaymentMethodType' ) ) {
		//require_once 'integrations/blocks/class-wc-peach-payments-blocks.php';
		require_once __DIR__ .  '/integrations/blocks/class-wc-peach-payments-blocks.php';
		add_action(
			'woocommerce_blocks_payment_method_type_registration',
			function( Automattic\WooCommerce\Blocks\Payments\PaymentMethodRegistry $payment_method_registry ) {
				$payment_method_registry->register( new WC_Gateway_Peach_Blocks_Support() );
			}
		);
	}
}
add_action( 'woocommerce_blocks_loaded', 'woocommerce_gateway_peach_woocommerce_block_support');