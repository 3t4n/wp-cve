<?php
/**
 * Setup import functions.
 *
 * @package Better_Admin_Bar
 */

namespace SwiftControl\Helpers;

/**
 * Setup widget import functions.
 */
class Import {
	/**
	 * Setup action & filter hooks.
	 */
	public function __construct() {}

	/**
	 * Run importer.
	 */
	public function import() {
		$options_meta = array(
			'swift_control_active_widgets',
			'swift_control_widget_settings',
			'swift_control_display_settings',
			'swift_control_color_settings',
			'swift_control_admin_bar_settings',
			'swift_control_misc_settings',
		);
		$import_file  = $_FILES['swift_control_import_file'];
		$file_name    = basename( sanitize_file_name( wp_unslash( $import_file['name'] ) ) );
		$explodes     = explode( '.', $file_name );
		$ext          = end( $explodes );

		// wp_check_filetype failed here, so check it manually.
		if ( 'json' !== $ext ) {
			add_settings_error(
				'swift_control_import',
				esc_attr( 'swift-control-invalid-import' ),
				__( 'Please upload a valid .json file', 'better-admin-bar' )
			);
			return;
		}

		$tmp_file = $import_file['tmp_name'];

		if ( empty( $tmp_file ) ) {
			add_settings_error(
				'swift_control_import',
				esc_attr( 'swift-control-import-not-exist' ),
				__( 'Please upload a file to import', 'better-admin-bar' )
			);
			return;
		}

		$imports = file_get_contents( $tmp_file, true );
		$imports = (array) json_decode( $imports, true );

		if ( ! $imports ) {
			add_settings_error(
				'swift_control_import',
				esc_attr( 'swift-control-empty-import' ),
				__( 'Your import is empty', 'better-admin-bar' )
			);
			return;
		}

		foreach ( $imports as $meta_key => $meta_value ) {
			if ( in_array( $meta_key, $options_meta, true ) ) {
				update_option( $meta_key, $meta_value );
			}
		}

		add_settings_error(
			'swift_control_import',
			esc_attr( 'swift-control-widgets-imported' ),
			__( 'Data imported', 'better-admin-bar' ),
			'success'
		);

	}
}
