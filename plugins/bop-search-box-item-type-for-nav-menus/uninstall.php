<?php

//Reject if accessed directly or when not uninstalling
defined( 'WP_UNINSTALL_PLUGIN' ) || die( 'Our survey says: ... X.' );

delete_site_option( 'bop_nav_search_box_item_version' );
delete_site_option( 'bop_nav_search_box_item_recd_vers_warn' );

//Uninstall code - remove everything with wiping
$menus = wp_get_nav_menus();

foreach( $menus as $menu ){
	$items = wp_get_nav_menu_items( $menu );
	foreach( $items as $item ){
		if( $item->type == 'search' ){
			wp_delete_post( $item->db_id );
		}
	}
}
