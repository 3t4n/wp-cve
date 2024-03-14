<?php
/**
 * @package SiteSuperCharger
 */
/*
Plugin Name: SiteSuperCharger
Description: Supercharges your website for the best SEO results.
Author: Marketing Heroes
Version: 5.3.1
Author URI: http://mheroes.com/
*/

if ( ! defined( 'ABSPATH' ) ) header("location:/");

register_activation_hook( __FILE__, array('Ssc_SuperCharger', 'activation') );
register_deactivation_hook( __FILE__, array('Ssc_SuperCharger', 'deactivation') );

require_once( plugin_dir_path(__FILE__) . '/classes/class.ssc_supercharger.php' );

add_action( 'plugins_loaded', array( 'Ssc_SuperCharger', 'Ssc_plugin_update_check' ) );

add_action( 'init', array( 'Ssc_SuperCharger', 'init' ) );

