<?php
// Don't allow direct execution
defined( 'ABSPATH' ) or die ( 'Forbidden' );

function wp247_body_classes_admin_menu() {
	return array( 'page_title'	=> 'WP247 Body Classes'
				, 'menu_title'	=> 'Body Classes'
				, 'capability'	=> 'manage_options'
				, 'menu_slug'	=> 'wp247_body_classes_options'
				, 'parent_slug'	=> 'options-general'
				);
}
?>