<?php
if( ! defined ('WP_UNINSTALL_PLUGIN') )
exit();
function wc_linepay_delete_plugin(){
	global $wpdb;

	//delete option settings
	$wpdb->query("DELETE FROM $wpdb->options WHERE option_name LIKE 'woocommerce\_linepay\_%';");
	$wpdb->query("DELETE FROM $wpdb->options WHERE option_name LIKE 'wc-linepay-%';");
}

wc_linepay_delete_plugin();
