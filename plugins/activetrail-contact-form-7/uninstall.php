<?php

/**
 * Fired when the plugin is uninstalled.
 *
 * @link       http://activetrail.com
 * @since      1.0.0
 *
 * @package    Activetrail_Cfs
 */

// If uninstall not called from WordPress, then exit.
if ( ! defined( 'WP_UNINSTALL_PLUGIN' ) ) {
	exit;
}

$args = array('post_type' => 'wpcf7_contact_form', 'posts_per_page' => -1);

$cf7_forms = get_posts( $args );

if(count($cf7_forms) > 0){
	$at_key_prefix = '_at_cf7_key';

	foreach ($cf7_forms as $cf7_form){
		$cf7_meta = get_post_meta($cf7_form->ID);
		if(count($cf7_meta) > 0){
			foreach($cf7_meta as $key => $value){
				if(substr($key, 0, strlen($at_key_prefix)) === $at_key_prefix){
					delete_post_meta($cf7_form->ID, $key); 
				}
			}
		}
	}
}
