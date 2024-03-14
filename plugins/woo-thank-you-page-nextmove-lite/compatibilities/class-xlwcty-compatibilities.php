<?php
defined( 'ABSPATH' ) || exit;

/**
 * Class XLWCTY_Compatibilities
 * Loads all the compatibilities files we have in finale against plugins
 */
class XLWCTY_Compatibilities {


	public static function load_all_compatibilities() {
		// load all the XLWCTY_Compatibilities files automatically
		foreach ( glob( plugin_dir_path( XLWCTY_PLUGIN_FILE ) . '/compatibilities/*.php' ) as $_field_filename ) {
			$file_data = pathinfo( $_field_filename );

			if ( isset( $file_data['basename'] ) && 'index.php' === $file_data['basename'] ) {
				continue;
			}

			require_once( $_field_filename );
		}
	}
}


//hooked over 999 so that all the plugins got initiaed by that time
add_action( 'plugins_loaded', array( 'XLWCTY_Compatibilities', 'load_all_compatibilities' ), 999 );
