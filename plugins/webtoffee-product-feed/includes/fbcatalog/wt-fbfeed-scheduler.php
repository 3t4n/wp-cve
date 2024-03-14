<?php

if ( !defined( 'ABSPATH' ) ) {
	exit;
}

/**
 * Schedule an action with the hook 'wt_fbfeed_midnight_sync' to run at midnight each day
 * so that our callback is run then.
 */
function wt_schedule_midnight_sync() {
	if ( false === as_next_scheduled_action( 'wt_fbfeed_midnight_sync' ) ) {
		as_schedule_recurring_action( strtotime( 'tomorrow' ), DAY_IN_SECONDS, 'wt_fbfeed_midnight_sync' );
	}
}

//add_action( 'init', 'wt_schedule_midnight_sync' );

/**
 * A callback to run when the 'eg_midnight_log' scheduled action is run.
 */
function wt_fbfeed_midnight_sync_process() {
	error_log( 'It is just after midnight on ' . date( 'Y-m-d' ) );
	$fb_feed_settings = new WT_Fb_Catalog_Manager_Settings();
	$fb_feed_settings->wt_fbfeed_process_upload();
}

//add_action( 'wt_fbfeed_midnight_sync', 'wt_fbfeed_midnight_sync_process' );
