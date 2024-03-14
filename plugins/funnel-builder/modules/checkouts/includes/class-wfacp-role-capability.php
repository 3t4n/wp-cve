<?php

if ( ! defined( 'ABSPATH' ) ) {
	exit; // Exit if accessed directly
}

/**
 * Class WFACP_Role_Capability
 */
if ( ! class_exists( 'WFACP_Role_Capability' ) ) {
	#[AllowDynamicProperties]

  class WFACP_Role_Capability {

		private static $ins = null;

		/**
		 * @return WFACP_Role_Capability|null
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
			 * Set default user role for full access
			 */

			if ( is_array( $all_roles ) && count( $all_roles ) > 0 ) {
				foreach ( $all_roles as $role_name => $all_role ) {

					/**
					 * manage_woocommerce user have full access permission
					 */
					if ( isset( $all_role['capabilities'] ) && isset( $all_role['capabilities']['manage_woocommerce'] ) && true === $all_role['capabilities']['manage_woocommerce'] ) {
						$funnel_user[ $role_name ] = array(
							'menu'     => array( 'read', 'write' ),
							'checkout' => array( 'read', 'write' ),
						);
					}
				}
			}

			/**
			 * Set user role capabilities for full access full
			 */
			$config = apply_filters( 'wfacp_user_access_capabilities', $funnel_user, $all_roles );

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

	if ( class_exists( 'WFACP_Core' ) && ! WFACP_Common::is_disabled() ) {
		WFACP_Core::register( 'role', 'WFACP_Role_Capability' );
	}

}
