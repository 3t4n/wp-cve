<?php
/**
 * Fired during plugin updates
 *
 * @link       https://shapedplugin.com/
 * @since      2.0.5
 *
 * @package    WP_Team
 * @subpackage WP_Team/includes
 */

namespace ShapedPlugin\WPTeam\Admin;

// don't call the file directly.
if ( ! defined( 'ABSPATH' ) ) {
	exit;
}

/**
 * Fired during plugin updates.
 *
 * This class defines all code necessary to run during the plugin's updates.
 *
 * @since      2.0.5
 * @package    WP_Team
 * @subpackage WP_Team/includes
 * @author     ShapedPlugin <support@shapedplugin.com>
 */
class DB_Updater {

	/**
	 * DB updates that need to be run
	 *
	 * @var array
	 */
	private static $updates = array(
		'2.0.5'  => 'updates/update-2.0.5.php',
		'2.1.0'  => 'updates/update-2.1.0.php',
		'2.2.6'  => 'updates/update-2.2.6.php',
		'2.2.13' => 'updates/update-2.2.13.php',
		'2.2.15' => 'updates/update-2.2.15.php',
		'3.0.0'  => 'updates/update-3.0.0.php',
	);

	/**
	 * Binding all events
	 *
	 * @since 2.0.5
	 *
	 * @return void
	 */
	public function __construct() {
		add_action( 'plugins_loaded', array( $this, 'do_updates' ) );
	}

	/**
	 * Check if need any update
	 *
	 * @since 2.0.5
	 *
	 * @return boolean
	 */
	public function is_needs_update() {
		$installed_version = get_option( 'sp_wp_team_version' );
		$first_version     = get_option( 'sp_wp_team_first_version' );
		$activation_date   = get_option( 'sp_wp_team_activation_date' );

		if ( false === $installed_version ) {
			update_option( 'sp_wp_team_version', SPT_PLUGIN_VERSION );
			update_option( 'sp_wp_team_db_version', SPT_PLUGIN_VERSION );
		}
		if ( false === $first_version ) {
			update_option( 'sp_wp_team_first_version', SPT_PLUGIN_VERSION );
		}
		if ( false === $activation_date ) {
			update_option( 'sp_wp_team_activation_date', current_time( 'timestamp' ) );
		}

		if ( version_compare( $installed_version, SPT_PLUGIN_VERSION, '<' ) ) {
			return true;
		}

		return false;
	}

	/**
	 * Do updates.
	 *
	 * @since 2.0.5
	 *
	 * @return void
	 */
	public function do_updates() {
		$this->perform_updates();
	}

	/**
	 * Perform all updates
	 *
	 * @since 2.0.5
	 *
	 * @return void
	 */
	public function perform_updates() {
		if ( ! $this->is_needs_update() ) {
			return;
		}

		$installed_version = get_option( 'sp_wp_team_version' );

		foreach ( self::$updates as $version => $path ) {
			if ( version_compare( $installed_version, $version, '<' ) ) {
				include $path;
				update_option( 'sp_wp_team_version', $version );
			}
		}

		update_option( 'sp_wp_team_version', SPT_PLUGIN_VERSION );

	}

}
