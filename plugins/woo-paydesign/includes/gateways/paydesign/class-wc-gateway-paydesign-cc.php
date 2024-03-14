<?php

use ArtisanWorkshop\WooCommerce\PluginFramework\v2_0_12 as Framework;

if ( ! defined( 'ABSPATH' ) ) exit; // Exit if accessed directly

/**
 * metaps PAYMENT Gateway
 *
 * Provides a metaps PAYMENT Credit Card Payment Gateway(Link Type).
 *
 * @class 		WC_PAYDESIGN
 * @extends		WC_Gateway_PAYDESIGN_CC
 * @version		1.2.0
 * @package		WooCommerce/Classes/Payment
 * @author		Artisan Workshop
 */
class WC_Gateway_PAYDESIGN_CC extends WC_Payment_Gateway {

	/**
	 * Framework.
	 *
	 * @var stdClass
	 */
	public $jp4wc_framework;


	/**
	 * Debug mode
	 *
	 * @var string
	 */
	public $debug;

	/**
	 * Test mode
	 *
	 * @var string
	 */
	public $test_mode;

	/**
	 * Set metaps request class
	 *
	 * @var stdClass
	 */
	public $metaps_request;

	/**
	 * payment methods
	 *
	 * @var array
	 */
	public $payment_methods;

	public $array_number_of_payments;

