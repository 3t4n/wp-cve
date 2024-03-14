<?php
/**
 * Functions.php
 *
 * @package  Kelkoogroup_SalesTracking
 * @author   Kelkoo Group
 * @since    1.0.0
 */

if ( ! defined( 'ABSPATH' ) ) {
	exit; // Exit if accessed directly.
}


/**
 * functions.php
 * Add lead tag
 */
function kelkoogroup_salestracking_call_leadtag_js() {
    echo '<script async="true" type="text/javascript" src="https://s.kk-resources.com/leadtag.js" ></script>';
}

add_action( 'wp_head', 'kelkoogroup_salestracking_call_leadtag_js' );
