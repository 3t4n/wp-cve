<?php
/**
 * Default available widget items.
 *
 * @package Better_Admin_Bar
 */

defined( 'ABSPATH' ) || die( "Can't access directly" );

return array(
	'new_page' => array(
		'text'       => __( 'New Page', 'better-admin-bar' ),
		'url'        => admin_url( 'post-new.php?post_type=page' ),
		'new_tab'    => false,
		'icon_class' => 'fas fa-file-alt',
	),
	'new_post' => array(
		'text'       => __( 'New Post', 'better-admin-bar' ),
		'url'        => admin_url( 'post-new.php' ),
		'new_tab'    => false,
		'icon_class' => 'fas fa-pencil-alt',
	),
	'themes'   => array(
		'text'       => __( 'Themes', 'better-admin-bar' ),
		'url'        => admin_url( 'themes.php' ),
		'new_tab'    => false,
		'icon_class' => 'far fa-images',
	),
	'plugins'  => array(
		'text'       => __( 'Plugins', 'better-admin-bar' ),
		'url'        => admin_url( 'plugins.php' ),
		'new_tab'    => false,
		'icon_class' => 'fas fa-plug',
	),
);