	/**
	 * Constructor for the gateway.
	 *
	 * @access public
	 * @return void
	 */
	public function __construct() {

		$this->id                = 'paydesign_cc';
		$this->has_fields        = false;
		$this->method_title      = __( 'metaps PAYMENT Credit Card', 'woo-paydesign' );
		
        // Create plugin fields and settings
		$this->init_form_fields();
		$this->init_settings();
		$this->method_description = __( 'Allows payments by metaps PAYMENT Credit Card in Japan.', 'woo-paydesign' );
		$this->supports           = array(
			'subscriptions',
			'products',
			'subscription_cancellation',
			'subscription_reactivation',
			'subscription_suspension',
			'subscription_amount_changes',
			'subscription_payment_method_change_admin',
			'subscription_date_changes',
			'multiple_subscriptions',
			'refunds'
		);

		$this->jp4wc_framework = new Framework\JP4WC_Plugin();

		include_once( 'includes/class-wc-gateway-paydesign-request.php' );
		$this->metaps_request = new WC_Gateway_PAYDESIGN_Request();

        // When no save setting error at chackout page
		if(is_null($this->title)){
			$this->title = __( 'Please set this payment at Control Panel! ', 'woo-paydesign' ).$this->method_title;
		}

		// Get setting values
		foreach ( $this->settings as $key => $val ) $this->$key = $val;
		// Number of payments
		$this->array_number_of_payments = array(
			'100'	=> __( '1 time', 'woo-paydesign' ),
//			'21'	=> __( 'Bonus One time', woo-paydesign' ),
			'80'	=> __( 'Revolving payment', 'woo-paydesign' ),
//			'2'		=> '2'.__( 'times', 'woo-paydesign' ),
			'3'		=> '3'.__( 'times', 'woo-paydesign' ),
//			'4'		=> '4'.__( 'times', 'woo-paydesign' ),
			'5'		=> '5'.__( 'times', 'woo-paydesign' ),
			'6'		=> '6'.__( 'times', 'woo-paydesign' ),
			'10'	=> '10'.__( 'times', 'woo-paydesign' ),
			'12'	=> '12'.__( 'times', 'woo-paydesign' ),
			'15'	=> '15'.__( 'times', 'woo-paydesign' ),
			'18'	=> '18'.__( 'times', 'woo-paydesign' ),
			'20'	=> '20'.__( 'times', 'woo-paydesign' ),
			'24'	=> '24'.__( 'times', 'woo-paydesign' ),
		);

		// Actions
//		add_action( 'woocommerce_receipt_paydesign_cc', array( $this, 'receipt_page' ) );
		add_action( 'woocommerce_update_options_payment_gateways',              array( $this, 'process_admin_options' ) );
		add_action( 'woocommerce_update_options_payment_gateways_' . $this->id, array( $this, 'process_admin_options' ) );
	}
      /**
       * Initialize Gateway Settings Form Fields.
       */
	function init_form_fields() {

		$this->form_fields = array(
			'enabled'     => array(
				'title'       => __( 'Enable/Disable', 'woo-paydesign' ),
				'label'       => __( 'Enable metaps PAYMENT Credit Card Payment', 'woo-paydesign' ),
				'type'        => 'checkbox',
				'description' => '',
				'default'     => 'no'
	        ),
			'title'       => array(
				'title'       => __( 'Title', 'woo-paydesign' ),
				'type'        => 'text',
				'description' => __( 'This controls the title which the user sees during checkout.', 'woo-paydesign' ),
				'default'     => __( 'Credit Card (Paydesign)', 'woo-paydesign' )
	        ),
			'description' => array(
				'title'       => __( 'Description', 'woo-paydesign' ),
				'type'        => 'textarea',
				'description' => __( 'This controls the description which the user sees during checkout.', 'woo-paydesign' ),
				'default'     => __( 'Pay with your credit card via metaps PAYMENT.', 'woo-paydesign' )
	        ),
			'order_button_text' => array(
				'title'       => __( 'Order Button Text', 'woo-paydesign' ),
				'type'        => 'text',
				'description' => __( 'This controls the description which the user sees during checkout.', 'woo-paydesign' ),
				'default'     => __( 'Proceed to metaps PAYMENT Credit Card', 'woo-paydesign' )
			),
			'ip_code' => array(
				'title'       => __( 'IP Code', 'woo-paydesign' ),
				'type'        => 'text',
				'description' => __( 'Enter IP Code here.', 'woo-paydesign' ),
			),
			'pass_code' => array(
				'title'       => __( 'IP Password', 'woo-paydesign' ),
				'type'        => 'text',
				'description' =>  __( 'Enter IP Password here', 'woo-paydesign' ),
			),
			'paymentaction' => array(
				'title'       => __( 'Payment Action', 'woocommerce' ),
				'type'        => 'select',
				'class'       => 'wc-enhanced-select',
				'description' => __( 'Choose whether you wish to capture funds immediately or authorize payment only.', 'woocommerce' ),
				'default'     => 'sale',
				'desc_tip'    => true,
				'options'     => array(
					'sale'          => __( 'Capture', 'woocommerce' ),
					'authorization' => __( 'Authorize', 'woocommerce' )
				)
			),
			'user_id_payment' => array(
				'title'       => __( 'User ID Payment', 'woo-paydesign' ),
				'id'          => 'wc-userid-payment',
				'type'        => 'checkbox',
				'label'       => __( 'User ID Payment', 'woo-paydesign' ),
				'default'     => 'yes',
				'description' => sprintf( __( 'Use the payment method of User ID payment.', 'woo-paydesign' )),
			),
			'number_of_payments' => array(
				'title'       => __( 'Number of payments', 'woo-paydesign' ),
				'type'        => 'multiselect',
				'class'       => 'wc-number-select',
				'description' => __( 'Choose whether you wish to capture funds immediately or authorize payment only.', 'woo-paydesign' ),
				'desc_tip'    => true,
				'options'     => array(
					'100'	=> __( '1 time', 'woo-paydesign' ),
//					'21'	=> __( 'Bonus One time', 'woo-paydesign' ),
					'80'	=> __( 'Revolving payment', 'woo-paydesign' ),
//					'2'		=> '2'.__( 'times', 'woo-paydesign' ),
					'3'		=> '3'.__( 'times', 'woo-paydesign' ),
//					'4'		=> '4'.__( 'times', 'woo-paydesign' ),
					'5'		=> '5'.__( 'times', 'woo-paydesign' ),
					'6'		=> '6'.__( 'times', 'woo-paydesign' ),
					'10'	=> '10'.__( 'times', 'woo-paydesign' ),
					'12'	=> '12'.__( 'times', 'woo-paydesign' ),
					'15'	=> '15'.__( 'times', 'woo-paydesign' ),
					'18'	=> '18'.__( 'times', 'woo-paydesign' ),
					'20'	=> '20'.__( 'times', 'woo-paydesign' ),
					'24'	=> '24'.__( 'times', 'woo-paydesign' ),
				)
			),
			'debug' => array(
				'title'   => __( 'Debug Mode', 'woo-paydesign' ),
				'type'    => 'checkbox',
				'label'   => __( 'Enable Debug Mode', 'woo-paydesign' ),
				'default' => 'no',
				'description' => __( 'Save debug data using WooCommerce logging.', 'woo-paydesign' ),
			),
		);
	}

