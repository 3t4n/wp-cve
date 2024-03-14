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

class ErrorHandler {
  private static $errors;
  private static $debugMode = false;
  
  public static function FatalError( $error, $error_description = null, $code = null , $debug = array() ) {
	  self::$errors["error"] = $error;
	  self::$errors["error_description"] = $error_description;
	  self::$errors["code"] = $code;
	  if (self::$debugMode){
		  self::$errors["debug"] = $debug;
	  }
	  if (defined("lrgawidget_output_mode") && lrgawidget_output_mode === "admin"){
		  self::$errors["debug"] = $debug;
		  DataStore::$RUNTIME["FatalError"][] = self::$errors;
	  }else{
		  header('Content-Type: application/json; charset=utf-8'); 
		  echo json_encode(self::$errors, JSON_FORCE_OBJECT);
		  exit();
	  }		
  }
  
  public static function setDebugMode($debugMode){
      self::$debugMode = $debugMode;
  }

}

class OutputHandler {
	
	public static function jsonOutput($output){
		header('Content-Type: application/json; charset=utf-8');
		if (empty(DataStore::$RUNTIME["FatalError"])){
			DataStore::commit();
		}
		echo json_encode($output, true);
		exit();
	}
}

?>