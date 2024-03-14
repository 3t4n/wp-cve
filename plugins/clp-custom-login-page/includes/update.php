<?php 

defined( 'ABSPATH' ) or die( 'No script kiddies please!' );

// set option for plugin update process
if ( !get_option( 'clp_version' ) ) {
	update_option( 'clp_version', CLP_VERSION );
}
// $pre_update_version = get_option('niteoCS_version');

// // 
// if ( version_compare( $pre_update_version, '1.5.0' ) < 0 ) {
//     // 
// 	// bump version for next udpate check
// }
// update_option( 'clp_version', CLP_VERSION );