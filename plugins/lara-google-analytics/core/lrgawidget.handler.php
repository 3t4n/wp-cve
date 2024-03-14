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

array_walk_recursive($_POST, "Lara\Widgets\GoogleAnalytics\SystemBootStrap::sanitizer");
$lrdata = $_POST;

if (isset($lrdata['action'])){
	
	if ($lrdata['action'] === "lrgawidget_hideShowWidget"){
		if ($lrdata['wstate'] === "show" || $lrdata['wstate'] === "hide"){
			DataStore::database_set("user_options", "wstate", $lrdata['wstate']);
			OutputHandler::jsonOutput(array("status" => "done"));
		}else{ErrorHandler::FatalError(__('Fatal Error', 'lara-google-analytics'),__('Invalid Call', 'lara-google-analytics'));}
	}elseif	($lrdata["action"] === "lrgawidget_review_response"){
		if ($lrdata['rresponse'] === "rated" || $lrdata['rresponse'] === "notinterested"){
			DataStore::database_set("global_options", "already_rated", $lrdata['rresponse']);
			OutputHandler::jsonOutput(array("status" => "done"));
		}else{ErrorHandler::FatalError(__('Fatal Error', 'lara-google-analytics'),__('Invalid Call', 'lara-google-analytics'));}
	}elseif(($lrdata['action'] === "lrgawidget_getPermissions") || ($lrdata['action'] === "lrgawidget_setPermissions")){

		$call = new Permissions();
		switch ($lrdata['action']) {
			case "lrgawidget_getPermissions":
				if (in_array("perm", DataStore::$RUNTIME["permissions"])){$call->getCurrentBlogRolesPermissions();}
				else{ ErrorHandler::FatalError(__('You do not have permission to access this tab!', 'lara-google-analytics'));}		
				break;	
			default:
				ErrorHandler::FatalError(__('Fatal Error', 'lara-google-analytics'),__('Invalid Call', 'lara-google-analytics'));
		}

	}else{
		require(lrgawidget_plugin_dir . "core/callURL.class.php");
		require(lrgawidget_plugin_dir . "core/GoogleAnalyticsAPI.class.php");
		require(lrgawidget_plugin_dir . "core/lrgawidget.class.php");
		$call = new LaraGoogleAnalyticsWidget();

		$call->setDateRange(date('Y-m-d', strtotime('-1 month')), date('Y-m-d'));

		switch ($lrdata['action']) {
			case "lrgawidget_getAuthURL":
				if (in_array("admin", DataStore::$RUNTIME["permissions"])){$call->getAuthURL($lrdata);}
				else{ ErrorHandler::FatalError(__('You do not have permission to access this tab!', 'lara-google-analytics'));}		
				break;	
			case "lrgawidget_getAccessToken":
				if (in_array("admin", DataStore::$RUNTIME["permissions"])){$call->getAccessToken($lrdata);}
				else{ ErrorHandler::FatalError(__('You do not have permission to access this tab!', 'lara-google-analytics'));}		
				break;	
			case "lrgawidget_getAccountSummaries":
				if (in_array("admin", DataStore::$RUNTIME["permissions"])){$call->getAccountSummaries($lrdata);}
				else{ ErrorHandler::FatalError(__('You do not have permission to access this tab!', 'lara-google-analytics'));}		
				break;
			case "lrgawidget_setMeasurementID":
				if (in_array("admin", DataStore::$RUNTIME["permissions"])){ $call->setMeasurementID($lrdata);}
				else{ ErrorHandler::FatalError(__('You do not have permission to access this tab!', 'lara-google-analytics'));}			
				break;
			case "lrgawidget_settingsReset":
				if (in_array("admin", DataStore::$RUNTIME["permissions"])){ $call->settingsReset();}
				else{ ErrorHandler::FatalError(__('You do not have permission to access this tab!', 'lara-google-analytics'));}			
				break;			
			case "lrgawidget_getMainGraph":
				if (in_array("sessions", DataStore::$RUNTIME["permissions"])){$call->getMainGraph();}
				else{ ErrorHandler::FatalError(__('You do not have permission to access this tab!', 'lara-google-analytics'));}
				break;
			case "lrgawidget_getPages":
				if (in_array("pages", DataStore::$RUNTIME["permissions"])){$call->getPages();}
				else{ ErrorHandler::FatalError(__('You do not have permission to access this tab!', 'lara-google-analytics'));}		
				break;			
			case "lrgawidget_getBrowsers":
				if (in_array("browsers", DataStore::$RUNTIME["permissions"])){$call->getBrowsers($lrdata);}
				else{ ErrorHandler::FatalError(__('You do not have permission to access this tab!', 'lara-google-analytics'));}		
				break;
			case "lrgawidget_getOS":
				if (in_array("os", DataStore::$RUNTIME["permissions"])){$call->getOS($lrdata);}
				else{ ErrorHandler::FatalError(__('You do not have permission to access this tab!', 'lara-google-analytics'));}		
				break;
			case "lrgawidget_getDevices":
				if (in_array("devices", DataStore::$RUNTIME["permissions"])){$call->getDevices($lrdata);}
				else{ ErrorHandler::FatalError(__('You do not have permission to access this tab!', 'lara-google-analytics'));}		
				break;
			case "lrgawidget_getLanguages":
				if (in_array("languages", DataStore::$RUNTIME["permissions"])){$call->getLanguages();}
				else{ ErrorHandler::FatalError(__('You do not have permission to access this tab!', 'lara-google-analytics'));}		
				break;	
			case "lrgawidget_getScreenResolution":
				if (in_array("screenres", DataStore::$RUNTIME["permissions"])){$call->getScreenResolution();}
				else{ ErrorHandler::FatalError(__('You do not have permission to access this tab!', 'lara-google-analytics'));}		
				break;
			case "lrgawidget_getGraphData":
				if (in_array("graph_options", DataStore::$RUNTIME["permissions"])){$call->getGraphData();}
				else{ ErrorHandler::FatalError(__('You do not have permission to access this tab!', 'lara-google-analytics'));}
				break;				
			default:
				ErrorHandler::FatalError(__('Fatal Error', 'lara-google-analytics'),__('Invalid Call', 'lara-google-analytics'));
		} 
	}
}else{ ErrorHandler::FatalError(__('Fatal Error', 'lara-google-analytics'),__('Invalid Call', 'lara-google-analytics'));}
?>