<?php
/*
Plugin Name: Category Country Aware Wordpress
Plugin URI: http://means.us.com
Description: Display different widget content depending on category and visitor location (country)
Author: Andrew Wrigley
Version: 1.2.3
Author URI: http://means.us.com/
*/

// outside of classes; constants and functions for "internal" use are prefixed "CCA_" for widget and "CCAX_" for extension/dashboard stuff
// CSS classes and user/developer filters/actions/shortcodes are prefixed "cca_" or "cca-" for CSS

// Exit if accessed directly
if ( ! defined( 'ABSPATH' ) ) exit;

// for update testing - uncomment in previous (i.e. currently installed) file do not uncomment in repository 
/*
require (WP_CONTENT_DIR . '/plugin-update-checker/plugin-update-checker.php');
$myUpdateChecker = Puc_v4_Factory::buildUpdateChecker('http://blog.XXXXXXXXXXXX.com/meta_cca.json',	__FILE__,	'category-country-aware');
*/

if (!defined('CCA_INIT_FILE'))define('CCA_INIT_FILE', __FILE__ );

if(require(dirname(__FILE__).'/inc/wp-php53.php')): // TRUE if running PHP v5.3+.
	require_once 'cca_textwidget.php';
else:
   wp_php53_notice('Category Country Aware Wordpress');
endif;
