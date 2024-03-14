<?php
/**
 * Iubenda Plugin Instance Initialization
 *
 * This file initializes the iubenda plugin.
 * It provides a function 'iubenda()' to get the plugin instance.
 *
 * @package iubenda
 */

/**
 * Initialize iubenda Privacy Controls and Cookie Solution.
 *
 * @return iubenda The iubenda plugin instance.
 */
function iubenda() {
	static $instance;

	// The first call to instance() initializes the plugin.
	if ( null === $instance || ! ( $instance instanceof iubenda ) ) {
		$instance = iubenda::instance();
	}

	return $instance;
}

// Create an instance of the iubenda plugin.
$iubenda = iubenda();
