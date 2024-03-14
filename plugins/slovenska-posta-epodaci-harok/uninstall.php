<?php
/*
 * Fired when the plugin is uninstalled.
*/
if ( ! defined( 'WP_UNINSTALL_PLUGIN' ) ) { exit; }

//Delete plugin options
delete_option('tsseph_options');
