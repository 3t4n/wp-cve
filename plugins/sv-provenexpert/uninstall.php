<?php
	if(!defined( 'WP_UNINSTALL_PLUGIN')){
		exit();
	}
	
	delete_option( 'sv_provenexpert_version' );
	delete_option( 'widget_sv_provenexpert_modules_widget' );
	delete_option('sv_provenexpert_modules_common_settings_settings_api_id' );
	delete_option('sv_provenexpert_modules_common_settings_settings_api_key' );
	delete_transient( 'sv_provenexpert' );
