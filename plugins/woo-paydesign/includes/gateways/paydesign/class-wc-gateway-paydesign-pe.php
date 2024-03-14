<?php

if ( ! defined( 'ABSPATH' ) ) exit; // Exit if accessed directly

/**
 * metaps PAYMENT Gateway
 *
 * Provides a metaps PAYMENT Pay Easy Payment Gateway.
 *
 * @class 		WC_PAYDESIGN
 * @extends		WC_Gateway_PAYDESIGN_PE
 * @version		1.1.17
 * @package		WooCommerce/Classes/Payment
 * @author		Artisan Workshop
 */
class WC_Gateway_PAYDESIGN_PE extends WC_Payment_Gateway {


	/**
	 * Constructor for the gateway.
	 *
	 * @access public
	 * @return void
	 */
	public function __construct() {

		$this->id                = 'paydesign_pe';
		$this->has_fields        = false;
		$this->method_title      = __( 'metaps PAYMENT Pay Easy', 'woo-paydesign' );

        // Create plugin fields and settings
		$this->init_form_fields();
		$this->init_settings();
		$this->method_description = __( 'Allows payments by metaps PAYMENT Pay Easy in Japan.', 'woo-paydesign' );
		if(is_null($this->title)){
			$this->title = __( 'Please set this payment at Control Panel! ', 'woo-paydesign' ).$this->method_title;
		}

		// Get setting values
		foreach ( $this->settings as $key => $val ) $this->$key = $val;

		// Actions
		add_action( 'woocommerce_update_options_payment_gateways',              array( $this, 'process_admin_options' ) );
		add_action( 'woocommerce_update_options_payment_gateways_' . $this->id, array( $this, 'process_admin_options' ) );

	    // Customer Emails
	    add_action( 'woocommerce_email_before_order_table', array( &$this, 'email_instructions' ), 10, 3 );
	}

