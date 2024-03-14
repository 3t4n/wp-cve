<?php

function sendle_shipping_ad_notice(){

    if(isset($_GET["sendle_dismiss"])){
	if($_GET["sendle_dismiss"] == 1){
		setcookie("sendle_dismiss",1, time()+ (30 * (60*60*24)) );
		$_COOKIE["sendle_dismiss"] = 1;
 
	}

	if(!isset($_COOKIE["sendle_dismiss"])) {

		printf( '<div style="border-left: 4px solid skyblue;padding: 1px 12px;margin: 15px 15px 2px 0px;box-shadow: 0 1px 1px 0 rgba(0,0,0,.1);background: #fff;"><p style="margin: 5px 0px;font-weight: bold;font-size: 11pt;">Need A Wordpress Developer ? Website Maintenance ? Security Services ? Have a issue ? <a href="http://www.softwarehtec.com/contact-us/" target="_blank">Contact Us</a><a href="'.get_admin_url( ).'?sendle_dismiss=1" style="float:right">X</a></p></div>'); 
	}
    }
}

function sendle_shipping_global_notice() {
	if(!is_sendle_widget_enable()){
		$class = 'notice notice-error';
 
		printf( '<div class="%1$s"><p>To Enable Sendle Tracking Widget / Shortcode, Have to fill API info <a href="admin.php?page=sendle_global">Here</a></p></div>', esc_attr( $class )); 
	}
}
add_action( 'admin_notices', 'sendle_shipping_global_notice' );
add_action( 'admin_notices', 'sendle_shipping_ad_notice' );

function is_sendle_widget_enable(){

	$api_mode = get_option('sendle_shipping_api_mode');
	$api_id = get_option('sendle_shipping_api_id');
	$api_key= get_option('sendle_shipping_api_key');
    
	if(!empty($api_mode) && !empty($api_id) && !empty($api_key) ){
		return true;
	}

	return false;
}


add_action('admin_menu', 'sendle_shipping_global_submenu');

function sendle_shipping_global_submenu() {
	add_submenu_page( 'woocommerce', 'Sendle Tracking Setting', 'Sendle Tracking Setting', 'manage_options', 'sendle_global', 'sendle_global_setting_page' );
	add_action( 'admin_init', 'register_sendle_shipping_global_settings' );
}


function register_sendle_shipping_global_settings() {

	register_setting( 'sendle-shipping-global-group', 'sendle_shipping_api_mode' );
	register_setting( 'sendle-shipping-global-group', 'sendle_shipping_api_debug' );
	register_setting( 'sendle-shipping-global-group', 'sendle_shipping_api_id' );
	register_setting( 'sendle-shipping-global-group', 'sendle_shipping_api_key' );

}

function sendle_global_setting_page() {

	$api_mode = get_option('sendle_shipping_api_mode');
	$api_id = get_option('sendle_shipping_api_id');
	$api_key= get_option('sendle_shipping_api_key');
	$api_debug= get_option('sendle_shipping_api_debug');
?>
<div class="wrap">
<h1>Sendle Shipping Setting For Tracking Widget / ShortCode</h1>
<p>This API info will only be used for sendle tracking widget/shortcode and will not overwrite the standard API data from Woocommerce Sendle Shipping method</p>

<form method="post" action="options.php">
    <?php settings_fields( 'sendle-shipping-global-group' ); ?>
    <?php do_settings_sections( 'sendle-shipping-global-group' ); ?>
    <table class="form-table">
        <tr valign="top">
        <th scope="row">API Mode</th>
        <td>
        <select  name="sendle_shipping_api_mode">
        <option <?php if($api_mode == "sandbox"){ echo "selected"; } ?> value="sandbox">Sandbox</option>
        <option <?php if($api_mode == "live"){ echo "selected"; } ?> value="live">Live</option>
        </select>
        </td>
        </tr>
        <tr valign="top">
        <th scope="row">Debug</th>
        <td>
        <select  name="sendle_shipping_api_debug">
        <option <?php if($api_debug == "yes"){ echo "selected"; } ?> value="yes">Yes</option>
        <option <?php if($api_debug == "no"){ echo "selected"; } ?> value="no">No</option>
        </select>
        </td>
        </tr>
        <tr valign="top">
        <th scope="row">API Id</th>
        <td><input type="text" name="sendle_shipping_api_id" value="<?php echo esc_attr( $api_id ); ?>" /></td>
        </tr>
        
         
        <tr valign="top">
        <th scope="row">API Key</th>
        <td><input type="text" name="sendle_shipping_api_key" value="<?php echo esc_attr( $api_key); ?>" /></td>
        </tr>

    </table>
    
    <?php submit_button(); ?>

</form>
</div>
<?php 
}


