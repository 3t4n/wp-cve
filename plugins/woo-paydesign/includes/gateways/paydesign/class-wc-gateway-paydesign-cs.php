<?php

use ArtisanWorkshop\WooCommerce\PluginFramework\v2_0_12 as Framework;

if ( ! defined( 'ABSPATH' ) ) exit; // Exit if accessed directly

/**
 * metaps PAYMENT Gateway
 *
 * Provides a metaps PAYMENT Convenience Store Payment Gateway.
 *
 * @class 		WC_PAYDESIGN
 * @extends		WC_Gateway_PAYDESIGN_CS
 * @version		1.3.0
 * @package		WooCommerce/Classes/Payment
 * @author		Artisan Workshop
 */
class WC_Gateway_PAYDESIGN_CS extends WC_Payment_Gateway {


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
	 * Convenience stores
     *
     * @var array
	 */
    public $cs_stores;
    /**
	 * Constructor for the gateway.
	 *
	 * @access public
	 * @return void
	 */	
	public function __construct() {

		$this->id                = 'paydesign_cs';
		$this->has_fields        = false;
		$this->method_title      = __( 'metaps PAYMENT Convenience Store', 'woo-paydesign' );

		// Create plugin fields and settings
		$this->init_form_fields();
		$this->init_settings();
		$this->method_description = __( 'Allows payments by metaps PAYMENT Convenience Store in Japan.', 'woo-paydesign' );
//		$this->method_description .= '<br /><strong>'.__( 'If "Lawson/Ministop" is checked in the convenience store payment column, please also check "Seicomart". You cannot use "Seicomart" unless you check the box.', 'woo-paydesign' ).'</strong>';
		$this->supports = array(
			'products',
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

        // Set Convenience Store
		$this->cs_stores = array();
		$cs_stores = array();
//		if(isset($this->setting_cs_lp) && $this->setting_cs_lp =='yes') $cs_stores['1'] =  __( 'Loppi Payment', 'woo-paydesign' );
		if(isset($this->setting_cs_sv) && $this->setting_cs_sv =='yes') $cs_stores['2'] =  __( 'Seven-Eleven', 'woo-paydesign' );
		if(isset($this->setting_cs_fm) && $this->setting_cs_fm =='yes') $cs_stores['3'] =  __( 'family mart', 'woo-paydesign' );
		if(isset($this->setting_cs_ol) && $this->setting_cs_ol =='yes') $cs_stores['73'] = __( 'Daily Yamazaki', 'woo-paydesign' );
		// from 2023/05
		if(isset($this->setting_cs_lp) && $this->setting_cs_lp =='yes') $cs_stores['5'] =  __( 'Lawson, MINISTOP', 'woo-paydesign' );
		if(isset($this->setting_cs_sm) && $this->setting_cs_sm =='yes') $cs_stores['6'] = __( 'Seicomart', 'woo-paydesign' );
		$this->cs_stores = $cs_stores;

		// Actions
//		add_action( 'woocommerce_receipt_paydesign_cv',                              array( $this, 'receipt_page' ) );
		add_action( 'woocommerce_update_options_payment_gateways',              array( $this, 'process_admin_options' ) );
		add_action( 'woocommerce_update_options_payment_gateways_' . $this->id, array( $this, 'process_admin_options' ) );
	    // Customer Emails
	    add_action( 'woocommerce_email_before_order_table', array( $this, 'pd_email_instructions' ), 10, 3 );
	}
	/**
	* Initialize Gateway Settings Form Fields.
	*/
	function init_form_fields() {

		$this->form_fields = array(
			'enabled'     => array(
				'title'       => __( 'Enable/Disable', 'woo-paydesign' ),
				'label'       => __( 'Enable metaps PAYMENT Convenience Store Payment', 'woo-paydesign' ),
				'type'        => 'checkbox',
				'description' => '',
				'default'     => 'no'
			),
			'title'       => array(
				'title'       => __( 'Title', 'woo-paydesign' ),
				'type'        => 'text',
				'description' => __( 'This controls the title which the user sees during checkout.', 'woo-paydesign' ),
				'default'     => __( 'Convenience Store Payment (Paydesign)', 'woo-paydesign' )
			),
			'description' => array(
				'title'       => __( 'Description', 'woo-paydesign' ),
				'type'        => 'textarea',
				'description' => __( 'This controls the description which the user sees during checkout.', 'woo-paydesign' ),
				'default'     => __( 'Pay at Convenience Store via metaps PAYMENT.', 'woo-paydesign' )
			),
			'order_button_text' => array(
				'title'       => __( 'Order Button Text', 'woo-paydesign' ),
				'type'        => 'text',
				'description' => __( 'This controls the description which the user sees during checkout.', 'woo-paydesign' ),
				'default'     => __( 'Proceed to metaps PAYMENT Convenience Store Payment', 'woo-paydesign' )
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
			'setting_cs_sv' => array(
				'id'              => 'wc-paydesign-cs-sv',
				'type'        => 'checkbox',
				'label'       => __( 'Seven-Eleven', 'woo-paydesign' ),
				'default'     => 'yes',
			),
			'setting_cs_lp' => array(
				'title'       => __( 'Convenience Payments', 'woo-paydesign' ),
				'id'              => 'wc-paydesign-cs-lp',
				'type'        => 'checkbox',
				'label'       => __( 'Lawson, MINISTOP', 'woo-paydesign' ),
				'default'     => 'yes',
			),
			'setting_cs_fm' => array(
				'id'              => 'wc-paydesign-cs-fm',
				'type'        => 'checkbox',
				'label'       => __( 'family mart', 'woo-paydesign' ),
				'default'     => 'yes',
			),
			'setting_cs_sm' => array(
				'id'              => 'wc-paydesign-cs-sm',
				'type'        => 'checkbox',
				'label'       => __( 'Seicomart', 'woo-paydesign' ),
				'default'     => 'yes',
			),
			'setting_cs_ol' => array(
				'id'              => 'wc-paydesign-cs-ol',
				'type'        => 'checkbox',
				'label'       => __( 'Daily Yamazaki', 'woo-paydesign' ),
				'default'     => 'yes',
			),
			'payment_deadline'       => array(
				'title'       => __( 'Due date for payment', 'woo-paydesign' ),
				'type'        => 'select',
				'description' => __( 'Select the days term of due date for payment', 'woo-paydesign' ),
				'options'     => array(
					'5'			=> '5'.__( 'days', 'woo-paydesign' ),
					'7'			=> '7'.__( 'days', 'woo-paydesign' ),
					'10'		=> '10'.__( 'days', 'woo-paydesign' ),
					'15'		=> '15'.__( 'days', 'woo-paydesign' ),
					'30'		=> '30'.__( 'days', 'woo-paydesign' ),
					'60'		=> '60'.__( 'days', 'woo-paydesign' ),
				)
			),
			'processing_email_subject'       => array(
				'title'       => __( 'Email Subject when complete payment check', 'woo-paydesign' ),
				'type'        => 'text',
				'description' => __( 'send e-mail subject when check metaps PAYMENT after customer paid.', 'woo-paydesign' ),
				'default'     => __( 'Payment Complete by CS', 'woo-paydesign' )
			),
			'processing_email_heading'       => array(
				'title'       => __( 'Email Heading when complete payment check', 'woo-paydesign' ),
				'type'        => 'text',
				'description' => __( 'send e-mail heading when check metaps PAYMENT after customer paid.', 'woo-paydesign' ),
				'default'     => __( 'Thank you for your payment', 'woo-paydesign' )
			),
			'processing_email_body'       => array(
				'title'       => __( 'Email body when complete payment check', 'woo-paydesign' ),
				'type'        => 'textarea',
				'description' => __( 'send e-mail Body when check metaps PAYMENT after customer paid.', 'woo-paydesign' ),
				'default'     => __( 'I checked your payment. Thank you. I will ship your order as soon as possible.', 'woo-paydesign' )
			),
			'payment_limit_description'       => array(
				'title'       => __( 'Explain Payment limit date', 'woo-paydesign' ),
				'type'        => 'text',
				'description' => __( 'Explain Payment limite date in New order E-mail.', 'woo-paydesign' ),
				'default'     => __( 'The payment deadline is 10 days from completed the order.', 'woo-paydesign' )
			),
		);
	}

	function cs_select() {
		?><select name="convenience">
			<?php //print_r($this->cs_stores);?>
		<?php foreach($this->cs_stores as $num => $value){?>
			<option value="<?php echo $num; ?>"><?php echo $value;?></option>
		<?php }?>
		</select><?php 
	}
	/**
	 * UI - Payment page fields for metaps PAYMENT Payment.
	 */
	function payment_fields() {
		// Description of payment method from settings
		if ( $this->description ) { ?>
			<p><?php echo $this->description; ?></p>
		<?php } ?>
		<fieldset  style="padding-left: 40px;">
		<?php $this->cs_select(); ?>
		</fieldset>
		<?php
    }

	/**
	 * Process the payment and return the result.
	 */
	function process_payment( $order_id ) {
		$prefix_order = get_option( 'wc_paydesign_prefix_order' );

		$order = wc_get_order( $order_id );
		$user = wp_get_current_user();
		if($order->user_id){
			$customer_id = $prefix_order.$user->ID;
		}else{
			$customer_id = $prefix_order.$order_id.'-user';
		}
		//Setting $send_data
		$setting_data = array();

		$setting_data['ip'] = $this->ip_code;
		$setting_data['sid'] = $prefix_order.$order_id;
		$setting_data['store'] = $this->get_post('convenience');
		// Set Payment limit date
		$kigen = mktime(0, 0, 0, date('m')  , date('d')+$this->payment_deadline, date('Y'));
		$setting_data['kigen'] = date('Ymd', $kigen);

		$connect_url = PAYDESIGN_CS_SALES_URL;
		$response = $this->metaps_request->paydesign_post_request( $order, $connect_url, $setting_data );
//		$order->add_order_note( 'test-001' );
		if( isset($response[0]) and substr($response[0],0,2) == 'OK' ){
			if(isset($setting_data['store']))add_post_meta( $order_id, '_paydesign_cvs_id', wc_clean( $setting_data['store'] ), true );
			if(isset($response[3])){
				if(version_compare( WC_VERSION, '2.7', '<' )){
					add_post_meta( $order_id, '_transaction_id', $response[3], true );
				}else{
					$order->set_transaction_id($response[3]);
				}
			}
			if(isset($response[6]))add_post_meta( $order_id, '_paydesign_payment_url', wc_clean( $response[6] ), true );
			if($setting_data['store'] == 1){
				$cvs_trans_title = __( 'Receipt number : ', 'woo-paydesign' );
			}elseif($setting_data['store'] == 2){
				$cvs_trans_title = __( 'Payment slip number : ', 'woo-paydesign' );
			}elseif($setting_data['store'] == 3){
				$cvs_trans_title = __( 'Company code - Order Number : ', 'woo-paydesign' );
			}elseif($setting_data['store'] == 73){
				$cvs_trans_title = __( 'Online payment number : ', 'woo-paydesign' );
			}
			$order->add_order_note( $cvs_trans_title . $response[3] );
			if(isset($response[6]))$order->add_order_note( __( 'Confirmation URL : ', 'woo-paydesign' ).$response[6] );

			$order->update_status( 'on-hold', __( 'This order is complete for pay.', 'woo-paydesign' ) );
			// Reduce stock levels
            wc_reduce_stock_levels( $order_id );

			// Remove cart
			WC()->cart->empty_cart();
			
			return array(
				'result'   => 'success',
				'redirect' => $this->get_return_url( $order ),
			);
		}else{
			$order->update_status( 'cancelled', __( 'This order is cancelled, because of Payment error.'.mb_convert_encoding($response[2], "UTF-8", "auto"), 'woo-paydesign' ) );
			wc_add_notice( __('Payment error:', 'woo-paydesign') . mb_convert_encoding($response[2], "UTF-8", "auto"), 'error' );
			return;
		}
	}

	/**
	 * Refund a charge
	 * @param  int $order_id
	 * @param  float $amount
	 * @return bool
	 */
	public function process_refund( $order_id, $amount = null, $reason = '' ) {
		$order = wc_get_order( $order_id );

		if ( ! $order ) {
			return false;
		}
		$prefix_order = get_option( 'wc_paydesign_prefix_order' );

		$cansel_connect_url = PAYDESIGN_CS_CANCEL_URL;

		$status = $order->get_status();
		$data['IP'] = $this->ip_code;
		$data['SID'] = $prefix_order.$order_id;
		$data['STORE'] = get_post_meta( $order_id, '_paydesign_cvs_id', true );
		$response = $this->metaps_request->paydesign_request( $data ,$cansel_connect_url ,$order );
		if(substr($response,0,10) == 'C-CHECK:OK' and $amount == $order->get_total()){
			return true;
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
			return $_POST[ $name ];
		}
		return null;
	}
    /**
     * Add content to the WC emails For Convenient Infomation.
     *
     * @access public
     * @param WC_Order $order
     * @param bool $sent_to_admin
     * @param bool $plain_text
     * @return void
     */
	public function pd_email_instructions( $order, $sent_to_admin, $plain_text = false ) {
		sleep(1);
        $payment_method = $order->get_payment_method();
        $status = $order->get_status();
    	if ( ! $sent_to_admin &&  $payment_method == 'paydesign_cs' && $status == 'on-hold'  ) {
			$this->paydesign_cs_details( $order->get_id() );
    	}
	}

	/**
     * Get Convini Payment details and place into a list format
	 */
	private function paydesign_cs_details( $order_id = '' ) {
//		global $woocommerce;
		$cvs = $this->cs_stores;
		$cvs_id = get_post_meta( $order_id, '_paydesign_cvs_id', true );
		$payment_url = get_post_meta( $order_id, '_paydesign_payment_url', true);
		$transaction_id = get_post_meta( $order_id, '_transaction_id', true);

		if($cvs_id == 1){
			$cvs_trans_title = __( 'Receipt number : ', 'woo-paydesign' );
		}elseif($cvs_id == 2){
			$cvs_trans_title = __( 'Payment slip number : ', 'woo-paydesign' );
		}elseif($cvs_id == 3){
			$cvs_trans_title = __( 'Company code - Order Number : ', 'woo-paydesign' );
		}elseif($cvs_id == 73){
			$cvs_trans_title = __( 'Online payment number : ', 'woo-paydesign' );
		}

		echo __('CVS Name : ', 'woo-paydesign').$cvs[$cvs_id].'<br />'.PHP_EOL;
		echo $cvs_trans_title.$transaction_id.'<br />'.PHP_EOL;
		echo __('How to Pay via CVS expalin URL : ', 'woo-paydesign').$payment_url.'<br />'.PHP_EOL;
		if(isset($this->payment_limit_description)){
			echo __('Payment limit term : ', 'woo-paydesign').$this->payment_limit_description;
		}
	}
}
/**
 * Add the gateway to woocommerce
 */
function add_wc_paydesign_cs_gateway( $methods ) {
	$methods[] = 'WC_Gateway_PAYDESIGN_CS';
	return $methods;
}

add_filter( 'woocommerce_payment_gateways', 'add_wc_paydesign_cs_gateway' );

/**
 * Get Convini Payment details and place into a list format
 */
function paydesign_cs_detail( $order ){
	global $woocommerce;

//	$cs_stores['1'] =  __( 'Loppi Payment', 'woo-paydesign' );
	$cs_stores['2'] =  __( 'Seven-Eleven', 'woo-paydesign' );
	$cs_stores['3'] =  __( 'family mart', 'woo-paydesign' );
	$cs_stores['5'] =  __( 'Lawson, MINISTOP', 'woo-paydesign' );
	$cs_stores['6'] =  __( 'Seicomart', 'woo-paydesign' );
	$cs_stores['73'] = __( 'Daily Yamazaki', 'woo-paydesign' );

	$cvs_trans_title['2'] = __( 'Payment slip number', 'woo-paydesign' );
	$cvs_trans_title['3'] = __( 'Company code - Order Number', 'woo-paydesign' );
	$cvs_trans_title['5'] = __( 'Receipt number', 'woo-paydesign' );
	$cvs_trans_title['6'] =  __( 'Receipt number', 'woo-paydesign' );
	$cvs_trans_title['73'] = __( 'Online payment number', 'woo-paydesign' );

	$payment_setting = get_option('woocommerce_paydesign_cs_settings');
	$payment_limit_description =$payment_setting['payment_limit_description'];
	$order_id = version_compare( WC_VERSION, '2.7', '<' ) ? $order->id : $order->get_id();
	$cvs_id = get_post_meta( $order_id, '_paydesign_cvs_id', true );
	$payment_url = get_post_meta( $order_id, '_paydesign_payment_url', true);
	$transaction_id = version_compare( WC_VERSION, '2.7', '<' ) ? get_post_meta( $order_id, '_transaction_id', true) : $order->get_transaction_id();

	if( get_post_meta( $order_id, '_payment_method', true ) == 'paydesign_cs' ){
		echo '<header class="title"><h3>'.__('Payment Detail', 'woo-paydesign').'</h3></header>';
		echo '<table class="shop_table order_details">';
		echo '<tr><th>'.__('CVS Payment', 'woo-paydesign').'</th><td>'.$cs_stores[$cvs_id].'</td></tr>'.PHP_EOL;
		echo '<tr><th>'.$cvs_trans_title[$cvs_id].'</th><td>'.$transaction_id.'</td></tr>'.PHP_EOL;
		echo '<tr><th>'.__('Payment URL', 'woo-paydesign').'</th><td><a href="'.$payment_url.'" target="_blank">'.__('Pay from here.', 'woo-paydesign').'</a></td></tr>'.PHP_EOL;
		if(isset($payment_limit_description)){
			echo '<tr><th>'.__('Payment limit term', 'woo-paydesign').'</th><td>'.$payment_limit_description.'</td></tr>'.PHP_EOL;
		}
		echo '</table>';
	}
}
add_action( 'woocommerce_order_details_after_order_table', 'paydesign_cs_detail', 10, 1);

/**
 * Recieved Convenience Payment complete from Paydesign
 */
function paydesign_cs_recieved(){
	global $woocommerce;
	global $wpdb;

	$email = WC()->mailer();
	$emails = $email->get_emails();
	$send_processing_email = $emails['WC_Email_Customer_Processing_Order'];//require php file

	if(isset($_GET['SEQ']) and isset($_GET['DATE']) and isset($_GET['SID'])){
		$pd_order_id = $_GET['SID'];
		$prefix_order = get_option( 'wc_paydesign_prefix_order' );
		$order_id = str_replace($prefix_order, '', $pd_order_id);
		$order = new WC_Order( $order_id );
		$order_status = $order->get_status();
		$order_payment_method  = get_post_meta( $order_id, '_payment_method', true );
		if(isset($_GET['TIME']) and isset($order_status) and $order_status != 'processing' and $order_payment_method == 'paydesign_cs'){
			// Mark as processing (payment complete)
			$order->update_status( 'processing', sprintf( __( 'Payment of %s was complete.', 'woo-paydesign' ) , __( 'Convenience Store Payment (Paydesign)', 'woo-paydesign' ) ) );
		}elseif(isset($order_status) and !isset($_GET['TIME']) and $order_status != 'cancelled' and $order_payment_method == 'paydesign_cs'){
			// Mark as cancel (payment cancelled)
			$order->update_status( 'cancelled', sprintf( __( 'Payment of %s was cancelled.', 'woo-paydesign' ) , __( 'Convenience Store Payment (Paydesign)', 'woo-paydesign' ) ) );
		}
		header("Location: ".plugin_dir_url( __FILE__ )."empty.php");
	}
}

add_action( 'woocommerce_cart_is_empty', 'paydesign_cs_recieved');

// E-mail Subject and heading and body Change when processing in this Payment
function change_email_subject_paydesign_cs($subject, $order){
	global $woocommerce;
	if(version_compare( WC_VERSION, '2.7', '<' )){
		$payment_method = $order->payment_method;
		$status = $order->status;
	}else{
		$payment_method = $order->get_payment_method();
		$status = $order->get_status();
	}
	if ( 'paydesign_cs' == $payment_method  && 'processing' === $status) {
		$payment_setting = get_option('woocommerce_paydesign_cs_settings');
		$subject =$payment_setting['processing_email_subject'];
	}
	return $subject;
}
function change_email_heading_paydesign_cs($heading, $order){
	global $woocommerce;
	if(version_compare( WC_VERSION, '2.7', '<' )){
		$payment_method = $order->payment_method;
		$status = $order->status;
	}else{
		$payment_method = $order->get_payment_method();
		$status = $order->get_status();
	}
	if ( 'paydesign_cs' == $payment_method  && 'processing' === $status) {
		$payment_setting = get_option('woocommerce_paydesign_cs_settings');
		$heading = $payment_setting['processing_email_heading'];
	}
	return $heading;
}
function change_email_instructions_cs( $order, $sent_to_admin ) {
	global $woocommerce;
	if(version_compare( WC_VERSION, '2.7', '<' )){
		$payment_method = $order->payment_method;
		$status = $order->status;
	}else{
		$payment_method = $order->get_payment_method();
		$status = $order->get_status();
	}
    if ( 'paydesign_cs' == $payment_method  && 'processing' === $status) {
		$payment_setting = get_option('woocommerce_paydesign_pe_settings');
		echo $payment_setting['processing_email_body'];
	}
}

add_filter( 'woocommerce_email_subject_customer_processing_order', 'change_email_subject_paydesign_cs', 1, 2 );
add_filter( 'woocommerce_email_heading_customer_processing_order', 'change_email_heading_paydesign_cs', 1, 2 );
add_action( 'woocommerce_email_before_order_table', 'change_email_instructions_cs', 1, 2 );

