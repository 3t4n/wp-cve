<?php

function cc_jetengine_importer_wp_all_import_is_images_section_enabled( $is_enabled, $post_type ){

	// Disable Images section for Users, return true to enable.
	if ( 'import_users' == $post_type )
		$is_enabled = false;

	// Disable Images section for Customers, return true to enable.
	if ( 'shop_customer' == $post_type )
		$is_enabled = false;

	return $is_enabled;

}
