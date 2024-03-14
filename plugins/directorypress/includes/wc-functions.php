<?php

function directorypress_has_wc() {
	global $DIRECTORYPRESS_ADIMN_SETTINGS;
	if (in_array('woocommerce/woocommerce.php', apply_filters('active_plugins', get_option('active_plugins')))) 
		return true;
}


