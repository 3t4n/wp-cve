<?php
if (!defined('ABSPATH')) exit; // Exit if accessed directly

require(WP_CONTENT_DIR.'/plugins/'.CF7RZP_DIR_NAME.'/razorpay-php/Razorpay.php');
use Razorpay\Api\Api;
use Razorpay\Api\Errors\SignatureVerificationError;


function is_cf7rzp_activated(){
    $cf7_id = sanitize_text_field($_POST['cf7_id']);
    $is_active = get_post_meta($cf7_id, "_cf7rzp_activate", true);

    $options = get_option('cf7rzp_options');
    //$api_key = $options['rzp_key_id']; 
    //$api_secret = $options['rzp_key_secret'];

    $api_key = $options['rzp_key_id']; 
    $api_secret = $options['rzp_key_secret'];
    $api = new Api($api_key, $api_secret);

    if($is_active == "1"){
        $res = ['success' => true];
        try{
            $api->customer->all();
        }
        catch(Exception $e){
            $error = $e->getMessage();
            $res = ['success' => false, 'error' => $error];
        }    
    }
    else{    
        $res = ['success' => false, 'error' => 'in_active'];
    }    
    echo json_encode($res);
    //var_dump($is_active);
    wp_die();
}
add_action( 'wp_ajax_nopriv_is_cf7rzp_activated', 'is_cf7rzp_activated' );
add_action( 'wp_ajax_is_cf7rzp_activated', 'is_cf7rzp_activated' );


function cf7rzp_create_order() {
    $success = true;
    $cf7_id = sanitize_text_field($_POST["cf7_id"]);
    $form_data = ["post" => $_POST, "files" => $_FILES];

    $cf7rzp_item_id = get_post_meta($cf7_id, "_cf7rzp_item_id", true);
    $cf7rzp_item_name = get_post_meta($cf7_id, "_cf7rzp_item_name", true);
    $cf7rzp_item_price = get_post_meta($cf7_id, "_cf7rzp_item_price", true);

    if( function_exists('cf7rzppa_frontend_item_price') ) {
        $item_price_data = apply_filters("cf7rzp_frontend_item_price", $cf7_id, $form_data);
        $cf7rzp_item_price = $item_price_data['price'];
        $cf7rzp_item_price_type = $item_price_data['type'];
    }    

    $amount = $cf7rzp_item_price*100;

    $options = get_option('cf7rzp_options');
    $rzp_mode = "";
    if($options['mode'] == 1)
        $rzp_mode = "Test";
    else        
        $rzp_mode = "Live";
        
    $rzp_cmp_name = (!empty($options['rzp_cmp_name']))? $options['rzp_cmp_name'] : null;
    /*$rzp_cmp_logo = (!empty($options['rzp_cmp_logo']))? $options['rzp_cmp_logo'] : null;*/   
    
    $api_key = $options['rzp_key_id']; 
    $api_secret = $options['rzp_key_secret'];

    $api = new Api($api_key, $api_secret);

    // Insert custom post type - cf7rzp_payments post details
    $post_id = wp_insert_post(array (
        'post_type' => 'cf7rzp_payments',
        'post_title' => '',
        'post_content' => '',
        'post_status' => 'cf7rzp_pending',
        'comment_status' => 'closed',   
        'ping_status' => 'closed',      
     ));

    // Generate cf7rzp order id 
    $cf7rzp_order_id = 'cf7rzp_'.$post_id;

    if( function_exists('cf7rzppa_admin_order_id') )
        $cf7rzp_order_id = apply_filters("cf7rzp_admin_order_id", $cf7rzp_order_id, $post_id, $cf7_id);

    // Create Razorpay order
    $orderData = [
        'receipt'         => $cf7rzp_order_id,
        'amount'          => $amount, // 39900 rupees in paise
        'currency'        => 'INR'
    ];

    /*try{
        $razorpayOrder = $api->order->create($orderData);
    }
    catch(Exception $e){
        $success = false;
        $error = $e->getMessage();
    }*/

    $razorpayOrder = $api->order->create($orderData);

    $razorpayOrderId = $razorpayOrder->id;

    // Insert custom post type - cf7rzp_payments meta details    
    if ($post_id) {

        $update_title = array(
            'ID' => $post_id,
            'post_title' => $cf7rzp_order_id
           );
           
        wp_update_post( $update_title );

        add_post_meta($post_id, 'cf7_id', $cf7_id);
        add_post_meta($post_id, 'gateway', 'razorpay');
        add_post_meta($post_id, 'mode', $rzp_mode);
        add_post_meta($post_id, 'item_id', $cf7rzp_item_id);
        add_post_meta($post_id, 'item_name', $cf7rzp_item_name);
        add_post_meta($post_id, 'item_price', $cf7rzp_item_price);
        add_post_meta($post_id, 'cf7rzp_order_id', $cf7rzp_order_id);
        add_post_meta($post_id, 'rzp_order_id', $razorpayOrderId);
        add_post_meta($post_id, 'rzp_payment_id', '');

        if( function_exists('cf7rzppa_frontend_add_payments_post_meta') )
            do_action("cf7rzp_frontend_add_payments_post_meta", $post_id, ['cf7rzp_item_price_type' => $cf7rzp_item_price_type, 'form_data' => $form_data, 'cf7_id' => $cf7_id]);

    }

    $data = [
        "key"               => $api_key,
        "amount"            => $amount,
        "name"              => $rzp_cmp_name,
        "description"       => $cf7rzp_item_name,
        /*"image"             => $rzp_cmp_logo,*/
        "notes"             => [
            "cf7rzp_order_id"   => $cf7rzp_order_id,
            "item_id"           => $cf7rzp_item_id,
            "item_name"         => $cf7rzp_item_name,
        ],
        "order_id"          => $razorpayOrderId,
    ];

    /*if($success)
        $res = ['success' => $success, 'data' => $data];
    else
    {
        update_cf7rzp_payments_post_status($cf7rzp_order_id, 'failure', $error);
        $res = ['success' => $success, 'error' => $error];    
    }*/    
    echo json_encode($data);
        
    wp_die();
}
add_action( 'wp_ajax_nopriv_cf7rzp_create_order', 'cf7rzp_create_order' );
add_action( 'wp_ajax_cf7rzp_create_order', 'cf7rzp_create_order' );


