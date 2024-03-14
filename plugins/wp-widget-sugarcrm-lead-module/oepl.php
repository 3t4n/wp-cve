<?php
 /*
 Plugin Name: WordPress to Sugar / SuiteCRM Lead
 Plugin URI: https://offshoreevolution.com/
 Description: This plugin will provide a Widget Form anywhere you want for easy,fast & hassle-free SugarCRM Leads.
 Version: 5.5
 Author: Offshore Evolution Pvt Ltd
 Author URI: https://offshoreevolution.com/
 License: GPL
 */
require_once ("oepl.conf.php");

/* Runs when plugin is activated */
register_activation_hook(__FILE__, 'WP2SL_Activate');

function WP2SL_Activate() {
	$ins = new WP2SLSugarCRMClass;
	$ins->wp2sl_activate();
}

if (is_admin()) {
	add_action('admin_menu', 'WP2SL_CreateMenu');
}