<?php
/**
 * Object to manage all actions needed during update or installation
 *
 * @package SurferSEO
 * @link https://surferseo.com
 */

namespace SurferSEO;

use SurferSEO\Upgrade\SQL\Upgrade_130;

defined( 'ABSPATH' ) || exit;

/**
 * Surfer Installer class.
 */
class Surfer_Installer {

	/**
	 * Object constructor.
	 */
	public function __construct() {
	}

	/**
	 * Runs installation actions.
	 *
	 * @return void
	 */
	public function install() {
		if ( ! is_blog_installed() ) {
			return;
		}

		// Check if we are not already running this routine.
		if ( self::is_installing() ) {
			return;
		}

		// If we made it till here nothing is running yet, lets set the transient now.
		set_transient( 'surfer_installing', 'yes', MINUTE_IN_SECONDS * 10 );

		self::set_activation_transients();
		self::update_surfer_database();
		self::make_version_related_actions();
		self::send_tracking_data();
		self::update_surfer_version();

		delete_transient( 'surfer_installing' );
	}

	/**
	 * Returns true if we're installing.
	 *
	 * @return bool
	 */
	private static function is_installing() {
		return 'yes' === get_transient( 'surfer_installing' );
	}

	/**
	 * See if we need to set redirect transients for activation or not.
	 *
	 * @return void
	 */
	private static function set_activation_transients() {
		if ( self::is_new_install() ) {
			set_transient( '_surfer_activation_redirect', 1, 30 );
		}
	}

	/**
	 * Is this a brand new Surfer install?
	 *
	 * A brand new install has no version yet.
	 *
	 * @return boolean
	 */
	public static function is_new_install() {
		return is_null( get_option( 'surfer_version', null ) );
	}

	/**
	 * Create and Updates tables in database for Surfer purposes.
	 */
	private static function update_surfer_database() {

		$last_active_version = get_option( 'surfer_version', false );

		if ( version_compare( $last_active_version, '1.3.0', '<' ) ) {
			$updater = new Upgrade_130();
			$updater->execute();
		}
	}

	/**
	 * Make actions that are related to Surfer version.
	 */
	private static function make_version_related_actions() {

		$last_active_version = get_option( 'surfer_version', false );

		if ( version_compare( $last_active_version, '1.3.0', '<' ) ) {
			// Transfer GSC data to new format.
			Surfer()->get_surfer()->get_gsc()->transfer_gsc_data_to_new_format();
		}
	}

	/**
	 * Update Surfer version to current.
	 *
	 * @return void
	 */
	private static function update_surfer_version() {
		update_option( 'surfer_version', Surfer()->version );
	}

	/**
	 * Sends tracking data to Surfer if user allowed it and Surfer was updated.
	 *
	 * @return void
	 */
	public static function send_tracking_data() {
		$previous_version = get_option( 'surfer_version', false );

		if ( ! $previous_version || Surfer()->version != $previous_version ) {
			return;
		}

		Surfer()->get_surfer_tracking()->track_wp_environment();
	}


	/**
	 * Set transient when Surfer is updated.
	 *
	 * @param object $upgrader_object - Upgrader object.
	 * @param array  $options - Options array.
	 */
	public function surfer_upgrade_completed( $upgrader_object, $options ) {
		$our_plugin = Surfer()->get_basedir();

		if ( 'update' === $options['action'] && 'plugin' === $options['type'] && isset( $options['plugins'] ) ) {
			foreach ( $options['plugins'] as $plugin ) {
				if ( $plugin === $our_plugin ) {
					set_transient( 'surfer_updated', 1 );
				}
			}
		}
	}
}
