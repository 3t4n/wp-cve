<?php
/**
 * Plugin Name: Stripe WooCommerce Addon
 * Plugin URI: https://wordpress.org/plugins/stripe-woocommerce-addon/
 * Description: This plugin adds a payment option in WooCommerce for customers to pay with their Credit Cards Via Stripe.
 * Version: 1.0.8
 * Author: Syed Nazrul Hassan
 * Author URI: https://nazrulhassan.wordpress.com/
 * Author Email: nazrulhassanmca@gmail.com
 * License: GPL3
 * PCI Compliance by: Conner Imrie (https://github.com/cimrie) and Stephen Zuniga (https://github.com/stezu/)
 */

if ( ! defined( 'ABSPATH' ) ) exit; // Exit if accessed directly
function stripe_init()
{

	if(!class_exists('Stripe'))
	{
		include(plugin_dir_path( __FILE__ )."lib/init.php");
	}
	function add_stripe_gateway_class( $methods ) 
	{
		$methods[] = 'WC_Stripe_Gateway'; 
		return $methods;
	}
	add_filter( 'woocommerce_payment_gateways', 'add_stripe_gateway_class' );

	if(class_exists('WC_Payment_Gateway'))
	{
		class WC_Stripe_Gateway extends WC_Payment_Gateway 
		{

			public function __construct()
			{

				$this->id               = 'stripe';
				$this->icon             = plugins_url( 'images/stripe.png' , __FILE__ ) ;
				$this->has_fields       = true;
				$this->method_title     = 'Stripe Cards Settings';             
				$this->init_form_fields();
				$this->init_settings();

				$this->supports                 = array( 'products','refunds');

				$this->title                            = $this->get_option( 'stripe_title' );
				$this->stripe_description       = $this->get_option( 'stripe_description');

				$this->stripe_testpublickey     = $this->get_option( 'stripe_testpublickey' );
				$this->stripe_testsecretkey     = $this->get_option( 'stripe_testsecretkey' );
				$this->stripe_livepublickey     = $this->get_option( 'stripe_livepublickey' );
				$this->stripe_livesecretkey     = $this->get_option( 'stripe_livesecretkey' );
				$this->stripe_sandbox           = $this->get_option( 'stripe_sandbox' ); 
				$this->stripe_authorize_only    = $this->get_option( 'stripe_authorize_only' );
				$this->stripe_statementdescriptor    = $this->get_option( 'stripe_statementdescriptor' );
				$this->stripe_cardtypes         = $this->get_option( 'stripe_cardtypes');
				$this->stripe_createcustomer    = $this->get_option( 'stripe_createcustomer');
				$this->stripe_meta_cartspan     = $this->get_option( 'stripe_meta_cartspan');

				$this->stripe_receipt_email     = $this->get_option('stripe_receipt_email') ;
				$this->stripe_saved_cards       = $this->get_option( 'stripe_saved_cards') ;
				$this->stripe_shipping_address  = $this->get_option( 'stripe_shipping_address') ;
				$this->stripe_zerocurrency      = array("BIF","CLP","DJF","GNF","JPY","KMF","KRW","MGA","PYG","RWF","VND","VUV","XAF","XOF","XPF");

				if(!defined("STRIPE_CUSTOMER")){
					define("STRIPE_CUSTOMER", ($this->stripe_createcustomer =='yes' ? true:false ) );
				}

				if(!defined("STRIPE_TRANSACTION_MODE"))
					{ define("STRIPE_TRANSACTION_MODE"  , ($this->stripe_authorize_only =='yes'? false : true)); }

				add_action( 'wp_enqueue_scripts', array( $this, 'load_stripe_scripts' ) );
				add_action( 'admin_notices' ,     array( $this, 'do_ssl_check'    ));

				if('yes'  == $this->stripe_sandbox  )
					{ \Stripe\Stripe::setApiKey($this->stripe_testsecretkey);  }
				else
					{ \Stripe\Stripe::setApiKey($this->stripe_livesecretkey);  }

				if (is_admin()) 
				{
					add_action( 'woocommerce_update_options_payment_gateways_' . $this->id, array( $this, 'process_admin_options' ) );
				}


			}

			public function admin_options()
			{
				?>
				<h3><?php _e( 'Stripe Credit cards paymeny gateway addon for Woocommerce', 'woocommerce' ); ?></h3>
				<p><?php  _e( 'Stripe is a company that provides a way for individuals and businesses to accept payments over the Internet.', 'woocommerce' ); ?></p>
				<table class="form-table">
					<?php $this->generate_settings_html(); ?>
					<script type="text/javascript">

						jQuery( '#woocommerce_stripe_stripe_statementdescriptor' ).on( 'keypress', function() {
							if(jQuery('#woocommerce_stripe_stripe_statementdescriptor').val().length > 22 )
							{
								alert('Statement Descriptor Accepts only 22 Characters.When you close this popup field will be emptied please make sure not to enter more than 22 Characters.');
								jQuery('#woocommerce_stripe_stripe_statementdescriptor').val('');
							}
						})
						jQuery( '#woocommerce_stripe_stripe_sandbox' ).on( 'change', function() {
							var sandbox    = jQuery( '#woocommerce_stripe_stripe_testsecretkey, #woocommerce_stripe_stripe_testpublickey' ).closest( 'tr' ),
							production = jQuery( '#woocommerce_stripe_stripe_livesecretkey, #woocommerce_stripe_stripe_livepublickey' ).closest( 'tr' );

							if ( jQuery( this ).is( ':checked' ) ) {
								sandbox.show();
								production.hide();
							} else {
								sandbox.hide();
								production.show();
							}
						}).change();
					</script>
				</table>
				<?php
			}

			public function init_form_fields()
			{

				$this->form_fields = array(
					'enabled' => array(
						'title' => __( 'Enable/Disable', 'woocommerce' ),
						'type' => 'checkbox',
						'label' => __( 'Enable Stripe', 'woocommerce' ),
						'default' => 'yes'
						),

					'stripe_title' => array(
						'title' => __( 'Title', 'woocommerce' ),
						'type' => 'text',
						'description' => __( 'This controls the title which the user sees during checkout.', 'woocommerce' ),
						'default' => __( 'Credit Card', 'woocommerce' ),
						'desc_tip'      => true,
						),

					'stripe_description' => array(
						'title' => __( 'Description', 'woocommerce' ),
						'type' => 'textarea',
						'description' => __( 'This controls the description which the user sees during checkout.', 'woocommerce' ),
						'default' => __( 'All cards are stored by &copy;Stripe servers we do not store any card details', 'woocommerce' ),
						'desc_tip'      => true,
						),

					'stripe_testsecretkey' => array(
						'title' => __( 'Test Secret Key', 'woocommerce' ),
						'type' => 'text',
						'description' => __( 'This is the Secret Key found in API Keys in Account Dashboard.', 'woocommerce' ),
						'default' => '',
						'desc_tip'      => true,
						'placeholder' => 'Stripe Test Secret Key'
						),

					'stripe_testpublickey' => array(
						'title' => __( 'Test Publishable Key', 'woocommerce' ),
						'type' => 'text',
						'description' => __( 'This is the Publishable Key found in API Keys in Account Dashboard.', 'woocommerce' ),
						'default' => '',
						'desc_tip'      => true,
						'placeholder' => 'Stripe Test Publishable Key'
						),

					'stripe_livesecretkey' => array(
						'title' => __( 'Live Secret Key', 'woocommerce' ),
						'type' => 'text',
						'description' => __( 'This is the Secret Key found in API Keys in Account Dashboard.', 'woocommerce' ),
						'default' => '',
						'desc_tip'      => true,
						'placeholder' => 'Stripe Live Secret Key'
						),

					'stripe_livepublickey' => array(
						'title' => __( 'Live Publishable Key', 'woocommerce' ),
						'type' => 'text',
						'description' => __( 'This is the Publishable Key found in API Keys in Account Dashboard.', 'woocommerce' ),
						'default' => '',
						'desc_tip'      => true,
						'placeholder' => 'Stripe Live Publishable Key'
						),

					'stripe_sandbox' => array(
						'title'       => __( 'Stripe Sandbox', 'woocommerce' ),
						'type'        => 'checkbox',
						'label'       => __( 'Enable stripe sandbox (Sandbox mode if checked)', 'woocommerce' ),
						'description' => __( 'If checked its in sanbox mode and if unchecked its in live mode', 'woocommerce' ),
						'desc_tip'      => true,
						'default'     => 'no',
						),

					'stripe_authorize_only' => array(
						'title'       => __( 'Authorize Only', 'woocommerce' ),
						'type'        => 'checkbox',
						'label'       => __( 'Enable Authorize Only Mode (Authorize only mode if checked)', 'woocommerce' ),
						'description' => __( 'If checked will only authorize the credit card only upon checkout.', 'woocommerce' ),
						'desc_tip'      => true,
						'default'     => 'no',
						),

					'stripe_statementdescriptor' => array(
						'title' 		=> __( 'Statement Descriptor', 'woocommerce' ),
						'type' 			=> 'text',
						'description' 	=> __( 'Extra information about a charge. This will appear on your customer’s credit card statement.Maximum 22 Chars', 'woocommerce' ),
						'default' 		=> __( 'Online Shopping', 'woocommerce' ),
						'desc_tip'      => true,
						
						),

					'stripe_cardtypes' => array(
						'title'    => __( 'Accepted Cards', 'woocommerce' ),
						'type'     => 'multiselect',
						'class'    => 'chosen_select',
						'css'      => 'width: 350px;',
						'desc_tip' => __( 'Select the card types to accept.', 'woocommerce' ),
						'options'  => array(
							'mastercard'       => 'MasterCard',
							'visa'             => 'Visa',
							'discover'         => 'Discover',
							'amex'             => 'American Express',
							'jcb'              => 'JCB',
							'dinersclub'       => 'Diners Club',
							),
						'default' => array( 'mastercard', 'visa', 'discover', 'amex' ),
						),

					'stripe_meta_cartspan' => array(
						'title'       => __( 'Enable CartSpan', 'woocommerce' ),
						'type'        => 'checkbox',
						'label'       => __( 'Enable <a href="http://www.cartspan.com/">CartSpan</a> to Stores Last4 & Brand of Card (Active If Checked)', 'woocommerce' ),
						'description' => __( 'If checked will store last4 and card brand in local db from charge object.', 'woocommerce' ),
						'desc_tip'      => true,
						'default'     => 'no',
						),


					'stripe_receipt_email' => array(
						'title'       => __( 'Enable stripe receipt email', 'woocommerce' ),
						'type'        => 'checkbox',
						'label'       => __( 'Enable receipt email from Stripe (Active If Checked)', 'woocommerce' ),
						'description' => __( 'If checked will send stripe receipt email to billing email in live mode only', 'woocommerce' ),
						'desc_tip'      => true,
						'default'     => 'no',
						),


					'stripe_createcustomer' => array(
						'title'       => __( 'Create Customers', 'woocommerce' ),
						'type'        => 'checkbox',
						'label'       => __( 'Enable creating Stripe customers for each #Order', 'woocommerce' ),
						'description' => __( 'If checked will only authorize the credit card only upon checkout after creating customer for each order.', 'woocommerce' ),
						'desc_tip'      => true,
						'default'     => 'no',
						),

					'stripe_shipping_address' => array(
						'title'       => __( 'Enable Shipping Address', 'woocommerce' ),
						'type'        => 'checkbox',
						'label'       => __( 'Enable sending shipping address to stripe (Active If Checked)', 'woocommerce' ),
						'description' => __( 'If checked will send shipping address to stripe.', 'woocommerce' ),
						'desc_tip'      => true,
						'default'     => 'no',
						)
					);

}



public function get_description() {
	return apply_filters( 'woocommerce_gateway_description',wpautop(wptexturize(trim($this->stripe_description))), $this->id );
}


/*Is Avalaible*/
public function is_available() {
	if ( ! in_array( get_woocommerce_currency(), apply_filters( 'stripe_woocommerce_supported_currencies', array( 'AED','ALL','ANG','ARS','AUD','AWG','BBD','BDT','BIF','BMD','BND','BOB','BRL','BSD','BWP','BZD','CAD','CHF','CLP','CNY','COP','CRC','CVE','CZK','DJF','DKK','DOP','DZD','EGP','ETB','EUR','FJD','FKP','GBP','GIP','GMD','GNF','GTQ','GYD','HKD','HNL','HRK','HTG','HUF','IDR','ILS','INR','ISK','JMD','JPY','KES','KHR','KMF','KRW','KYD','KZT','LAK','LBP','LKR','LRD','MAD','MDL','MNT','MOP','MRO','MUR','MVR','MWK','MXN','MYR','NAD','NGN','NIO','NOK','NPR','NZD','PAB','PKR','PLN','PYG','QAR','RUB','SAR','SBD','SCR','SEK','SGD','SHP','SLL','SOS','STD','SVC','SZL','THB','TOP','TTD','TWD','TZS','UAH','UGX','USD','UYU','UZS','VND','VUV','WST','XAF','XOF','XPF','YER','ZAR','AFN','AMD','AOA','AZN','BAM','BGN','CDF','GEL','KGS','LSL','MGA','MKD','MZN','RON','RSD','RWF','SRD','TJS','TRY','XCD','ZMW' ) ) ) ) 
		{ return false; }


	if( 'yes'  == $this->stripe_sandbox && (empty($this->stripe_testpublickey) || empty($this->stripe_testsecretkey))) 
		{ return false; }

	if( 'no'  == $this->stripe_sandbox && (empty($this->stripe_livepublickey) || empty($this->stripe_livesecretkey)))
		{ return false; }

	return true;
}
/*end is availaible*/

public function do_ssl_check()
{
	if( 'yes'  != $this->stripe_sandbox && "no" == get_option( 'woocommerce_force_ssl_checkout' )  && "yes" == $this->enabled ) {
		echo "<div class=\"error\"><p>". sprintf( __( "<strong>%s</strong> is enabled and WooCommerce is not forcing the SSL on your checkout page. Please ensure that you have a valid SSL certificate and that you are <a href=\"%s\">forcing the checkout pages to be secured.</a>" ), $this->method_title, admin_url( 'admin.php?page=wc-settings&tab=checkout' ) ) ."</p></div>"; 
	}
}


public function load_stripe_scripts() {

	wp_enqueue_script( 'stripe', 'https://js.stripe.com/v2/', false, '2.0', true );

	wp_enqueue_script( 'stripewoojs', plugins_url( 'assets/js/stripewoo.js',  __FILE__  ), array( 'stripe', 'wc-credit-card-form' ), '', true );

	$stripe_array = array(
		'stripe_publishablekey'    => $this->stripe_sandbox == 'yes' ? $this->stripe_testpublickey : $this->stripe_livepublickey);


	if ( is_checkout_pay_page() ) {
		$order_key = urldecode( $_GET['key'] );
		$order_id  = absint( get_query_var( 'order-pay' ) );
		$order     = new WC_Order( $order_id );

		if ( $order->id == $order_id && $order->order_key == $order_key ) {
			$stripe_array['billing_name']      = $order->billing_first_name.' '.$order->billing_last_name;
			$stripe_array['billing_address_1'] = $order->billing_address_1;
			$stripe_array['billing_address_2'] = $order->billing_address_2;
			$stripe_array['billing_city']      = $order->billing_city;
			$stripe_array['billing_state']     = $order->billing_state;
			$stripe_array['billing_postcode']  = $order->billing_postcode;
			$stripe_array['billing_country']   = $order->billing_country;
		}
	}


	wp_localize_script( 'stripewoojs', 'stripe_array', $stripe_array );

}





                //Function to check IP
function get_client_ip() 
{
	$ipaddress = '';
	if (getenv('HTTP_CLIENT_IP'))
		$ipaddress = getenv('HTTP_CLIENT_IP');
	else if(getenv('HTTP_X_FORWARDED_FOR'))
		$ipaddress = getenv('HTTP_X_FORWARDED_FOR');
	else if(getenv('HTTP_X_FORWARDED'))
		$ipaddress = getenv('HTTP_X_FORWARDED');
	else if(getenv('HTTP_FORWARDED_FOR'))
		$ipaddress = getenv('HTTP_FORWARDED_FOR');
	else if(getenv('HTTP_FORWARDED'))
		$ipaddress = getenv('HTTP_FORWARDED');
	else if(getenv('REMOTE_ADDR'))
		$ipaddress = getenv('REMOTE_ADDR');
	else
		$ipaddress = '0.0.0.0';
	return $ipaddress;
}

                //End of function to check IP




/*Get Icon*/
public function get_icon() {
	$icon = '';
	if(is_array($this->stripe_cardtypes))
	{
		foreach ( $this->stripe_cardtypes as $card_type ) {

			if ( $url = $this->stripe_get_active_card_logo_url( $card_type ) ) {

				$icon .= '<img width="40" src="'.esc_url( $url ).'" alt="'.esc_attr( strtolower( $card_type ) ).'" />';
			}
		}
	}
	else
	{
		$icon .= '<img src="'.esc_url( plugins_url( 'images/stripe.png' , __FILE__ ) ).'" alt="Stripe Gateway" />';       
	}

	return apply_filters( 'woocommerce_stripe_icon', $icon, $this->id );
}

public function stripe_get_active_card_logo_url( $type ) {

	$image_type = strtolower( $type );
	return  WC_HTTPS::force_https_url( plugins_url( 'images/' . $image_type . '.png' , __FILE__ ) ); 
}



/*Start of credit card form */
public function payment_fields() {
	echo apply_filters( 'wc_stripe_description', wpautop(wp_kses_post( wptexturize(trim($this->stripe_description) ) ) ) );
	$this->form();
}

public function field_name( $name ) {
	return $this->supports( 'tokenization' ) ? '' : ' name="' . esc_attr( $this->id . '-' . $name ) . '" ';
}

public function form() {
	wp_enqueue_script( 'wc-credit-card-form' );
	$fields = array();
	$cvc_field = '<p class="form-row form-row-last">
	<label for="' . esc_attr( $this->id ) . '-card-cvc">' . __( 'Card Code', 'woocommerce' ) . ' <span class="required">*</span></label>
	<input id="' . esc_attr( $this->id ) . '-card-cvc" class="input-text wc-credit-card-form-card-cvc" type="text" autocomplete="off" placeholder="' . esc_attr__( 'CVC', 'woocommerce' ) . '" ' . $this->field_name( 'card-cvc' ) . ' />
</p>';
$default_fields = array(
	'card-number-field' => '<p class="form-row form-row-wide">
	<label for="' . esc_attr( $this->id ) . '-card-number">' . __( 'Card Number', 'woocommerce' ) . ' <span class="required">*</span></label>
	<input id="' . esc_attr( $this->id ) . '-card-number" class="input-text wc-credit-card-form-card-number" type="text" maxlength="20" autocomplete="off" placeholder="&bull;&bull;&bull;&bull; &bull;&bull;&bull;&bull; &bull;&bull;&bull;&bull; &bull;&bull;&bull;&bull;" ' . $this->field_name( 'card-number' ) . ' />
</p>',
'card-expiry-field' => '<p class="form-row form-row-first">
<label for="' . esc_attr( $this->id ) . '-card-expiry">' . __( 'Expiry (MM/YY)', 'woocommerce' ) . ' <span class="required">*</span></label>
<input id="' . esc_attr( $this->id ) . '-card-expiry" class="input-text wc-credit-card-form-card-expiry" type="text" autocomplete="off" placeholder="' . esc_attr__( 'MM / YY', 'woocommerce' ) . '" ' . $this->field_name( 'card-expiry' ) . ' />
</p>',
'card-cvc-field'  => $cvc_field
);

$fields = wp_parse_args( $fields, apply_filters( 'woocommerce_credit_card_form_fields', $default_fields, $this->id ) );
?>

<fieldset id="wc-<?php echo esc_attr( $this->id ); ?>-cc-form" class='wc-credit-card-form wc-payment-form'>
	<?php do_action( 'woocommerce_credit_card_form_start', $this->id ); ?>
	<?php
	foreach ( $fields as $field ) {
		echo $field;
	}
	?>
	<?php do_action( 'woocommerce_credit_card_form_end', $this->id ); ?>
	<div class="clear"></div>
</fieldset>
<?php

}
/*End of credit card form*/



                /*
                @function charge_array(order:object,stripe.js-token:string,stripe-custid:string,stripe-cardid:string)
                */
                private function charge_array($wc_order,$token_id,$cust_id){

                	$chargearray = array( 
                		'amount'                    => $this->stripe_order_total($wc_order), 
                		'currency'                  => $wc_order->get_order_currency(), 
                		'capture'                   => STRIPE_TRANSACTION_MODE,
                		'statement_descriptor'      => $this->stripe_statementdescriptor,
                		'metadata'                  => array(
                			'Order #'               => $wc_order->get_order_number(),
                			'Total Tax'             => $wc_order->get_total_tax(),
                			'Total Shipping'        => $wc_order->get_total_shipping(),
                			'Customer IP'           => $this->get_client_ip(),
                			'WP customer #'         => $wc_order->user_id,
                			'Billing Email'         => $wc_order->billing_email,
                			) ,
                		'description'               => get_bloginfo('blogname').' Order #'.$wc_order->get_order_number(),
                		);

                	if('yes' == $this->stripe_receipt_email)
                	{
                		$chargearray['receipt_email'] = $wc_order->billing_email;
                	}

                	if('yes' == $this->stripe_shipping_address){

                		$chargearray['shipping'] = array(
                			'address' => array(
                				'line1'                 => $wc_order->shipping_address_1,
                				'line2'                 => $wc_order->shipping_address_2,
                				'city'                  => $wc_order->shipping_city,
                				'state'                 => $wc_order->shipping_state,
                				'country'               => $wc_order->shipping_country,
                				'postal_code'   => $wc_order->shipping_postcode         
                				),
                			'name' => $wc_order->shipping_first_name.' '.$wc_order->shipping_last_name,
                			'phone'=> $wc_order->billing_phone 
                			);
                	}


                	if (!empty($cust_id) && empty($token_id) ) {
                		$chargearray['customer']  = $cust_id;
                	}
                	else{
                		$chargearray['card']      = $token_id;
                	}
							 //echo '<pre>'; print_r($chargearray);die;
                	return $chargearray ;

                }


                private function stripe_order_total($wc_order)
                {
                	$grand_total    = $wc_order->order_total;
                	$currency = '' != $wc_order->get_order_currency() ? $wc_order->get_order_currency() : get_woocommerce_currency() ;

                	if(in_array($currency ,$this->stripe_zerocurrency ))
                	{
                		$amount              = number_format($grand_total,0,".","") ;
                	}
                	else
                	{
                		$amount              = $grand_total * 100 ;
                	}

                	return $amount;
                }


                private function stripe_refund_total($wc_order, $refund_amount)
                {
                	
                	$currency = '' != $wc_order->get_order_currency() ? $wc_order->get_order_currency() : get_woocommerce_currency() ;

                	if(in_array($currency ,$this->stripe_zerocurrency ))
                	{
                		$refund_amount              = number_format($refund_amount,0,".","") ;
                	}
                	else
                	{
                		$refund_amount              = $refund_amount * 100 ;
                	}

                	return $refund_amount;
                }


                /*Process Payment*/
                public function process_payment( $order_id )
                {       
                	global $error;
                	global $woocommerce;
                	$wc_order        = wc_get_order( $order_id );
                        // Create Token for Card or Customer
                	$token_id = sanitize_text_field($_POST['stripe_token']);

                	try
                	{


         			// create customer for each order
                		if(true == STRIPE_CUSTOMER)
                		{
                			$cust = \Stripe\Customer::create(array(
                				'source'        => $token_id,
                				'email'         => $wc_order->billing_email,
                				'description'   => $wc_order->get_order_number()));
                			$chargeparam = $this->charge_array($wc_order,'',$cust->id);
                			$charge          = \Stripe\Charge::create($chargeparam);
                    }//  create customer for each order
                    else{
                    	$chargeparam = $this->charge_array($wc_order,$token_id,'');
                    	$charge      = \Stripe\Charge::create($chargeparam);
                    }


                    if(''!=$token_id || ''!=$cust->id )
                    { 
                    	if ($charge->paid == true)
                    	{

                    		$timestamp = date('Y-m-d H:i:s A e', $charge->created);

                    		if($charge->source->object == "card")
                    		{
                    			$wc_order->add_order_note(__( 'Charge '.$charge->status. ' at '.$timestamp.',Charge ID='.$charge->id.',Card='.$charge->source->brand.' : '.$charge->source->last4.' : '.$charge->source->exp_month.'/'.$charge->source->exp_year,'woocommerce'));
                    		}

                    		$wc_order->payment_complete($charge->id);
                    		WC()->cart->empty_cart();

                    		if('yes' == $this->stripe_meta_cartspan)
                    		{
                    			$stripe_metas_for_cartspan = array(
                    				'cc_type'                       => $charge->source->brand,
                    				'cc_last4'                      => $charge->source->last4,
                    				'cc_trans_id'           => $charge->id,
                    				);
                    			add_post_meta( $order_id, '_stripe_metas_for_cartspan', $stripe_metas_for_cartspan);
                    		}


                    		if(true == $charge->captured && true == $charge->paid)
                    		{
                    			add_post_meta( $order_id, '_stripe_charge_status', 'charge_auth_captured');
                    		}

                    		if(false == $charge->captured && true == $charge->paid)
                    		{
                    			add_post_meta( $order_id, '_stripe_charge_status', 'charge_auth_only');
                    		}


                    		return array (
                    			'result'   => 'success',
                    			'redirect' => $this->get_return_url( $wc_order ),
                    			);
                    	}
                    	else
                    	{
                    		$wc_order->add_order_note( __( 'Charge '.$charge->status, 'woocommerce' ) );
                    		wc_add_notice($charge->status, $notice_type = 'error' );

                    	}

                    }
        }//end ot try block
        catch (Exception $e)
        {

        	$body         = $e->getJsonBody();
        	$error        = $body['error']['message'];
        	$wc_order->add_order_note( __( 'Stripe Error.'.$error, 'woocommerce' ) );
        	if ( has_filter( 'woocommerce_stripe_woocommerce_addon_error') ){
        	$error = apply_filters( 'woocommerce_stripe_woocommerce_addon_error', $body );	
        	}
        	wc_add_notice($error,  $notice_type = 'error' );
        }




                } // end of function process_payment()

                /*Process Payment*/



                /*process refund function*/
                public function process_refund($order_id, $amount = NULL, $reason = '' ) {


                	if($amount > 0 )
                	{
                		$CHARGE_ID      = get_post_meta( $order_id , '_transaction_id', true );
                		$wc_order    = new WC_Order( $order_id );
                		$charge                 = \Stripe\Charge::retrieve($CHARGE_ID);
                		$refund                 = $charge->refunds->create(
                			array(
                				'amount'        => $this->stripe_refund_total($wc_order,$amount) ,
                				'metadata'      => array('Order #'   => $order_id,
                					'Refund reason' => $reason 
                					),
                				)
                			);
                		if($refund)     
                		{

                			$rtimestamp  = date('Y-m-d H:i:s A e', $refund->created);
                			$refundid    = $refund->id; 

                			$wc_order->add_order_note( __('Refund '.$charge->status.' at. '.$rtimestamp.' with Refund ID = '.$refundid , 'woocommerce' ) );                         
                			return true;
                		}
                		else
                		{
                			return false;
                		}


                	}
                	else
                	{
                		return false;
                	}



                }// end of  process_refund()



        }  // end of class WC_Stripe_Gateway

} // end of if class exist WC_Gateway

}