	/**
	 * UI - Payment page fields for metaps PAYMENT Payment.
	 */
	function payment_fields() {
		// Description of payment method from settings
		if ( $this->description ) { ?>
			<p><?php echo $this->description; ?></p>
		<?php }
		$user = wp_get_current_user();
		$number_payment_array = array(
			'100'	=> __( '1 time', 'woo-paydesign' ),
//			'21'	=> __( 'Bonus One time', 'woo-paydesign' ),
			'80'	=> __( 'Revolving payment', 'woo-paydesign' ),
//			'2'		=> '2'.__( 'times', 'woo-paydesign' ),
			'3'		=> '3'.__( 'times', 'woo-paydesign' ),
//			'4'		=> '4'.__( 'times', 'woo-paydesign' ),
			'5'		=> '5'.__( 'times', 'woo-paydesign' ),
			'6'		=> '6'.__( 'times', 'woo-paydesign' ),
			'10'	=> '10'.__( 'times', 'woo-paydesign' ),
			'12'	=> '12'.__( 'times', 'woo-paydesign' ),
			'15'	=> '15'.__( 'times', 'woo-paydesign' ),
			'18'	=> '18'.__( 'times', 'woo-paydesign' ),
			'20'	=> '20'.__( 'times', 'woo-paydesign' ),
			'24'	=> '24'.__( 'times', 'woo-paydesign' ),
		);
		$paydesign_user_id = get_user_meta( $user->ID, '_paydesign_user_id', true );
		if($this->user_id_payment == 'yes' and $paydesign_user_id !='' and is_user_logged_in()){
			echo '<input type="radio" name="select_card" value="old" checked="checked"><span style="padding-left:15px;">'.__( 'Use Stored Card.', 'woo-paydesign' ).'</span><br />'.PHP_EOL;
			if(!empty($this->number_of_payments)){
				echo '<select name="number_of_payments">';
				foreach($this->number_of_payments as $key => $value){
					echo '<option value="'.$value.'">'.$number_payment_array[$value].'</option>';
				}
				echo '</select>';
			}
			echo '<br />';
			echo '<input type="radio" name="select_card" value="new_credit"><span style="padding-left:15px;">'.__( 'Use New Card.', 'woo-paydesign' ).'</span><br />'.PHP_EOL;
		}
	}

