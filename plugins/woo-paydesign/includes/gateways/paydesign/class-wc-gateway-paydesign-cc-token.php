<?php

if ( ! defined( 'ABSPATH' ) ) exit; // Exit if accessed directly

/**
 * metaps PAYMENT Gateway
 *
 * Provides a metaps PAYMENT Credit Card Payment Gateway(TOKEN Type).
 *
 * @class 		WC_PAYDESIGN
 * @extends		WC_Gateway_PAYDESIGN_CC_TOKEN
 * @version		1.1.24
 * @package		WooCommerce/Classes/Payment
 * @author		Artisan Workshop
 */
class WC_Gateway_PAYDESIGN_CC_TOKEN extends WC_Payment_Gateway {

	public $array_number_of_payments = array();
	/**
	 * Constructor for the gateway.
	 *
	 * @access public
	 * @return void
	 */
	public function __construct() {

		$this->id                = 'paydesign_cc_token';
		$this->has_fields        = false;
		$this->method_title      = __( 'metaps PAYMENT Credit Card with Token', 'woo-paydesign' );
		
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
			'default_credit_card_form',
			'refunds'
		);

        // When no save setting error at chackout page
		if(is_null($this->title)){
			$this->title = __( 'Please set this payment at Control Panel! ', 'woo-paydesign' ).$this->method_title;
		}

