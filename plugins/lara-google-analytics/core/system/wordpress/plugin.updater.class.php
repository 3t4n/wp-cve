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

global $wpdb;
define ("lrgawidget_legacy_plugin_table", $wpdb->base_prefix . 'lrgawidget_global_settings');
define ("lrgawidget_legacy_plugin_prefiex", "lrgalite-");

class PluginUpdater {

	private static function legacy_is_analytics($str){
		return (bool) preg_match('/^ua-\d{4,20}(-\d{1,10})?$/i', $str);
	}	
	
	public static function update($version){
		global $wpdb;
		if (version_compare($version, '3.0.0', '<')){
			$old_settings = array();
			$results = $wpdb->get_results ( "SELECT `name`, `value` FROM  `".lrgawidget_legacy_plugin_table."`", ARRAY_A );
			if (!empty($results)){
				foreach ($results as $setting) {
					$old_settings[$setting['name']] = $setting['value'];
				}			
			}

			$property_id = get_option('lrgawidget_property_id',"");
			if ( (!empty($property_id) && !self::legacy_is_analytics($property_id)) || (!empty($old_settings['property_id']) && !self::legacy_is_analytics($old_settings['property_id']))){
				$wpdb->query("TRUNCATE TABLE `".lrgawidget_legacy_plugin_table."`");
				if (!session_id()){session_start();}
				foreach ($_SESSION as $key => $value) {
					if(preg_match('/^lrgatmp_/s', $key)){
						unset($_SESSION[$key]);
					}
				}
				$property_id = "";
				$old_settings = array();
			}
			
			if (!empty($property_id) && self::legacy_is_analytics($property_id)) {
				$wpdb->insert( lrgawidget_legacy_plugin_table, array( 'name' => 'enable_universal_tracking', 'value' => 'on'));
			}else{
				if(!empty($old_settings)){
					$wpdb->insert( lrgawidget_legacy_plugin_table, array( 'name' => 'enable_universal_tracking', 'value' => 'off'));
				}
			}
			delete_option('lrgawidget_property_id');
		}
		if (version_compare($version, '3.2.0', '<')){
			$results = $wpdb->get_results ( "SELECT `name`, `value` FROM  `".lrgawidget_legacy_plugin_table."`", ARRAY_A );
			$settings = array();
			if (!empty($results)){
				foreach ($results as $setting) {
					$settings[$setting['name']] = $setting['value'];
				}			
			}
			if (empty($settings["enable_ecommerce_graph"])){
				$wpdb->insert( lrgawidget_legacy_plugin_table, array( 'name' => 'enable_ecommerce_graph', 'value' => 'on'));
			}
		}

		if (version_compare($version, '3.3.0', '<')){
			$results = $wpdb->get_results ( "SELECT `name`, `value` FROM  `".lrgawidget_legacy_plugin_table."`", ARRAY_A );
			$settings = array();
			if (!empty($results)){
				foreach ($results as $setting) {
					$settings[$setting['name']] = $setting['value'];
				}			
			}
			
			$wpdb->query("TRUNCATE TABLE `".lrgawidget_legacy_plugin_table."`");
			$wpdb->insert( lrgawidget_legacy_plugin_table, array( 'name' => 'settings', 'value' => json_encode($settings, JSON_FORCE_OBJECT)), array('%s', '%s'));
			
			#initiate global options
			$global_options = array();
			
			$install_date  = get_network_option(1,lrgawidget_legacy_plugin_prefiex.'install_date', '');
			if(!empty($install_date)){$global_options["install_date"] = $install_date;}
			delete_network_option( 1, lrgawidget_legacy_plugin_prefiex.'install_date' );
			
			$already_rated = get_network_option(1,lrgawidget_legacy_plugin_prefiex.'already_rated', '');
			if(!empty($already_rated) && $already_rated != "no"){$global_options["already_rated"] = $already_rated;}
			delete_network_option( 1, lrgawidget_legacy_plugin_prefiex.'already_rated' );
			
			if(!empty($global_options)){
				$value = json_encode($global_options, JSON_FORCE_OBJECT);
				if (!is_multisite()){
					update_option(lrgawidget_legacy_plugin_prefiex.'global_options', $value, 'yes' );
				}else{
					update_network_option(1, lrgawidget_legacy_plugin_prefiex.'global_options', $value );
				}
			}			
			
			delete_option('lrgawidget_property_id');
			delete_network_option( 1, lrgawidget_legacy_plugin_prefiex.'version' );
		}
	}
}
?>