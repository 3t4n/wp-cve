<?php
/*
Plugin Name: WP Easy Replace Howdy
Plugin URI: http://asifalimca.wordpress.com
Description: This plugin will Replace "Howdy" in the top right corner with "Welcome" of your WordPress dashboard.
Author: Asif Ali
Author URI: http://asifalimca.wordpress.com
Version: 1.1.0
License: GPLv2
*/
define( 'PLUGIN_PATH', plugins_url( __FILE__ ) );
add_action('admin_bar_menu', 'werh_my_custom_account_menu', 11 );

function werh_my_custom_account_menu( $werh_wp_admin_bar ) {
	$werh_user_id = get_current_user_id();
	$werh_current_user = wp_get_current_user();
	$werh_profile_url = get_edit_profile_url( $werh_user_id );

	if ( 0 != $werh_user_id ) {
		/* Add the "My Account" menu */
		$werh_avatar = get_avatar( $werh_user_id, 28 );
		$werh_howdy = sprintf( __('Welcome, %1$s'), $werh_current_user->display_name );
		$werh_class = empty( $werh_avatar ) ? '' : 'with-avatar';

		$werh_wp_admin_bar->add_menu( array(
			'id' => 'my-account',
			'parent' => 'top-secondary',
			'title' => $werh_howdy . $werh_avatar,
			'href' => $werh_profile_url,
			'meta' => array(
			'class' => $werh_class,
			),
		) );
	}
}
