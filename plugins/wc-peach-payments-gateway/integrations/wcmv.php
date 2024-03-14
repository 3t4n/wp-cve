<?php
if (!defined('ABSPATH')) {
    exit;
}

//add_action('init', array('class WCFMmp_Gateway_PeachPayments', 'init'));

//New payment gateway option under WCFM Marketplace withdrawal
add_filter( 'wcfm_marketplace_withdrwal_payment_methods', function( $payment_methods ) {
	$payment_methods['peach_payment'] = 'Peach Payments';
	return $payment_methods;
});


//Payment gateway API keys fields
add_filter( 'wcfm_marketplace_settings_fields_withdrawal_payment_keys', function( $payment_keys, $wcfm_withdrawal_options ) {
	$gateway_slug = 'peach_payment';
	
	$withdrawal_peach_request_status_url = isset( $wcfm_withdrawal_options[$gateway_slug.'_request_status_url'] ) ? $wcfm_withdrawal_options[$gateway_slug.'_request_status_url'] : '';
	
	$withdrawal_peach_secret_key = isset( $wcfm_withdrawal_options[$gateway_slug.'_secret_key'] ) ? $wcfm_withdrawal_options[$gateway_slug.'_secret_key'] : '';
	
	$withdrawal_peach_secure_key = isset( $wcfm_withdrawal_options[$gateway_slug.'_secure_key'] ) ? $wcfm_withdrawal_options[$gateway_slug.'_secure_key'] : '';
	
	$withdrawal_peach_recurring_key = isset( $wcfm_withdrawal_options[$gateway_slug.'_recurring_key'] ) ? $wcfm_withdrawal_options[$gateway_slug.'_recurring_key'] : '';
	
	$withdrawal_peach_webhook_key = isset( $wcfm_withdrawal_options[$gateway_slug.'_webhook_key'] ) ? $wcfm_withdrawal_options[$gateway_slug.'_webhook_key'] : '';
	
	$payment_peach_keys = array(
		"withdrawal_".$gateway_slug."_request_status_url" => array(
			'label' => __('Peach Payments Access Token', 'woocommerce-gateway-peach-payments'), 
			'name' => 'wcfm_withdrawal_options['.$gateway_slug.'_request_status_url]', 
			'type' => 'text', 
			'class' => 'wcfm-text wcfm_ele withdrawal_mode withdrawal_mode_live withdrawal_mode_'.$gateway_slug,
			'label_class' => 'wcfm_title withdrawal_mode withdrawal_mode_live withdrawal_mode_'.$gateway_slug,
			'value' => $withdrawal_peach_request_status_url
		),
		"withdrawal_".$gateway_slug."_secret_key" => array(
			'label' => __('Peach Payments Secret Token', 'woocommerce-gateway-peach-payments'),
			'name' => 'wcfm_withdrawal_options['.$gateway_slug.'_secret_key]',
			'type' => 'text', 
			'class' => 'wcfm-text wcfm_ele withdrawal_mode withdrawal_mode_live withdrawal_mode_'.$gateway_slug,
			'label_class' => 'wcfm_title withdrawal_mode withdrawal_mode_live withdrawal_mode_'.$gateway_slug, 
			'value' => $withdrawal_peach_secret_key
		),
		"withdrawal_".$gateway_slug."_secure_key" => array(
			'label' => __('Peach Payments 3DSecure Channel ID', 'woocommerce-gateway-peach-payments'),
			'name' => 'wcfm_withdrawal_options['.$gateway_slug.'_secure_key]',
			'type' => 'text', 
			'class' => 'wcfm-text wcfm_ele withdrawal_mode withdrawal_mode_live withdrawal_mode_'.$gateway_slug,
			'label_class' => 'wcfm_title withdrawal_mode withdrawal_mode_live withdrawal_mode_'.$gateway_slug, 
			'value' => $withdrawal_peach_secure_key
		),
		"withdrawal_".$gateway_slug."_recurring_key" => array(
			'label' => __('Peach Payments Recurring Channel ID', 'woocommerce-gateway-peach-payments'),
			'name' => 'wcfm_withdrawal_options['.$gateway_slug.'_recurring_key]',
			'type' => 'text', 
			'class' => 'wcfm-text wcfm_ele withdrawal_mode withdrawal_mode_live withdrawal_mode_'.$gateway_slug,
			'label_class' => 'wcfm_title withdrawal_mode withdrawal_mode_live withdrawal_mode_'.$gateway_slug, 
			'value' => $withdrawal_peach_recurring_key
		),
		"withdrawal_".$gateway_slug."_webhook_key" => array(
			'label' => __('Peach Payments Card Webhook Decryption Key', 'woocommerce-gateway-peach-payments'),
			'name' => 'wcfm_withdrawal_options['.$gateway_slug.'_webhook_key]',
			'type' => 'text', 
			'class' => 'wcfm-text wcfm_ele withdrawal_mode withdrawal_mode_live withdrawal_mode_'.$gateway_slug,
			'label_class' => 'wcfm_title withdrawal_mode withdrawal_mode_live withdrawal_mode_'.$gateway_slug, 
			'value' => $withdrawal_peach_webhook_key
		)
	);
	
	$payment_keys = array_merge( $payment_keys, $payment_peach_keys );
	return $payment_keys;
}, 50, 2);


