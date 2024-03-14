<?php
if ( !defined( 'ABSPATH' ) ) exit;

if ( !class_exists( 'ewduwcfPermissions' ) ) {
/**
 * Class to handle plugin permissions for Ultimate WooCommerce Filters
 *
 * @since 3.0.0
 */
class ewduwcfPermissions {

	private $plugin_permissions;
	private $permission_level;

	public function __construct() {

		$this->plugin_permissions = array(
			'scheduling'	=> 2,
			'labelling'		=> 2,
			'styling' 		=> 2,
			'premium' 		=> 2,
		);
	}

	public function set_permissions() {
		global $ewd_uwcf_controller;

		if ( is_array( get_option( 'ewd-uwcf-permission-level' ) ) ) { return; }

		if ( ! empty( get_option( 'ewd-uwcf-permission-level' ) ) ) { 

			update_option( 'ewd-uwcf-permission-level', array( get_option( 'ewd-uwcf-permission-level' ) ) );

			return;
		}

		$this->permission_level = get_option( 'EWD_UWCF_Full_Version' ) == 'Yes' ? 2 : 1;

		update_option( 'ewd-uwcf-permission-level', array( $this->permission_level ) );
	}

	public function get_permission_level() {

		if ( ! is_array( get_option( 'ewd-uwcf-permission-level' ) ) ) { $this->set_permissions(); }

		$permissions_array = get_option( 'ewd-uwcf-permission-level' );

		$this->permission_level = is_array( $permissions_array ) ? reset( $permissions_array ) : $permissions_array;
	}

	public function check_permission( $permission_type = '' ) {

		if ( ! $this->permission_level ) { $this->get_permission_level(); }

		return ( array_key_exists( $permission_type, $this->plugin_permissions ) ? ( $this->permission_level >= $this->plugin_permissions[$permission_type] ? true : false ) : false );
	}

	public function update_permissions() {

		$this->permission_level = get_option( 'ewd-uwcf-permission-level' );
	}
}

}