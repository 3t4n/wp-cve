<?php
/**
 * @package Addon Creator for Addon Library
 * @author UniteCMS http://unitecms.net
 * @copyright Copyright (c) 2016 UniteCMS
 * @license http://www.gnu.org/licenses/gpl-2.0.html GNU/GPLv2 or later
*/

//no direct accees
defined ('LAYOUTS_EDITOR_INC') or die ('restricted aceess');

class AddonLibraryLayoutEditorPluginUC extends UniteCreatorPluginBase{
	
	protected $extraInitParams = array();
	
	private $version = "1.0";
	private $pluginName = "layouts_builder";
	private $title = "Layouts Builder Plugin";
	private $description = "Give the ability to create, and modify layouts.";
	
	/**
	 * is blox exists
	 */
	public static function isBloxExists(){
		
		$arrPlugins = get_plugins();
		
		$pluginName = "blox-page-builder/blox_builder.php";
		
		if(isset($arrPlugins[$pluginName]) == true){
			$isActive = is_plugin_active($pluginName);
			
			return($isActive);
		}
		
		return(false);
	}
	
	
	/**
	 * modify view url
	 */
	public function modifyUrlView($link, $view, $params){
		
		switch($view){
			case GlobalsUC::VIEW_LAYOUT:
				$link = LayoutEditorGlobals::$urlComoponentAdmin."_layout".$params;	
			break;
			case GlobalsUC::VIEW_LAYOUTS_LIST:
				$link = admin_url()."edit.php?post_type=".UniteLayoutEditorAdmin::POST_TYPE;
			break;
			case GlobalsUC::VIEW_LAYOUT_PREVIEW:
				$link = LayoutEditorGlobals::$urlComoponentAdmin.$params;
			break;
		}
		
		
		return($link);
	}
	
	
	/**
	 * constructor
	 */
	public function __construct(){
		
		parent::__construct();
				
		$this->init();
	}
	
	
	/**
	 * init the plugin
	 */
	protected function init(){
		
		$this->register($this->pluginName, $this->title, $this->version, $this->description, $this->extraInitParams);
		
		$isBlox = self::isBloxExists();
				
		if($isBlox == false)
			$this->addFilter(self::FILTER_MODIFY_URL_VIEW, "modifyUrlView",10,3);
		
	}
	
}


//run the plugin

new AddonLibraryLayoutEditorPluginUC();

