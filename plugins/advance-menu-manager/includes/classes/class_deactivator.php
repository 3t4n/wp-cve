<?php
/**
 * Fired during plugin deactivation
 *
 * @link       http://multidots.com
 * @since      1.0.0
 *
 * @package    Multidots Advance Menu Manager
 * @subpackage advance-menu-manager/includes/classes/
 */

/**
 * Fired during plugin deactivation.
 *
 * This class revert all code necessary to run during the plugin's deactivation.
 *
 * @since      1.0.0
 * @package    Multidots Advance Menu Manager
 * @subpackage advance-menu-manager/includes/classes
 * @author     Multidots Solutions Pvt. Ltd. <info@multidots.com>
 */
class DSAMM_Deactivator{
	
	/**
	 * This function runs when plugin deactivates. (use period)
	 *
	 * This function executes when plugin deactivates.
	 *
	 * @since    1.0.0
	 */
	function __construct() {
		
	}
	
	/**
	 * parent_plugin_notice function
	 *
	 * This is the notice hook, runs when plugin is not activated.
	 *
	 * @version		1.0.0
	 * @author 		theDotstore
	 */
	function dsamm_plugin_deactivation_notice() {
	    
	}
}