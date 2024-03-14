<?php
/**
 * Fired during plugin activation.
 * This class defines all code necessary to run during the plugin's activation.
 *
 * @since      1.0.0
 * @package    Wpgsi
 * @subpackage Wpgsi/includes
 * @author     javmah <jaedmah@gmail.com>
 */
class Wpgsi_Activator {

	/**
	 * Installed and reinstall date;
	 *
	 * this is important for tracking  and time base notification;
	 * 
	 * @since    1.0.0
	 */
	public static function activate(){
		# Stop Duala Installation or aka Error Handler 
		$active_plugins = get_option('active_plugins');
		
		if(in_array('wpgsi/wpgsi.php', $active_plugins)){
			die('<h3>Please uninstall & remove the Free version of this plugin before installing the Professional version ! </h3>');
		}

		# Setting the Instal time 
		$installed = get_option("wpgsi_installed");

		if(! $installed){
			update_option("wpgsi_installed", time());				# first time installed date;
		}else{
			update_option("wpgsi_re_installed", time());			# last time installed date;
		}
	}
}

