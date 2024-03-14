<?php

function process_membee_options() {                           // save Membee options set by user or return current option values

  global $membee_options;

  $membee_options = array();

  $membee_options['membee_message'] = '';

  if ( (isset($_POST['membee_client_id'])) && (strlen($_POST['membee_client_id'])<=10) && (is_numeric($_POST['membee_client_id'])) && (current_user_can('manage_options')) ) {

    $membee_options['membee_client_id'] = intval($_POST['membee_client_id']);

    update_option('membee_client_id', $membee_options['membee_client_id']);    

  } else {

    if (isset($_POST['membee_client_id'])&&((strlen($_POST['membee_client_id'])>10)||(!is_numeric($_POST['membee_client_id'])))) $membee_options['membee_message'] .= 'Client ID you entered is invalid!<br />';   

    $membee_options['membee_client_id'] = get_option('membee_client_id');

  } 

  if ( (isset($_POST['membee_secret'])) && (strip_tags($_POST['membee_secret'])==$_POST['membee_secret']) && ((strlen(trim($_POST['membee_secret'])) == 36)||(strlen(trim($_POST['membee_secret'])) == 0)) && (current_user_can('manage_options')) ) {   // double check length of the secret

    $membee_options['membee_secret'] = strip_tags(trim($_POST['membee_secret']));

    update_option('membee_secret', $membee_options['membee_secret']);    

  } else {

    if ((isset($_POST['membee_secret']))&&((strip_tags($_POST['membee_secret'])!=$_POST['membee_secret'])||(strlen($_POST['membee_secret']) != 36 ))) $membee_options['membee_message'] .= 'Secret you entered is invalid!<br />';

    $membee_options['membee_secret'] = get_option('membee_secret');

  }

  if ( (isset($_POST['membee_app_id'])) && (strlen($_POST['membee_app_id'])<=10) && (is_numeric($_POST['membee_app_id'])) &&  (current_user_can('manage_options')) ) {

    $membee_options['membee_app_id'] = intval($_POST['membee_app_id']);

    update_option('membee_app_id', $membee_options['membee_app_id']);    

  } else {

    if (isset($_POST['membee_app_id'])&&((strlen($_POST['membee_app_id'])>10)||(!is_numeric($_POST['membee_app_id'])))) $membee_options['membee_message'] .= 'Application ID you entered is invalid!<br />';   

    $membee_options['membee_app_id'] = get_option('membee_app_id');

  } 

  return $membee_options; 

}



function send_get_request($url, $parameters) {                //sending request to Membee server

  if (function_exists('wp_remote_get')) {    

    $args = array(

      'method'      => 'GET',

      'timeout'     => 45,

      'redirection' => 5,

      'user-agent'  => 'membee-login',

      'blocking'    => true,

      'compress'    => true,

      'decompress'  => true,

      'sslverify'   => false,

      'headers'     => array('Referer'=>get_bloginfo('url'))

    );    

    $parameters = implode('&',$parameters);

    if ( !empty($parameters) ) {

      $get_url = $url.'?'.$parameters;

      $wp_get = @wp_remote_get($get_url,$args);

      if ((is_wp_error($wp_get))||(!$wp_get['body'])) {

        membee_error_message('An error occured during HTTP GET request: "'.serialize($wp_get).'"');        

        return false;

      } else {

        return $wp_get['body'];

      }

    } else {

      membee_error_message('An error occured during HTTP GET request: "Missing required parameters"');

      return false;

    }

  } else {

    membee_error_message('Please upgrade Wordpress to version 2.7 or higher');

    return false;

  }  

}



function create_new_user($username, $user_email) {            //create new WP user

  $random_password = wp_generate_password( 12, false );  

	$new_user_id = wp_create_user($username, $random_password, $user_email);

  if (is_wp_error($new_user_id)) {

    membee_error_message('An error occured during user creation: "'.serialize($new_user_id).'"');        

    return false;

  } else {    

    return $new_user_id;

  }

}



function update_user_roles($userdata, $user_id) {             //update user roles according to received data

  $cur_user = new WP_User( $user_id );

  $cur_user->set_role('subscriber'); 

  if (count($userdata->Roles)) {    

    foreach($userdata->Roles as $role) {      

      $existing_role = get_role(strtolower($role));

      if (!$existing_role->name) {

        add_role(strtolower($role), $role);       

      }                        

      $cur_user->add_role(strtolower($role));

    }

  }

    wp_update_user(array('ID' => $user_id, 'user_email' => esc_attr($userdata->Email), 'display_name' => $userdata->FirstName.' '.$userdata->LastName));

  return $cur_user;

}



function sign_user_in($user_id, $username) {                  //sign user in

  global $post;  

  wp_set_auth_cookie($user_id, false);

  $cur_user = wp_set_current_user($user_id);

  do_action('wp_login', $username, $cur_user);

  if (get_permalink()) {

    $url = get_permalink($post->ID);

  } else {

    $url = get_bloginfo('url'); 

  }

  $url = prepare_redirect('http://'.$_SERVER['HTTP_HOST'].$_SERVER['REQUEST_URI']);  

  wp_safe_redirect($url);

  exit;          

}



function prepare_redirect($url) {                             //remove token from URL

  $url = parse_url($url);

  parse_str($url['query'], $newquery);

  unset($newquery['token']);
  
  $newquery['loggedin'] = 'true';

  $newquery = http_build_query($newquery);

  if ($newquery) {

    return 'http://'.$_SERVER['HTTP_HOST'].$url['path'].'?'.$newquery;

  } else {

    return 'http://'.$_SERVER['HTTP_HOST'].$url['path'];

  }  

}

?>