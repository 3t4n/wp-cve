<?php
if (empty($_GET["redirect_to"]))
  $referrer = esc_url(wp_get_referer(), ['http', 'https']);
else
  $referrer = esc_url($_GET["redirect_to"], ['http', 'https']);

$args = array(
  'echo'           => true,
  'redirect'       => $referrer, 
  'form_id'        => 'cluevo-login-form',
  'label_username' => __( 'Username', "cluevo" ),
  'label_password' => __( 'Password', "cluevo" ),
  'label_remember' => __( 'Remember Me', "cluevo" ),
  'label_log_in'   => __( 'Log In', "cluevo" ),
  'id_username'    => 'user_login',
  'id_password'    => 'user_pass',
  'id_remember'    => 'rememberme',
  'id_submit'      => 'wp-submit',
  'remember'       => true,
  'value_username' => NULL,
  'value_remember' => true
); 

// Calling the login form.
wp_login_form( $args );
?>
