<?php
if ( !is_user_logged_in() ) {
	require_once SAKOLAWP_PLUGIN_DIR . '/template/register-shortcode.php';
}
else {
	require_once SAKOLAWP_PLUGIN_DIR . '/template/already-login.php';
}

?>