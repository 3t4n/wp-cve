<?php
 
if ( ! defined( 'WPINC' ) ) {
    die;
}

if ( !in_array( 'woocommerce/woocommerce.php', apply_filters( 'active_plugins', get_option( 'active_plugins' ) ) ) ) {
    return false;
}
require_once("sendle-widget.php");

function sendle_shipping_zone_method() {
    if ( ! class_exists( 'Sendle_Shipping_Zone_Method' ) ) {
        class Sendle_Shipping_Zone_Method extends WC_Shipping_Method {
            var $api_id,$api_key,$pickup_suburb,$pickup_postcode,$plan_name,$mode,$apiurl,$debug,$extra_cost;

            public function __construct( $instance_id = 0 ) {

		        $this->instance_id 			 = absint( $instance_id );
                $this->id                 = 'sendle-zone'; 
                $this->method_title       = __( 'Sendle', 'softwarehtec' );  

                if(!is_callable('curl_init')){
                    $this->method_description = __( '<span style="color:red">To use Sendle Shipping Method, you have to enabled CURL</span>', 'softwarehtec' ); 
                }else{

                    $this->method_description = __( 'Sendle delivers parcels door-to-door across Australia at flat rates cheaper than post. Send 25kg from $5.98. Save time with fast ordering & easy tracking.<br/><strong style="color:red">Currency Of Shipping Price Is In Australian Dollar</strong><br/><strong style="color:black">Support URL: <a href="http://www.softwarehtec.com/contact-us/" target="_blank">http://www.softwarehtec.com/contact-us/</a></strong><br/><strong style="color:black">Plugin URL: <a href="http://www.softwarehtec.com/project/woocommerce-sendle-shipping-method/" target="_blank">http://www.softwarehtec.com/project/woocommerce-sendle-shipping-method/</a></strong>', 'softwarehtec' );  
                }

		$this->supports              = array(
			'shipping-zones',
			'instance-settings',
			'instance-settings-modal');

                $this->init();
 
                //$this->enabled = isset( $this->settings['enabled'] ) ? $this->settings['enabled'] : 'yes';

 
                $title = $this->get_option('title');
                $this->title = !empty( $title ) ? $title : __( 'Sendle Shipping', 'softwarehtec' );


                $this->mode = $this->get_option('mode');
                if($this->mode == "live"){
                    $this->apiurl = "https://api.sendle.com";
                }else{
                    $this->apiurl = "https://sandbox.sendle.com";
                }
		        $this->tax_status   = $this->get_option( 'tax_status' );
                $this->api_id = $this->get_option('api_id');
                $this->debug = $this->get_option('debug');
                $this->api_key = $this->get_option('api_key');
                $this->pickup_suburb = $this->get_option('pickup_suburb');
                $this->pickup_postcode = $this->get_option('pickup_postcode');
                $this->plan_name = $this->get_option('plan_name');
                $this->extra_cost = $this->get_option('extra_cost');
                $this->pobox_detection = $this->get_option('pobox_detection');

                $this->plan_easy_label = $this->get_option('plan_easy_label');
                $this->plan_premium_label = $this->get_option('plan_premium_label');
                $this->plan_pro_label = $this->get_option('plan_pro_label');
                $this->min_weight = $this->get_option('min_weight');
                $this->double_fee= $this->get_option('double_fee');

  
            }

            function init() {
                $this->init_form_fields(); 
                $this->init_settings(); 


                add_action( 'woocommerce_update_options_shipping_' . $this->id, array( $this, 'process_admin_options' ) );
            }
            function init_form_fields() { 
 
                if(!is_callable('curl_init')){
                    $this->instance_form_fields = array(
                    'mode' => array(
                        'title' => __( 'Mode', 'softwarehtec' ),
                        'type' => 'select',
                        'description' => __( '<span style="color:red">To use Sendle Shipping Method, you have to enabled CURL</span>', 'softwarehtec' ),
                        'default' => 'sandbox',
                        'options' => array("sandbox"=>"Sandbox"),
                    )
                    );
                    return ;
                }

 
		$this->instance_form_fields = array(
 
                    'mode' => array(
                        'title' => __( 'Mode', 'softwarehtec' ),
                        'type' => 'select',
                        'description' => __( '', 'softwarehtec' ),
                        'default' => 'sandbox',
                        'options' => array("sandbox"=>"Sandbox","live"=>"Live"),
                    ),
                    'debug' => array(
                        'title' => __( 'Debug', 'softwarehtec' ),
                        'type' => 'select',
                        'description' => __( '', 'softwarehtec' ),
                        'default' => 'no',
                        'options' => array("yes"=>"Yes","no"=>"No"),
                    ),
                    'title' => array(
                        'title' => __( 'Title', 'softwarehtec' ),
                        'type' => 'text',
                        'description' => __( 'Title to be display on site', 'softwarehtec' ),
                        'default' => __( 'Sendle Shipping', 'softwarehtec' )
                    ),
                    'api_id' => array(
                        'title' => __( 'Sendle ID', 'softwarehtec' ),
                        'type' => 'text',
                        'description' => __( '', 'softwarehtec' ),
                        'default' => __( '', 'softwarehtec' )
                    ),
                    'api_key' => array(
                        'title' => __( 'API Key', 'softwarehtec' ),
                        'type' => 'password',
                        'description' => __( 'Do not know what is your API key ? <a target="_blank" href="https://support.sendle.com/hc/en-us/articles/210798518-Sendle-API">Click Here</a>', 'softwarehtec' ),
                        'default' => __( '', 'softwarehtec' )
                    ),
                    'pickup_suburb' => array(
                        'title' => __( 'Pickup Suburb', 'softwarehtec' ),
                        'type' => 'text',
                        'description' => __( 'Suburb must be real and match pickup postcode.', 'softwarehtec' ),
                        'default' => __( '', 'softwarehtec' )
                    ),
                    'pickup_postcode' => array(
                        'title' => __( 'Pickup Postcode', 'softwarehtec' ),
                        'type' => 'text',
                        'description' => __( 'Four-digit post code for the pickup address.', 'softwarehtec' ),
                        'default' => __( '', 'softwarehtec' )
                    ),
                    'pickup_state_name' => array(
                        'title' => __( 'Pickup State', 'softwarehtec' ),
                        'type' => 'select',
                        'description' => __( 'Must be the pickup location’s state or territory', 'softwarehtec' ),
                        'default' => __( '', 'softwarehtec' ),
                        'options' => array("ACT"=>"ACT","NSW"=>"NSW","NT"=>"NT","QLD"=>"QLD","SA"=>"SA","TAS"=>"TAS","VIC"=>"VIC","WA"=>"WA"),
                    ),
                    'pickup_address_line1' => array(
                        'title' => __( 'Pickup Address Line 1', 'softwarehtec' ),
                        'type' => 'text',
                        'description' => __( 'The street address where the parcel will be picked up', 'softwarehtec' ),
                        'default' => __( '', 'softwarehtec' )
                    ),
                    'pickup_address_line2' => array(
                        'title' => __( 'Pickup Address Line 2', 'softwarehtec' ),
                        'type' => 'text',
                        'description' => __( 'Its optional. Second line of the street address for the pickup location.', 'softwarehtec' ),
                        'default' => __( '', 'softwarehtec' )
                    ),
                    'plan_name' => array(
                        'title' => __( 'Plan Name', 'softwarehtec' ),
                        'type' => 'select',
                        'description' => __( "Without authenticating, the API will give quotes for all publicly available plans by default. If Plan Name is specified, the API will respond with a quote for just the given plan. Current available plans are Easy, Premium, and Pro. <a href='https://www.sendle.com/pricing' target='_blank'>Details Of Plans</a> For authenticated requests, the API always returns the quote for the account's current plan and ignores Plan Name you have set", 'softwarehtec' ),
                        'default' => __( '', 'softwarehtec' ),
                        'options' => array(""=>"Default","Easy"=>"Easy","Premium"=>"Premium","Pro"=>"Pro"),
                    ),

                    'plan_easy_label' => array(
                        'title' => __( 'Label For Easy Plan', 'softwarehtec' ),
                        'type' => 'text',
                        'description' => __( 'Text of Easy Plan, Leave it as empty if would like to use defaul text', 'softwarehtec' ),
                        'default' => __( '', 'softwarehtec' )
                    ),
                    'plan_premium_label' => array(
                        'title' => __( 'Label For Premium Plan', 'softwarehtec' ),
                        'type' => 'text',
                        'description' => __( 'Text of Premium Plan, Leave it as empty if would like to use defaul text', 'softwarehtec' ),
                        'default' => __( '', 'softwarehtec' )
                    ),
                    'plan_pro_label' => array(
                        'title' => __( 'Label For Pro Plan', 'softwarehtec' ),
                        'type' => 'text',
                        'description' => __( 'Text of Pro Plan, Leave it as empty if would like to use defaul text', 'softwarehtec' ),
                        'default' => __( '', 'softwarehtec' )
                    ),
                    'tax_status' => array(
                        'title' 		=> __( 'Tax status', 'woocommerce' ),
                        'type' 			=> 'select',
                        'class'         => 'wc-enhanced-select',
                        'default' 		=> 'none',
                        'options'		=> array(
			    'taxable' 	=> __( 'Taxable', 'woocommerce' ),
			    'none' 		=> _x( 'None', 'Tax status', 'woocommerce' ),
                        ),
                    ),
                    'extra_cost' => array(
                        'title' => __( 'Extra Cost', 'softwarehtec' ),
                        'type' => 'text',
                        'description' => __( 'For packaging cost or others', 'softwarehtec' ),
                        'default' => __( '', 'softwarehtec' )
                    ),
                    'double_fee' => array(
                        'title' => __( 'Return Fee', 'softwarehtec' ),
                        'type' => 'select',
                        'description' => __( 'Add return fee as delivery price', 'softwarehtec' ),
                        'default' => __( 'no', 'softwarehtec' ),
                        'options' => array("yes"=>"Yes","no"=>"No")
                    ),
                    'pobox_detection' => array(
                        'title' => __( 'PO BOX Detection (BETA)', 'softwarehtec' ),
                        'type' => 'select',
                        'description' => __( 'Hide the Sendle Shipping Method for PO BOX address', 'softwarehtec' ),
                        'default' => __( 'no', 'softwarehtec' ),
                        'options' => array("yes"=>"Yes","no"=>"No")
                    ),
                    'delivery_instructions' => array(
                        'title' => __( 'Delivery Instructions', 'softwarehtec' ),
                        'type' => 'checkbox',
                        'description' => __( 'Short message from customer used as delivery instructions for courier.', 'softwarehtec' ),
                        'default' => 'yes'
                    ),
                    'sender_instructions' => array(
                        'title' => __( 'Sender Instructions', 'softwarehtec' ),
                        'type' => 'text',
                        'description' => __( 'Short message used as pickup instructions for courier. It must be under 255 chars, but is recommended to be under 40 chars due to label-size limitations.', 'softwarehtec' ),
                        'default' => __( '', 'softwarehtec' )
                    ),
                    'contact_name' => array(
                        'title' => __( 'Contact Name', 'softwarehtec' ),
                        'type' => 'text',
                        'description' => __( 'A collection of sender contact details. - Contact Name', 'softwarehtec' ),
                        'default' => __( '', 'softwarehtec' )
                    ),
                    'contact_email' => array(
                        'title' => __( 'Contact Email', 'softwarehtec' ),
                        'type' => 'text',
                        'description' => __( 'A collection of sender contact details. - Contact Email', 'softwarehtec' ),
                        'default' => __( '', 'softwarehtec' )
                    ),
                    'contact_phone' => array(
                        'title' => __( 'Contact Phone', 'softwarehtec' ),
                        'type' => 'text',
                        'description' => __( 'Used to coordinate pickup if the courier is outside attempting delivery. It must be a valid Australian phone number (including area code), or fully qualified international number. Examples: (02) 1234 1234, +1 519 123 1234, +61 (0)4 1234 1234.', 'softwarehtec' ),
                        'default' => __( '', 'softwarehtec' )
                    ),
                    'receiver_instructions_default' => array(
                        'title' => __( 'Default Of Receiver Instructions', 'softwarehtec' ),
                        'type' => 'text',
                        'description' => __( 'Short message used as delivery instructions for courier. It must be under 255 chars, but is recommended to be under 40 chars due to label-size limitations.', 'softwarehtec' ),
                        'default' => __( 'Give directly to Receiver', 'softwarehtec' )
                    ),
                    'min_weight' => array(
                        'title' => __( 'Minimum Weight (kg)', 'softwarehtec' ),
                        'type' => 'text',
                        'description' => __( 'If the weight of an order is lower than Minimum Weight, the script will pass Minimum Weight to instead of actual weight to Sendle.', 'softwarehtec' ),
                        'default' => __( '0', 'softwarehtec' )
                    )

                );
 
            }
            public function calculate_shipping( $package = array() ) {


                $shipping_option_key = $this->instance_id ? "woocommerce_" . "sendle-zone" . '_' . $this->instance_id . '_settings' : '';

                $formsetting = get_option($shipping_option_key);
                $formsetting['enabled'] = "yes";

                $weight = 0;
                $cost = 0;
                $volume = 0;

                $extracost = $this->extra_cost;
                $extracost = apply_filters( 'sendle_extracost', $extracost,$package);
                if(!is_numeric($extracost)){
                    $extracost = 0;
                }



                $pickup_postcode = $this->pickup_postcode;

                $pickup_postcode = apply_filters( 'sendle_pickup_postcode', $pickup_postcode,$package);

                $pickup_suburb = $this->pickup_suburb;

                $pickup_suburb = apply_filters( 'sendle_pickup_suburb', $pickup_suburb,$package);

 

                $country = $package["destination"]["country"];
 

                foreach ( $package['contents'] as $item_id => $values ) { 
                    $_product = $values['data']; 
                    $tmp_weight = $_product->get_weight();
                    if(!is_numeric($tmp_weight)){
                        $tmp_weight = 0;
                    }
                    $weight = $weight + $tmp_weight * $values['quantity']; 

                    $tmp_length = wc_get_dimension($_product->get_length(), 'm');
                    $tmp_width = wc_get_dimension($_product->get_width(), 'm');
                    $tmp_height = wc_get_dimension($_product->get_height(), 'm');
                    if($tmp_length >= 1.2 || $tmp_width >= 1.2 || $tmp_height >= 1.2 )
                        return ;
                    $volume = $volume + ($tmp_length * $tmp_width * $tmp_height) * $values['quantity'];
                }
 
                $ignore_weight = apply_filters( 'ignore_weight',0);
 
                $weight = wc_get_weight( $weight, 'kg' );
                if(($weight == 0 || $weight  > 25) && !$ignore_weight){
                    return ;
                }
                if($this->min_weight > $weight){
                    $weight = $this->min_weight;
                }
                if(!is_callable('curl_init')){
                    return ;
                }

                if(empty($pickup_suburb ) || empty($pickup_postcode)){
                    return ;
                }

                if($country == "AU"){

                    $d_suburb = urlencode($package["destination"]["city"]);
                    $d_postcode = urlencode($package["destination"]["postcode"]);

                    if($this->pobox_detection == "yes"){

                        $d_address= ($package["destination"]["address"]);
                        $d_address_2= ($package["destination"]["address_2"]);

                        $regex = '/(?:P(?:ost(?:al)?)?[\.\-\s]*(?:(?:O(?:ffice)?[\.\-\s]*)?B(?:ox|in|\b|\d)|o(?:ffice|\b)(?:[-\s]*\d)|code)|box[-\s\b]*\d)/i';

                        if(preg_match($regex , $d_address, $matches, PREG_OFFSET_CAPTURE, 0) || preg_match($regex , $d_address_2, $matches, PREG_OFFSET_CAPTURE, 0)){
                            return ;
                        }

                        $regex2 = '/(?:parcel)\s(?:locker)/i';
                        if(preg_match($regex2 , $d_address, $matches, PREG_OFFSET_CAPTURE, 0) || preg_match($regex2 , $d_address_2, $matches, PREG_OFFSET_CAPTURE, 0)){
                            return ;
                        }
                    }


                    if(empty($d_suburb ) || empty($d_postcode)){
                        return ;
                    }

                    if($volume > 0){
                        $url = "/api/quote?pickup_suburb=".urlencode($pickup_suburb)."&pickup_postcode=".urlencode($pickup_postcode)."&kilogram_weight=".$weight."&cubic_metre_volume=".$volume."&delivery_suburb=".($d_suburb)."&delivery_postcode=".($d_postcode);
                    }else{
                        $url = "/api/quote?pickup_suburb=".urlencode($pickup_suburb)."&pickup_postcode=".urlencode($pickup_postcode)."&kilogram_weight=".$weight."&delivery_suburb=".($d_suburb)."&delivery_postcode=".($d_postcode);
                    }


                }else{
                    if($volume > 0){
                        $url = "/api/quote?pickup_suburb=".urlencode($pickup_suburb)."&pickup_postcode=".urlencode($pickup_postcode)."&kilogram_weight=".$weight."&cubic_metre_volume=".$volume."&delivery_country=".($country);
                    }else{
                        $url = "/api/quote?pickup_suburb=".urlencode($pickup_suburb)."&pickup_postcode=".urlencode($pickup_postcode)."&kilogram_weight=".$weight."&delivery_country=".($country);
                    }
                }


                if(!empty($this->plan_name )){
                    $url .= "&plan_name=".$this->plan_name;
                }


 
                $args= array();

                if(!empty($this->api_key ) && !empty($this->api_id)){

                    $args["headers"] = array(
                            'Authorization' => 'Basic ' .  base64_encode( $this->api_id . ':' . $this->api_key )
                    );

                }

                $args["user-agent"] = "WooCommerce Sendle Shipping Method";
				$result = calling_sendle_api($formsetting,array(),$url,"get");


                if($this->debug == "yes"){
                    file_put_contents(ERROR_FILE, date("y-M-D h:i:s")." shipping-zone calculate_shipping ".$url."\n", FILE_APPEND );
                }


                //$response =  wp_remote_get( $url, $args );

                if($this->debug == "yes"){
                    file_put_contents(ERROR_FILE, date("y-M-D h:i:s")." shipping-zone calculate_shipping ".serialize($response)."\n", FILE_APPEND );
                }
 
                //$content = wp_remote_retrieve_body( $response );
                //$result = json_decode( $content); // show target page
 
                $quote_filter = apply_filters( 'quote_filter',$this->apiurl,$pickup_suburb,$pickup_postcode,$weight,$d_suburb,$d_postcode,$country,$args);
 
                if(is_array($quote_filter)){
                    $result = $quote_filter;
                }


                if(is_array($result)){
                    if(count($result) > 0){
                        foreach($result as $k => $r){
                            $plan_name = $r->plan_name;
                            if($plan_name == "Easy"){
                                if(!empty($this->plan_easy_label)){
                                    $plan_name = $this->plan_easy_label;
                                }
                            }
                            if($plan_name == "Premium"){
                                if(!empty($this->plan_premium_label)){
                                    $plan_name = $this->plan_premium_label;
                                }
                            }
                            if($plan_name == "Pro"){
                                if(!empty($this->plan_pro_label)){
                                    $plan_name = $this->plan_pro_label;
                                }
                            }

                            if($this->is_taxable()){
                                $final_price = $r->quote->net->amount+$extracost;

                                if($final_price < 0){
                                    $final_price = 0;
                                }
                                if($this->double_fee == "yes"){
                                    $final_price = $final_price * 2;
                                }

                                $rate = array(
                                    'id' => $this->id."_".$this->instance_id ."-".$k,
                                    'label' => $this->title." - ".$plan_name,
                                    'cost' => $final_price,
                                    'calc_tax' => 'per_order'
                                );
                            }else{
                                $final_price = $r->quote->gross->amount+$extracost;
                                if($final_price < 0){
                                    $final_price = 0;
                                }
                                if($this->double_fee == "yes"){
                                    $final_price = $final_price * 2;
                                }
                                $rate = array(
                                    'id' => $this->id."_".$this->instance_id ."-".$k,
                                    'label' => $this->title." - ".$plan_name,
                                    'cost' => $final_price,
                                    'taxes' => false
                                );
                            }

 
                            $this->add_rate( $rate );
                        }
                    }
                }
            }
        }
    }
}

add_action( 'woocommerce_shipping_init', 'sendle_shipping_zone_method' );
 
function add_sendle_shipping_zone_method( $methods ) {
    $methods["sendle-zone"] = 'Sendle_Shipping_Zone_Method';
    return $methods;
}
 
add_filter( 'woocommerce_shipping_methods', 'add_sendle_shipping_zone_method' );
add_filter( 'woocommerce_shipping_calculator_enable_city','__return_true'  );