add_action( 'add_meta_boxes', 'sendle_shipping_meta_boxes' );

function sendle_shipping_meta_boxes(){
    global $post;

 
    $turnoff = apply_filters( 'sendle_turnoff', 0);
 
    if($turnoff == 1){
        return false;
    }


    $order_turnoff = apply_filters( 'sendle_order_turnoff', 0);

    if($order_turnoff == 1){
        return false;
    }

    if($post->post_type != "shop_order")
        return false;



    $order = new WC_Order($post->ID);
    if($order){
        foreach ( $order->get_shipping_methods() as $shipping_method ) {
            if ( strpos( $shipping_method->get_method_id(), "sendle-zone") === 0 ) {
                //$shipping_method_id = str_replace("-0","",$shipping_method->get_method_id());
                //$shipping_option_key = "woocommerce_".$shipping_method_id."_settings";
                $shipping_option_key = $shipping_method->get_instance_id() ? "woocommerce_" . "sendle-zone" . '_' . $shipping_method->get_instance_id() . '_settings' : '';

                $formsetting = get_option($shipping_option_key);
                $formsetting['enabled'] = "yes";
                break;
            }

            if ( strpos( $shipping_method->get_method_id(), "sendle") === 0 ) {
                $formsetting = get_option("woocommerce_sendle_settings");
                break;
            }
        }

        if(is_array($formsetting) && count($formsetting) > 0 ){
            $enabled = isset( $formsetting['enabled'] ) ? $formsetting['enabled'] : 'yes';


            if($enabled == "yes" ){

                $tracking_number = get_post_meta($post->ID,"sendle_tracking_number",true);

                if(empty($tracking_number)){
                    add_meta_box( 'sendle_shipping_order', __('Sendle Shipping Order','woocommerce'), 'sendle_shipping_order', 'shop_order', 'side', 'core' );
                }
                add_meta_box( 'sendle_shipping_label', __('Sendle Shipping Label','woocommerce'), 'sendle_shipping_label', 'shop_order', 'side', 'core' );
            }
        }
    }
}

function sendle_shipping_order(){

    $turnoff = apply_filters( 'sendle_turnoff', 0);

    if($turnoff == 1){
        return false;
    }

    $order_turnoff = apply_filters( 'sendle_order_turnoff', 0);

    if($order_turnoff == 1){
        return false;
    }

    global $post;

    $order = new WC_Order($post->ID);
    if($order){

        $tracking_number = get_post_meta($post->ID,"sendle_tracking_number",true);

        if(empty($tracking_number)){

            if($order->shipping_country != "AU"){

    ?>
                <p><small>A collection of details about the parcel contents</small></p>
                <p><input type="text" id="sendle_contents" value="" placeholder="Contents" style="width:100%;"/></p>
                <p><small>The country in which the goods where manufactured.</small></p>
                <p><?php
                $countries_obj   = new WC_Countries();
                $countries   = $countries_obj->__get('countries');
                ?>
                <select id="sendle_country_of_origin">
                <option value="">Country Of Origin</option>
                <?php
                foreach($countries as $k => $v){
                    echo "<option value='".$k."'>".$v."</option>";
                }
                ?>
                </select>
                </p>
            <?php
            }
            ?>
            <button type="button" class="button creating-sendle-order">Creating Sendle Order</button>
            <script type="text/javascript" >
                jQuery(document).ready(function($) {
                    jQuery(".creating-sendle-order").click(function(){

                    <?php
                    if($order->shipping_country != "AU"){
                    ?>
                        var contents = jQuery("#sendle_contents").val();
                        var country_of_origin = jQuery("#sendle_country_of_origin").val();

                        if(contents == "" || country_of_origin == ""){
                            alert("Please fill all info for the international order");
                        }else{
                            var data = {
                                'action': 'new_sendle_order',
                                'order': <?php echo $post->ID; ?>,
                                'contents': contents,
                                'country_of_origin' : country_of_origin
                            };

                            jQuery.post(ajaxurl, data, function(response) {
                                if(response == ""){
                                    setTimeout(function(){ location.reload(); }, 5000);
                                }else{
                                    alert(response);
                                }
                            });
                        }
                    <?php
                    }else{
                    ?>
                        var data = {
                            'action': 'new_sendle_order',
                            'order': <?php echo $post->ID; ?>
                        };
                        jQuery.post(ajaxurl, data, function(response) {
                            if(response == ""){
                                setTimeout(function(){ location.reload(); }, 5000);
                            }else{
                                alert(response);
                            }
                        });
                    <?php } ?>
                    });
                });
            </script>
        <?php
        }else{
            echo "<strong>Tracking Number: </strong>".$tracking_number;
        }
    }
}

