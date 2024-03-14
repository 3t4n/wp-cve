<?php
/**
 * Default active widget keys.
 *
 * @package Better_Admin_Bar
 */

defined( 'ABSPATH' ) || die( "Can't access directly" );

return array(
	'dashboard'        => array(
		'text'       => __( 'Dashboard', 'better-admin-bar' ),
		'url'        => admin_url(),
		'new_tab'    => false,
		'icon_class' => 'fas fa-tachometer-alt',
	),
	'edit_post_type'   => array(
		'text'       => __( 'Edit {Post_Type}', 'better-admin-bar' ),
		'url'        => 'auto',
		'new_tab'    => false,
		'icon_class' => 'fas fa-edit',
	),
	'theme_customizer' => array(
		'text'       => __( 'Customize', 'better-admin-bar' ),
		'url'        => admin_url( 'customize.php' ),
		'new_tab'    => false,
		'icon_class' => 'fas fa-paint-brush',
	),
);
