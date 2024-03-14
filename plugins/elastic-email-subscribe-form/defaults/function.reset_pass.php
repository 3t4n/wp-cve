<?php

function ee_custom_password_reset($message, $key, $user_login, $user_data )    {

$message = __( 'Someone has requested a password reset for the following account:') . "<br><br>\r\n\r\n";
/* translators: %s: site name */
$message .= sprintf( __( 'Site Name: %s'), get_bloginfo() ) . "<br>\r\n\r\n";
$message .= sprintf( __( 'Email Adress: %s'), $user_data->user_email ) . "<br>\r\n\r\n";

/* translators: %s: user login */
$message .= sprintf( __( 'Username: %s' ), $user_login ) . "<br><br>\r\n\r\n";
$message .= __( 'If this was a mistake, just ignore this email and nothing will happen.') . "<br><br>\r\n\r\n";
$message .= __( 'To reset your password, visit the following address:') . "\r\n\r\n";
$message .= '<a href="' . network_site_url("wp-login.php?action=rp&key=$key&login=" . rawurlencode($user_login), 'login') . "\">". __( 'Reset your Password') ."</a>\r\n";

return $message;

}


add_filter("retrieve_password_message", "ee_custom_password_reset", 99, 4);