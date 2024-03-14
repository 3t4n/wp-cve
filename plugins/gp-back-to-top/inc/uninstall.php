<?php
global $wpdb;
$option_name = 'gp_btt';
delete_option( $option_name );
delete_site_option( $option_name ); 
$wpdb->query( "DELETE FROM $wpdb->options WHERE option_name LIKE '%gp_btt_%';" );