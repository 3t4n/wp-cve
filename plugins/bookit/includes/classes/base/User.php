<?php

namespace Bookit\Classes\Base;

use Bookit\Classes\Database\Staff;

/**
 * Class User
 * Bookit users connection with wp user, plugin custom roles and capabilities
 */
class User {

	public static $staff_role         = 'bookit_staff';
	public static $customer_role      = 'bookit_customer';
	protected static $wp_roles        = array( 'administrator', 'editor' );
	private static $bookit_user_roles = array(
		'bookit_customer' => array(
			'name'  => 'Bookit Customer',
			'roles' => array(
				'read'         => false,
				'edit_posts'   => false,
				'upload_files' => false,
			),
		),
		'bookit_staff'    => array(
			'name'  => 'Bookit Staff',
			'roles' => array(
				'read'                => true,
				'edit_posts'          => false,
				'upload_files'        => false,
				'manage_bookit_staff' => true,
			),
		),
	);

	public static function getUserData() {
		$user = wp_get_current_user();

		$staff   = array();
		$isStaff = false;

		if ( array_intersect( array( self::$staff_role ), $user->roles ) ) {
			$isStaff = true;
			$staff   = Staff::get_by_wp_user_id( $user->data->ID );
		}

		return array(
			'staff' => $staff,
			'is_staff' => $isStaff,
		);
	}

	/**
	 * Add user roles with capabilities for bookit plugin
	 */
	public static function addBookitUserRoles() {
		foreach ( self::$bookit_user_roles as $bookitRoleName => $bookitRoleInfo ) {
			remove_role( $bookitRoleName );
			add_role( $bookitRoleName, $bookitRoleInfo['name'], $bookitRoleInfo['roles'] );
		}
	}

	/**
	 * Add 'manage_bookit_staff' capability to exist WordPress roles ($wp_roles)
	 */
	public static function addBookitCapabilitiesToWpRoles() {

		foreach ( self::$wp_roles as $wpRole ) {
			$role = get_role( $wpRole );
			$role->add_cap( 'manage_bookit_staff' );
		}
	}

	/**
	 * Remove bookit user roles
	 */
	public static function cleanRoles() {
		foreach ( self::$bookit_user_roles as $bookitRoleName => $bookitRoleInfo ) {
			remove_role( $bookitRoleName );
		}
	}

	/**
	 * Remove 'manage_bookit_staff' capability from exist WordPress roles ($wp_roles)
	 */
	public static function cleanCapabilities() {
		foreach ( self::$wp_roles as $wpRole ) {
			$role = get_role( $wpRole );
			$role->remove_cap( 'manage_bookit_staff' );
		}
	}
}