//Payment gateway Test API keys fields
add_filter( 'wcfm_marketplace_settings_fields_withdrawal_payment_test_keys', function( $payment_test_keys, $wcfm_withdrawal_options ) {
	$gateway_slug = 'peach_payment';
	
	$withdrawal_peach_test_request_status_url = isset( $wcfm_withdrawal_options[$gateway_slug.'_test_request_status_url'] ) ? $wcfm_withdrawal_options[$gateway_slug.'_test_request_status_url'] : '';
	
	$withdrawal_peach_test_secret_key = isset( $wcfm_withdrawal_options[$gateway_slug.'_test_secret_key'] ) ? $wcfm_withdrawal_options[$gateway_slug.'_test_secret_key'] : '';
	
	$withdrawal_peach_test_secure_key = isset( $wcfm_withdrawal_options[$gateway_slug.'_test_secure_key'] ) ? $wcfm_withdrawal_options[$gateway_slug.'_test_secure_key'] : '';
	
	$withdrawal_peach_test_recurring_key = isset( $wcfm_withdrawal_options[$gateway_slug.'_test_recurring_key'] ) ? $wcfm_withdrawal_options[$gateway_slug.'_test_recurring_key'] : '';
	
	$withdrawal_peach_test_webhook_key = isset( $wcfm_withdrawal_options[$gateway_slug.'_test_webhook_key'] ) ? $wcfm_withdrawal_options[$gateway_slug.'_test_webhook_key'] : '';
	
	$payment_peach_test_keys = array(
		"withdrawal_".$gateway_slug."_test_request_status_url" => array(
			'label' => __('Peach Payments Test Access Token', 'woocommerce-gateway-peach-payments'),
			'name' => 'wcfm_withdrawal_options['.$gateway_slug.'_test_request_status_url]',
			'type' => 'text',
			'class' => 'wcfm-text wcfm_ele withdrawal_mode withdrawal_mode_test withdrawal_mode_'.$gateway_slug,
			'label_class' => 'wcfm_title withdrawal_mode withdrawal_mode_test withdrawal_mode_'.$gateway_slug,
			'value' => $withdrawal_peach_test_request_status_url
		),
		"withdrawal_".$gateway_slug."_test_secret_key" => array(
			'label' => __('Peach Payments Test Secret Token', 'woocommerce-gateway-peach-payments'),
			'name' => 'wcfm_withdrawal_options['.$gateway_slug.'_test_secret_key]',
			'type' => 'text',
			'class' => 'wcfm-text wcfm_ele withdrawal_mode withdrawal_mode_test withdrawal_mode_'.$gateway_slug,
			'label_class' => 'wcfm_title withdrawal_mode withdrawal_mode_test withdrawal_mode_'.$gateway_slug,
			'value' => $withdrawal_peach_test_secret_key
		),
		"withdrawal_".$gateway_slug."_test_secure_key" => array(
			'label' => __('Peach Payments Test 3DSecure Channel ID', 'woocommerce-gateway-peach-payments'),
			'name' => 'wcfm_withdrawal_options['.$gateway_slug.'_test_secure_key]',
			'type' => 'text', 
			'class' => 'wcfm-text wcfm_ele withdrawal_mode withdrawal_mode_test withdrawal_mode_'.$gateway_slug,
			'label_class' => 'wcfm_title withdrawal_mode withdrawal_mode_test withdrawal_mode_'.$gateway_slug, 
			'value' => $withdrawal_peach_test_secure_key
		),
		"withdrawal_".$gateway_slug."_test_recurring_key" => array(
			'label' => __('Peach Payments Test Recurring Channel ID', 'woocommerce-gateway-peach-payments'),
			'name' => 'wcfm_withdrawal_options['.$gateway_slug.'_test_recurring_key]',
			'type' => 'text', 
			'class' => 'wcfm-text wcfm_ele withdrawal_mode withdrawal_mode_test withdrawal_mode_'.$gateway_slug,
			'label_class' => 'wcfm_title withdrawal_mode withdrawal_mode_test withdrawal_mode_'.$gateway_slug, 
			'value' => $withdrawal_peach_test_recurring_key
		),
		"withdrawal_".$gateway_slug."_test_webhook_key" => array(
			'label' => __('Peach Payments Test Card Webhook Decryption Key', 'woocommerce-gateway-peach-payments'),
			'name' => 'wcfm_withdrawal_options['.$gateway_slug.'_test_webhook_key]',
			'type' => 'text', 
			'class' => 'wcfm-text wcfm_ele withdrawal_mode withdrawal_mode_test withdrawal_mode_'.$gateway_slug,
			'label_class' => 'wcfm_title withdrawal_mode withdrawal_mode_test withdrawal_mode_'.$gateway_slug, 
			'value' => $withdrawal_peach_test_webhook_key
		)
	);
	
	$payment_test_keys = array_merge( $payment_test_keys, $payment_peach_test_keys );
	return $payment_test_keys;
}, 50, 2);

