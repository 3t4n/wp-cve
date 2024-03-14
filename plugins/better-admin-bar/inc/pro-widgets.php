<?php
/**
 * Locked pro widget items.
 *
 * @package Better_Admin_Bar
 */

defined( 'ABSPATH' ) || die( "Can't access directly" );

return array(
	'custom'        => array(
		'text'        => __( 'Custom Controls', 'better-admin-bar' ),
		'description' => __( 'Add Custom Controls to your Quick Access Panel.', 'better-admin-bar' ),
	),
	'page_builders' => array(
		'text'        => __( 'Page Builder Support', 'better-admin-bar' ),
		'description' => __( 'Better Admin Bar PRO will automatically detect and launch the respective page builder if your page was created in Elementor, Brizy, Divi, Oxygen or Beaver Builder.', 'better-admin-bar' ),
	),
	'cpt'           => array(
		'text'        => __( 'Custom Post Type Support', 'better-admin-bar' ),
		'description' => __( 'Better Admin Bar PRO automatically detects registered post types and provides you with the necessary controls.', 'better-admin-bar' ),
	),
	'multisite'     => array(
		'text'        => __( 'Multisite Support', 'better-admin-bar' ),
		'description' => __( 'Better Admin Bar PRO is 100% multisite compatible. Configure the Quick Access Panel from the main site of your network and your changes will apply to all subsites.', 'better-admin-bar' ),
	),
	'access'        => array(
		'text'        => __( 'User Role Access', 'better-admin-bar' ),
		'description' => __( 'Restrict Quick Access Panel controls to specific User Roles with Better Admin Bar PRO.', 'better-admin-bar' ),
	),
);
