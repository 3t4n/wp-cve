<?php
add_action( 'rest_api_init', function () {
    register_rest_route( 'click5_history_log/API', '/support_plugin', array(
        'methods' => 'POST',
        'callback' => 'click5_history_log_API_support_plugin',
        'permission_callback' => '__return_true',
        ) 
    );

    register_rest_route( 'click5_history_log/API', '/support_module', array(
        'methods' => 'POST',
        'callback' => 'click5_history_log_API_support_module',
        'permission_callback' => '__return_true',
        ) 
    );

    /*//All-In-One WP Migration backup download
    register_rest_route( 'click5_history_log/API', '/ai1wpm_backup_download', array(
        'methods' => 'POST',
        'callback' => 'click5_history_log_API_ai1wpm_backup_download',
        'permission_callback' => '__return_true',
        ) 
    );*/
});

function click5_history_log_API_support_plugin( WP_REST_Request $request ) { 
    if (!click5_history_log_requestAuthentication($request)) {
        return false;
      }
    $postBody = (array)(json_decode(stripslashes(file_get_contents("php://input"))));
    $info = get_plugin_data(WP_PLUGIN_DIR . '/' . $postBody['name']);
    $name_plugin = $info['Name'];
    global $wpdb;
    $user_name_log = wp_get_current_user()->user_login;
    $table_name = $wpdb->prefix . "c5_history";
    if($postBody['track']) {
        update_option('click5_history_log_' . $postBody['name'], "1");
        $wpdb->insert($table_name, array('description'=>"<b>" . $name_plugin . "</b> tracking has been enabled",'date'=>date('Y-m-d H:i:s'), 'plugin'=>'History Log by click5', 'user'=>$postBody['user']));
    } else {
        update_option('click5_history_log_' . $postBody['name'], "0");
        $wpdb->insert($table_name, array('description'=>"<b>" . $name_plugin . "</b> tracking has been disabled",'date'=>date('Y-m-d H:i:s'), 'plugin'=>'History Log by click5', 'user'=>$postBody['user']));
    }
    $table_name = $wpdb->prefix . "c5_history";
    return array($name_plugin, $postBody['track']);
}

function click5_history_log_API_support_module( WP_REST_Request $request ) { 
    if (!click5_history_log_requestAuthentication($request)) {
        return false;
      }
    $postBody = (array)(json_decode(stripslashes(file_get_contents("php://input"))));
    $name_plugin = $postBody['name'];
    global $wpdb;
    $user_name_log = wp_get_current_user()->user_login;
    $table_name = $wpdb->prefix . "c5_history";
    if($postBody['track']) {
        update_option('click5_history_log_module_' . $postBody['id'], "1");
        $wpdb->insert($table_name, array('description'=>"<b>" . $name_plugin . "</b> module tracking has been enabled",'date'=>date('Y-m-d H:i:s'), 'plugin'=>'History Log by click5', 'user'=>$postBody['user']));
    } else {
        update_option('click5_history_log_module_' . $postBody['id'], "0");
        $wpdb->insert($table_name, array('description'=>"<b>" . $name_plugin . "</b> module tracking has been disabled",'date'=>date('Y-m-d H:i:s'), 'plugin'=>'History Log by click5', 'user'=>$postBody['user']));
    }
    $table_name = $wpdb->prefix . "c5_history";
    return array($name_plugin, $postBody['track']);
}

function click5_history_log_API_ai1wpm_backup_download( WP_REST_Request $request ){
    if (!click5_history_log_requestAuthentication($request)) {
        return false;
      }

      $postBody = (array)(json_decode(stripslashes(file_get_contents("php://input"))));
    
}

function click5_history_log_requestAuthentication($request) {
    $token = $request->get_header('token');
    $user = $request->get_header('user');
    $saved_token = get_option('click5_history_log_token_'.$user);
    $result = $saved_token ? ( $token ? ( strcmp($token, $saved_token) === 0 ) : false ) : false;
  
    return $result;
  }