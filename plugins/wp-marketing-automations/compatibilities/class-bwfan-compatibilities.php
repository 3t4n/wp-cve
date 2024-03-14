<?php

/**
 * Class BWFAN_Compatibilities
 * Loads all the compatibilities files we have in Autonami against plugins
 */
class BWFAN_Compatibilities {

	public static function load_all_compatibilities() {

		/** Compatibilities folder */
		$dir = plugin_dir_path( BWFAN_PLUGIN_FILE ) . 'compatibilities';

		/** Include sub folders */
		foreach ( glob( $dir . '/*/class-*.php' ) as $_field_filename ) {
			require_once( $_field_filename );
		}

		/** Include direct files */
		foreach ( glob( $dir . '/class-*.php' ) as $_field_filename ) {
			if ( strpos( $_field_filename, 'class-bwfan-compatibilities.php' ) !== false ) {
				continue;
			}
			require_once( $_field_filename );
		}
	}
}

add_action( 'plugins_loaded', array( 'BWFAN_Compatibilities', 'load_all_compatibilities' ), 999 );
