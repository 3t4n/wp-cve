<?php
add_action( 'wp_ajax_pe_deactivation_ajax_function', 'pe_deactivation_ajax_function' );
add_action( 'wp_ajax_nopriv_pe_deactivation_ajax_function', 'pe_deactivation_ajax_function' );
if(!function_exists('pe_deactivation_ajax_function')){
    function pe_deactivation_ajax_function(){
        $plugin_id = $_POST['plugin_id'];
        if(!isset($plugin_id)){
            echo 'emptyField';
            die;
        }
        $token = $_POST['token'];
        if(!isset($token)){
            echo 'emptyField';
            die;
        }
        $token = sanitize_text_field($token);
        $plugin_id = sanitize_text_field($plugin_id);
        $row_id = get_option('pe-plugin-id-response-'.$plugin_id, false);
        if(!$row_id){
            echo 'emptyOption';
            die;
        }
        $reason = $_POST['reason'];
        // if is empty $reason or is an Invalid Argument use defoult one: 6, it means "other"!
        if(isset($reason) && $reason > 0 && $reason < 7 )
            $reason = sanitize_text_field($reason);
        else    
            $reason = 6;  
        $body = json_encode(array(
            'plugin_status'     => 0,
            'deactivate_reason' => (int) $reason
        ));
        $args = array(
            'body'          => $body,
            'method'     => 'PUT',
            'timeout'       => '5',
            'redirection'   => '5',
            'httpversion'   => '1.0',
            'blocking'      => true,
            'headers'       => array('Content-Type' => 'application/json; charset=utf-8',
                                    'Authorization' => $token
                                    ),
            'cookies'       => array()
        );
        $response = wp_remote_request( 'http://api.plugineye.com/public/api/v1/updateStatus/'.$row_id, $args );
    }
}
?>