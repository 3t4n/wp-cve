<?php
defined( 'ABSPATH' ) or die( 'Please don&rsquo;t call the plugin directly. Thanks :)' );

/**
 * Admin bar customization
 */

function wpui_admin_bar_links() {

	global $wp_admin_bar;

	// Adds a new top level admin bar link and a submenu to it
	$wp_admin_bar->add_menu( array(
		'parent'	=> false,
		'id'		=> 'wpui_custom_top_level',
		'title'		=> __( 'WP Admin UI', 'wp-admin-ui' ),
		'href'		=> admin_url( 'admin.php?page=wpui-option' ),
	));
	$wp_admin_bar->add_menu( array(
		'parent'	=> 'wpui_custom_top_level',
		'id'		=> 'wpui_custom_sub_menu_login',
		'title'		=> __( 'Login', 'wp-admin-ui' ),
		'href'		=> admin_url( 'admin.php?page=wpui-login' ),
	));
	$wp_admin_bar->add_menu( array(
		'parent'	=> 'wpui_custom_top_level',
		'id'		=> 'wpui_custom_sub_menu_global',
		'title'		=> __( 'Global', 'wp-admin-ui' ),
		'href'		=> admin_url( 'admin.php?page=wpui-global' ),
	));
	$wp_admin_bar->add_menu( array(
		'parent'	=> 'wpui_custom_top_level',
		'id'		=> 'wpui_custom_sub_menu_dashboard',
		'title'		=> __( 'Dashboard', 'wp-admin-ui' ),
		'href'		=> admin_url( 'admin.php?page=wpui-dashboard' ),
	));
	$wp_admin_bar->add_menu( array(
		'parent'	=> 'wpui_custom_top_level',
		'id'		=> 'wpui_custom_sub_menu_admin_menu',
		'title'		=> __( 'Admin Menu', 'wp-admin-ui' ),
		'href'		=> admin_url( 'admin.php?page=wpui-admin-menu' ),
	));
	$wp_admin_bar->add_menu( array(
		'parent'	=> 'wpui_custom_top_level',
		'id'		=> 'wpui_custom_sub_menu_admin_bar',
		'title'		=> __( 'Admin Bar', 'wp-admin-ui' ),
		'href'		=> admin_url( 'admin.php?page=wpui-admin-bar' ),
	));
	$wp_admin_bar->add_menu( array(
		'parent'	=> 'wpui_custom_top_level',
		'id'		=> 'wpui_custom_sub_menu_editor',
		'title'		=> __( 'Editor', 'wp-admin-ui' ),
		'href'		=> admin_url( 'admin.php?page=wpui-editor' ),
	));
	$wp_admin_bar->add_menu( array(
		'parent'	=> 'wpui_custom_top_level',
		'id'		=> 'wpui_custom_sub_menu_library',
		'title'		=> __( 'Library', 'wp-admin-ui' ),
		'href'		=> admin_url( 'admin.php?page=wpui-library' ),
	));
	$wp_admin_bar->add_menu( array(
		'parent'	=> 'wpui_custom_top_level',
		'id'		=> 'wpui_custom_sub_menu_profil',
		'title'		=> __( 'Profil', 'wp-admin-ui' ),
		'href'		=> admin_url( 'admin.php?page=wpui-profil' ),
	));
	$wp_admin_bar->add_menu( array(
		'parent'	=> 'wpui_custom_top_level',
		'id'		=> 'wpui_custom_sub_menu_roles',
		'title'		=> __( 'Role Manager', 'wp-admin-ui' ),
		'href'		=> admin_url( 'admin.php?page=wpui-roles' ),
	));
	$wp_admin_bar->add_menu( array(
		'parent'	=> 'wpui_custom_top_level',
		'id'		=> 'wpui_custom_sub_menu_import_export',
		'title'		=> __( 'Import / Export', 'wp-admin-ui' ),
		'href'		=> admin_url( 'admin.php?page=wpui-import-export' ),
	));
	if ( is_plugin_active( 'wp-admin-ui-pro/wpadminui-pro.php' ) ) {
		$wp_admin_bar->add_menu( array(
			'parent'	=> 'wpui_custom_top_level',
			'id'		=> 'wpui_custom_sub_menu_metaboxes',
			'title'		=> __( 'Metaboxes', 'wp-admin-ui' ),
			'href'		=> admin_url( 'admin.php?page=wpui-metaboxes' ),
		));
		$wp_admin_bar->add_menu( array(
			'parent'	=> 'wpui_custom_top_level',
			'id'		=> 'wpui_custom_sub_menu_columns',
			'title'		=> __( 'Columns', 'wp-admin-ui' ),
			'href'		=> admin_url( 'admin.php?page=wpui-columns' ),
		));
		$wp_admin_bar->add_menu( array(
			'parent'	=> 'wpui_custom_top_level',
			'id'		=> 'wpui_custom_sub_menu_plugins',
			'title'		=> __( 'Plugins', 'wp-admin-ui' ),
			'href'		=> admin_url( 'admin.php?page=wpui-plugins' ),
		));	
		$wp_admin_bar->add_menu( array(
			'parent'	=> 'wpui_custom_top_level',
			'id'		=> 'wpui_custom_sub_menu_plugins',
			'title'		=> __( 'Themes', 'wp-admin-ui' ),
			'href'		=> admin_url( 'admin.php?page=wpui-themes' ),
		));
		$wp_admin_bar->add_menu( array(
			'parent'	=> 'wpui_custom_top_level',
			'id'		=> 'wpui_custom_sub_menu_mails',
			'title'		=> __( 'Mails', 'wp-admin-ui' ),
			'href'		=> admin_url( 'admin.php?page=wpui-mails' ),
		));
		$wp_admin_bar->add_menu( array(
			'parent'	=> 'wpui_custom_top_level',
			'id'		=> 'wpui_custom_sub_menu_woocommerce',
			'title'		=> __( 'WooCommerce', 'wp-admin-ui' ),
			'href'		=> admin_url( 'admin.php?page=wpui-woocommerce' ),
		));
		$wp_admin_bar->add_menu( array(
			'parent'	=> 'wpui_custom_top_level',
			'id'		=> 'wpui_custom_sub_menu_license',
			'title'		=> __( 'License', 'wp-admin-ui' ),
			'href'		=> admin_url( 'admin.php?page=wpui-license' ),
		));
	}
}
add_action( 'admin_bar_menu', 'wpui_admin_bar_links', 99 );
