<?php
/**
 * Setup export functions.
 *
 * @package Better_Admin_Bar
 */

namespace SwiftControl\Helpers;

/**
 * Setup widget export functions.
 */
class Export {
	/**
	 * Setup action & filter hooks.
	 */
	public function __construct() {}

	/**
	 * Run importer.
	 */
	public function export() {
		$options_data = array();

		$options_meta = array(
			'swift_control_active_widgets',
			'swift_control_widget_settings',
			'swift_control_display_settings',
			'swift_control_color_settings',
			'swift_control_admin_bar_settings',
			'swift_control_misc_settings',
		);

		foreach ( $options_meta as $meta_key ) {
			$options_data[ $meta_key ] = get_option( $meta_key, array() );
		}

		header( 'Content-disposition: attachment; filename=swift-control-export-' . date( 'Y-m-d-H.i.s', strtotime( 'now' ) ) . '.json' );
		header( 'Content-type: application/json' );

		echo wp_json_encode( $options_data );

		exit;
	}
}
