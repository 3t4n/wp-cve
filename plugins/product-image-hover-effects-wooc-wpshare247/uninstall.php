<?php

if ( ! defined( 'WP_UNINSTALL_PLUGIN' ) ) {
	exit();
}

function pl_Ws247_piew_delete_plugin() {
	global $wpdb;
	
	//Delete option if exist
	global $wpdb;
	$prefix = 'Ws247_piew';
	$plugin_options = $wpdb->get_results( "	SELECT `option_name` 
											FROM $wpdb->options 
											WHERE ( `option_name` LIKE '".$prefix."%' )
										" );
	if($plugin_options):
		foreach( $plugin_options as $option ) {
			delete_option( $option->option_name );
		}
	endif;
	
	//Delete post if exist
}

pl_Ws247_piew_delete_plugin();