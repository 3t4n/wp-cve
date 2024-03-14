<?php
// if we're not uninstalling..
if( !defined( 'WP_UNINSTALL_PLUGIN' ) )
	exit();

// clean up..
delete_option( 'dilli_labs_email_validator' );