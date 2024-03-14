<?php
/**
 * Class to handle all plugin upgrades
 * @since      1.1
 * @package    GeoTarget
 * @subpackage GeoTarget/includes
 * @author     Damian Logghe <info@timersys.com>
 */

class GeoTarget_Upgrader {

	/**
	 * The version of this plugin.
	 *
	 * @since    1.1
	 * @access   private
	 * @var      string    $version    The current version of this plugin.
	 */
	private $version;


	public function upgrade_plugin() {
		global $wpdb;
		$current_version = get_option( 'geot_version' );

		// drop old table
		if( empty($current_version) || version_compare( $current_version, 1.1, '<' ) ) {
			$drop_table = "DROP TABLE `{$wpdb->base_prefix}Maxmind_geoIP`;";
			$wpdb->query( $drop_table );
		}
		// show feedback box if updating plugin
		if( !empty($current_version) && version_compare( $current_version, GEOT_VERSION, '<' )) {
			update_option('geot_plugin_updated', true);
		}
	}

}
