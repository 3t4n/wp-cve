<?php

//Get post_id by cf7rzp_order_id
function get_cf7rzp_payments_post_id($cf7rzp_order_id){
    global $wpdb;

    $query = "select post_id from $wpdb->postmeta where meta_key='cf7rzp_order_id' AND meta_value = '$cf7rzp_order_id'";
    $results = $wpdb->get_results( $query, ARRAY_A);
    $post_id = (int)$results[0]['post_id'];

    return $post_id;
}

//Get cf7_id by cf7rzp_order_id
function cf7rzp_get_cf7_id($cf7rzp_order_id){
    $post_id = get_cf7rzp_payments_post_id($cf7rzp_order_id);
    $cf7_id = (int)get_post_meta($post_id, 'cf7_id', true);

    return $cf7_id;
}

//Update post status by cf7rzp_order_id
function update_cf7rzp_payments_post_status($cf7rzp_order_id, $status, $msg=""){
    $post_id = get_cf7rzp_payments_post_id($cf7rzp_order_id);
    if($status === "success"){
        $status = "cf7rzp_success";
    }
    else{     
        $status = "cf7rzp_failure";
        $error_msg = $msg;
        update_post_meta($post_id, 'failure_reason', $error_msg);
    }

    wp_update_post(array(
        'ID'			=> $post_id,
        'post_status'	=> $status
    ));
}

/*function get_cf7rzp_plugin_path(){
    return WP_PLUGIN_DIR."/".CF7RZP_FOLDER_NAME."/";
}

function get_cf7rzp_plugin_url(){
    return plugin_dir_url( __DIR__ );
}*/

/*function get_post_details($post_id){
    return $post_id;
}*/


