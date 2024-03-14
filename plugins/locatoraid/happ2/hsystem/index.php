<?php if (! defined('ABSPATH')) exit; // Exit if accessed directly
if ( version_compare( PHP_VERSION, '5.3', '<' ) ) {
	echo "This software requires PHP version 5.3 or newer, yours is " . PHP_VERSION;
	exit;
}
require( dirname(__FILE__) . '/index2.php' );