<?php
// In order to prevent direct access to the plugin
 defined('ABSPATH') or die("No access please!");
 if(!isset($_SESSION)){session_start();}
// Plugin header- notifies wordpress of the existence of the plugin
/* Plugin Name: Payment Gateway for MTN MoMo on WooCommerce Free
* Plugin URI: https://woomtnmomo.demkitech.com/
* Description: Payment Gateway for MTN MoMo on WooCommerce Free
* Version: 1.0.0 
* Author: Demkitech Solutions
* Author URI: https://demkitech.com/
* Licence: GPL2 
* WC requires at least: 2.2
* WC tested up to: 5.0.3
*/
add_action('plugins_loaded', 'woomomo_payment_gateway_init');
//defining the classclass
/**
 * MoMo Payment Gateway
 *
 * @class          WC_Gateway_Momo
 * @extends        WC_Payment_Gateway
 * @version        1.0.0
 */
 
 function woomomo_adds_to_the_head() {
 
   wp_enqueue_script('Callbacks', plugin_dir_url(__FILE__) . 'trxcheck.js', array('jquery'));
   wp_enqueue_style( 'Responses', plugin_dir_url(__FILE__) . '/display.css',false,'1.1','all');
 
}
//Add the css and js files to the header.
add_action( 'wp_enqueue_scripts', 'woomomo_adds_to_the_head' );
//Calls the woomomo_trx_install function during plugin activation which creates table that records transactions.
register_activation_hook(__FILE__,'woomomo_trx_install');
//Request payment function start//
add_action( 'init', function() {
    /** Add a custom path and set a custom query argument. */
    add_rewrite_rule( '^/payment/?([^/]*)/?', 'index.php?payment_action=1', 'top' );
} );
add_filter( 'query_vars', function( $query_vars ) {
    /** Make sure WordPress knows about this custom action. */
    $query_vars []= 'payment_action';
    return $query_vars;
} );
add_action( 'wp', function() {
    /** This is an call for our custom action. */
    if ( get_query_var( 'payment_action' ) ) {
        // your code here
		woomomo_request_payment();
    }
} );
//Request payment function end
//Results scanner function start
add_action( 'init', function() {
    
    add_rewrite_rule( '^/scanner/?([^/]*)/?', 'index.php?scanner_action=1', 'top' );
} );
add_filter( 'query_vars', function( $query_vars ) {
    
    $query_vars []= 'scanner_action';
    return $query_vars;
} );
add_action( 'wp', function() {
  
    if ( get_query_var( 'scanner_action' ) ) {
        // invoke scanner function
		woomomo_scan_transactions();
    }
} );
//Results scanner function end
function woomomo_payment_gateway_init() {
    if( !class_exists( 'WC_Payment_Gateway' )) return;
class WC_Gateway_Momo extends WC_Payment_Gateway {
/**
*  Plugin constructor for the class
*/
public function __construct(){		
		
		if(!isset($_SESSION)){
			session_start(); 
			}
        // Basic settings
		$this->id                 = 'momo';
		$this->icon               = plugin_dir_url(__FILE__) . 'momologo.jpg';
        $this->has_fields         = false;
        $this->method_title       = __( 'MTN', 'woocommerce' );
        $this->method_description = __( 'Enable customers to make payments to your business easily' );
       
        // load the settings
        $this->init_form_fields();
        $this->init_settings();
        // Define variables set by the user in the admin section
        $this->title            = $this->get_option( 'title' );
        $this->description      = $this->get_option( 'description' );
        $this->instructions     = $this->get_option( 'instructions', $this->description );
        $this->mer              = $this->get_option( 'mer' );	
		
		$_SESSION['order_status'] = $this->get_option('order_status');
        $_SESSION['environment_type'] = $this->get_option('environment_type');
		$_SESSION['credentials_endpoint_momo']   = $this->get_option( 'credentials_endpoint' ); 
		$_SESSION['payments_endpoint_momo']   	= $this->get_option( 'payments_endpoint' ); 
		$_SESSION['passkey_momo']      			= $this->get_option( 'passkey_momo' ); 
		$_SESSION['ck_momo']      				= $this->get_option( 'api_user' ); 
		$_SESSION['cs_momo']   					= $this->get_option( 'api_key' );
		$_SESSION['currency']   					= $this->get_option( 'currency' );
				
        //Save the admin options
        if ( version_compare( WOOCOMMERCE_VERSION, '2.0.0', '>=' ) ) {
            add_action( 'woocommerce_update_options_payment_gateways_'.$this->id, array( $this, 'process_admin_options' ) );
        } else {
            add_action( 'woocommerce_update_options_payment_gateways', array( $this, 'process_admin_options' ) );
        }
        add_action( 'woocommerce_receipt_momo', array( $this, 'receipt_page' ));
		
    }
/**
*Initialize form fields that will be displayed in the admin section.
*/
public function init_form_fields() {
    $this->form_fields = array(
        'enabled' => array(
            'title'   => __( 'Enable/Disable', 'woocommerce' ),
            'type'    => 'checkbox',
            'label'   => __( 'Enable momo Payments Gateway', 'woocommerce' ),
            'default' => 'yes'
            ),
        'title' => array(
            'title'       => __( 'Title', 'woocommerce' ),
            'type'        => 'text',
            'description' => __( 'This controls the title which the user sees during checkout.', 'woocommerce' ),
            'default'     => __( 'MoMo', 'woocommerce' ),
            'desc_tip'    => true,
            ),
        'description' => array(
            'title'       => __( 'Description', 'woocommerce' ),
            'type'        => 'textarea',
            'description' => __( 'Payment method description that the customer will see on your checkout.', 'woocommerce' ),
            'default'     => __( 'Place order and pay using MTN MoMo.'),
            'desc_tip'    => true,
            ),
        'instructions' => array(
            'title'       => __( 'Instructions', 'woocommerce' ),
            'type'        => 'textarea',
            'description' => __( 'Instructions that will be added to the thank you page and emails.', 'woocommerce' ),
            'default'     => __( 'Place order and pay using MTN MoMo.', 'woocommerce' ),
                // 'css'         => 'textarea { read-only};',
            'desc_tip'    => true,
            ),
        'mer' => array(
            'title'       => __( 'Merchant Name', 'woocommerce' ),
            'description' => __( 'Company name', 'woocommerce' ),
            'type'        => 'text',
            'default'     => __( 'Company Name', 'woocommerce'),
            'desc_tip'    => false,
            ),
			
			///Give option to choose order status
			
			'order_status' => array( 
			'title'       => __( 'Successful Payment Status', 'woocommerce' ),
			'type'        => 'select',	
			'options' => array(		
			1 => __( 'On Hold', 'woocommerce' ),	
			2 => __( 'Processing', 'woocommerce' ),	
			3 => __( 'Completed', 'woocommerce' )	
			),					
			'description' => __( 'Payment status for the order after successful MTN MoMo payment.', 'woocommerce' ),	
			'desc_tip'    => false,	
			),
			
			'environment_type' => array( 
			'title'       => __( 'Environment', 'woocommerce' ),
			'type'        => 'text',
			'description' => __( 'The target environment as advised by the MTN MoMo team.', 'woocommerce' ),	
			'default'     => __( 'sandbox', 'woocommerce'),		
			'description' => __( 'The MoMo environment being used.', 'woocommerce' ),	
			'desc_tip'    => false,	
			),
			
	
            'currency' => array( 
			'title'       => __( 'Currency', 'woocommerce' ),
            'type'        => 'text',
			'description' => __( 'The currency in use in Sandbox or in Production.', 'woocommerce' ),	
			'default'     => __( 'EUR', 'woocommerce'),
			'desc_tip'    => false,	
			),
            
			///End in modification
			
		'credentials_endpoint' => array(
			'title'       =>  __( 'Credentials Endpoint(Sandbox/Production)', 'woocommerce' ),
			'default'     => __( 'https://sandbox.momodeveloper.mtn.com/collection/token/', 'woocommerce'),
			
			'description' => __( 'Sandbox is only for testing, production endpoint will be provided after a successful Go Live', 'woocommerce' ),
			'type'        => 'text',
			
		),				
		'payments_endpoint' => array(
			'title'       =>  __( 'Payments Endpoint(Sandbox/Production)', 'woocommerce' ),
			'default'     => __( 'https://sandbox.momodeveloper.mtn.com/collection/v1_0/requesttopay', 'woocommerce'),
			
			'description' => __( 'Sandbox is only for testing, production endpoint will be provided after a successful Go Live', 'woocommerce' ),
			'type'        => 'text',
		),
		'passkey_momo' => array(
			'title'       =>  __( 'Ocp Apim Subscription Key', 'woocommerce' ),
			 'default'     => __( '', 'woocommerce'),
			'type'        => 'password',
		),
		
		'api_user' => array(
			'title'       =>  __( 'API User', 'woocommerce' ),
			 'default'     => __( '', 'woocommerce'),
			'type'        => 'password',
		),
		'api_key' => array(
			'title'       =>  __( 'API Key', 'woocommerce' ),
			 'default'     => __( '', 'woocommerce'),
			'type'        => 'password',
		)
		);
}
/**
 * Generates the HTML for admin settings page
 */
public function admin_options(){
    /*
     *The heading and paragraph below are the ones that appear on the backend MoMo settings page
     */
    echo '<h3>' . 'MoMo Payments Gateway' . '</h3>';
    
    echo '<p>' . 'Payments Made Simple' . '</p>';
    
    echo '<table class="form-table">';
    
    $this->generate_settings_html( );
    
    echo '</table>';
}
/**
 * Receipt Page
 **/
public function receipt_page( $order_id ) {
    echo $this->woomomo_generate_iframe( $order_id );
}
/**
 * Function that posts the params to momo and generates the html for the page
 */
public function woomomo_generate_iframe( $order_id ) {
    global $woocommerce;
    $order = new WC_Order ( $order_id );
    $_SESSION['total'] = (int)$order->order_total;
    $tel = $order->billing_phone;
    //cleanup the phone number and remove unecessary symbols
    $tel = str_replace("-", "", $tel);
    $tel = str_replace( array(' ', '<', '>', '&', '{', '}', '*', "+", '!', '@', '#', "$", '%', '^', '&'), "", $tel );
	
	$_SESSION['tel'] = substr($tel, -11);
	
	    
/**
 * Make the payment here by clicking on pay button and confirm by clicking on complete order button
 */
if ($_GET['transactionType']=='checkout') {
	
	echo "<h4>Payment Instructions:</h4>";
    echo "
		  1. Click on the <b>Pay</b> button in order to initiate the MTN Mobile Money payment.<br/>
		  2. Check your mobile phone for a prompt requesting authorization of payment.<br/>
    	  3. Authorize the payment and it will be deducted from your MTN Mobile Money balance.<br/>  	
    	  4. After receiving the MTN Mobile Money payment confirmation message please click on the <b>Complete Order</b> button below to complete the order and confirm the payment made.<br/>";
    echo "<br/>";?>
	
	<input type="hidden" value="" id="txid"/>	
	<?php echo $_SESSION['response_status']; ?>
	<div id="commonname"></div>
	<button onClick="pay()" id="pay_btn">Pay</button>
	<button onClick="complete()" id="complete_btn">Complete Order</button>	
    <?php	
    echo "<br/>";
}
}
/**
* Process the payment field and redirect to checkout/pay page.
*
*
*
*/
public function process_payment( $order_id ) {
		$order = new WC_Order( $order_id );		
		$_SESSION['orderID'] = $order->id;      		
       // Redirect to checkout/pay page
        $checkout_url = $order->get_checkout_payment_url(true);
        $checkout_edited_url = $checkout_url."&transactionType=checkout";
        return array(
            'result' => 'success',
            'redirect' => add_query_arg('order', $order->id,
                add_query_arg('key', $order->order_key, $checkout_edited_url))
            ); 
}
}
}
/**
 * Telling woocommerce that momo payments gateway class exists
 * Filtering woocommerce_payment_gateways
 * Add the Gateway to WooCommerce
 **/
