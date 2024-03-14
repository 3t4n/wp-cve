<?php
/*
Plugin Name: Easy Mailchimp Opt-in
Plugin URL: http://www.mailchimp.com
Description: Very nice and professional Opt-in form for Mailchimp list. 
Version: 1.3
Author: Mahfuzar
Author URI: http://www.mahfuzar.info
Contributors: mahfuzar
*/

/**************************************************
* CONSTANTS
**************************************************/

if(!defined('PMC_PLUGIN_DIR')) {
	define('PMC_PLUGIN_DIR', dirname(__FILE__));	
}

/**************************************************
* globals
**************************************************/

global $pmc_options;

$pmc_options = get_option('pmc_mc_settings');

/**************************************************
* languages
**************************************************/

load_plugin_textdomain( 'pmc', false, dirname( plugin_basename( __FILE__ ) ) . '/languages/' );


/**************************************************
* includes
**************************************************/

include_once(PMC_PLUGIN_DIR . '/includes/settings.php');
include_once(PMC_PLUGIN_DIR . '/includes/functions.php');
include_once(PMC_PLUGIN_DIR . '/includes/widgets.php');
include_once(PMC_PLUGIN_DIR . '/includes/form.php');


