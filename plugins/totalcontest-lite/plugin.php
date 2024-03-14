<?php
/*
 * Plugin Name: TotalContest – Lite
 * Plugin URI: https://totalsuite.net/products/totalcontest/
 * Description: Yet another powerful contest plugin for WordPress.
 * Version: 2.7.5
 * Author: TotalSuite
 * Author URI: https://totalsuite.net/
 * Text Domain: totalcontest
 * Domain Path: languages
 * Requires at least: 4.8
 * Requires PHP: 5.6
 * Tested up to: 6.4.3
 */



// Root plugin file name
define( 'TOTALCONTEST_ROOT', __FILE__ );

// TotalContest environment
$env = require dirname( __FILE__ ) . '/env.php';

// Include plugin setup
include_once dirname( __FILE__ ) . '/setup.php';

// Setup
$plugin = new TotalContestSetup( $env );
// Oh yeah, we're up and running!
