<?php

if( !defined( 'ABSPATH' ) ) { exit; }

function rs_eucc_deactivate() {
	$option = rs_eucc_get_option();
	if( $option['delete_option_on_deactivate'] === '1' ) {
		delete_option( RS_EUCC__OPTION );
	}
}