<?php
if ( !is_user_logged_in() ) {
	require_once SAKOLAWP_PLUGIN_DIR . '/template/login-shortcode.php';
}
else {
	require_once SAKOLAWP_PLUGIN_DIR . '/template/already-login.php';
}

?>