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

define ('lrgawidget_plugin_client_id', "789117741534-0n8ad88vo7jepbi04nr3bhvp8b4lj3re.apps.googleusercontent.com");
define ('lrgawidget_plugin_client_secret', "2d2gTzTdXcp8Hg1NAcql7MRf");
define ('lrgawidget_plugin_redirect_uri', "https://auth.xtraorbit.com");

define ("lrgawidget_system_bootstrap_loaded", true);

class SystemBootStrap {
	private static $initialized  = false;
	private static $session_token;
	private static $user_id;
	
	public static function initInstance() {

		if (self::$initialized){
			return;
		}

		self::$user_id       = self::current_user_id();
		self::$session_token = self::current_session_token();
		self::$initialized  = true;
    }
	
	public static function sanitizer(&$value) {
	  $value = trim(htmlspecialchars($value, ENT_QUOTES, 'UTF-8'));
	}
	

	private static function current_session_token(){
		return wp_get_session_token();
	}

	private static function current_user_id(){
		return get_current_user_id();
	}

	public static function getRoles(){
		global $wp_roles;
		$currentRoles = array();
		$roles = $wp_roles->roles;
		foreach ($roles as $role => $properties){
			$currentRoles[] = array('id' => $role, 'name' => $properties['name']);
		}
		return $currentRoles;
	}

	public static function getSystemTimeZone(){
		return date_default_timezone_get();
	}

	private static function get_data_from_database(){
		global $wpdb;
		$results = array("settings"=>array());
        $call = $wpdb->get_results ( "SELECT `name`, `value` FROM  `".lrgawidget_plugin_table."`", ARRAY_A);
		if (!empty($wpdb->last_error)){ErrorHandler::FatalError(__('Fatal Error', 'lara-google-analytics'),__('DataBase Error .. please contact an administrator', 'lara-google-analytics'),10001,$wpdb->last_error);}
		if ((empty($wpdb->last_error)) && !empty($call)){
			foreach($call as $result){
				$results[$result["name"]] = json_decode($result["value"], true);
			}
		}
		return $results;
	}
	
	private static function set_field_to_database($name, $array){
		global $wpdb;
		$value = json_encode($array, JSON_FORCE_OBJECT);
		$sql    = $wpdb->prepare ( "SELECT `name`, `value` FROM  `".lrgawidget_plugin_table."`  WHERE `name` = %s", array($name)); 
		$exists = $wpdb->get_results( $sql , ARRAY_A );
		if (!empty($wpdb->last_error)){ErrorHandler::FatalError(__('Fatal Error', 'lara-google-analytics'),__('DataBase Error .. please contact an administrator', 'lara-google-analytics'),10002,$wpdb->last_error);}
		if (!empty($exists)){
			$wpdb->update( lrgawidget_plugin_table, array('value' => $value), array('name' => $name),array('%s'),array('%s'));
			if (!empty($wpdb->last_error)){ErrorHandler::FatalError(__('Fatal Error', 'lara-google-analytics'),__('DataBase Error .. please contact an administrator', 'lara-google-analytics'),10003,$wpdb->last_error);}
		}else{
			$wpdb->insert( lrgawidget_plugin_table, array( 'name' => $name, 'value' => $value), array('%s', '%s'));
			if (!empty($wpdb->last_error)){ErrorHandler::FatalError(__('Fatal Error', 'lara-google-analytics'),__('DataBase Error .. please contact an administrator', 'lara-google-analytics'),10004,$wpdb->last_error);}
		}
	}	
	
	private static function get_global_options(){
		$options = get_network_option(1,lrgawidget_plugin_prefiex.'global_options', "{}");
		$global_options = json_decode($options, true);
		return $global_options;
	}
	
	private static function set_global_options($array){
		$value = json_encode($array, JSON_FORCE_OBJECT);
		if (!is_multisite()){
			update_option(lrgawidget_plugin_prefiex.'global_options', $value, 'yes' );
		}else{
			update_network_option(1, lrgawidget_plugin_prefiex.'global_options', $value );
		}
	}
	
	private static function init_user_options(){
		$user_options = array();
		$wstate = get_user_option('lrgawidget_hideShowWidget', self::$user_id);
		$user_options["wstate"] = "show";
		if ($wstate === "hide"){ $user_options["wstate"] = "hide";}
		delete_user_option(self::$user_id, 'lrgawidget_hideShowWidget');
		self::set_user_options($user_options);
		return $user_options;
	}	
	
	private static function get_user_options(){
		$user_options = array();
		$options = get_user_option(lrgawidget_plugin_prefiex.'user_options', self::$user_id);
		if($options !== false){
			$user_options = json_decode($options, true);
		}else{
			$user_options = self::init_user_options();
		}
		return $user_options;
	}

	public static function set_user_options($array){
		$value = json_encode($array, JSON_FORCE_OBJECT);
		update_user_option(self::$user_id, lrgawidget_plugin_prefiex.'user_options', $value);
	}	

	public static function init_session(){
		$session = array();
		if (!empty(self::$session_token)){
			$transient = get_site_transient( lrgawidget_plugin_prefiex . self::$session_token);
			if($transient !== false){
				$session = json_decode($transient, true);
			}
		}
		DataStore::init_session($session);
	}	
	
	public static function init_database(){
		$db = array();
		$db["global_options"] = self::get_global_options();
		$db["user_options"]   = self::get_user_options();
		$db_data = self::get_data_from_database();
		$db["settings"]       = $db_data["settings"];
		DataStore::init_database($db);
	}
	
	public static function commit_session($session){
		if (!empty(self::$session_token)){
			set_site_transient( lrgawidget_plugin_prefiex . self::$session_token, json_encode($session, JSON_FORCE_OBJECT), 3600);
		}
	}

	public static function commit_database($db){

		if(isset($db["global_options"]) && is_array($db["global_options"])){
			self::set_global_options($db["global_options"]);
		}

		if(isset($db["user_options"]) && is_array($db["user_options"])){
			self::set_user_options($db["user_options"]);
		}

		if(isset($db["settings"]) && is_array($db["settings"])){
			self::set_field_to_database("settings", $db["settings"]);
		}		

	}
}

SystemBootStrap::initInstance();

?>