	/**
	 * Process the payment and return the result.
	 */
	function process_payment( $order_id  , $subscription = false) {
		$prefix_order = get_option( 'wc_paydesign_prefix_order' );

		$order = wc_get_order( $order_id );
		$user = wp_get_current_user();
		//Setting $send_data
		$setting_data = array();
		if(isset($user->ID) and $user->ID != 0 ){
			$customer_id = $prefix_order.$user->ID;
		}else{
			$customer_id = $prefix_order.$order_id.'-user';
		}
//		$order->add_order_note('User ID:'.$customer_id);

		// Set User id
		if($this->user_id_payment == 'yes' and is_user_logged_in()) $setting_data['ip_user_id'] = $customer_id;

		$setting_data['pass'] = $this->pass_code;
		// User ID payment check 
			$setting_data['store'] = '51';
		if($this->user_id_payment == 'yes' and $this->get_post('select_card') == 'old' and is_user_logged_in()){
			$setting_data['store'] = null;
		}

		$setting_data['ip'] = $this->ip_code;
		if($this->paymentaction == 'sale'){
			$setting_data['kakutei'] = '1';//capture = 1
		}else{
			$setting_data['kakutei'] = '0';//auth = 0
		}
		$setting_data['lang'] = '0';// Use Language 0 = Japanese, 1 = English
		$setting_data['sid'] = $prefix_order.$order_id;

		//Number of Payment check
		$number_of_payments = $this->get_post('number_of_payments');
		if(isset($number_of_payments)){
			if($number_of_payments == 21 or $number_of_payments == 80){
				$setting_data['paymode'] = $number_of_payments;
			}elseif($number_of_payments ==100){
				$setting_data['paymode'] = 10;
			}else{
				$setting_data['paymode'] = 61;
				$setting_data['incount'] = $number_of_payments;
			}
		}

		if(isset($setting_data['store'])){// When not use user id payment
			$connect_url = PAYDESIGN_CC_SALES_URL;
			$thanks_url = $this->get_return_url( $order );
			// Reduce stock levels
			wc_reduce_stock_levels( $order_id );
			$order->add_order_note( __('Finished to send payment data to metaps PAYMENT.', 'woo-paydesign') );

			$get_url = $this->metaps_request->get_post_to_paydesign( $order ,$connect_url , $setting_data, $thanks_url, $this->debug );

			return array(
				'result'   => 'success',
				'redirect' => $get_url
			);
		}else{ // When use user id payment
			$connect_url = PAYDESIGN_CC_SALES_USER_URL;
			$response = $this->metaps_request->paydesign_post_request( $order, $connect_url, $setting_data, $this->debug );
			if( isset($response[0]) and substr($response[0],0,2) == 'OK' ){
				update_user_meta($user->ID, '_paydesign_user_id' , $customer_id);
				$order->add_order_note( __('Finished to send payment data to metaps PAYMENT.', 'woo-paydesign') );
				return array(
					'result'   => 'success',
					'redirect' => $this->get_return_url( $order ),
				);
			}else{
				if(is_checkout())wc_add_notice( __('Payment error:', 'woo-paydesign') . mb_convert_encoding($response[2], "UTF-8", "sjis"), 'error' );
//				$order->add_order_note(__('Payment error:', 'woo-paydesign') . $setting_data['ip_user_id'].$setting_data['store']);
				$order->update_status( 'cancelled', __( 'This order is cancelled, because of Payment error.'.mb_convert_encoding($response[2], "UTF-8", "sjis"), 'woo-paydesign' ) );
				delete_user_meta($user->ID, '_paydesign_user_id' , $customer_id);
				return array(
					'result'   => 'failure',
					'message'   => 'Payment with the saved card failed. Please re-enter the card information.',
					'redirect' => wc_get_checkout_url()
				);
			}
		}
	}