//add withdrawal charge option
add_filter( 'wcfm_marketplace_settings_fields_withdrawal_charges', function( $withdrawal_charges, $wcfm_withdrawal_options, $withdrawal_charge ){
	$gateway_slug = 'peach_payment';
	$withdrawal_charge_peach = isset( $withdrawal_charge[$gateway_slug] ) ? $withdrawal_charge[$gateway_slug] : array();
	
	$payment_withdrawal_charges = array(
		"withdrawal_charge_".$gateway_slug => array(
			'label' => __('Peach Payments Charge', 'woocommerce-gateway-peach-payments'),
			'type' => 'multiinput',
			'name' => 'wcfm_withdrawal_options[withdrawal_charge]['.$gateway_slug.']',
			'class' => 'withdraw_charge_block withdraw_charge_'.$gateway_slug,
			'label_class' => 'wcfm_title wcfm_ele wcfm_fill_ele withdraw_charge_block withdraw_charge_'.$gateway_slug,
			'value' => $withdrawal_charge_peach,
			'custom_attributes' => array('limit' => 1 ),
			'options' => array(
				"percent" => array(
					'label' => __('Percent Charge(%)', 'woocommerce-gateway-peach-payments'),
					'type' => 'number',
					'class' => 'wcfm-text wcfm_ele withdraw_charge_field withdraw_charge_percent withdraw_charge_percent_fixed',
					'label_class' => 'wcfm_title wcfm_ele withdraw_charge_field withdraw_charge_percent withdraw_charge_percent_fixed', 
					'attributes' => array( 'min' => '0.1', 'step' => '0.1')
				),
				"fixed" => array(
					'label' => __('Fixed Charge', 'woocommerce-gateway-peach-payments'),
					'type' => 'number',
					'class' => 'wcfm-text wcfm_ele withdraw_charge_field withdraw_charge_fixed withdraw_charge_percent_fixed',
					'label_class' => 'wcfm_title wcfm_ele withdraw_charge_field withdraw_charge_fixed withdraw_charge_percent_fixed',
					'attributes' => array( 'min' => '0.1', 'step' => '0.1')
				),
				"tax" => array(
					'label' => __('Charge Tax', 'woocommerce-gateway-peach-payments'),
					'type' => 'number',
					'class' => 'wcfm-text wcfm_ele',
					'label_class' => 'wcfm_title wcfm_ele',
					'attributes' => array( 'min' => '0.1', 'step' => '0.1'),
					'hints' => __( 'Tax for withdrawal charge, calculate in percent.', 'woocommerce-gateway-peach-payments' )
				),
			)
		)
	);
	
	$withdrawal_charges = array_merge( $withdrawal_charges, $payment_withdrawal_charges );
	return $withdrawal_charges;
}, 50, 3);


