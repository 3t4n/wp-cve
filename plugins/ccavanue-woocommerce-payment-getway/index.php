<?php
/*
Plugin Name: CCAvenue Payment Gateway for WooCommerce
Plugin URI: 
Description: WooCommerce with ccavenue Indian payment gateway.
Version: 3.1
Author: Nilesh Chourasia
Author URI: 

Copyright: Â© 2015-2023 Nilesh
License: GNU General Public License v3.0
License URI: http://www.gnu.org/licenses/gpl-3.0.html
*/
    if ( ! defined( 'ABSPATH' ) )
        exit;
    add_action('plugins_loaded', 'woocommerce_nilesh_ccave_init', 0);

    function woocommerce_nilesh_ccave_init() {

        if ( !class_exists( 'WC_Payment_Gateway' ) ) return;

    /**
     * Gateway class
     */
    class WC_nilesh_Ccave extends WC_Payment_Gateway {
		
		/*Define local variables*/
		public $payment_option;
		public $card_type;
		public $card_name;
		public $data_accept;
		public $card_number;
		public $expiry_month;
		public $expiry_year;
		public $cvv_number;
		public $issuing_bank;
		/*end of variable*/
		
        public function __construct(){

            // Go wild in here
            $this -> id           = 'ccavenue';
            $this -> method_title = __('CCAvenue', 'nilesh');
            $this -> icon         =  plugins_url( 'images/logo.png' , __FILE__ );
            $this -> has_fields   = true;
            
            $this -> init_form_fields();
            $this -> init_settings();

            $this -> title            = $this -> settings['title'];
            $this -> description      = $this -> settings['description'];
            $this -> merchant_id      = $this -> settings['merchant_id'];
            $this -> working_key      = $this -> settings['working_key'];
            $this -> access_code      = $this -> settings['access_code'];
			$this -> iframemode       = $this -> settings['iframemode'];
			$this -> sandbox      	  = $this -> settings['sandbox'];			
			$this -> enable_currency_conversion      = $this -> settings['enable_currency_conversion'];
			
			$this -> default_add1 = $this -> settings['default_add1'];
			$this -> default_country = $this -> settings['default_country'];
			$this -> default_state = $this -> settings['default_state'];
			$this -> default_city = $this -> settings['default_city'];
			$this -> default_zip = $this -> settings['default_zip'];
			$this -> default_phone = $this -> settings['default_phone'];
			
			if($this -> sandbox=='yes')
			{
				 $this -> liveurlonly = "https://test.ccavenue.com/transaction/transaction.do";
			}else{
				$this -> liveurlonly = "https://secure.ccavenue.com/transaction/transaction.do";
            }
			 $this -> liveurl  = $this -> liveurlonly.'?command=initiateTransaction';
			
			$this->notify_url = home_url( '/wc-api/WC_nilesh_Ccave' ) ;

            $this -> msg['message'] = "";
            $this -> msg['class']   = "";
			
			$this -> payment_option = isset($_POST['payment_option']) ? sanitize_text_field($_POST['payment_option']) : "";
			$this -> card_type		= isset($_POST['card_type']) ? sanitize_text_field($_POST['card_type']) : "";
			$this -> card_name 		= isset($_POST['card_name']) ? sanitize_text_field($_POST['card_name']) : "";
			$this -> data_accept 	= isset($_POST['data_accept']) ? sanitize_text_field($_POST['data_accept']) : "";
			$this -> card_number 	= isset($_POST['card_number']) ? sanitize_text_field($_POST['card_number']) : "";
			$this -> expiry_month 	= isset($_POST['expiry_month']) ? sanitize_text_field($_POST['expiry_month']) : "";
			$this -> expiry_year 	= isset($_POST['expiry_year']) ? sanitize_text_field($_POST['expiry_year']) : "";
			$this -> cvv_number 	= isset($_POST['cvv_number']) ? sanitize_text_field($_POST['cvv_number']) : "";
			$this -> issuing_bank 	= isset($_POST['issuing_bank']) ? sanitize_text_field($_POST['issuing_bank']) : "";
			
            add_action( 'woocommerce_api_wc_nilesh_ccave', array( $this, 'check_ccavenue_response' ) );
            add_action('valid-ccavenue-request', array($this, 'successful_request'));
            if ( version_compare( WOOCOMMERCE_VERSION, '2.0.0', '>=' ) ) {
                add_action( 'woocommerce_update_options_payment_gateways_' . $this->id, array( $this, 'process_admin_options' ) );
            } else {
                add_action( 'woocommerce_update_options_payment_gateways', array( &$this, 'process_admin_options' ) );
            }
            add_action('woocommerce_receipt_ccavenue', array($this, 'receipt_page'));
            add_action('woocommerce_thankyou_ccavenue',array($this, 'thankyou_page'));
        }

        function init_form_fields(){
			$countries = array("Afghanistan", "Albania", "Algeria", "American Samoa", "Andorra", "Angola", "Anguilla", "Antarctica", "Antigua and Barbuda", "Argentina", "Armenia", "Aruba", "Australia", "Austria", "Azerbaijan", "Bahamas", "Bahrain", "Bangladesh", "Barbados", "Belarus", "Belgium", "Belize", "Benin", "Bermuda", "Bhutan", "Bolivia", "Bosnia and Herzegowina", "Botswana", "Bouvet Island", "Brazil", "British Indian Ocean Territory", "Brunei Darussalam", "Bulgaria", "Burkina Faso", "Burundi", "Cambodia", "Cameroon", "Canada", "Cape Verde", "Cayman Islands", "Central African Republic", "Chad", "Chile", "China", "Christmas Island", "Cocos (Keeling) Islands", "Colombia", "Comoros", "Congo", "Congo, the Democratic Republic of the", "Cook Islands", "Costa Rica", "Cote d'Ivoire", "Croatia (Hrvatska)", "Cuba", "Cyprus", "Czech Republic", "Denmark", "Djibouti", "Dominica", "Dominican Republic", "East Timor", "Ecuador", "Egypt", "El Salvador", "Equatorial Guinea", "Eritrea", "Estonia", "Ethiopia", "Falkland Islands (Malvinas)", "Faroe Islands", "Fiji", "Finland", "France", "France Metropolitan", "French Guiana", "French Polynesia", "French Southern Territories", "Gabon", "Gambia", "Georgia", "Germany", "Ghana", "Gibraltar", "Greece", "Greenland", "Grenada", "Guadeloupe", "Guam", "Guatemala", "Guinea", "Guinea-Bissau", "Guyana", "Haiti", "Heard and Mc Donald Islands", "Holy See (Vatican City State)", "Honduras", "Hong Kong", "Hungary", "Iceland", "India", "Indonesia", "Iran (Islamic Republic of)", "Iraq", "Ireland", "Israel", "Italy", "Jamaica", "Japan", "Jordan", "Kazakhstan", "Kenya", "Kiribati", "Korea, Democratic People's Republic of", "Korea, Republic of", "Kuwait", "Kyrgyzstan", "Lao, People's Democratic Republic", "Latvia", "Lebanon", "Lesotho", "Liberia", "Libyan Arab Jamahiriya", "Liechtenstein", "Lithuania", "Luxembourg", "Macau", "Macedonia, The Former Yugoslav Republic of", "Madagascar", "Malawi", "Malaysia", "Maldives", "Mali", "Malta", "Marshall Islands", "Martinique", "Mauritania", "Mauritius", "Mayotte", "Mexico", "Micronesia, Federated States of", "Moldova, Republic of", "Monaco", "Mongolia", "Montserrat", "Morocco", "Mozambique", "Myanmar", "Namibia", "Nauru", "Nepal", "Netherlands", "Netherlands Antilles", "New Caledonia", "New Zealand", "Nicaragua", "Niger", "Nigeria", "Niue", "Norfolk Island", "Northern Mariana Islands", "Norway", "Oman", "Pakistan", "Palau", "Panama", "Papua New Guinea", "Paraguay", "Peru", "Philippines", "Pitcairn", "Poland", "Portugal", "Puerto Rico", "Qatar", "Reunion", "Romania", "Russian Federation", "Rwanda", "Saint Kitts and Nevis", "Saint Lucia", "Saint Vincent and the Grenadines", "Samoa", "San Marino", "Sao Tome and Principe", "Saudi Arabia", "Senegal", "Seychelles", "Sierra Leone", "Singapore", "Slovakia (Slovak Republic)", "Slovenia", "Solomon Islands", "Somalia", "South Africa", "South Georgia and the South Sandwich Islands", "Spain", "Sri Lanka", "St. Helena", "St. Pierre and Miquelon", "Sudan", "Suriname", "Svalbard and Jan Mayen Islands", "Swaziland", "Sweden", "Switzerland", "Syrian Arab Republic", "Taiwan, Province of China", "Tajikistan", "Tanzania, United Republic of", "Thailand", "Togo", "Tokelau", "Tonga", "Trinidad and Tobago", "Tunisia", "Turkey", "Turkmenistan", "Turks and Caicos Islands", "Tuvalu", "Uganda", "Ukraine", "United Arab Emirates", "United Kingdom", "United States", "United States Minor Outlying Islands", "Uruguay", "Uzbekistan", "Vanuatu", "Venezuela", "Vietnam", "Virgin Islands (British)", "Virgin Islands (U.S.)", "Wallis and Futuna Islands", "Western Sahara", "Yemen", "Yugoslavia", "Zambia", "Zimbabwe");
			
            $this -> form_fields = array(
                'enabled' => array(
                    'title' => __('Enable/Disable', 'nilesh'),
                    'type' => 'checkbox',
                    'label' => __('Enable CCAvenue Payment Module.', 'nilesh'),
                    'default' => 'no'),
				
				 'sandbox' => array(
                    'title' => __('Enable Sandbox?', 'nilesh'),
                    'type' => 'checkbox',
                    'label' => __('Enable Sandbox CCAvenue Payment.', 'nilesh'),
                    'default' => 'no'),
				 
				 'enable_currency_conversion' => array(
                    'title' => __('Currency Conversion to INR?', 'nilesh'),
                    'type' => 'checkbox',
                    'label' => __('Enable Currency Conversion to INR.', 'nilesh'),
					'description'=> __('converted to equivalent amount in INR for faster payment processing'),
                    'default' => 'no'),

				 'iframemode' => array(
                    'title' => __('Iframe/Redirect Payment', 'nilesh'),
                    'type' => 'checkbox',
                    'label' => __('Enable Iframe method', 'nilesh'),
					'description'=> __('If you do not want customer to redirect on CCAvenue site and do the payment from checkout page only.'),
                    'default' => 'no'),
				 
                'title' => array(
                    'title' => __('Title:', 'nilesh'),
                    'type'=> 'text',
                    'description' => __('This controls the title which the user sees during checkout.', 'nilesh'),
                    'default' => __('CCAvenue', 'nilesh')),
                'description' => array(
                    'title' => __('Description:', 'nilesh'),
                    'type' => 'textarea',
                    'description' => __('This controls the description which the user sees during checkout.', 'nilesh'),
                    'default' => __('Pay securely by Credit or Debit card or internet banking through CCAvenue Secure Servers.', 'nilesh')),
                'merchant_id' => array(
                    'title' => __('Merchant ID', 'nilesh'),
                    'type' => 'text',
                    'description' => __('This id(USER ID) available at "Generate Working Key" of "Settings and Options at CCAvenue."')),
                'working_key' => array(
                    'title' => __('Working Key', 'nilesh'),
                    'type' => 'text',
                    'description' =>  __('Given to Merchant by CCAvenue', 'nilesh'),
                    ),
                'access_code' => array(
                    'title' => __('Access Code', 'nilesh'),
                    'type' => 'text',
                    'description' =>  __('Given to Merchant by CCAvenue', 'nilesh'),
                    ),
				'default_add1' => array(
                    'title' => __('Default Address', 'nilesh'),
                    'type' => 'text',
                    'description' =>  __('Enter Address in case of user address not selected while checkout. eg: 302 california, US', 'nilesh'),
                    ),
				'default_city' => array(
                    'title' => __('Default City', 'nilesh'),
                    'type' => 'text',
                    'description' =>  __('Enter City in case of user city not selected while checkout. eg: California', 'nilesh'),
                    ),
				'default_state' => array(
                    'title' => __('Default State', 'nilesh'),
                    'type' => 'text',
                    'description' =>  __('Enter State in case of user state not selected while checkout. eg: US', 'nilesh'),
                    ),
				'default_zip' => array(
                    'title' => __('Default Zip', 'nilesh'),
                    'type' => 'text',
                    'description' =>  __('Enter Zip in case of user zip not selected while checkout. eg: 452145', 'nilesh'),
                    ),
                'default_country' => array(
                    'title' => __('Default Country', 'nilesh'),
                    'type' => 'select',
					'options' => $countries,
                    'description' =>  __('Select Country in case of user country not selected while checkout. eg: US', 'nilesh'),
                    ),

				'default_phone' => array(
                    'title' => __('Default Phone Number', 'nilesh'),
                    'type' => 'text',
                    'description' =>  __('Enter Phone Number in case of user phone number not selected while checkout. eg: 91-253-258694', 'nilesh'),
                    ),
				);				

		}
		
        /**
         * Admin Panel Options
         * - Options for bits like 'title' and availability on a country-by-country basis
         **/
		 
        public function admin_options(){
            echo '<h3>'.__('CCAvenue Payment Gateway', 'nilesh').'</h3>';
            echo '<p>'.__('CCAvenue is most popular payment gateway for online shopping in India').'</p>';
            echo '<table class="form-table">';
            $this -> generate_settings_html();
            echo '</table>';

        }
        /**
         *  There are no payment fields for CCAvenue, but we want to show the description if set.
         **/
        function payment_fields(){
            if($this -> description) echo wpautop(wptexturize($this -> description));
        }
        /**
         * Receipt Page
         **/
        function receipt_page($order){
			if($this -> iframemode=='no')
            	echo '<p>'.__('Thank you for your order, please click the button below to pay with CCAvenue.', 'nilesh').'</p>';
            echo $this -> generate_ccavenue_form($order);
        }
	 	/*** Thankyou Page**/
        function thankyou_page($order){
          if (!empty($this->instructions))
        	echo wpautop( wptexturize( $this->instructions ) );
		
        }		
        /**
         * Process the payment and return the result
         **/
        function process_payment($order_id){
            $order = new WC_Order($order_id);
			update_post_meta($order_id,'_post_data',$_POST);
			return array('result' => 'success', 'redirect' => $order->get_checkout_payment_url( true ));
        }
        /**
         * Check for valid CCAvenue server callback
         **/
        function check_ccavenue_response(){
            global $woocommerce;

            $msg['class']   = 'error';
            $msg['message'] = "Thank you for shopping with us. However, the transaction has been declined.";
            
            if(isset($_REQUEST['encResp'])){

                $encResponse = $_REQUEST["encResp"];         
                $rcvdString  = nilesh_decrypt($encResponse,$this -> working_key);      
                
                $decryptValues = array();

                parse_str( $rcvdString, $decryptValues );
                $order_id_time = $decryptValues['order_id'];
                $order_id = explode('_', $decryptValues['order_id']);
                $order_id = (int)$order_id[0];

                if($order_id != ''){
                    try{
                        $order = new WC_Order($order_id);
                        $order_status = $decryptValues['order_status'];
                        $transauthorised = false;
                        if($order -> status !=='completed'){
                            if($order_status=="Success")
                            {
                                $transauthorised = true;
                                $msg['message'] = "Thank you for shopping with us. Your account has been charged and your transaction is successful. We will be shipping your order to you soon.";
                                $msg['class'] = 'success';
                                if($order -> status != 'processing'){
                                    $order -> payment_complete();
                                    $order -> add_order_note('CCAvenue payment successful<br/>Bank Ref Number: '.$decryptValues['bank_ref_no']);
                                    $woocommerce -> cart -> empty_cart();

                                }

                            }
                            else if($order_status==="Aborted")
                            {
								$admin_email = get_option('admin_email');
                                $msg['message'] = 'Oh! Something went wrong. Payment was cancelled. Have any questions? Please email to <a href="mailto:'.$admin_email.'">'.$admin_email.'</a>';
                                $msg['class'] = 'error';

                            }
                            else if($order_status==="Failure")
                            {
                             $msg['class'] = 'error';
                             $msg['message'] = "Thank you for shopping with us. However, the transaction has been declined.";
                         }
                         else
                         {
                           $msg['class'] = 'error';
                           $msg['message'] = "Thank you for shopping with us. However, the transaction has been declined.";
						}

                       if($transauthorised==false){
                        $order -> update_status('failed');
                        $order -> add_order_note('Failed');
                        $order -> add_order_note($this->msg['message']);
                    }

                }
            }catch(Exception $e){

                $msg['class'] = 'error';
                $msg['message'] = "Thank you for shopping with us. However, the transaction has been declined.";

            }

        }

    }

    if ( function_exists( 'wc_add_notice' ) )
    {
        wc_add_notice( $msg['message'], $msg['class'] );

    }
    else 
    {
        if($msg['class']=='success'){
            $woocommerce->add_message( $msg['message']);
        }else{
            $woocommerce->add_error( $msg['message'] );

        }
        $woocommerce->set_messages();
    }
	//$redirect_url = get_permalink(woocommerce_get_page_id('myaccount'));
	$redirect_url = $this->get_return_url( $order );
    wp_redirect( $redirect_url );
    exit;

}
       /*
        //Removed For WooCommerce 2.0
       function showMessage($content){
            return '<div class="box '.$this -> msg['class'].'-box">'.$this -> msg['message'].'</div>'.$content;
        }*/
		/*currency convertor API*/
		function currency_convert($currency_from,$currency_to,$currency_input)
		{
			if ($currency_from != $currency_to)
			{
				$from_Currency = urlencode($currency_from);
				$to_Currency = urlencode($currency_to);
				$variable=$from_Currency."_".$to_Currency;
				$get = file_get_contents("https://free.currconv.com/api/v7/convert?q=".$variable."&compact=ultra&apiKey=7bedb773b0b9f2362607");
				$get = json_decode($get);
				$converted_currency = (isset($get->$variable) ? ($get->$variable*$currency_input) : $currency_input);
				return $converted_currency;
			}
			else
			{
				return $currency_input;
			}
		}
        /**
         * Generate CCAvenue button link
         **/
        public function generate_ccavenue_form($order_id){
            global $woocommerce;
            $order = new WC_Order($order_id);
            $order_id = $order_id.'_'.date("ymds");
			
			$post_data = get_post_meta($order_id,'_post_data',true);
			update_post_meta($order_id,'_post_data',array());
			
			if($order -> billing_address_1 && $order -> billing_country && $order -> billing_state && $order -> billing_city && $order -> billing_postcode)
			{	
				$country = wc()->countries -> countries [$order -> billing_country];
				$state = $order -> billing_state;
				$city = $order -> billing_city;
				$zip = $order -> billing_postcode;
				$phone = $order->billing_phone;
				$billing_address_1 = trim($order -> billing_address_1, ',');
			}else{
				$billing_address_1 = $this->default_add1;
				$country = $this->default_country;
				$state = $this->default_state;
				$city = $this->default_city;
				$zip = $this->default_zip;
				$phone = $this->default_phone;
			}
			
			
			$the_currency = get_woocommerce_currency();
			$the_order_total = $order->order_total;
			if($this->enable_currency_conversion=='yes')
			{
				$the_order_total = $this->currency_convert($the_currency, 'INR', $the_order_total);	
				$the_display_msg = "<small> $the_currency has been converted to equivalent amount in INR for faster payment processing.</small><br />";		
			}
			//$the_currency = 'INR';
			$ccavenue_args = array(
                'merchant_id'      => $this -> merchant_id,
                'amount'           => $the_order_total,
                'order_id'         => $order_id,
                'redirect_url'     => $this->notify_url,
                'cancel_url'       => $this->notify_url,
                'billing_name'     => $order -> billing_first_name .' '. $order -> billing_last_name,
                'billing_address'  => $billing_address_1,
                'billing_country'  => $country,
                'billing_state'    => $state,
                'billing_city'     => $city,
                'billing_zip'      => $zip,
                'billing_tel'      => $phone,
                'billing_email'    => $order -> billing_email,
                'delivery_name'    => $order -> shipping_first_name .' '. $order -> shipping_last_name,
                'delivery_address' => $order -> shipping_address_1,
                'delivery_country' => $order -> shipping_country,
                'delivery_state'   => $order -> shipping_state,
                'delivery_tel'     => '',
                'delivery_city'    => $order -> shipping_city,
                'delivery_zip'     => $order -> shipping_postcode,
                'language'         => 'EN',
                'currency'         => $the_currency,
				
				'payment_option'	=> $post_data['payment_option'],
				'card_type'		 	=> $post_data['card_type'],
				'card_name' 		=> $post_data['card_name'],
				'data_accept' 		=> $post_data['data_accept'],
				'card_number' 		=> $post_data['card_number'],
				'expiry_month' 		=> $post_data['expiry_month'],
				'expiry_year' 		=> $post_data['expiry_year'],
				'cvv_number' 		=> $post_data['cvv_number'],
				'issuing_bank' 		=> $post_data['issuing_bank'],
                );
			/*-------------------------------*/
			if($this -> iframemode=='yes') //Iframe mode
			{
				$ccavenue_args['integration_type'] = 'iframe_normal';
			}
			/*-------------------------------*/
			foreach($ccavenue_args as $param => $value) {
			 $paramsJoined[] = "$param=$value";
			}
			$merchant_data   = implode('&', $paramsJoined);
			//echo $merchant_data;
			$encrypted_data = nilesh_encrypt($merchant_data, $this -> working_key);

			$form = '';
			if($this -> iframemode=='yes') //Iframe mode
			{
				$production_url = $this -> liveurl.'&encRequest='.$encrypted_data.'&access_code='.$this->access_code;
				
				$form .= $the_display_msg.'<iframe src="'.$production_url.'" id="paymentFrame" name="paymentFrame"  height="800" width="600" frameborder="0" scrolling="No" ></iframe>
				
				<script type="text/javascript">
					jQuery(document).ready(function(){
						 window.addEventListener(\'message\', function(e) {
							 jQuery("#paymentFrame").css("height",e.data[\'newHeight\']+\'px\'); 	 
						 }, false);
						
					});
				</script>';
			}else{ //redirect to CCAvenue site
				wc_enqueue_js( '
					$.blockUI({
						message: "' . esc_js( __( 'Thank you for your order. We are now redirecting you to CcAvenue to make payment.', 'woocommerce' ) ) . '",
						baseZ: 99999,
						overlayCSS:
						{
							background: "#fff",
							opacity: 0.6
						},
						css: {
							padding:        "20px",
							zindex:         "9999999",
							textAlign:      "center",
							color:          "#555",
							border:         "3px solid #aaa",
							backgroundColor:"#fff",
							cursor:         "wait",
							lineHeight:     "24px",
						}
					});
				jQuery("#submit_ccavenue_payment_form").click();
				' );
				$targetto = 'target="_top"';				
				//===================================
				$ccavenue_args_array   = array();
				$ccavenue_args_array[] = "<input type='hidden' name='encRequest' value='$encrypted_data'/>";
				$ccavenue_args_array[] = "<input type='hidden' name='access_code' value='{$this->access_code}'/>";	
				
				$form .= '<form action="' . esc_url( $this -> liveurl ) . '" method="post" id="ccavenue_payment_form"  '.$targetto.'>
				' . implode( '', $ccavenue_args_array ) . '
				<!-- Button Fallback -->
				<div class="payment_buttons">
				<input type="submit" class="button alt" id="submit_ccavenue_payment_form" value="' . __( 'Pay via CCAvenue', 'woocommerce' ) . '" /> <a class="button cancel" href="' . esc_url( $order->get_cancel_order_url() ) . '">' . __( 'Cancel order &amp; restore cart', 'woocommerce' ) . '</a>
				</div>
				<script type="text/javascript">
				jQuery(".payment_buttons").hide();
				</script>
				</form>';
				}
			return $form;
}

// get all pages
function get_pages($title = false, $indent = true) {
    $wp_pages = get_pages('sort_column=menu_order');
    $page_list = array();
    if ($title) $page_list[] = $title;
    foreach ($wp_pages as $page) {
        $prefix = '';
                // show indented child pages?
        if ($indent) {
            $has_parent = $page->post_parent;
            while($has_parent) {
                $prefix .=  ' - ';
                $next_page = get_page($has_parent);
                $has_parent = $next_page->post_parent;
            }
        }
                // add to page list array array
        $page_list[$page->ID] = $prefix . $page->post_title;
    }
    return $page_list;
}

}

    /**
     * Add the Gateway to WooCommerce
     **/
    function woocommerce_add_nilesh_ccave_gateway($methods) {
        $methods[] = 'WC_Nilesh_Ccave';
		
        return $methods;
    }

    add_filter('woocommerce_payment_gateways', 'woocommerce_add_nilesh_ccave_gateway' );
}

/*
ccavenue functions
 */
/**
 * Encrypts with a bit more complexity
 *
 * @since 1.1.2
 */
function nilesh_encrypt($plainText,$key)
{
	$encryptionMethod = "AES-128-CBC";
	$secretKey = nilesh_hextobin(md5($key));
	$initVector = pack("C*", 0x00, 0x01, 0x02, 0x03, 0x04, 0x05, 0x06, 0x07, 0x08, 0x09, 0x0a, 0x0b, 0x0c, 0x0d, 0x0e, 0x0f);
	$encryptedText = openssl_encrypt($plainText, $encryptionMethod, $secretKey, OPENSSL_RAW_DATA, $initVector);
	return bin2hex($encryptedText);
}

function nilesh_decrypt($encryptedText,$key)
{
	$encryptionMethod     = "AES-128-CBC";
	$secretKey         = nilesh_hextobin(md5($key));
	$initVector         =  pack("C*", 0x00, 0x01, 0x02, 0x03, 0x04, 0x05, 0x06, 0x07, 0x08, 0x09, 0x0a, 0x0b, 0x0c, 0x0d, 0x0e, 0x0f);
	$encryptedText      = nilesh_hextobin($encryptedText);
	$decryptedText         =  openssl_decrypt($encryptedText, $encryptionMethod, $secretKey, OPENSSL_RAW_DATA, $initVector);
	return $decryptedText;
}

function nilesh_pkcs5_pad ($plainText, $blockSize)
{
	$pad = $blockSize - (strlen($plainText) % $blockSize);
	return $plainText . str_repeat(chr($pad), $pad);
}

function nilesh_hextobin($hexString) 
{ 
	$length = strlen($hexString); 
	$binString="";   
	$count=0; 
	while($count<$length) 
	{       
		$subString =substr($hexString,$count,2);           
		$packedString = pack("H*",$subString); 
		if ($count==0)
		{
			$binString=$packedString;
		} 
			
		else 
		{
			$binString.=$packedString;
		} 
			
		$count+=2; 
	} 
	return $binString; 
}
   
function nilesh_debug($what){
    echo '<pre>';
    print_r($what);
    echo '</pre>';
}
?>