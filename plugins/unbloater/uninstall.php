<?php

defined( 'WP_UNINSTALL_PLUGIN' ) || die;
 
if( is_multisite() ) {
	delete_network_option( null, 'unbloater_settings' );
} else {
	delete_option( 'unbloater_settings' );
}