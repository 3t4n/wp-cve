<?php
/**
 * Deprecated functions. You shouldn't
 * use these functions or variables and look for the alternatives instead.
 * The functions will be removed in a later version.
 *
 * @since 2.0.0
 *
 * @package AutoClose
 */

// If this file is called directly, abort.
if ( ! defined( 'WPINC' ) ) {
	die;
}


/**
 * Holds the filesystem directory path.
 *
 * @since   1.0
 * @deprecated 2.0.0
 */
define( 'ALD_ACC_DIR', dirname( __FILE__ ) );


/**
 * Holds the filesystem directory path (with trailing slash) for AutoClose
 *
 * @since   1.4
 * @deprecated 2.0.0
 *
 * @var string
 */
$acc_path = plugin_dir_path( __FILE__ );


/**
 * Holds the URL for AutoClose
 *
 * @since   1.4
 * @deprecated 2.0.0
 *
 * @var string
 */
$acc_url = plugins_url() . '/' . plugin_basename( dirname( __FILE__ ) );



/**
 * Main function.
 *
 * @since   1.0
 * @deprecated 2.0.0
 */
function ald_acc() {

	_deprecated_function( __FUNCTION__, '2.0.0', 'acc_main()' );

	acc_main();
}


/**
 * Default options.
 *
 * @since   1.0
 * @deprecated 2.0.0
 *
 * @return array Default settings
 */
function acc_default_options() {

	_deprecated_function( __FUNCTION__, '2.0.0' );

	$comment_post_types = http_build_query( array( 'post' => 'post' ), '', '&' );
	$pbtb_post_types    = $comment_post_types;

	$acc_settings = array(
		'comment_age'         => '90',  // Close comments before these many days.
		'pbtb_age'            => '90',     // Close pingbacks/trackbacks before these many days.
		'comment_pids'        => '',   // Comments on these Post IDs to open.
		'pbtb_pids'           => '',      // Pingback on these Post IDs to open.
		'close_comment'       => false,   // Close Comments on posts.
		'close_comment_pages' => false, // Close Comments on pages.
		'close_pbtb'          => false,      // Close Pingbacks and Trackbacks on posts.
		'close_pbtb_pages'    => false,        // Close Pingbacks and Trackbacks on pages.
		'delete_revisions'    => false,        // Delete post revisions.
		'daily_run'           => false,       // Run Daily?
		'cron_hour'           => '0',     // Cron Hour.
		'cron_min'            => '0',      // Cron Minute.
		'comment_post_types'  => $comment_post_types,        // WordPress custom post types.
		'pbtb_post_types'     => $pbtb_post_types,      // WordPress custom post types.
	);

	return apply_filters( 'acc_default_options', $acc_settings );
}


/**
 * Function to read options from the database.
 *
 * @since   1.0
 * @deprecated 2.0.0
 *
 * @return array Options for the database. Will add any missing options.
 */
function acc_read_options() {

	_deprecated_function( __FUNCTION__, '2.0.0', 'acc_get_settings' );

	$acc_settings = acc_get_settings();

	return apply_filters( 'acc_read_options', $acc_settings );
}
