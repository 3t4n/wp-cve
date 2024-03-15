<?php
/**
 * @package Unite Gallery
 * @author Valiano
 * @copyright (C) 2022 Unite Gallery, All Rights Reserved. 
 * @license GNU/GPLv3 http://www.gnu.org/licenses/gpl-3.0.html
 * */
defined('UNITEGALLERY_INC') or die('Restricted access');

 
 class UniteBaseAdminClassUG{
 	
		protected static $master_view;
		public static $view;
		
		private static $arrSettings = array();
		private static $tempVars = array();
		
		
		/**
		 * 
		 * main constructor		 
		 */
		public function __construct(){
						
			$this->initView();
			
			//self::addCommonScripts();
		}		
		
		/**
		 * 
		 * get path to settings file
		 * @param $settingsFile
		 */
		protected static function getSettingsFilePath($settingsFile){
			
			$filepath = self::$path_plugin."settings/$settingsFile.php";
			return($filepath);
		}
		
		
 		/**
		 * 
		 * set the view from GET variables
		 */
		private function initView(){
			
			$defaultView = GlobalsUG::VIEW_DEFAULT;
			
			//set view
			$viewInput = UniteFunctionsUG::getGetVar("view");
			$page = UniteFunctionsUG::getGetVar("page");

			$viewInput = UniteProviderFunctionsUG::sanitizeVar($viewInput, UniteFunctionsUG::SANITIZE_KEY);
			$page = UniteProviderFunctionsUG::sanitizeVar($page, UniteFunctionsUG::SANITIZE_KEY);
						
			//get the view out of the page
			if(strpos($page,"_") !== false){
				$parts = explode("_", $page);
				$view = $parts[1];
				$page = $parts[0];
			}
						
			if(!empty($page) && $page != GlobalsUG::PLUGIN_NAME)
				return(false);
			
			if(!empty($viewInput))
				$view = $viewInput;
			
			if(empty($view)){
				$view = $defaultView;
			}
			
			self::$view = $view;
			
		}
				
		
		/**
		 * 
		 * add common used scripts
		 */
		public static function addCommonScripts(){
			
			UniteProviderFunctionsUG::addScriptsFramework();
			
			HelperUG::addScriptCommon("admin","unitegallery_admin");			
			HelperUG::addScriptCommon("settings","unitegallery_settings");
			HelperUG::addScriptCommon("settings_new","unitegallery_settings_new");
			HelperUG::addScriptCommon("jquery.tipsy","tipsy");
			HelperUG::addScriptCommon("media_dialog","unitegallery_media_dialog");
			
			HelperUG::addStyleCommon("admin","unitegallery_admin");			
			HelperUG::addStyleCommon("tipsy","tipsy");
			HelperUG::addStyleCommon("media_dialog","unitegallery_media_dialog");
			
			//include farbtastic
			HelperUG::addScriptCommon("farbtastic","farbtastic","js/farbtastic");
			HelperUG::addStyleCommon("farbtastic","farbtastic","js/farbtastic");
			
			HelperUG::addScriptCommon("select2.full.min", "select2","js/select2");
			HelperUG::addStyleCommon("select2","select2","js/select2");
			
			
			//include fancybox
			HelperUG::addScriptCommon("jquery.fancybox-1.3.4.pack","fancybox","js/fancybox");
			HelperUG::addStyleCommon("jquery.fancybox-1.3.4","fancybox","js/fancybox");
		}
		
		
		/**
		 * 
		 * set view that will be the master
		 */
		protected static function setMasterView($masterView){
			self::$master_view = $masterView;
		}
		
		/**
		 * 
		 * inlcude some view file
		 */
		protected static function requireView($view){
			try{
				
				
				//require master view file, and 
				if(!empty(self::$master_view) && !isset(self::$tempVars["is_masterView"]) ){
					$masterViewFilepath = GlobalsUG::$pathViews.self::$master_view.".php";
					UniteFunctionsUG::validateFilepath($masterViewFilepath,"Master View");
					
					self::$tempVars["is_masterView"] = true;
										
					require $masterViewFilepath;
										
				}
				else{		//simple require the view file.
					$viewFilepath = GlobalsUG::$pathViews.$view.".php";
					
					UniteFunctionsUG::validateFilepath($viewFilepath,"View");
					require $viewFilepath;
				}
				
			}catch (Exception $e){
				echo "<br><br>View ($view) Error: <b>".$e->getMessage()."</b>";
				
				if(GlobalsUG::SHOW_TRACE == true)
					dmp($e->getTraceAsString());
			}
		}
		
		
		/**
		 * 
		 * require settings file, the filename without .php
		 */
		protected static function requireSettings($settingsFile){
						
			try{
				require self::$path_plugin."settings/$settingsFile.php";
			}catch (Exception $e){
				echo "<br><br>Settings ($settingsFile) Error: <b>".$e->getMessage()."</b>";
				dmp($e->getTraceAsString());
			}
		}
		
		
 	
 }
 
 ?>