<?php
if ( !defined( 'WP_UNINSTALL_PLUGIN' ) ) {
	die( 'Direct access not allowed!' );
}

function google_recaptcha_delete($array) {
	foreach ($array as $one) {
		delete_option("google_recaptcha_{$one}");
	}	
}

google_recaptcha_delete(array("site_key", "secret_key", "login_check_disable"));
