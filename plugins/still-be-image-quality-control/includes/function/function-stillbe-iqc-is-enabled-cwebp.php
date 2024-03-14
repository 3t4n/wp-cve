<?php

// Do not allow direct access to the file.
if( ! defined( 'ABSPATH' ) ) {
	exit;
}




// Check if "cwebp" is Enabled
function stillbe_iqc_is_enabled_cwebp() {

	if( ! stillbe_iqc_is_extended() ) {
		return false;
	}

	return stillbe_iqc_extends_chk_cwebp();

}





// END

?>