<?php
if ( ! defined( 'ABSPATH' ) ) {
	exit; // Exit if accessed directly
}

// Check nonce
if( !isset($_POST['nonce']) || !wp_verify_nonce($_POST['nonce']) ){
    die(__('Cheating huh?', 'taskbuilder'));
}
$username = isset($_POST['username']) ? sanitize_text_field($_POST['username']) : '';
if (!$username) die();
$password = isset($_POST['password']) ? $_POST['password'] : '';
if (!$password) die();
$remember = isset($_POST['remember']) ? true : false;
$creds = array(
  'user_login'    => $username,
  'user_password' => $password,
  'remember'      => $remember,
);
$user = wp_signon( $creds, false );
$response = array();
if ( is_wp_error( $user ) ) {
  $response['error'] = '1';
  $response['message'] = $user->get_error_message();
} else {
  $response['error'] = '0';
  $response['message'] = __('Success!','taskbuilder');
}
echo json_encode( $response );