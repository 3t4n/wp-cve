<?php
/*
Plugin Name: Addon Library Layouts Builder
Plugin URI: http://addon-library.com
Description: Addon Library Layouts Builder - Create and edit layouts from addon library addons
Author: Unite CMS
Version: 1.1.3
Author URI: http://addon-library.com
*/

//ini_set("display_errors", "on");
//ini_set("error_reporting", E_ALL);

if(!defined("LAYOUTS_EDITOR_INC"))
	define("LAYOUTS_EDITOR_INC", true);

if(!defined("ADDONLIBRARY_TEXTDOMAIN"))	
	define("ADDONLIBRARY_TEXTDOMAIN", "addon_library");

	
$mainFilepath = __FILE__;
$currentFolder = dirname($mainFilepath);


try{
	require_once $currentFolder.'/includes.php';
	require_once "inc_php/main_file.php";
	
}catch(Exception $e){
	$message = $e->getMessage();
	$trace = $e->getTraceAsString();
	echo "<br>";
	echo $message;
	echo "<pre>";
	print_r($trace);
}