		// Get setting values
		foreach ( $this->settings as $key => $val ) $this->$key = $val;
		// Actions
		add_action( 'woocommerce_receipt_paydesign_cc_token',                   array( $this, 'receipt_page' ) );
		add_action( 'woocommerce_update_options_payment_gateways',              array( $this, 'process_admin_options' ) );
		add_action( 'woocommerce_update_options_payment_gateways_' . $this->id, array( $this, 'process_admin_options' ) );
		add_action( 'wp_enqueue_scripts', array( $this, 'custom_wp_enqueue_script') );
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
				'default'     => __( 'Credit Card', 'woo-paydesign' )
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
			'payment_time_text' => array(
				'title'       => __( 'Payment Time Text', 'woo-paydesign' ),
				'type'        => 'text',
				'description' => __( 'This controls the description which the user sees during checkout.', 'woo-paydesign' ),
				'default'     => __( 'Payment Times : ', 'woo-paydesign' )
			),
			'number_of_payments' => array(
				'title'       => __( 'Number of payments', 'woo-paydesign' ),
				'type'        => 'multiselect',
				'class'       => 'wc-number-select',
				'description' => __( 'Choose whether you wish to capture funds immediately or authorize payment only.', 'woo-paydesign' ),
				'desc_tip'    => true,
				'options'     => array(
					'100'	=> __( '1 time', 'woo-paydesign' ),
//					'21'	=> __( 'Bonus One time', woo-paydesign' ),
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
		$number_payment_array = $this->form_fields['number_of_payments']['options'];
		$paydesign_user_id = get_user_meta( $user->ID, '_paydesign_user_id', true );
		if($this->user_id_payment == 'yes' and $paydesign_user_id !='' and is_user_logged_in()){?>
			<input type="radio" name="select_card" value="old" checked="checked" onclick="document.getElementById('paydesign-new-info').style.display='none'"><span style="padding-left:15px;"><?php echo __( 'Use Stored Card.', 'woo-paydesign' );?></span><br />
			<?php
			$paydesign_setting = get_option('woocommerce_paydesign_cc_token_settings');
			if(isset($paydesign_setting['payment_time_text']))echo '<label>'.$paydesign_setting['payment_time_text'].'</label>';
			if(!empty($this->number_of_payments)){
				echo '<select name="number_of_payments">';
				foreach($this->number_of_payments as $key => $value){
					echo '<option value="'.$value.'">'.$number_payment_array[$value].'</option>';
				}
				echo '</select>';
			}?>
			<br />
			<input type="radio" name="select_card" value="new" onclick="document.getElementById('paydesign-new-info').style.display='block'"><span style="padding-left:15px;"><?php echo __( 'Use New Card.', 'woo-paydesign' );?></span><br />
		<?php }
		if($this->user_id_payment == 'yes' and $paydesign_user_id !='' and is_user_logged_in()){
			?><div id="paydesign-new-info" style="display:none"><?php
		}else{
			?><!-- Show input boxes for new data -->
			<div id="paydesign-new-info"><?php

		}
		if(version_compare( WC_VERSION, '2.5', '<' )){
			$this->credit_card_form( array( 'fields_have_names' => true ) );
		}else{
			$cc_form = new WC_Payment_Gateway_CC;
			$cc_form->id       = $this->id;
			$cc_form->supports = $this->supports;
			$cc_form->form();
			$paydesign_setting = get_option('woocommerce_paydesign_cc_token_settings');
			if(isset($paydesign_setting['payment_time_text']))echo '<label>'.$paydesign_setting['payment_time_text'].'</label>';
			if(!empty($this->number_of_payments)){
				echo '<select name="number_of_payments_token">';
				foreach($this->number_of_payments as $key => $value){
					echo '<option value="'.$value.'">'.$number_payment_array[$value].'</option>';
				}
				echo '</select>';
			}
		}?>
</div>
<script language="javascript">
document.getElementById("paydesign_cc_token-card-cvc").addEventListener("input", metapspaymentToken);
document.getElementById("paydesign_cc_token-card-number").addEventListener("input", metapspaymentToken);
document.getElementById("paydesign_cc_token-card-expiry").addEventListener("input", metapspaymentToken);
var metapspaymentToken = function () {
	if(jQuery(":radio[name=payment_method]:checked").val() != 'paydesign_cc_token'){return;}
	if(jQuery(":radio[name='select_card']:checked").val() == "old"){return;}
	var cr = document.getElementById('paydesign_cc_token-card-number').value ;
	cr = cr.replace(/ /g, '');
	var cs = document.getElementById('paydesign_cc_token-card-cvc').value ;
	var exp_my = document.getElementById('paydesign_cc_token-card-expiry').value ;
	exp_my = exp_my.replace(/ /g, '');
	exp_my = exp_my.replace('/', '');
	var exp_m = exp_my.substr(0,2);
	var exp_y = exp_my.substr(2).substr(-2);
    jQuery('#place_order').prop("disabled", true);
	if(metapspayment.validateCardNumber(cr) && metapspayment.validateExpiry(exp_m,exp_y) && metapspayment.validateCSC(cs)){
		jQuery("#paydesign_cc_token_id").val('');
		metapspayment.setTimeout(20000);
		metapspayment.setLang("ja");
		metapspayment.createToken({number:cr,csc:cs,exp_m:exp_m,exp_y:exp_y},metapspaymentResponseHandler);
	}
}
//}, false);
var metapspaymentResponseHandler = function(status, response) {
  var token_id = jQuery("#paydesign_cc_token_id");
  if (response.error) {
	var select_card = jQuery("input[name='select_card']:checked").val();
  } else {
	token_id.val(response.id);
	document.getElementById('paydesign_cc_token_crno').value = response.crno ;
	document.getElementById('paydesign_cc_token_r_exp_y').value = response.exp_y ;
	document.getElementById('paydesign_cc_token_r_exp_m').value = response.exp_m ;
  }
  if(token_id.val() != ''){
    jQuery('#place_order').prop("disabled", false);
  }
}

jQuery(function($){
	$('#place_order').focus(function (){
		$('#paydesign_cc_token-card-number').prop("disabled", true);
		$('#paydesign_cc_token-card-expiry').prop("disabled", true);
		$('#paydesign_cc_token-card-cvc').prop("disabled", true);
	});
	$('#place_order').blur(function (){
		$('#paydesign_cc_token-card-number').prop("disabled", false);
		$('#paydesign_cc_token-card-expiry').prop("disabled", false);
		$('#paydesign_cc_token-card-cvc').prop("disabled", false);
	});
    $(":radio[name=payment_method]").on('change', function(){
        var checked = $(this).prop('checked');
        var id = this.id;
        $('#paydesign_cc_token-card-number').val('');
        $('#paydesign_cc_token-card-expiry').val('');
        $('#paydesign_cc_token-card-cvc').val('');
        $('#paydesign_cc_token_id').val('');
		if (id == "payment_method_paydesign_cc_token"){
			if(select_card = $("input[name='select_card']:checked").val() == "new") {
				$('#place_order').prop("disabled", true);
			}
		} else {
			$('#place_order').prop("disabled", false);
		}
    });
    $(":radio[name='select_card']").on('change', function(){
        $('#paydesign_cc_token-card-number').val('');
        $('#paydesign_cc_token-card-expiry').val('');
        $('#paydesign_cc_token-card-cvc').val('');
        $('#paydesign_cc_token_id').val('');
		if ($(this).val() == "new") {
			$('#place_order').prop("disabled", true);
		} else {
			$('#place_order').prop("disabled", false);
		}
    });
	$( document.body ).on( 'checkout_error', function() {
		if ( $(':radio[name=payment_method]:checked').val() == "paydesign_cc_token"){
			selectcard = $(":radio[name='select_card']:checked").val()
			if ( selectcard == null || selectcard == "new"){
				$('#paydesign_cc_token_id').val('');
				$('#paydesign_cc_token-card-number').val('');
				$('#paydesign_cc_token-card-expiry').val('');
				$('#paydesign_cc_token-card-cvc').val('');
				$('#paydesign_cc_token-card-number').prop("disabled", false);
				$('#paydesign_cc_token-card-expiry').prop("disabled", false);
				$('#paydesign_cc_token-card-cvc').prop("disabled", false);
			}
		}
	});
});

</script>
<input type="hidden" name="paydesign_cc_token_crno" id="paydesign_cc_token_crno"/>
<input type="hidden" name="paydesign_cc_token_r_exp_y" id="paydesign_cc_token_r_exp_y"/>
<input type="hidden" name="paydesign_cc_token_r_exp_m" id="paydesign_cc_token_r_exp_m"/>
<input type="hidden" name="paydesign_cc_token_id" id="paydesign_cc_token_id"/>
	<?php }

	/**
	 * Process the payment and return the result.
	 */
	function process_payment( $order_id  , $subscription = false) {
		global $woocommerce;
		include_once( 'includes/class-wc-gateway-paydesign-request.php' );
		$paydesign_request = new WC_Gateway_PAYDESIGN_Request();

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

		// Set User id
		if($this->user_id_payment == 'yes' and is_user_logged_in()) $setting_data['ip_user_id'] = $customer_id;
//		$order->add_order_note( $setting_data['ip_user_id'].'test01' );

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
		if($setting_data['store'] == '51' ){
			$number_of_payments = $this->get_post('number_of_payments_token');
		}else{
			$number_of_payments = $this->get_post('number_of_payments');
		}
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
		$setting_data['token'] = $this->get_post('paydesign_cc_token_id');
		if(isset($setting_data['store'])){// When not use user id payment
			$connect_url = PAYDESIGN_CS_SALES_URL;
			$order->add_order_note( __('Finished to send payment data to metaps PAYMENT.', 'woo-paydesign') );

			$response = $paydesign_request->paydesign_post_request( $order ,$connect_url , $setting_data);
			
			if( isset($response[0]) and substr($response[0],0,2) == 'OK' ){
				if(isset($response[1]))add_post_meta( $order_id, '_transaction_id', $response[1], true );

				// Update user id
				if($this->user_id_payment == 'yes')update_user_meta($user->ID, '_paydesign_user_id' , $customer_id);
				// Reduce stock levels
                wc_reduce_stock_levels( $order_id );

				// Remove cart
				WC()->cart->empty_cart();

				return array(
					'result'   => 'success',
					'redirect' => $this->get_return_url( $order ),
				);
			}else{
				$order->update_status( 'cancelled', __( 'This order is cancelled, because of Payment error.'.mb_convert_encoding($response[2], "UTF-8", "sjis"), 'woo-paydesign' ) );
				wc_add_notice( __('Payment error:', 'woo-paydesign') . mb_convert_encoding($response[2], "UTF-8", "sjis"), 'error' );
				return false;
			}
		}else{ // When use user id payment
			$connect_url = PAYDESIGN_CC_SALES_USER_URL;
			$response = $paydesign_request->paydesign_post_request( $order, $connect_url, $setting_data );
			if( isset($response[0]) and substr($response[0],0,2) == 'OK' ){
				update_user_meta($user->ID, '_paydesign_user_id' , $customer_id);
				$order->add_order_note( __('Finished to send payment data to metaps PAYMENT.', 'woo-paydesign') );
				// Reduce stock levels
                wc_reduce_stock_levels($order_id);
				return array(
					'result'   => 'success',
					'redirect' => $this->get_return_url( $order ),
				);
			}else{
				if(is_checkout())wc_add_notice( __('Payment error:', 'woo-paydesign') . mb_convert_encoding($response[2], "UTF-8", "sjis"), 'error' );
				$order->update_status( 'cancelled', __( 'This order is cancelled, because of Payment error.'.mb_convert_encoding($response[2], "UTF-8", "sjis"), 'woo-paydesign' ) );
				return false;
			}
		}

	}

    /**
     * Validate input fields
     * @return bool
     */
    function validate_fields() {
        $token = $this->get_post('paydesign_cc_token_id');
        if(is_null($this->get_post('select_card')) and empty($token)) {
            wc_add_notice( __('Payment error:', 'woo-paydesign') . "カード情報を入力してください", 'error' );
            return false;
        }
        return true;
    }

    /**
	 * Refund a charge
	 * @param  int $order_id
	 * @param  float $amount
     * @param  string $reason
	 * @return mixed bool and WP_Error
	 */
	public function process_refund( $order_id, $amount = null, $reason = '' ) {
		include_once( 'includes/class-wc-gateway-paydesign-request.php' );
		$order = wc_get_order( $order_id );

		if ( ! $order ) {
			return false;
		}elseif($amount != $order->order_total){
			$order->add_order_note( __( 'Auto refund must total only. ', 'woo-paydesign' ).__( 'If you need, please contact to metaps PAYMENT Support.', 'woo-paydesign' ) );
			return new WP_Error( 'paydesign_refund_error', __( 'Auto refund must total only. ', 'woo-paydesign' ).__( 'If you need, please contact to metaps PAYMENT Support.', 'woo-paydesign' ) );
		}
		$paydesign_request = new WC_Gateway_PAYDESIGN_Request();
		$prefix_order = get_option( 'wc_paydesign_prefix_order' );

		$refund_connect_url = PAYDESIGN_CC_SALES_CANCEL_URL;
		$cansel_connect_url = PAYDESIGN_CC_SALES_REFUND_URL;

		$status = $order->get_status();
		$data['IP'] = $this->ip_code;
		$data['PASS'] = $this->pass_code;
		$data['SID'] = $prefix_order.$order_id;
//		$order->add_order_note( 'test001' );
		if($status == 'completed'){
			$response = $paydesign_request->paydesign_request( $data ,$cansel_connect_url ,$order );
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
				$response = $paydesign_request->paydesign_request( $data ,$cansel_connect_url ,$order );				
			}else{
				$response = $paydesign_request->paydesign_request( $data ,$refund_connect_url ,$order );				
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
     *
     * @param  string $name
     * @return string
	 */
	private function get_post( $name ) {
		if ( isset( $_POST[ $name ] ) ) {
			return sanitize_text_field( $_POST[ $name ] );
		}
		return null;
	}
	/**
	 * Set token Metaps Token JavaScript.
	 */
	public function custom_wp_enqueue_script() {
		if ( is_checkout() ) {
			wp_enqueue_script( 'paydesign_token_script', '//www.paydesign.jp/settle/token/metapsToken-min.js' , array(), null, false);
		}
	}
}
/**
 * Add the gateway to woocommerce
 *
 * @param  array $methods
 * @return array $methods
 */
function add_wc_paydesign_cc_token_gateway( $methods ) {
	$subscription_support_enabled = false;
	if ( class_exists( 'WC_Subscriptions_Order' ) && function_exists( 'wcs_create_renewal_order' ) ) {
		$subscription_support_enabled = true;
	}
	if ( $subscription_support_enabled ) {
		$methods[] = 'WC_Addons_Gateway_PAYDESIGN_CC_TOKEN';
	} else {
		$methods[] = 'WC_Gateway_PAYDESIGN_CC_TOKEN';
	}
	return $methods;
}

add_filter( 'woocommerce_payment_gateways', 'add_wc_paydesign_cc_token_gateway' );

/**
 * Update Sale from Auth to Paydesign
 */

function order_status_completed_to_capture_token( $order_id ){
	global $woocommerce;
	$payment_setting = new WC_Gateway_PAYDESIGN_CC_TOKEN();

	if($payment_setting->paymentaction != 'sale'){
		$paydesign_setting = get_option('woocommerce_paydesign_cc_token_settings');
		$prefix_order = get_option( 'wc_paydesign_prefix_order' );

		$connect_url = PAYDESIGN_CC_SALES_COMP_URL;
		$order = wc_get_order( $order_id );
		if($order && $order->payment_method == 'paydesign_cc_token'){
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
                    }elseif($order->get_status() == 'completed' && $payment_setting->paymentaction == 'sale' ){
                        $order->add_order_note( __( 'This order has already completed.', 'woo-paydesign' ).__( 'If you need, please contact to metaps PAYMENT Support.', 'woo-paydesign' ) );
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
		}else{
            return true;
        }
	}else{
        return true;
    }

}
add_action( 'woocommerce_order_status_completed', 'order_status_completed_to_capture_token');


/**
 * Recieved Credit Payment complete from Paydesign
 */
function paydesign_cc_token_received(){
	global $woocommerce;
	global $wpdb;

	if(isset($_GET['SEQ']) and isset($_GET['DATE']) and isset($_GET['SID'])){
		$pd_order_id = $_GET['SID'];
		$prefix_order = get_option( 'wc_paydesign_prefix_order' );
		$order_id = str_replace($prefix_order, '', $pd_order_id);
		$order = new WC_Order( $order_id );
		$order->add_order_note( __( 'Received Payment complete signal from metaps PAYMENT.', 'woo-paydesign' ) );
		$order_payment_method  = get_post_meta( $order_id, '_payment_method', true );
		$payment_title = __( 'Credit Card (Paydesign)', 'woo-paydesign' );
		if($order_payment_method == 'paydesign_cc_token'){
			$order->update_status( 'processing', sprintf( __( 'Payment of %s was complete.', 'woo-paydesign' ) , $payment_title ) );
			header("Location: ".plugin_dir_url( __FILE__ )."empty.php");
		}
	}
}

add_action( 'woocommerce_cart_is_empty', 'paydesign_cc_token_received');
