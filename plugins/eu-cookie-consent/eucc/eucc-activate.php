<?php

if( !defined( 'ABSPATH' ) ) { exit; }

function rs_eucc_activate() {
	if( !rs_eucc_option_exists() ) {
		$option = rs_eucc_option_defaults();
		add_option( RS_EUCC__OPTION, $option );
	}
}