//Add new payment gateway setting field under vendor’s setting
add_filter( 'wcfm_marketplace_settings_fields_billing', function( $vendor_billing_fileds, $vendor_id ) {
	$gateway_slug = 'peach_payment';
	$vendor_data = get_user_meta( $vendor_id, 'wcfmmp_profile_settings', true );
	
	if( !$vendor_data ) $vendor_data = array();
	
	$peach_email = isset( $vendor_data['payment'][$gateway_slug]['email'] ) ? esc_attr( $vendor_data['payment'][$gateway_slug]['email'] ) : '' ;
	$peach_token = isset( $vendor_data['payment'][$gateway_slug]['token'] ) ? esc_attr( $vendor_data['payment'][$gateway_slug]['token'] ) : '' ;
	$peach_secret = isset( $vendor_data['payment'][$gateway_slug]['secret'] ) ? esc_attr( $vendor_data['payment'][$gateway_slug]['secret'] ) : '' ;
	$peach_secure = isset( $vendor_data['payment'][$gateway_slug]['secure'] ) ? esc_attr( $vendor_data['payment'][$gateway_slug]['secure'] ) : '' ;
	$peach_recurring = isset( $vendor_data['payment'][$gateway_slug]['recurring'] ) ? esc_attr( $vendor_data['payment'][$gateway_slug]['recurring'] ) : '' ;
	$peach_webhook = isset( $vendor_data['payment'][$gateway_slug]['webhook'] ) ? esc_attr( $vendor_data['payment'][$gateway_slug]['webhook'] ) : '' ;
	
	$vendor_peach_billing_fileds = array(
		"vendor_".$gateway_slug."_email" => array(
			'label' => __('Email', 'woocommerce-gateway-peach-payments'),
			'name' => 'payment['.$gateway_slug.'][email]',
			'type' => 'text',
			'class' => 'wcfm-text wcfm_ele paymode_field paymode_'.$gateway_slug,
			'label_class' => 'wcfm_title wcfm_ele paymode_field paymode_'.$gateway_slug,
			'value' => $peach_email
		),
		"vendor_".$gateway_slug."_token" => array(
			'label' => __('Peach Payments Access Token', 'woocommerce-gateway-peach-payments'),
			'name' => 'payment['.$gateway_slug.'][token]',
			'type' => 'text',
			'class' => 'wcfm-text wcfm_ele paymode_field paymode_'.$gateway_slug,
			'label_class' => 'wcfm_title wcfm_ele paymode_field paymode_'.$gateway_slug,
			'value' => $peach_token
		),
		"vendor_".$gateway_slug."_secret" => array(
			'label' => __('Peach Payments Secret Token', 'woocommerce-gateway-peach-payments'),
			'name' => 'payment['.$gateway_slug.'][secret]',
			'type' => 'text',
			'class' => 'wcfm-text wcfm_ele paymode_field paymode_'.$gateway_slug,
			'label_class' => 'wcfm_title wcfm_ele paymode_field paymode_'.$gateway_slug,
			'value' => $peach_secret
		),
		"vendor_".$gateway_slug."_secure"  => array(
			'label' => __('Peach Payments 3DSecure Channel ID', 'woocommerce-gateway-peach-payments'),
			'name' => 'payment['.$gateway_slug.'][secure]',
			'type' => 'text',
			'class' => 'wcfm-text wcfm_ele paymode_field paymode_'.$gateway_slug,
			'label_class' => 'wcfm_title wcfm_ele paymode_field paymode_'.$gateway_slug,
			'value' => $peach_secure
		),
		"vendor_".$gateway_slug."_recurring" => array(
			'label' => __('Peach Payments Recurring Channel ID', 'woocommerce-gateway-peach-payments'),
			'name' => 'payment['.$gateway_slug.'][recurring]',
			'type' => 'text',
			'class' => 'wcfm-text wcfm_ele paymode_field paymode_'.$gateway_slug,
			'label_class' => 'wcfm_title wcfm_ele paymode_field paymode_'.$gateway_slug,
			'value' => $peach_recurring
		),
		"vendor_".$gateway_slug."_webhook" => array(
			'label' => __('Peach Payments Card Webhook Decryption Key', 'woocommerce-gateway-peach-payments'),
			'name' => 'payment['.$gateway_slug.'][webhook]',
			'type' => 'text',
			'class' => 'wcfm-text wcfm_ele paymode_field paymode_'.$gateway_slug,
			'label_class' => 'wcfm_title wcfm_ele paymode_field paymode_'.$gateway_slug,
			'value' => $peach_webhook
		)
	);
	
	$vendor_billing_fileds = array_merge( $vendor_billing_fileds, $vendor_peach_billing_fileds );
	return $vendor_billing_fileds;
}, 50, 2);

