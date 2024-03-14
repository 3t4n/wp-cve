<?php

/* EXIT IF FILE IS CALLED DIRECTLY */

if ( ! defined( 'ABSPATH' ) ) {
	exit;
}

/* FOR UPGRADE FUNCTIONS */

function iprm_upgrade_check() {
	global $iprm_current_version;
	$version = iprm_get_option( 'iprm_current_version' );
	if ( $iprm_current_version !== $version ) {
		/* REMOVE OLD KEYS */
	}
}
