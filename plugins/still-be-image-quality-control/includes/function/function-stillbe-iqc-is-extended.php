<?php

// Do not allow direct access to the file.
if( ! defined( 'ABSPATH' ) ) {
	exit;
}




// Check if the Extends Plugin is Installed
function stillbe_iqc_is_extended() {

	if( ! function_exists( 'stillbe_iqc_extends_conv_cwebp' ) ||
	    ! function_exists( 'stillbe_iqc_extends_chk_cwebp' )  ||
	    ! function_exists( 'stillbe_iqc_extends_chk_near_lossless' ) ) {
		return false;
	}

	if( ! defined( 'STILLBE_IQ_EXT_PLUGIN_VER' ) ||
	    version_compare( STILLBE_IQ_EXT_PLUGIN_VER, STILLBE_IQ_REQUIRED_EXT_PLUGIN_VER, '<' ) ) {
		return false;
	}

	return true;

}





// END

?>