class WCFMmp_Gateway_Peach_payment{

	public $id;
	public $gateway_title;
	public $payment_gateway;
	public $message = array();
	
	public $withdrawal_id;
	public $vendor_id;
	
	public $withdraw_amount = 0;
	
	public $currency;
	public $transaction_mode;
	
	private $client_id;
	private $client_secret;
	private $test_mode = false;
	private $reciver_email;
	private $process_checkout_url;
	private $request_checkout_url;
	private $request_status_url;
	private $request_pre_status_url;
	private $request_refund_url;
	private $ssl_verifypeer;
	private $success_code;
	
	private $access_token;
	private $secret_key;
	private $secure_key;
	private $recurring_key;
	private $webhook_key;

	public function __construct() {
		global $WCFM, $WCFMmp;
		
		$this->id              = 'peach_payment';
		$this->gateway_title   = __('Peach Payments', 'woocommerce-gateway-peach-payments');
		$this->payment_gateway = $this->id;
		$withdrawal_test_mode = isset( $WCFMmp->wcfmmp_withdrawal_options['test_mode'] ) ? 'yes' : 'no';
		
		$this->process_checkout_url = 'https://eu-test.oppwa.com';
		$this->request_checkout_url = 'https://testsecure.peachpayments.com/checkout';
		$this->request_status_url = 'https://testapi.peachpayments.com/v1/checkout/status';
		$this->request_pre_status_url = 'https://eu-test.oppwa.com/v1/payments';
		$this->request_refund_url = 'https://testapi.peachpayments.com/v1/checkout/refund';
		$this->ssl_verifypeer = false;
		$this->success_code = '000.100.110';

		$this->access_token = isset( $WCFMmp->wcfmmp_withdrawal_options[$this->payment_gateway.'_test_request_status_url'] ) ? $WCFMmp->wcfmmp_withdrawal_options[$this->payment_gateway.'_test_request_status_url'] : '';
		$this->secret_key = isset( $WCFMmp->wcfmmp_withdrawal_options[$this->payment_gateway.'_test_secret_key'] ) ? $WCFMmp->wcfmmp_withdrawal_options[$this->payment_gateway.'_test_secret_key'] : '';
		$this->secure_key = isset( $WCFMmp->wcfmmp_withdrawal_options[$this->payment_gateway.'_test_secure_key'] ) ? $WCFMmp->wcfmmp_withdrawal_options[$this->payment_gateway.'_test_secure_key'] : '';
		$this->recurring_key = isset( $WCFMmp->wcfmmp_withdrawal_options[$this->payment_gateway.'_test_recurring_key'] ) ? $WCFMmp->wcfmmp_withdrawal_options[$this->payment_gateway.'_test_recurring_key'] : '';
		$this->webhook_key = isset( $WCFMmp->wcfmmp_withdrawal_options[$this->payment_gateway.'_test_webhook_key'] ) ? $WCFMmp->wcfmmp_withdrawal_options[$this->payment_gateway.'_test_webhook_key'] : '';
		
		if ( $withdrawal_test_mode == 'no') {
			$this->process_checkout_url = 'https://eu-prod.oppwa.com';
			$this->request_checkout_url = 'https://secure.peachpayments.com/checkout';
			$this->request_status_url = 'https://api.peachpayments.com/v1/checkout/status';
			$this->request_pre_status_url = 'https://eu-prod.oppwa.com/v1/payments';
			$this->request_refund_url = 'https://api.peachpayments.com/v1/checkout/refund';
			$this->ssl_verifypeer = true;
			$this->success_code = '000.000.000';
			
			$this->access_token = isset( $WCFMmp->wcfmmp_withdrawal_options[$this->payment_gateway.'_request_status_url'] ) ? $WCFMmp->wcfmmp_withdrawal_options[$this->payment_gateway.'_request_status_url'] : '';
			$this->secret_key = isset( $WCFMmp->wcfmmp_withdrawal_options[$this->payment_gateway.'_secret_key'] ) ? $WCFMmp->wcfmmp_withdrawal_options[$this->payment_gateway.'_secret_key'] : '';
			$this->secure_key = isset( $WCFMmp->wcfmmp_withdrawal_options[$this->payment_gateway.'_secure_key'] ) ? $WCFMmp->wcfmmp_withdrawal_options[$this->payment_gateway.'_secure_key'] : '';
			$this->recurring_key = isset( $WCFMmp->wcfmmp_withdrawal_options[$this->payment_gateway.'_recurring_key'] ) ? $WCFMmp->wcfmmp_withdrawal_options[$this->payment_gateway.'_recurring_key'] : '';
			$this->webhook_key = isset( $WCFMmp->wcfmmp_withdrawal_options[$this->payment_gateway.'_webhook_key'] ) ? $WCFMmp->wcfmmp_withdrawal_options[$this->payment_gateway.'_webhook_key'] : '';
		
		}
	}
	
