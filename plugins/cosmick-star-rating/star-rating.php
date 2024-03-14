<?php
/**
 * Plugin Name: Cosmick Star Rating
 * Plugin URI: http://cosmicktechnologies.com/
 * Description: Google Organic Search Rich Snippets for Reviews and Rating.
 * Version: 1.2.2
 * Author: Cosmick Technologies
 * Author URI: http://cosmicktechnologies.com/
 * License: GPL2
 */

global $wpdb;

define('CSRVERSION', '1.2.2');
define('CSRVOTESTBL', $wpdb->prefix . 'csr_votes');

register_activation_hook( __FILE__, 'csr_install' );
register_deactivation_hook( __FILE__, 'csr_uninstall' );


require ( dirname(__FILE__) . '/functions.php' );