function sendle_shipping_label(){


    $turnoff = apply_filters( 'sendle_turnoff', 0);

    if($turnoff == 1){
        return false;
    }


    $order_turnoff = apply_filters( 'sendle_order_turnoff', 0);

    if($order_turnoff == 1){
        return false;
    }

    global $post;


    $order = new WC_Order($post->ID);
    if($order){
 
        $sendle_pdf_url_0 = get_post_meta($post->ID,"sendle_pdf_url_0",true);
        $sendle_pdf_url_1 = get_post_meta($post->ID,"sendle_pdf_url_1",true);
        $tmp_pickup_date = get_post_meta($post->ID,"sendle_pickup_date",true);


        if(!empty($tmp_pickup_date) && !empty($sendle_pdf_url_0) && !empty($sendle_pdf_url_1)){

            if(!empty($tmp_pickup_date)){
                echo "<strong>Pickup Date:</strong> ".$tmp_pickup_date."<br/>";
            }
            if(!empty($sendle_pdf_url_0)){
                echo "<a href='".$sendle_pdf_url_0."' target='_blank'>Sendle Shipping Label A4</a><br/>";
            }
            if(!empty($sendle_pdf_url_1)){
                echo "<a href='".$sendle_pdf_url_1."' target='_blank'>Sendle Shipping Label Cropped</a>";
            }
            return true;
        }

        foreach ( $order->get_shipping_methods() as $shipping_method ) {
            if ( strpos( $shipping_method->get_method_id(), "sendle-zone") === 0 ) {
                //$shipping_method_id = str_replace("-0","",$shipping_method->get_method_id());
                //$shipping_option_key = "woocommerce_".$shipping_method_id."_settings";
                $shipping_option_key = $shipping_method->get_instance_id() ? "woocommerce_" . "sendle-zone" . '_' . $shipping_method->get_instance_id() . '_settings' : '';

                $formsetting = get_option($shipping_option_key);
                $formsetting['enabled'] = "yes";
                break;
            }

            if ( strpos( $shipping_method->get_method_id(), "sendle") === 0 ) {
                $formsetting = get_option("woocommerce_sendle_settings");
                break;
            }
        }


        $order_id_data = unserialize(get_post_meta($post->ID,"sendle_response_data",true));
        $order_id = $order_id_data->order_id;

 

        if(is_array($formsetting) && count($formsetting) > 0 && !empty($order_id)){
            $enabled = isset( $formsetting['enabled'] ) ? $formsetting['enabled'] : 'yes';


            $sendle_label_a4_tmp = "";

            if($enabled == "yes"){

                $response = calling_sendle_api($formsetting,array(),"/api/orders/".$order_id,"get");


                if( $formsetting["debug"] == "yes"){
                    file_put_contents(ERROR_FILE, date("y-M-D h:i:s")." legacy calculate_shipping ".serialize($response)."\n", FILE_APPEND );
                }

 
                if($response && is_object($response)){

                    $tmp_labels = $response->labels;
                    $tmp_pickup_date = $response->scheduling->pickup_date;
 
                    update_post_meta($post->ID,"sendle_pickup_date",$tmp_pickup_date);

                    if(count($tmp_labels) > 0){
                        if($tmp_labels[0]->size == "a4"){
                            $sendle_label_tmp_0 =$tmp_labels[0]->url;
                            $sendle_label_tmp_1 =$tmp_labels[1]->url;
                        }else{
                            $sendle_label_tmp_0 =$tmp_labels[1]->url;
                            $sendle_label_tmp_1 =$tmp_labels[0]->url;
                        }

                    }
 
 
                }

 
 


                if(!empty($sendle_label_tmp_0)){
                    $data = array();
                    $data["label_url"] = $sendle_label_tmp_0;

                    $response = calling_sendle_api($formsetting,$data,"labels","get");

                    if($response){
                        $filename = dirname(__FILE__)."/pdf/".$post->ID."_0.pdf";
                        file_put_contents($filename,$response );
                        $filetype = mime_content_type($filename);
                        if($filetype != "application/pdf"){
                            unlink($filename);
                        }else{
                            update_post_meta($post->ID,"sendle_pdf_url_0",plugins_url( "/pdf/".$post->ID."_0.pdf", __FILE__ ) );
                        }
                    }
                }




                if(!empty($sendle_label_tmp_1)){
                    $data = array();
                    $data["label_url"] = $sendle_label_tmp_1;

                    $response = calling_sendle_api($formsetting,$data,"labels","get");

                    if($response){
                        $filename = dirname(__FILE__)."/pdf/".$post->ID."_1.pdf";
                        file_put_contents($filename,$response );
                        $filetype = mime_content_type($filename);
                        if($filetype != "application/pdf"){
                            unlink($filename);
                        }else{
                            update_post_meta($post->ID,"sendle_pdf_url_1",plugins_url( "/pdf/".$post->ID."_1.pdf", __FILE__ ) );
                        }
                    }
                }


            }
        }

        $sendle_pdf_url_0 = get_post_meta($post->ID,"sendle_pdf_url_0",true);
        $sendle_pdf_url_1 = get_post_meta($post->ID,"sendle_pdf_url_1",true);
        $tmp_pickup_date = get_post_meta($post->ID,"sendle_pickup_date",true);
        if(!empty($tmp_pickup_date)){
            echo "<strong>Pickup Date:</strong> ".$tmp_pickup_date."<br/>";
        }
        if(!empty($sendle_pdf_url_0)){
            echo "<a href='".$sendle_pdf_url_0."' target='_blank'>Sendle Shipping Label A4</a><br/>";
        }
        if(!empty($sendle_pdf_url_1)){
            echo "<a href='".$sendle_pdf_url_1."' target='_blank'>Sendle Shipping Label Cropped</a>";
        }
        return true;
    }
}