	/**
	 * Refund a charge
	 * @param  int $order_id
	 * @param  float $amount
	 * @return bool|WP_Error True or false based on success, or a WP_Error object.
	 */
	public function process_refund( $order_id, $amount = null, $reason = '' ) {
		$order = wc_get_order( $order_id );

		if ( ! $order ) {
			return false;
		}elseif($amount != $order->order_total){
			$order->add_order_note( __( 'Auto refund must total only. ', 'woo-paydesign' ).__( 'If you need, please contact to metaps PAYMENT Support.', 'woo-paydesign' ) );
			return new WP_Error( 'paydesign_refund_error', __( 'Auto refund must total only. ', 'woo-paydesign' ).__( 'If you need, please contact to metaps PAYMENT Support.', 'woo-paydesign' ) );
		}
		$prefix_order = get_option( 'wc_paydesign_prefix_order' );

		$refund_connect_url = PAYDESIGN_CC_SALES_CANCEL_URL;
		$cansel_connect_url = PAYDESIGN_CC_SALES_REFUND_URL;

		$status = $order->get_status();
		$data['IP'] = $this->ip_code;
		$data['PASS'] = $this->pass_code;
		$data['SID'] = $prefix_order.$order_id;
//		$order->add_order_note( 'test001' );
		if($status == 'completed'){
			$response = $this->metaps_request->paydesign_request( $data ,$cansel_connect_url ,$order, $this->debug );
			if(isset($response) and substr($response, 0 ,10) == 'C-CHECK:OK'){
				$order->add_order_note( __( 'This order is refunded now at metaps PAYMENT.', 'woo-paydesign' ) );
				return true;
			}elseif(isset($response) and substr($response, 0 ,10) == 'C-CHECK:NG'){
				if(substr($response, -3, 1) == '2'){
					$order->add_order_note( __( 'This order has already auth canselled.', 'woo-paydesign' ).__( 'If you need, please contact to metaps PAYMENT Support.', 'woo-paydesign' ) );
				}elseif(substr($response, -3, 1) == '3'){
					$order->add_order_note( __( 'This order has completed.', 'woo-paydesign' ).__( 'If you need, please contact to metaps PAYMENT Support.', 'woo-paydesign' ) );
				}elseif(substr($response, -3, 1) == '4'){
					$order->add_order_note( __( 'This order has already canselled.', 'woo-paydesign' ).__( 'If you need, please contact to metaps PAYMENT Support.', 'woo-paydesign' ) );
				}
				return new WP_Error( 'paydesign_refund_error', __( 'Error has happened. ', 'woo-paydesign' ).__( 'If you need, please contact to metaps PAYMENT Support.', 'woo-paydesign' ) );
			}elseif(isset($response) and substr($response, 0 ,10) == 'C-CHECK:TO'){
				$order->add_order_note( __( 'Expired. Status not changed.', 'woo-paydesign' ).__( 'If you need, please contact to metaps PAYMENT Support.', 'woo-paydesign' ) );
				return new WP_Error( 'paydesign_refund_error', __( 'Error has happened. ', 'woo-paydesign' ).__( 'If you need, please contact to metaps PAYMENT Support.', 'woo-paydesign' ) );
			}elseif(isset($response) and substr($response, 0 ,10) == 'C-CHECK:ER'){
				$order->add_order_note( __( 'Error has happend. Status not changed.', 'woo-paydesign' ).__( 'If you need, please contact to metaps PAYMENT Support.', 'woo-paydesign' ) );
				return new WP_Error( 'paydesign_refund_error', __( 'Error has happened. ', 'woo-paydesign' ).__( 'If you need, please contact to metaps PAYMENT Support.', 'woo-paydesign' ) );
			}
		}else{
			if($this->paymentaction == 'sale'){
				$response = $this->metaps_request->paydesign_request( $data ,$cansel_connect_url ,$order, $this->debug );
			}else{
				$response = $this->metaps_request->paydesign_request( $data ,$refund_connect_url ,$order, $this->debug );
			}
			if(isset($response) and substr($response, 0 ,10) == 'C-CHECK:OK'){
				$order->add_order_note( __( 'This order is refunded now at metaps PAYMENT.', 'woo-paydesign' ) );
				return true;
			}elseif(isset($response) and substr($response, 0 ,10) == 'C-CHECK:NG'){
//				$order->add_order_note( substr($response, -4 ) );
				if(substr($response, -3, 1) == '1'){
					$order->add_order_note( __( 'This order is authorized now.', 'woo-paydesign' ).__( 'If you need, please contact to metaps PAYMENT Support.', 'woo-paydesign' ) );
				}elseif(substr($response, -3, 1) == '2'){
					$order->add_order_note( __( 'This order has already auth canselled.', 'woo-paydesign' ).__( 'If you need, please contact to metaps PAYMENT Support.', 'woo-paydesign' ) );
				}elseif(substr($response, -3, 1) == '3'){
					$order->add_order_note( __( 'This order has completed.', 'woo-paydesign' ).__( 'If you need, please contact to metaps PAYMENT Support.', 'woo-paydesign' ) );
				}elseif(substr($response, -3, 1) == '4'){
					$order->add_order_note( __( 'This order has already canselled.', 'woo-paydesign' ).__( 'If you need, please contact to metaps PAYMENT Support.', 'woo-paydesign' ) );
				}
				return new WP_Error( 'paydesign_refund_error', __( 'Error has happened. ', 'woo-paydesign' ).__( 'If you need, please contact to metaps PAYMENT Support.', 'woo-paydesign' ) );
			}elseif(isset($response) and substr($response, 0 ,10) == 'C-CHECK:TO'){
				$order->add_order_note( __( 'Expired. Status not changed.', 'woo-paydesign' ).__( 'If you need, please contact to metaps PAYMENT Support.', 'woo-paydesign' ) );
				return new WP_Error( 'paydesign_refund_error', __( 'Error has happened. ', 'woo-paydesign' ).__( 'If you need, please contact to metaps PAYMENT Support.', 'woo-paydesign' ) );
			}elseif(isset($response) and substr($response, 0 ,10) == 'C-CHECK:ER'){
				$order->add_order_note( __( 'Error has happend. Status not changed.', 'woo-paydesign' ).__( 'If you need, please contact to metaps PAYMENT Support.', 'woo-paydesign' ) );
				return new WP_Error( 'paydesign_refund_error', __( 'Error has happened. ', 'woo-paydesign' ).__( 'If you need, please contact to metaps PAYMENT Support.', 'woo-paydesign' ) );
			}
		}
	}