	//Need revision
	public function gateway_logo() { global $WCFMmp; return $WCFMmp->plugin_url . 'assets/images/'.$this->id.'.png'; }
	
	
	public function process_payment( $withdrawal_id, $vendor_id, $withdraw_amount, $withdraw_charges, $transaction_mode = 'auto' ) {
		global $WCFM, $WCFMmp;
		
		$this->withdrawal_id = $withdrawal_id;
		$this->vendor_id = $vendor_id;
		$this->withdraw_amount = $withdraw_amount;
		$this->currency = get_woocommerce_currency();
		$this->transaction_mode = $transaction_mode;
		$this->reciver_email = $WCFMmp->wcfmmp_vendor->get_vendor_payment_account( $this->vendor_id, 'email' );
		if ($this->validate_request()) {
			$this->generate_request_status_url();
			$paypal_response = $this->process_paypal_payout();
			if ($paypal_response) {
				return array( 'status' => true, 'message' => __('New transaction has been initiated', 'woocommerce-gateway-peach-payments') );
			} else {
				return false;
			}
		} else {
			return $this->message;
		}
	}

	public function validate_request() {
		global $WCFMmp;
		if (!$this->access_token && !$this->secret_key) {
			$this->message[] = array( 'status' => false, 'message' => __('Peach Payments setting is not configured properly, please contact site administrator.', 'woocommerce-gateway-peach-payments') );
			return false;
		} else if (!$this->reciver_email) {
			$this->message[] = array( 'status' => false, 'message' => __('Please update your Peach Payments email to receive commission', 'woocommerce-gateway-peach-payments') );
			return false;
		}
		return true;
	}