function sendle_order_status_processing($order_id,$contents="",$country_of_origin =""){


    $turnoff = apply_filters( 'sendle_turnoff', 0);

    if($turnoff == 1){
        return false;
    }

    $order = new WC_Order($order_id);
    if($order){
        foreach ( $order->get_shipping_methods() as $shipping_method ) {
            if ( strpos( $shipping_method->get_method_id(), "sendle-zone") === 0 ) {
                //$shipping_method_id = str_replace("-0","",$shipping_method->get_method_id());
                //$shipping_option_key = "woocommerce_".$shipping_method_id."_settings";
                $shipping_option_key = $shipping_method->get_instance_id() ? "woocommerce_" . "sendle-zone" . '_' . $shipping_method->get_instance_id() . '_settings' : '';

                $formsetting = get_option($shipping_option_key);
                $formsetting['enabled'] = "yes";
                break;
            }

            if ( strpos( $shipping_method->get_method_id(), "sendle") === 0 ) {
                $formsetting = get_option("woocommerce_sendle_settings");
                break;
            }
        }
 
        $tracking_number = get_post_meta($order_id,"sendle_tracking_number",true);


        if(is_array($formsetting) && count($formsetting) > 0 && empty($tracking_number)){
            $enabled = isset( $formsetting['enabled'] ) ? $formsetting['enabled'] : 'yes';

            if($enabled == "yes" ){

                $data = array();

                if( $order->shipping_country != "AU"){
                    if(empty($contents) || empty($country_of_origin))
                        return false;

                    $data["contents"] = array();
                    $data["contents"]["description"] = $contents;
                    $data["contents"]["country_of_origin"] = $country_of_origin;
                    $data["contents"]["value"] = $order->get_subtotal();
                }


                //$data["pickup_date"] =  date("Y-m-d",strtotime('+1 Weekday', current_time('timestamp',1)));



                $order_item = $order->get_items();

                $data["description"] = apply_filters( 'sendle_content_description', $order_id,$order_item);


                $w = 0;
                $v = 0;
                foreach( $order_item as $item) {
                    $product_id = $item['product_id'];
                    //$product = wc_get_product( $product_id );
                    $product = $item->get_product(); 

                    if ( $product->has_weight() ) {
                        $w += $product->get_weight()*$item['qty'];
                    }

                    $tmp_length = wc_get_dimension($product ->get_length(), 'm');
                    $tmp_width = wc_get_dimension($product ->get_width(), 'm');
                    $tmp_height = wc_get_dimension($product ->get_height(), 'm');

                    if($tmp_length >= 1.2 || $tmp_width >= 1.2 || $tmp_height >= 1.2 )
                        return false;

                    $v = $v + ($tmp_length * $tmp_width * $tmp_height) * $item['qty'];
                }
                if($w != 0){
                    $w  = wc_get_weight( $w , 'kg' );
                    //$data["kilogram_weight"] = $w;
                    $data["weight"] = array();
                    $data["weight"]["value"] = $w;
                    $data["weight"]["units"] = "kg";

                    $data["cubic_metre_volume"] = $v;

                    $data["customer_reference"] = apply_filters( 'sendle_customer_reference', $order_id, $order_item);
                    $data["sender"] = array();
                    $data["receiver"] = array();

                    if($w  > 0.5){
                        if($v != 0){
                            if($v > 0.002){
                                $sender_instructions =  apply_filters( 'sendle_pickup_instructions', $formsetting["pickup_instructions"],$order);
                                if(!empty($sender_instructions))
                                    $data["sender"]["instructions"] = $sender_instructions;

                                $receiver_instructions =  apply_filters( 'sendle_pickup_receiver_instructions',$formsetting["receiver_instructions_default"],$order);
                                if(!empty($receiver_instructions ))
                                    $data["receiver"]["instructions"] = $receiver_instructions;
							}
                        }else{
                                $sender_instructions =  apply_filters( 'sendle_pickup_instructions', $formsetting["pickup_instructions"],$order);
                                if(!empty($sender_instructions))
                                    $data["sender"]["instructions"] = $sender_instructions;

                                $receiver_instructions =  apply_filters( 'sendle_pickup_receiver_instructions',$formsetting["receiver_instructions_default"],$order);
                                if(!empty($receiver_instructions ))
                                    $data["receiver"]["instructions"] = $receiver_instructions;

                        }
                    }

 
                    $data["sender"]["contact"] = array();
                    $data["sender"]["contact"]["name"] = $formsetting["contact_name"];
                    $data["sender"]["contact"]["email"] = $formsetting["contact_email"];
                    $data["sender"]["contact"]["phone"] = $formsetting["contact_phone"];
                    $data["sender"]["address"] = array();
                    $data["sender"]["address"]["address_line1"] = $formsetting["pickup_address_line1"];
                    if(!empty($formsetting["sender_address_line2"]))
                        $data["sender"]["address"]["address_line2"] = $formsetting["pickup_address_line2"];

                    $data["sender"]["address"]["suburb"] = $formsetting["pickup_suburb"];
                    $data["sender"]["address"]["postcode"] = $formsetting["pickup_postcode"];
                    $data["sender"]["address"]["state_name"] = $formsetting["pickup_state_name"];
                    $data["sender"]["address"]["country"] =  "Australia";


                    $data["receiver"]["contact"] = array();
                    $data["receiver"]["contact"]["name"] =$order->shipping_first_name." ".$order->shipping_last_name;
                    $data["receiver"]["contact"]["email"] = $order->billing_email;
                    $data["receiver"]["address"] =  array();
                    $data["receiver"]["address"]["address_line1"] =  $order->shipping_address_1;
                    if(!empty($order->shipping_address_2)){
                        $data["receiver"]["address"]["address_line2"] =  $order->shipping_address_2;
                    }
                    $data["receiver"]["address"]["suburb"] =  $order->shipping_city;
                    if( $order->shipping_country == "AU")
                    $data["receiver"]["address"]["state_name"] = $order->shipping_state;
                    $data["receiver"]["address"]["postcode"] = $order->shipping_postcode;

                    $countries_obj = new WC_Countries();
                    $countries_array = $countries_obj->get_countries();


                    $data["receiver"]["address"]["country"] = $countries_array[$order->shipping_country];
 
 
                    $response = calling_sendle_api($formsetting,$data,"/api/orders","post");
 

                    if($response && is_object($response)){


                        update_post_meta($order_id,"sendle_tracking_number",$response->sendle_reference);
                        update_post_meta($order_id,"sendle_response_data",serialize($response));
                        $order->add_order_note( "Shipping Tracking Number: ".$response->sendle_reference );

                        return true;
                    }else{
                        return false;
                    }
                }
            }
        }
    }
    return false;
}
//add_action('woocommerce_order_status_processing', 'sendle_order_status_processing'  );


add_action( 'wp_ajax_new_sendle_order', 'new_sendle_order' );

function new_sendle_order() {


    $turnoff = apply_filters( 'sendle_turnoff', 0);

    if($turnoff == 1){
        return false;
    }

    global $wpdb;
    $order_id = intval($_POST["order"]);
    $contents = htmlentities($_POST["contents"]);
    $country_of_origin = htmlentities($_POST["country_of_origin"]);


    $response = sendle_order_status_processing($order_id,$contents,$country_of_origin);
 
    if(empty($response)){
        echo "There was an error to create a Sendle order";
    }
    wp_die(); 
}
