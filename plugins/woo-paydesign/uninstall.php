<?php
if( ! defined ('WP_UNINSTALL_PLUGIN') )
exit();
function wc_paydesign_delete_plugin(){
	global $wpdb;

	//delete option settings
//	delete_option('woocommerce_paydesign_cc');
	$wpdb->query("DELETE FROM $wpdb->options WHERE option_name LIKE 'woocommerce\_paydesign\_%';");
	$wpdb->query("DELETE FROM $wpdb->options WHERE option_name LIKE 'wc-paydesign-%';");
}

wc_paydesign_delete_plugin();