	private function generate_request_status_url() {
		if ( ! rgempty('gf_peach_return', $_GET)) {
            return false;
        }
		
		GFAPI::update_entry_property($entry['id'], 'payment_status', 'Pending');
		
		$meta = rgar( $feed, 'meta' );
		$mode = $this->get_plugin_setting( 'transaction_mode_gf_peach' );
		if($mode == 'LIVE'){
			$url = 'https://secure.peachpayments.com/checkout/initiate';
			$ssl_verifypeer = true;
		}else{
			$url = 'https://testsecure.peachpayments.com/checkout/initiate';
			$ssl_verifypeer = false;
		}
		
		$currency = 'ZAR';
		if($entry['currency']){
			$currency = $entry['currency'];
		}
		$merchantTransactionId = 'PeachGFOrderNo-'.$form['id'].'-'.$entry['id'];
		$nonce = wp_create_nonce( $entry['id'].'_'.time() );
		$returnURL = $entry['source_url'];

		$resultURL = $entry['source_url'];
		$siteURL = get_site_url().'/';
		
		$channel_3ds = $this->get_plugin_setting( 'channel_3ds_gf_peach' );
		$data = array(
			"authentication.entityId" => $channel_3ds,
			"merchantTransactionId" => $merchantTransactionId,
			"amount" => $submission_data['payment_amount'], 
			"paymentType" => 'DB',
			"currency" => $currency, 
			"nonce" => $nonce,
			"shopperResultUrl" => "".$resultURL."",
			"cancelUrl" => $entry['source_url'] 	
		);
		
		if($meta['transactionType'] == 'subscription'){
			$data['defaultPaymentMethod'] = 'CARD';
			$data['forceDefaultMethod'] = 'true';
			$data['createRegistration'] = 'true';
		}
				
		$data['customParameters[Gravity Forms]'] = $this->_version;
		
		if($meta['billingInformation_firstName'] != ''){
			$data['customer.givenName'] = $entry[$meta['billingInformation_firstName']];
		}
		if($meta['billingInformation_lastName'] != ''){
			$data ['customer.surname'] = $entry[$meta['billingInformation_lastName']];
		}
		if($submission_data['email'] != ''){
			$data['customer.email'] = $submission_data['email'];
		}
		
		//Check for valid address entry
		if($submission_data['address'] != '' && $submission_data['city'] != '' && $submission_data['state'] != '' && $submission_data['zip'] != '' && $submission_data['country'] != ''){
			$data['billing.street1'] = $submission_data['address'];
			if($submission_data['address2'] != ''){
				$data['billing.street2'] = $submission_data['address2'];
			}
			$data['billing.city'] = $submission_data['city'];
			$data['billing.state'] = $submission_data['state'];
			$data['billing.postcode'] = $submission_data['zip'];
			$data['billing.country'] = $submission_data['country'];
		}
		
		ksort($data);
		
		$sig_string = '';
		$dataFields = '';
		foreach($data as $key => $value){
			if(isset($key) && $value != ''){
				$sig_string .= $key.$value;
				$dataFields .= '&'.$key.'='.$value;
			}
		}
		
		$dataFields = ltrim($dataFields, '&');
		
		$secret = $this->get_plugin_setting( 'secret_gf_peach' );
		$signature = hash_hmac('sha256', $sig_string, $secret);
		
		$dataFields .= "&signature=" .$signature;
		
		$curl = curl_init();
		
		curl_setopt_array($curl, array(
		  CURLOPT_URL => $url,
		  CURLOPT_RETURNTRANSFER => true,
		  CURLOPT_ENCODING => '',
		  CURLOPT_MAXREDIRS => 10,
		  CURLOPT_TIMEOUT => 0,
		  CURLOPT_FOLLOWLOCATION => true,
		  CURLOPT_HTTP_VERSION => CURL_HTTP_VERSION_1_1,
		  CURLOPT_CUSTOMREQUEST => 'POST',
		  CURLOPT_POSTFIELDS => $dataFields,
		  CURLOPT_HTTPHEADER => array(
			'Content-Type: application/x-www-form-urlencoded',
			'Accept: application/json',
			$siteURL
		  ),
		  ));
		  
		$responseData = curl_exec($curl);
		
		if(curl_errno($curl)) {
			$curlError = curl_error($curl);
			curl_close($curl);
			return false;
		}else{
			$response = json_decode($responseData);
			if($response->redirectUrl){
				$returnURL = $response->redirectUrl;
			}else{
				curl_close($curl);
				return false;
			}
		}
		
		curl_close($curl);
		
		return $returnURL;
		
		$this->request_status_url = isset($response_array['request_status_url']) ? $response_array['request_status_url'] : '';
		$this->request_pre_status_url = isset($response_array['request_pre_status_url']) ? $response_array['request_pre_status_url'] : '';
	}