	function receipt_page( $order ) {
		echo '<p>' . __( 'Thank you for your order.', 'woo-paydesign' ) . '</p>';
	}

	/**
	 * Get post data if set
	 */
	private function get_post( $name ) {
		if ( isset( $_POST[ $name ] ) ) {
			return sanitize_text_field( $_POST[ $name ] );
		}
		return null;
	}
}
/**
 * Add the gateway to woocommerce
 */
function add_wc_paydesign_cc_gateway( $methods ) {
	$subscription_support_enabled = false;
	if ( class_exists( 'WC_Subscriptions_Order' ) && function_exists( 'wcs_create_renewal_order' ) ) {
		$subscription_support_enabled = true;
	}
	if ( $subscription_support_enabled ) {
		$methods[] = 'WC_Gateway_PAYDESIGN_CC_Addons';
	} else {
		$methods[] = 'WC_Gateway_PAYDESIGN_CC';
	}
	return $methods;
}

add_filter( 'woocommerce_payment_gateways', 'add_wc_paydesign_cc_gateway' );

/**
 * Update Sale from Auth to Paydesign
 */

function order_status_completed_to_capture( $order_id ){
	global $woocommerce;
	$payment_setting = new WC_Gateway_PAYDESIGN_CC();

	if($payment_setting->paymentaction != 'sale'){
		$paydesign_setting = get_option('woocommerce_paydesign_cc_settings');
		$prefix_order = get_option( 'wc_paydesign_prefix_order' );

		$connect_url = PAYDESIGN_CC_SALES_COMP_URL;
		$order = wc_get_order( $order_id );
		if($order && $order->payment_method == 'paydesign_cc'){
			$prefix_order = get_option( 'wc_paydesign_prefix_order' );
			$data['IP'] = $paydesign_setting['ip_code'];
			$data['SID'] = $prefix_order.$order_id;
			include_once( 'includes/class-wc-gateway-paydesign-request.php' );
			$paydesign_request = new WC_Gateway_PAYDESIGN_Request();

			$response = $paydesign_request->paydesign_request( $data ,$connect_url ,$order );
			if( isset($response[0]) and substr($response[0],0,2) == 'OK' ){
                return true;
			}elseif(isset($response) and substr($response, 0 ,10) == 'C-CHECK:NG'){
				if(substr($response, -3, 1) == '3'){
                    if($order->get_status() != 'completed'){
                        // Payment complete
                        $order->payment_complete();
                        return true;
                    }elseif($order->get_status() == 'completed' && $payment_setting->paymentaction == 'sale' ){
                        $order->add_order_note(__('This order has already completed.', 'woo-paydesign') . __('If you need, please contact to metaps PAYMENT Support.', 'woo-paydesign'));
                    }
                    return true;
				}elseif(substr($response, -3, 1) == '2'){
					$order->add_order_note( __( 'This order has already auth canselled.', 'woo-paydesign' ).__( 'If you need, please contact to metaps PAYMENT Support.', 'woo-paydesign' ) );
				}elseif(substr($response, -3, 1) == '4'){
					$order->add_order_note( __( 'This order has already canselled.', 'woo-paydesign' ).__( 'If you need, please contact to metaps PAYMENT Support.', 'woo-paydesign' ) );
				}
				add_filter( 'woocommerce_email_actions', function($email_actions){unset($email_actions['woocommerce_order_status_completed']);return $email_actions;},1);
				$update_post_data  = array(
					'ID'          => $order_id,
					'post_status' => 'wc-processing',
				);
				wp_update_post( $update_post_data );
            }elseif(isset($response) and substr($response, 0 ,10) == 'C-CHECK:ER'){
				$order->add_order_note( __( 'Error has happend. Status not changed.', 'woo-paydesign' ).__( 'If you need, please contact to metaps PAYMENT Support.', 'woo-paydesign' ) );
			}
			return false;
		}
		return true;
	}
}
add_action( 'woocommerce_order_status_completed', 'order_status_completed_to_capture');