/*Activation hook*/
add_action( 'plugins_loaded', 'stripe_init' );

function stripe_woocommerce_addon_activate() {

	if(!function_exists('curl_exec'))
	{
		wp_die( '<pre>This plugin requires PHP CURL library installled in order to be activated </pre>' );
	}
}
register_activation_hook( __FILE__, 'stripe_woocommerce_addon_activate' );
/*Activation hook*/

/*Plugin Settings Link*/


function stripe_woocommerce_addon_settings_link( $links ) {
	$settings_link = '<a href="admin.php?page=wc-settings&tab=checkout&section=wc_stripe_gateway">' . __( 'Settings' ) . '</a>';
	array_push( $links, $settings_link );
	return $links;
}
$plugin = plugin_basename( __FILE__ );
add_filter( "plugin_action_links_$plugin",'stripe_woocommerce_addon_settings_link' );
/*Plugin Settings Link*/

/*Capture Charge*/

function stripe_capture_meta_box() {
	global $post;
	$chargestatus = get_post_meta( $post->ID, '_stripe_charge_status', true );
	if($chargestatus == 'charge_auth_only')
	{
		add_meta_box(
			'stripe_capture_chargeid',
			__( 'Capture Charge', 'woocommerce' ),
			'stripe_capture_meta_box_callback',
			'shop_order',
			'side',
			'default'
			);
	}
}
add_action( 'add_meta_boxes', 'stripe_capture_meta_box' );


