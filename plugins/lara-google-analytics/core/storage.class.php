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

class DataStore{
	
	public  static $RUNTIME         = array();

	private static $DATABASE        = array();
	private static $DATABASE_HASH   = "d751713988987e9331980363e24189ce"; 
	public  static $DATABASE_HASHES = array();	
   
	private static $SESSION         = array();
	private static $SESSION_HASH    = "d751713988987e9331980363e24189ce";

	public static function init_database($database){
		self::$DATABASE = $database;
		self::$DATABASE_HASH = md5(json_encode($database));
		foreach (self::$DATABASE as $group => $array){
			self::$DATABASE_HASHES[$group] = md5(json_encode($array));
		}
	}
	
	public static function init_session($session){
		self::$SESSION = $session;
		self::$SESSION_HASH = md5(json_encode($session));
	}
	
	public static function commit(){
		if(!hash_equals(md5(json_encode(self::$SESSION)), self::$SESSION_HASH)){
			SystemBootStrap::commit_session(self::$SESSION);
		}
		
		if(!hash_equals(md5(json_encode(self::$DATABASE)), self::$DATABASE_HASH)){
			$db = array();
			foreach (self::$DATABASE as $group => $array){
				if(empty(self::$DATABASE_HASHES[$group]) || (!hash_equals(md5(json_encode($array)), self::$DATABASE_HASHES[$group]))){
					$db[$group] = $array;
					$db[$group]["lr_timestamp"] = time();
				}
			}			
			SystemBootStrap::commit_database($db);
		}	
	}
	

	public static function database_get($group, $name){
		$value = null;
		if (!empty(self::$DATABASE[$group][$name])){
			$value = self::$DATABASE[$group][$name];
		}
		return $value;
	}	

	public static function database_set($group, $name, $value){
		if (empty($value)){self::database_delete($group, $name);}
		elseif (!empty($name)){
			self::$DATABASE[$group][$name] = $value;
		}
	}	

	public static function database_delete($group, $name){
		if (isset(self::$DATABASE[$group][$name])){
			unset(self::$DATABASE[$group][$name]);
		}
	}
	
	public static function session_get($group, $name){
		$value = null;
		if (!empty(self::$SESSION[$group][$name])){
			$value = self::$SESSION[$group][$name];
		}
		return $value;
	}	

	public static function session_set($group, $name, $value){
		if (empty($value)){self::session_delete($group, $name);}
		elseif (!empty($name)){
			self::$SESSION[$group][$name] = $value;
		}
	}	

	public static function session_delete($group, $name){
		if (isset(self::$SESSION[$group][$name])){
			unset(self::$SESSION[$group][$name]);
		}
	}	
	
	public static function reset_settings(){
		self::$DATABASE["settings"] = array();
		self::$SESSION["settings"]  = array();
	}
	
	public static function purge_cache($cachePrefix){
		if (!empty(self::$SESSION["cache"]) && is_array(self::$SESSION["cache"])){
			foreach (self::$SESSION["cache"] as $key => $value) {
				if(preg_match("/^".$cachePrefix."/s", $key)){ 
				   unset(self::$SESSION["cache"][$key]);
				}
			}
		}		
	}	

	public static function get_from_cache($cachePrefix, $queryID, $expires_in=0){
		if (isset(self::$SESSION["cache"][$cachePrefix.$queryID]["cache"])){
			if ((self::$SESSION["cache"][$cachePrefix.$queryID]["created_on"] + $expires_in) >=  time()){
				return self::$SESSION["cache"][$cachePrefix.$queryID]["cache"];
			}else{
				self::delete_from_cache($cachePrefix , $queryID);
			}
		}
	}

	public static function save_to_cache($cachePrefix , $queryID, $value){
		if (!empty($queryID) && !empty($value) && !empty($cachePrefix)){
			self::$SESSION["cache"][$cachePrefix.$queryID]["cache"] = $value;
			self::$SESSION["cache"][$cachePrefix.$queryID]["created_on"] = time();
		}		
	}

	public static function delete_from_cache($cachePrefix , $queryID){
		if (isset(self::$SESSION["cache"][$cachePrefix.$queryID])){
			unset(self::$SESSION["cache"][$cachePrefix.$queryID]);
		}
	}
}
?>