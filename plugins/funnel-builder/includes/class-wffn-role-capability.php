<?php

if ( ! defined( 'ABSPATH' ) ) {
	exit; // Exit if accessed directly
}

/**
 * Class WFFN_Role_Capability
 */
if ( ! class_exists( 'WFFN_Role_Capability' ) ) {
	class WFFN_Role_Capability {

		private static $ins = null;

		/**
		 * @return WFFN_Role_Capability|null
		 */
		public static function get_instance() {
			if ( null === self::$ins ) {
				self::$ins = new self;
			}

			return self::$ins;
		}

		/**
		 * check if user have funnel capabilities access
		 * @param $cap
		 * @param $access
		 *
		 * @return false|string
		 */
		public function user_access( $cap, $access ) {
			if ( ! is_user_logged_in() ) {
				return false;
			}

			$current_user = wp_get_current_user();

			// not logged in
			if ( $current_user->ID === 0 ) {
				return false;
			}

			/**
			 * full access for administrator user
			 */
			if ( current_user_can( 'administrator' ) ) {
				return 'administrator';
			}

			global $wp_roles;

			$all_roles   = $wp_roles->roles;
			$funnel_user = array();

			/**
			 * Set default user role for access full funnel
			 */

			if ( is_array( $all_roles ) && count( $all_roles ) > 0 ) {
				foreach ( $all_roles as $role_name => $all_role ) {

					/**
					 * manage_options user have full funnel access permission
					 */
					if ( isset( $all_role['capabilities'] ) && isset( $all_role['capabilities']['manage_options'] ) && true === $all_role['capabilities']['manage_options'] ) {
						$funnel_user[ $role_name ] = array(
							'menu'      => array( 'read', 'write' ),
							'funnel'    => array( 'read', 'write' ),
							'analytics' => array( 'read', 'write' )
						);
					}
				}
			}

			/**
			 * Set user role capabilities for access full funnel
			 */
			$config = apply_filters( 'wffn_user_access_capabilities', $funnel_user, $all_roles );

			$current_user_roles = $current_user->roles;
			if ( is_array( $current_user_roles ) && count( $current_user_roles ) > 0 ) {
				foreach ( $current_user_roles as $role ) {
					if ( isset( $config[ $role ] ) && isset( $config[ $role ][ $cap ] ) && in_array( $access, $config[ $role ][ $cap ], true ) ) {
						return $role;
					}
				}
			}

			return false;
		}

	}

	if ( class_exists( 'WFFN_Core' ) ) {
		WFFN_Core::register( 'role', 'WFFN_Role_Capability' );
	}
}