function stripe_capture_meta_box_callback( $post ) {

        //charge_auth_only, charge_auth_captured, charge_auth_captured_later
	echo '<input type="checkbox" name="_stripe_capture_charge" value="1"/>&nbsp;Check & Save Order to Capture';
}


/*Execute charge on order save*/
function stripe_capture_meta_box_action($order_id, $items )
{
	if(isset($items['_stripe_capture_charge']) && (1 ==$items['_stripe_capture_charge']) ) 
	{
		global $post;
		$chargeid = get_post_meta( $post->ID, '_transaction_id', true );
		if(class_exists('WC_Stripe_Gateway'))
		{
			$stripepg = new WC_Stripe_Gateway();

			if('yes'  == $stripepg->stripe_sandbox  )
				{ \Stripe\Stripe::setApiKey($stripepg->stripe_testsecretkey);  }
			else
				{ \Stripe\Stripe::setApiKey($stripepg->stripe_livesecretkey);  }

		}

		try
		{
			$wc_order = new WC_Order($order_id);
			$capturecharge   = \Stripe\Charge::retrieve($chargeid);
			$captureresponse = $capturecharge->capture();

			if(true == $captureresponse->captured && true == $captureresponse->paid)
			{

				$timestamp = date('Y-m-d H:i:s A e', $captureresponse->created);
				update_post_meta( $order_id, '_stripe_charge_status', 'charge_auth_captured_later');
				$wc_order->add_order_note(__( 'Capture '.$captureresponse->status.' at '.$timestamp.'-with Charge ID='.$captureresponse->id.',Card='.$captureresponse->source->brand.' : '.$captureresponse->source->last4.' : '.$captureresponse->source->exp_month.'/'.$captureresponse->source->exp_year ,'woocommerce'));

			}
		}catch(Exception $e){

			update_post_meta( $order_id, '_stripe_charge_status', 'charge_auth_expired');
			$wc_order->add_order_note(__( $captureresponse->status.' '.$e->getMessage(),'woocommerce'));
		}

		unset($wc_order);




	}       

}
add_action ("woocommerce_saved_order_items", "stripe_capture_meta_box_action", 10,2);
/*Execute charge on order save*/
