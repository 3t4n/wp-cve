<?php
/**
 * Manager for Plugin Capabilities.
 *
 * @link       https://etracker.com
 * @since      1.0.0
 *
 * @package    Etracker
 */

namespace Etracker\Plugin;

use Etracker\Util\Logger;

/**
 * Manager for Plugin Capabilities.
 *
 * This class defines all capabilities used to control access to etracker reporting figures.
 *
 * @since      2.0.0
 *
 * @package    Etracker
 *
 * @author     etracker GmbH <support@etracker.com>
 */
class CapabilityManager {
	/**
	 * Action called during uninstall process of plugin.
	 *
	 * We need to cleanup capabilities provided by this plugin.
	 *
	 * @see Uninstaller
	 *
	 * @return void
	 */
	public static function uninstall() {
		self::remove_capabilities();
	}

	/**
	 * Action to add plugin specific capabilities to existing roles.
	 *
	 * @return void
	 */
	public static function add_capabilities() {
		$logger = new Logger();
		$logger->set_prefix( __METHOD__ );

		$roles = wp_roles()->roles;

		$roles_to_add_capabilities_to = array();

		foreach ( $roles as $role_name => $role_info ) {
			if ( array_key_exists( 'edit_others_pages', $role_info['capabilities'] ) &&
				true === boolval( $role_info['capabilities']['edit_others_pages'] ) ) {
				// Role $role has 'edit_others_pages' capability, so add etracker_read_reporting_figures capability.
				array_push( $roles_to_add_capabilities_to, $role_name );
			}
		}

		/**
		 * Filter roles to be extended with etracker capabilities.
		 *
		 * Usually, any role with `edit_others_pages` capability will be
		 * extended with etracker capabilities to allow reading report
		 * informations in WordPress page and posts administation backend.
		 *
		 * @since 2.0.0
		 *
		 * @param array $etracker_roles_to_add_capabilities_to WordPress roles to be extended with etracker capabilities.
		 */
		$roles_to_add_capabilities_to = \apply_filters( 'etracker_roles_to_add_capabilities_to', $roles_to_add_capabilities_to );

		foreach ( $roles_to_add_capabilities_to as $role_name ) {
			$role = get_role( $role_name );
			if ( ! $role->has_cap( 'etracker_read_reporting_figures' ) ) {
				$role->add_cap( 'etracker_read_reporting_figures', true );
				$logger->debug( "Adding capability etracker_read_reporting_figures to role $role_name" );
			}
		}
	}

	/**
	 * Action to remove plugin specific capabilities during deactivation.
	 *
	 * @return void
	 */
	public static function remove_capabilities() {
		$logger = new Logger();
		$logger->set_prefix( __METHOD__ );

		$roles = wp_roles()->roles;

		$roles_to_remove_capabilities_from = array();

		foreach ( $roles as $role_name => $role_info ) {
			if ( array_key_exists( 'etracker_read_reporting_figures', $role_info['capabilities'] ) ) {
				// Role $role has 'etracker_read_reporting_figures' capability, so cleanup their capability.
				array_push( $roles_to_remove_capabilities_from, $role_name );
			}
		}

		foreach ( $roles_to_remove_capabilities_from as $role_name ) {
			$role = get_role( $role_name );
			if ( ! $role->has_cap( 'etracker_read_reporting_figures' ) ) {
				$role->remove_cap( 'etracker_read_reporting_figures' );
				$logger->debug( "Removing capability etracker_read_reporting_figures from role $role_name" );
			}
		}
	}

	/**
	 * Returns wether the current user is allowed to read etracker reporting figures.
	 *
	 * @return boolean Whether the current user has the etracker_read_reporting_figures capability.
	 */
	public static function current_user_can_read_reporting_figures(): bool {
		$logger = new Logger();
		$logger->set_prefix( __METHOD__ );

		//phpcs:disable Generic.WhiteSpace.ArbitraryParenthesesSpacing.FoundEmpty
		$u = \wp_get_current_user();
		$u = $u->user_login;

		$logger->debug( "Current user '$u' " . ( \current_user_can( 'etracker_read_reporting_figures' ) ? 'can' : 'can not' ) . ' read etracker figures' );
		return \current_user_can( 'etracker_read_reporting_figures' );
	}
}
