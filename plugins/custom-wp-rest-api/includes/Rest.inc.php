<?php
if ( ! defined( 'ABSPATH' ) ) exit; // Exit if accessed directly
function wcra_get_status_message($code=200){
            $status = array(
                        100 => 'Continue',  
                        101 => 'Switching Protocols',  
                        200 => 'OK',
                        201 => 'Created',  
                        202 => 'Accepted',  
                        203 => 'Non-Authoritative Information',  
                        204 => 'No Content',  
                        205 => 'Reset Content',  
                        206 => 'Partial Content',  
                        300 => 'Multiple Choices',  
                        301 => 'Moved Permanently',  
                        302 => 'Found',  
                        303 => 'See Other',  
                        304 => 'Not Modified',  
                        305 => 'Use Proxy',  
                        306 => '(Unused)',  
                        307 => 'Temporary Redirect',  
                        400 => 'Bad Request',  
                        401 => 'Unauthorized',  
                        402 => 'Payment Required',  
                        403 => 'Forbidden',  
                        404 => 'Not Found',  
                        405 => 'Method Not Allowed',  
                        406 => 'Not Acceptable',  
                        407 => 'Proxy Authentication Required',  
                        408 => 'Request Timeout',  
                        409 => 'Conflict',  
                        410 => 'Gone',  
                        411 => 'Length Required',  
                        412 => 'Precondition Failed',  
                        413 => 'Request Entity Too Large',  
                        414 => 'Request-URI Too Long',  
                        415 => 'Unsupported Media Type',  
                        416 => 'Requested Range Not Satisfiable',  
                        417 => 'Expectation Failed',  
                        500 => 'Internal Server Error',  
                        501 => 'Not Implemented',  
                        502 => 'Bad Gateway',  
                        503 => 'Service Unavailable',  
                        504 => 'Gateway Timeout',  
                        505 => 'HTTP Version Not Supported',
                        1001 => 'Parameters required!',
                        1002 => 'No Results Found'
                        );
            return $status[$code];
}


 function wcra_response($code='',$response,$_authintication,$data=array()){
            $_code = ($code)?$code:wcra_http_response_code();
            $opt = array('status' =>wcra_get_status_message($code),'response' => $response , 'code' => $code , 'data' => $data  );
            $create_log = wcra_create_api_log($_authintication,$opt);
            return $opt;
            exit;
 }

 function wcra_request_url(){
      $actual_link = (isset($_SERVER['HTTPS']) && $_SERVER['HTTPS'] === 'on' ? "https" : "http") . "://$_SERVER[HTTP_HOST]$_SERVER[REQUEST_URI]";
      return $actual_link;
}

function wcra_get_base($url){
    $_get_settings = wcra_get_settings();
    $my_version = esc_attr($_get_settings['wpr_set_ver']);
    $output = explode( "v$my_version/", $url );
    $output = explode( "/?", $output[1] );
    return $output[0];
}
function wcra_create_api_log($ApiSecret,$response){
    $_get_settings = esc_attr(wcra_get_settings('wpr_set_req_check'));
    if($_get_settings == 0 ){
        return;
    }
      global $wpdb;
      $tab = $wpdb->prefix.WCRA_DB.'api_log';
      $_request_url = wcra_request_url();
      $ip = wcra_get_client_ip();
      $_user_system_info = wcra_user_system_info();
      $response = json_encode($response);
      $time = current_time('mysql');
      $data = array(
            'secret' => serialize($ApiSecret) , 
            'requested_url' => $_request_url , 
            'response_delivered' => $response,
            'connectedAt' => $time ,
            'System_info' => $_user_system_info,
            'Ip' => $ip,
      );
      $insert = $wpdb->insert($tab , $data);
      $notification = "<strong>1</strong> endpoint url has been requested by client - <strong>$_request_url</strong>";
        wcra_save_recent_activity(array('txt' => $notification ));
}
function wcra_findRandom() {
    $mRandom = rand(48, 122);
    return $mRandom;
}

function wcra_isRandomInRange($mRandom) {
    if(($mRandom >=58 && $mRandom <= 64) ||
            (($mRandom >=91 && $mRandom <= 96))) {
        return 0;
    } else {
        return $mRandom;
    }
}

function wcra_api_key_gen(){
    $output = '';
      for($loop = 0; $loop <= 31; $loop++) {
          for($isRandomInRange = 0; $isRandomInRange === 0;){
              $isRandomInRange = wcra_isRandomInRange(wcra_findRandom());
          }
          $output .= html_entity_decode('&#' . $isRandomInRange . ';');
      }
      return $output;
}

function wcra_get_client_ip() {
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
        $ipaddress = 'UNKNOWN';
 
    return $ipaddress;
}

function wcra_user_system_info(){
    $HTTP_USER_AGENT =  $_SERVER['HTTP_USER_AGENT'];
    return $HTTP_USER_AGENT;
}


function wcra_reset_deafault_settings(){
  global $wpdb;
    $wpr__settings = array();
      $tab = $wpdb->prefix.WCRA_DB.'api_base';
      $ApiSecret = wcra_api_key_gen();
      $wpr_set_req_check = 0;
      $wpr_set_auth_check = 1;
      $wpr_set_ver = 1;
      $wpr_set_enb_api = 1;
      $wpr_set_end_slug = 'wcra';
      $wpr_set_recent_activity_dur = 3;
      $_get_settings = wcra_get_settings('wpr_set_end_slug');
      $wpr__settings['root_secret'] = $ApiSecret;
      $wpr__settings['wpr_set_req_check'] = $wpr_set_req_check;
      $wpr__settings['wpr_set_ver'] = $wpr_set_ver;
      $wpr__settings['wpr_set_end_slug'] = $wpr_set_end_slug;
      $wpr__settings['wpr_set_auth_check'] = $wpr_set_auth_check;
      $wpr__settings['wpr_set_enb_api'] = $wpr_set_enb_api;
      $wpr__settings['wpr_set_recent_activity_dur'] = $wpr_set_recent_activity_dur;
    update_option('wcra_settings_meta' , $wpr__settings);
      
}