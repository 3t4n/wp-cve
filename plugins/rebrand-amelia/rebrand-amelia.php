<?php
/**
 * Plugin Name: 	Rebrand Amelia 
 * Plugin URI: 	    https://rebrandpress.com/rebrand-amelia/
 * Description: 	Amelia is an automated booking specialist that allows customers to book appointments or make payments with a simple plugin. Thanks to Rebrand Amelia, you can remove any Amelia branded colors or messaging and replace it with your own. Change the plugin’s name and description and make the calendar look like it’s native to your website, among other features. 
 * Version:     	1.0
 * Author:      	RebrandPress
 * Author URI:  	https://rebrandpress.com/
 * License:     	GPL2 etc
 * Network:         Active
*/

if (!defined('ABSPATH')) { exit; }				

if ( !class_exists('Rebrand_Amelia_Pro') ) {
	
	class Rebrand_Amelia_Pro {
		
		public function bzrap_load()
		{
			global $bzrap_load;
			load_plugin_textdomain( 'bzrap', false, dirname( plugin_basename( __FILE__ ) ) . '/languages' ); 

			if ( !isset($bzrap_load) )
			{
			  require_once(__DIR__ . '/blitz-rap-settings.php');
			  $PluginRAP = new BZ_RAP\BZRebrandAmeliaSettings;
			  $PluginRAP->init();
			}
			return $bzrap_load;
		}
		
	}
}
$PluginRebrandAmelia = new Rebrand_Amelia_Pro;
$PluginRebrandAmelia->bzrap_load();