function cf7rzp_verify_payment(){

    $cf7rzp_order_id = sanitize_text_field($_POST['cf7rzp_order_id']);
    $rzp_payment_id = sanitize_text_field($_POST['rzp_payment_id']);
    $rzp_signature = sanitize_text_field($_POST['rzp_signature']);
    
    //$rzp_order_id = get_rzp_order_id($cf7rzp_order_id);
    $post_id = get_cf7rzp_payments_post_id($cf7rzp_order_id);
    $rzp_order_id = get_post_meta($post_id, 'rzp_order_id', true);

    $success = true;

    $error = "Payment Failed";

    if (empty($rzp_payment_id) === false)
    {
        $options = get_option('cf7rzp_options');
        $api_key = $options['rzp_key_id']; 
        $api_secret = $options['rzp_key_secret'];
        $api = new Api($api_key, $api_secret);

        update_post_meta($post_id, 'rzp_payment_id', $rzp_payment_id);

        try
        {
            // Please note that the razorpay order ID must
            // come from a trusted source (session here, but
            // could be database or something else)
            $attributes = array(
                'razorpay_order_id' => $rzp_order_id,
                'razorpay_payment_id' => $rzp_payment_id,
                'razorpay_signature' => $rzp_signature
            );

            $api->utility->verifyPaymentSignature($attributes);
        }
        catch(SignatureVerificationError $e)
        {
            $success = false;
            $error = $e->getMessage();
        }
    }

    if ($success === true)
        $data = ['success' => $success];
    else
        $data = ['success' => $success, 'error' => $error];

    echo json_encode($data);

    wp_die();
}
add_action( 'wp_ajax_nopriv_cf7rzp_verify_payment', 'cf7rzp_verify_payment' );
add_action( 'wp_ajax_cf7rzp_verify_payment', 'cf7rzp_verify_payment' );


function cf7rzp_update_payment_status(){
    $cf7rzp_order_id = sanitize_text_field($_POST['cf7rzp_order_id']);
    $status = sanitize_text_field($_POST['status']);
    $msg = sanitize_text_field($_POST['msg']);
    
    update_cf7rzp_payments_post_status($cf7rzp_order_id, $status, $msg);

    if( function_exists('cf7rzppa_frontend_session') )
        do_action("cf7rzp_frontend_session", $cf7rzp_order_id);    

    $options = get_option('cf7rzp_options');
    $return_url = $options['return_url'];

    if($status == "success") {
        if( function_exists('cf7rzppa_admin_return_url') )
            $return_url = apply_filters("cf7rzp_admin_return_url", $return_url, $cf7rzp_order_id);

        $res = ['success' => true, 'return_url' => $return_url];
    }    
    else
        $res = ['success' => false, 'error' => $msg];        

    echo json_encode($res);

    wp_die();
}
add_action( 'wp_ajax_nopriv_cf7rzp_update_payment_status', 'cf7rzp_update_payment_status' );
add_action( 'wp_ajax_cf7rzp_update_payment_status', 'cf7rzp_update_payment_status' );


function cf7rzp_prefix_footer_code() {
    $loader_img = CF7RZP_DIR_URL.'assets/img/loader-cod-trans.gif';
    //echo $loader_img;
    echo "<div class='cf7rzp-loader'>
    <img src='".esc_url($loader_img)."' alt='loader'/>
    </div>";
}
add_action( 'wp_footer', 'cf7rzp_prefix_footer_code' );
