<?php
/**
 * @package Addon Library
 * @author UniteCMS.net
 * @copyright (C) 2012 Unite CMS, All Rights Reserved. 
 * @license GNU/GPLv3 http://www.gnu.org/licenses/gpl-3.0.html
 * */
defined('LAYOUTS_EDITOR_INC') or die('Restricted access');


	class LayoutEditorGlobals{
		
		const PLUGIN_TITLE = "Addon Library Layouts Builder";
		const PLUGIN_NAME = "addon-library-layouts";
		public static $pathPlugin;
		public static $pathViews;
		public static $urlPlugin;
		public static $urlComoponentAdmin;
		
		
		/**
		 * init globals
		 */
		public static function initGlobals(){
			
			self::$pathPlugin = realpath(dirname(__FILE__)."/../")."/";
			self::$pathViews = self::$pathPlugin."views/";
			
			self::$urlComoponentAdmin = admin_url()."admin.php?page=".self::PLUGIN_NAME;
			
			self::$urlPlugin = plugins_url(self::PLUGIN_NAME)."/";
			
			//LayoutEditorGlobals::printVars();
		}

		
		/**
		 * print all globals variables
		 */
		public static function printVars(){
			$methods = get_class_vars( "LayoutEditorGlobals" );
			dmp($methods);
			exit();
		}
		
	}

	//init the globals
	LayoutEditorGlobals::initGlobals();
	
?>