	/**
	* Initialize Gateway Settings Form Fields.
	*/
	function init_form_fields() {

		$this->form_fields = array(
			'enabled'     => array(
				'title'       => __( 'Enable/Disable', 'woo-paydesign' ),
				'label'       => __( 'Enable metaps PAYMENT Pay Easy Payment', 'woo-paydesign' ),
				'type'        => 'checkbox',
				'description' => '',
				'default'     => 'no'
			),
			'title'       => array(
				'title'       => __( 'Title', 'woo-paydesign' ),
				'type'        => 'text',
				'description' => __( 'This controls the title which the user sees during checkout.', 'woo-paydesign' ),
				'default'     => __( 'Pay Easy Payment (PAYDESIGN)', 'woo-paydesign' )
			),
			'description' => array(
				'title'       => __( 'Description', 'woo-paydesign' ),
				'type'        => 'textarea',
				'description' => __( 'This controls the description which the user sees during checkout.', 'woo-paydesign' ),
				'default'     => __( 'Pay with your Pay Easy via metaps PAYMENT.', 'woo-paydesign' )
			),
			'order_button_text' => array(
				'title'       => __( 'Order Button Text', 'woo-paydesign' ),
				'type'        => 'textarea',
				'description' => __( 'This controls the description which the user sees during checkout.', 'woo-paydesign' ),
				'default'     => __( 'Proceed to metaps PAYMENT Pay Easy', 'woo-paydesign' )
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
			'payeasy_email_desc'       => array(
				'title'       => __( 'Explain the Pay-Easy method in Email', 'woo-paydesign' ),
				'type'        => 'textarea',
				'description' => __( 'This explains the Pay-Easy method of payment in Email, how to use.', 'woo-paydesign' ),
			),
			'processing_email_subject'       => array(
				'title'       => __( 'Email Subject when complete payment check', 'woo-paydesign' ),
				'type'        => 'text',
				'description' => __( 'send e-mail subject when check metaps PAYMENT after customer paid.', 'woo-paydesign' ),
				'default'     => __( 'Payment Complete by Pay-easy', 'woo-paydesign' )
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

	/**
	* UI - Admin Panel Options
	*/
	function admin_options() { ?>
		<h3><?php _e( 'metaps PAYMENT Pay Easy Payment','woo-paydesign' ); ?></h3>
		<table class="form-table">
		<?php $this->generate_settings_html(); ?>
		</table>
	<?php }
    /**
     * UI - Payment page fields for metaps PAYMENT Payment.
    */
	function payment_fields() {
		// Description of payment method from settings
		if ( $this->description ) { ?>
        <p><?php echo $this->description; ?></p>
      	<?php }
    }

	/**
	 * Process the payment and return the result.
	 */
	function process_payment( $order_id ) {
		include_once( 'includes/class-wc-gateway-paydesign-request.php' );
		$paydesign_request = new WC_Gateway_PAYDESIGN_Request();

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
		$setting_data['store'] = '84';
		// Set Payment limit date
		$kigen = mktime(0, 0, 0, date('m')  , date('d')+$this->payment_deadline, date('Y'));
		$setting_data['kigen'] = date('Ymd', $kigen);
		$connect_url = PAYDESIGN_CS_SALES_URL;
		$response = $paydesign_request->paydesign_post_request( $order, $connect_url, $setting_data );
//		$order->add_order_note( 'test-001' );
		if( isset($response[0]) and substr($response[0],0,2) == 'OK' ){
			if(isset($response[3])){
				if(version_compare( WC_VERSION, '2.7', '<' )){
					add_post_meta( $order_id, '_transaction_id', $response[3], true );
				}else{
					$order->set_transaction_id($response[3]);
				}
			}
			if(isset($response[6])){
				add_post_meta( $order_id, '_paydesign_payment_url', wc_clean( $response[6] ), true );
				$order->add_order_note( __( 'Housing agency code : ', 'woo-paydesign' ).substr($response[3],0,5).', '.__( 'Customer Number : ', 'woo-paydesign' ).substr($response[3],6,20).', '.__( 'Authorization number : ', 'woo-paydesign' ).substr($response[3],27,6) );
				$order->add_order_note( __( 'Confirmation URL : ', 'woo-paydesign' ).$response[6] );
			}

			$order->update_status( 'on-hold', __( 'This order is complete for pay.', 'woo-paydesign' ) );
			// Reduce stock levels
			$order->reduce_order_stock();

			// Remove cart
			WC()->cart->empty_cart();
			
			return array(
				'result'   => 'success',
				'redirect' => $this->get_return_url( $order ),
			);
		}else{
			$order->update_status( 'cancelled', __( 'This order is cancelled, because of Payment error.'.mb_convert_encoding($response[2], "UTF-8", "auto"), 'woo-paydesign' ) );
			if(is_checkout())wc_add_notice( __('Payment error:', 'woo-paydesign') . mb_convert_encoding($response[2], "UTF-8", "auto"), 'error' );
			return;
		}
	}
	
    /**
     * Check payment details for valid format
     */
	function validate_fields() {
		$last_name = $this->get_post( 'billing_last_name' );
		$first_name = $this->get_post( 'billing_first_name' );
		$last_name_kana = $this->get_post( 'billing_yomigana_last_name' );
		$first_name_kana = $this->get_post( 'billing_yomigana_first_name' );
		$billing_city = $this->get_post( 'billing_city' );
		if($this->is_zenkaku($last_name, false ) == false) wc_add_notice( sprintf(__('ERROR : %s must be Zenkaku when you use Payeasy Payment.', 'woo-paydesign'), __('Last name', 'woocommerce')), 'error' );
		if($this->is_zenkaku($first_name, false ) == false) wc_add_notice( sprintf(__('ERROR : %s must be Zenkaku when you use Payeasy Payment.', 'woo-paydesign'), __('First name', 'woocommerce')), 'error' );
		if($this->is_zenkaku($last_name_kana, true ) == false) wc_add_notice( sprintf(__('ERROR : %s must be Zenkaku Katakana when you use Payeasy Payment.', 'woo-paydesign'), __( 'Last Name (Yomigana)', 'woocommerce-for-japan' )), 'error' );
		if($this->is_zenkaku($first_name_kana, true ) == false) wc_add_notice( sprintf(__('ERROR : %s must be Zenkaku Katakana when you use Payeasy Payment.', 'woo-paydesign'), __( 'First Name (Yomigana)', 'woocommerce-for-japan' )), 'error' );
		if($this->is_zenkaku($billing_city, false ) == false) wc_add_notice( sprintf(__('ERROR : %s must be Zenkaku when you use Payeasy Payment.', 'woo-paydesign'), __( 'Town / City', 'woocommerce-for-japan' )), 'error' );
		return ;
	}

	function is_zenkaku($text, $katakana = false){
		$len = strlen($text);
		// UTF-8の場合は全角を3文字カウントするので「* 3」にする
		$mblen = mb_strlen($text, "UTF-8") * 3;
		if($len != $mblen) {
			return false;
		} else {
			if($katakana){
				if(preg_match("/^[ァ-ヾ]+$/u",$text)){
					return true;
				}else{
					return false;
				}				
			}else{
				return true;
			}
		}

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
    public function email_instructions( $order, $sent_to_admin, $plain_text = false ) {
		if(version_compare( WC_VERSION, '2.7', '<' )){
			$payment_method = $order->payment_method;
			$status = $order->status;
		}else{
			$payment_method = $order->get_payment_method();
			$status = $order->get_status();
		}
		$order_id = version_compare( WC_VERSION, '2.7', '<' ) ? $order->id : $order->get_id();
    	if ( ! $sent_to_admin &&  $payment_method == 'paydesign_pe' &&  $status == 'on-hold') {
			$this->paydesign_pe_details( $order_id );
		}
	}

    /**
     * Get Convini Payment details and place into a list format
     */
    private function paydesign_pe_details( $order_id = '' ) {
		$order = new WC_Order( $order_id );
		$payment_url = get_post_meta($order_id, '_paydesign_payment_url',true);

		echo __('Payment Information URL : ', 'woo-paydesign').$payment_url.'<br />'.PHP_EOL;
		if(isset($this->payeasy_email_desc)){
			echo $this->payeasy_email_desc;
		}
    }

	/**
	 * Process a refund if supported
	 * @param  int $order_id
	 * @param  float $amount
	 * @param  string $reason
	 * @return  boolean True or false based on success, or a WP_Error object
	 */
	public function process_refund( $order_id, $amount = null, $reason = '' ) {
		$order = wc_get_order( $order_id );
		return false;
	}

    /**
     * Include jQuery and our scripts
     */
/*    function add_paydesign_cc_scripts() {

      if ( ! $this->user_has_stored_data( wp_get_current_user()->ID ) ) return;

      wp_enqueue_script( 'jquery' );
      wp_enqueue_script( 'edit_billing_details', plugin_dir_path( __FILE__ ) . 'js/edit_billing_details.js', array( 'jquery' ), 1.0 );

    }
*/
	/**
	 * Get post data if set
	 */
	private function get_post( $name ) {
		if ( isset( $_POST[ $name ] ) ) {
			return $_POST[ $name ];
		}
		return null;
	}

}

/**
 * Add the gateway to woocommerce
 */
function add_wc_paydesign_pe_gateway( $methods ) {
	$methods[] = 'WC_Gateway_PAYDESIGN_PE';
	return $methods;
}

add_filter( 'woocommerce_payment_gateways', 'add_wc_paydesign_pe_gateway' );

/**
 * Get Payeasy Payment details and place into a list format
 */
function paydesign_pe_detail( $order ){
	global $woocommerce;
	$order_id = version_compare( WC_VERSION, '2.7', '<' ) ? $order->id : $order->get_id();

	$payment_setting = get_option('woocommerce_paydesign_pe_settings');
	$payment_limit_description =$payment_setting['payment_limit_description'];
	$payment_url = get_post_meta( $order_id, '_paydesign_payment_url', true);
	$transaction_id = get_post_meta( $order_id, '_transaction_id', true);

	if( get_post_meta( $order_id, '_payment_method', true ) == 'paydesign_pe' ){
		echo '<header class="title"><h3>'.__('Payment Detail', 'woo-paydesign').'</h3></header>';
		echo '<table class="shop_table order_details">';
		echo '<tr><th>'.__('Payment Detail', 'woo-paydesign').'</th><td>'.__( 'Housing agency code : ', 'woo-paydesign' ).substr($transaction_id,0,5).'<br />'.__( 'Customer Number : ', 'woo-paydesign' ).substr($transaction_id,6,20).'<br />'.__( 'Authorization number : ', 'woo-paydesign' ).substr($transaction_id,27,6).'<br /></td></tr>'.PHP_EOL;
		echo '<tr><th>'.__('Payment URL', 'woo-paydesign').'</th><td><a href="'.$payment_url.'" target="_blank">'.__('Pay from here.', 'woo-paydesign').'</a></td></tr>'.PHP_EOL;
		if(isset($payment_limit_description)){
			echo '<tr><th>'.__('Payment limit term', 'woo-paydesign').'</th><td>'.$payment_limit_description.'</td></tr>'.PHP_EOL;
		}
		echo '</table>';
	}
}
add_action( 'woocommerce_order_details_after_order_table', 'paydesign_pe_detail', 10, 1);

/**
 * Recieved Payeasy Payment complete from Paydesign
 */
function paydesign_pe_recieved(){
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
		if(isset($_GET['TIME']) and isset($order_status) and $order_status != 'processing' and $order_payment_method == 'paydesign_pe'){
			// Mark as processing (payment complete)
			$order->update_status( 'processing', sprintf( __( 'Payment of %s was complete.', 'woo-paydesign' ) , __( 'Payeasey Payment (Paydesign)', 'woo-paydesign' ) ) );
		}
		header("Location: ".plugin_dir_url( __FILE__ )."empty.php");
	}
}

add_action( 'woocommerce_cart_is_empty', 'paydesign_pe_recieved');

// E-mail Subject and heading and body Change when processing in this Payment
function change_email_subject_paydesign_pe($subject, $order){
	global $woocommerce;
	if(version_compare( WC_VERSION, '2.7', '<' )){
		$payment_method = $order->payment_method;
		$status = $order->status;
	}else{
		$payment_method = $order->get_payment_method();
		$status = $order->get_status();
	}
	if ( 'paydesign_pe' == $payment_method  && 'processing' === $status) {
		$payment_setting = get_option('woocommerce_paydesign_pe_settings');
		$subject =$payment_setting['processing_email_subject'];
	}
	return $subject;
}
function change_email_heading_paydesign_pe($heading, $order){
	global $woocommerce;
	if(version_compare( WC_VERSION, '2.7', '<' )){
		$payment_method = $order->payment_method;
		$status = $order->status;
	}else{
		$payment_method = $order->get_payment_method();
		$status = $order->get_status();
	}
	if ( 'paydesign_pe' == $payment_method  && 'processing' === $status) {
		$payment_setting = get_option('woocommerce_paydesign_pe_settings');
		$heading = $payment_setting['processing_email_heading'];
	}
	return $heading;
}
function change_email_instructions_pe( $order, $sent_to_admin ) {
	global $woocommerce;
	if(version_compare( WC_VERSION, '2.7', '<' )){
		$payment_method = $order->payment_method;
		$status = $order->status;
	}else{
		$payment_method = $order->get_payment_method();
		$status = $order->get_status();
	}
    if ( 'paydesign_pe' == $payment_method  && 'processing' === $status) {
		$payment_setting = get_option('woocommerce_paydesign_pe_settings');
		echo $payment_setting['processing_email_body'];
	}
}

add_filter( 'woocommerce_email_subject_customer_processing_order', 'change_email_subject_paydesign_pe', 1, 2 );
add_filter( 'woocommerce_email_heading_customer_processing_order', 'change_email_heading_paydesign_pe', 1, 2 );
add_action( 'woocommerce_email_before_order_table', 'change_email_instructions_pe', 1, 2 );

