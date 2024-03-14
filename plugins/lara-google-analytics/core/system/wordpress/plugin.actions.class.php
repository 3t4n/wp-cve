<?php
namespace Lara\Widgets\GoogleAnalytics;

/**
 * @package    Google Analytics by Lara
 * @author     Amr M. Ibrahim <mailamr@gmail.com>
 * @link       https://www.xtraorbit.com/
 * @copyright  Copyright (c) XtraOrbit Web development SRL 2016 - 2020
 */

if (!defined("ABSPATH"))
    die("This file cannot be accessed directly");
	
class PluginActions {	

	public static function activate() {
		if ( version_compare( PHP_VERSION, '5.6', '<' ) ) {
			deactivate_plugins( lrgawidget_plugin_main_file );
			wp_die('<p>'.sprintf('This plugin can not be activated because it requires a PHP version greater than <b>5.6.0</b>.<br>You are currently using PHP <b>%1$s</b>.<br><br>Your PHP version can be updated by your hosting company.',PHP_VERSION).'</p><a href="'. admin_url('plugins.php').'">Go back</a>');
		}else{		
			global $wpdb;
			if ( $wpdb->get_var( $wpdb->prepare( "SHOW TABLES LIKE %s", lrgawidget_plugin_table ) ) != lrgawidget_plugin_table ) {
				$wpdb->query("CREATE TABLE `".lrgawidget_plugin_table."` (`id` int(10) NOT NULL AUTO_INCREMENT, `name` TEXT NOT NULL, `value` TEXT NOT NULL, PRIMARY KEY (`id`))");
				$global_options = array("version" => lrgawidget_plugin_version);
				if (!is_multisite()){
					update_option(lrgawidget_plugin_prefiex.'global_options', json_encode($global_options, JSON_FORCE_OBJECT), 'yes' );
				}else{
					update_network_option(1, lrgawidget_plugin_prefiex.'global_options', json_encode($global_options, JSON_FORCE_OBJECT) );
				}
			}
		}
	}

	public static function uninstall() {
		global $wpdb;
		$wpdb->query("DROP TABLE `".lrgawidget_plugin_table."`");
		delete_network_option( 1, lrgawidget_plugin_prefiex.'global_options' );
	}
}

?>