	private function process_paypal_payout() {
		global $WCFM, $WCFMmp;
		$api_authorization = "Authorization: {$this->request_pre_status_url} {$this->request_status_url}";
		$note = sprintf( __('Payment recieved from %1$s as commission at %2$s on %3$s', 'woocommerce-gateway-peach-payments'), get_bloginfo('name'), date('H:i:s'), date('d-m-Y'));
		$request_params = '{
												"sender_batch_header": {
														"sender_batch_id":"' . uniqid() . '",
														"email_subject": "You have a payment",
														"recipient_type": "EMAIL"
												},
												"items": [
													{
														"recipient_type": "EMAIL",
														"amount": {
															"value": ' . $this->withdraw_amount . ',
															"currency": "' . $this->currency . '"
														},
														"receiver": "' . $this->reciver_email . '",
														"note": "' . $note . '",
														"sender_item_id": "' . $this->vendor_id . '"
													}
												]
											}';
		$curl = curl_init();
		curl_setopt($curl, CURLOPT_HEADER, false);
		curl_setopt($curl, CURLOPT_HTTPHEADER, array('Content-type:application/json', $api_authorization));
		curl_setopt($curl, CURLOPT_VERBOSE, 1);
		curl_setopt($curl, CURLOPT_TIMEOUT, 30);
		curl_setopt($curl, CURLOPT_URL, $this->process_checkout_url);
		curl_setopt($curl, CURLOPT_RETURNTRANSFER, true);
		curl_setopt($curl, CURLOPT_POSTFIELDS, $request_params);
		curl_setopt($curl, CURLOPT_SSLVERSION, 6);
		$result = curl_exec($curl);
		curl_close($curl);
		$result_array = json_decode($result, true);
		$batch_status = $result_array['batch_header']['batch_status'];
		
		$batch_payout_status = apply_filters('wcfmmp_paypal_payout_batch_status', array('PENDING', 'PROCESSING', 'SUCCESS', 'NEW'));
		if (in_array($batch_status, $batch_payout_status) ) {
			// Updating withdrawal meta
			$WCFMmp->wcfmmp_withdraw->wcfmmp_update_withdrawal_meta( $this->withdrawal_id, 'withdraw_amount', $this->withdraw_amount );
			$WCFMmp->wcfmmp_withdraw->wcfmmp_update_withdrawal_meta( $this->withdrawal_id, 'currency', $this->currency );
			$WCFMmp->wcfmmp_withdraw->wcfmmp_update_withdrawal_meta( $this->withdrawal_id, 'reciver_email', $this->reciver_email );
			$WCFMmp->wcfmmp_withdraw->wcfmmp_update_withdrawal_meta( $this->withdrawal_id, 'payout_batch_id', $result_array['batch_header']['payout_batch_id'] );
			$WCFMmp->wcfmmp_withdraw->wcfmmp_update_withdrawal_meta( $this->withdrawal_id, 'batch_status', $batch_status );
			$WCFMmp->wcfmmp_withdraw->wcfmmp_update_withdrawal_meta( $this->withdrawal_id, 'sender_batch_id', $result_array['batch_header']['sender_batch_header']['sender_batch_id'] );
			
			return $result_array;
		} else {
			wcfmmp_log( sprintf( '#%s - PayPal payment processing failed: %s', sprintf( '%06u', $this->withdrawal_id ), json_encode($result_array) ), 'error' );
			return false;
		}
  }
}
?>