/**
 * Recieved Credit Payment complete from Paydesign
 */
function paydesign_cc_recieved(){
	global $woocommerce;
	global $wpdb;

	if(isset($_GET['SEQ']) and isset($_GET['DATE']) and isset($_GET['SID'])){
		$pd_order_id = $_GET['SID'];
		$prefix_order = get_option( 'wc_paydesign_prefix_order' );
		$order_id = str_replace($prefix_order, '', $pd_order_id);
		$order = new WC_Order( $order_id );
		if(version_compare( WC_VERSION, '2.7', '<' )){
			$order_payment_method = $order->payment_method;
			$order_status = $order->status;
		}else{
			$order_payment_method = $order->get_payment_method();
			$order_status = $order->get_status();
		}
		$user = wp_get_current_user();
		if(isset($_GET['TIME']) and isset($order_status) and $order_status != 'processing' and $order_payment_method == 'paydesign_cc'){
			// Mark as processing (payment complete)
			$order->update_status( 'processing', sprintf( __( 'Payment of %s was complete.', 'woo-paydesign' ) , __( 'Credit Card (Paydesign)', 'woo-paydesign' ) ) );
			update_user_meta($order->get_user_id(), '_paydesign_user_id' , $prefix_order.$order->get_user_id());
//			$order->add_order_note( '_paydesign_user_id'.$order->get_user_id() );
		}
		header("Location: ".plugin_dir_url( __FILE__ )."empty.php");
	}
}

add_action( 'woocommerce_cart_is_empty', 'paydesign_cc_recieved');

/**
 * Recieved Credit Payment complete from Paydesign
 */
function paydesign_cc_return(){
	global $woocommerce;
	global $wpdb;
	if(isset($_GET['pd']) == 'return' and isset($_GET['sid'])){
		$prefix_order = get_option( 'wc_paydesign_prefix_order' );
		$order_id = str_replace($prefix_order, '', $_GET['sid']);
		$order = new WC_Order( $order_id );
		$order_payment_method  = get_post_meta( $order_id, '_payment_method', true );
		if($order_payment_method == 'paydesign_cc'){
			wc_increase_stock_levels( $order_id );
			$order->update_status( 'cancelled', __( 'This order is cancelled, because of the return from metaps PAYMENT site.', 'woo-paydesign' ) );
		}
	}
	
}
add_action('woocommerce_before_cart', 'paydesign_cc_return');