function woomomo_add_gateway_class( $methods ) {
    $methods[] = 'WC_Gateway_Momo';
    return $methods;
}
if(!add_filter( 'woocommerce_payment_gateways', 'woomomo_add_gateway_class' )){
    die;
}
//Create Table for MoMo Transactions
function woomomo_trx_install() {
      create_momo_trx_table();
}
//Create table for transactions
function create_momo_trx_table(){
	global $wpdb;
	global $trx_db_version;
	$trx_db_version = '1.0';
	$table_name = $wpdb->prefix .'momo_trx';
	
	$charset_collate = $wpdb->get_charset_collate();
	$sql = "CREATE TABLE IF NOT EXISTS $table_name (
		id mediumint(9) NOT NULL AUTO_INCREMENT,
		order_id varchar(150) DEFAULT '' NULL,
		amount varchar(150) DEFAULT '' NULL,
		phone_number varchar(150) DEFAULT '' NULL,
		trx_time datetime DEFAULT '0000-00-00 00:00:00' NOT NULL,
		merchant_request_id varchar(150) DEFAULT '' NULL,
		resultcode varchar(150) DEFAULT '' NULL,
		resultdesc varchar(150) DEFAULT '' NULL,
		processing_status varchar(20) DEFAULT '0' NULL,
		PRIMARY KEY  (id)
	) $charset_collate;";
		
	require_once( ABSPATH . 'wp-admin/includes/upgrade.php' );
	dbDelta( $sql );
	add_option( 'trx_db_version', $trx_db_version );
		
}
//Payments start
function woomomo_request_payment(){
		if(!isset($_SESSION)){
			session_start();
			}
		global $wpdb; 
		
	$total = $_SESSION['total'];
	$url = $_SESSION['credentials_endpoint_momo'];
    $YOUR_APP_CONSUMER_KEY =  $_SESSION['ck_momo'];
    $YOUR_APP_CONSUMER_SECRET = $_SESSION['cs_momo'];
    $credentials = base64_encode($YOUR_APP_CONSUMER_KEY . ':' . $YOUR_APP_CONSUMER_SECRET);
	//Request for access token
    
	$token_response = wp_remote_post( $url, array('headers' => 
	array('Content-Type' => 'application/json',
	'Authorization' => 'Basic ' . $credentials,
	'Ocp-Apim-Subscription-Key' => $_SESSION['passkey_momo'])));	
	
	$token_array = json_decode('{"token_results":[' . $token_response['body'] . ']}');
	
    if (isset($token_array->token_results[0]->access_token)) {
        $access_token = $token_array->token_results[0]->access_token;
		$_SESSION['access_token'] = $access_token;
    } 
	else {		
		echo json_encode(array("rescode" => "1", "resmsg" => "Error, unable to send payment request"));
		exit();
    }		
	
    ///If the access token is available, start lipa na momo process
	if (isset($token_array->token_results[0]->access_token)) {
        ////Starting momo payment process
     	
		///Generate UUID using WP function///
		$_SESSION["myUUID"] = wp_generate_uuid4();
		
		//Fill in the request parameters with valid values
        $curl_post_data = array(            
            'amount' => $total,
			'currency' => $_SESSION['currency'],
            'externalId' => $_SESSION["myUUID"],
            'payer' => array('partyIdType' => 'MSISDN','partyId' => $_SESSION['tel'] ), 
            'payerMessage' => 'Online Payment for order number '.$_SESSION['orderID'],
            'payeeNote' => 'Online Payment for order number '.$_SESSION['orderID']
        );
		
        $data_string = json_encode($curl_post_data);		
		$response = wp_remote_post($_SESSION['payments_endpoint_momo'],
		array('headers' => array(
		'Content-Type' => 'application/json',
		'Authorization' => 'Bearer ' . $access_token,
		'X-Reference-Id' => $_SESSION["myUUID"],
		'X-Target-Environment' => $_SESSION['environment_type'],
		'Ocp-Apim-Subscription-Key' => $_SESSION['passkey_momo'] 
		),
		'body'    => $data_string));		
		
		$response_array = $response['response'];
	 
		if($response_array['code'] == 202){
			
			woomomo_insert_transaction($_SESSION["myUUID"]);			
				
			echo json_encode(array("rescode" => "0", "resmsg" => "Request accepted for processing, please authorize the transaction"));	
			
		}
		else{
			echo json_encode(array("rescode" => $response_array['code'], "resmsg" => "Payment request failed, please try again"));	
			
		}
        exit();
		
    }
	
}
//Payments end
//Scanner start
function woomomo_scan_transactions(){

echo json_encode(array("rescode" => "9999", "resmsg" => "Payment status confirmation has been disabled, please request for the Pro Version"));

exit();
}
////Scanner end
function woomomo_insert_transaction( $merchant_id ) {
  if(!isset($_SESSION)){ 
  session_start();
  }
  global $wpdb; 
  $table_name = $wpdb->prefix . 'momo_trx';
  $wpdb->insert( $table_name, array(
    'order_id' => $_SESSION['orderID'],
	'amount' => $_SESSION['total'],
    'phone_number' => $_SESSION['tel'],
    'merchant_request_id' => $merchant_id,
	'trx_time' => date("Y-m-d H:i:s")
  ) );
}

	
function woomomo_update_transaction( $merchant_id,$rescode,$resdesc ) {
		
	  global $wpdb;
	 
	  $table_name = $wpdb->prefix . 'momo_trx';
	  $wpdb->update($table_name, array('resultcode' => $rescode,
			'resultdesc' => $resdesc, 'processing_status' => '1'),
			array('merchant_request_id' => $merchant_id), array('%s','%s', '%s'),
			 array('%s'));
	}
 
?>