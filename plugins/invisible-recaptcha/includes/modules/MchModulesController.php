<?php
/**
 * Copyright (c) 2016 Ultra Community (http://www.ultracommunity.com)
 */

namespace  InvisibleReCaptcha\MchLib\Modules;

use InvisibleReCaptcha\MchLib\Plugin\MchBasePlugin;
use InvisibleReCaptcha\MchLib\Utils\MchUtils;

//spl_autoload_register(array(__NAMESPACE__ . '\MchModulesController', 'autoLoadModulesClasses'), true, true);

class MchModulesController
{
	
	private static $arrRegisteredModules   = null;
	private static $arrAllAvailableModules = null;

//	$arrAllAvailableModules = array(
//		self::MODULE_SETTINGS => array(
//			'info'    => array(
//				'ModuleId'   => 1,
//				'IsLicensed' => false,
//			),
//			'classes' => array(
//				'GdbcSettingsAdminModule'  => '/modules/settings/SettingsAdminModule.php',
//				'GdbcSettingsPublicModule' => '/modules/settings/SettingsPublicModule.php',
//			),
//		)
//	);
	
	public static function initializeAvailableModules()
	{
		if(null !== self::$arrAllAvailableModules)
			return;
		
		self::$arrAllAvailableModules = (array)static::getAllAvailableModules();
	}
	
	
	protected static function getAllAvailableModules()
	{
		return array();
	}
	
	public static function getRegisteredModules()
	{
		if(null === self::$arrRegisteredModules)
			self::setRegisteredModules();
		
		return self::$arrRegisteredModules;
	}
	
	private static function setRegisteredModules()
	{
		if(null !== self::$arrRegisteredModules)
			return;
		
		self::initializeAvailableModules();
		self::$arrRegisteredModules = array();
		
		$activatedPlugins = array();
		if(defined('WP_PLUGIN_DIR'))
		{
			$activatedPlugins = array_merge( array_flip((array) get_option( 'active_plugins', array())), (array) get_site_option( 'active_sitewide_plugins', array() ) ) ;
			unset($activatedPlugins[MchBasePlugin::getPluginBaseName()]);
		}
		
		$engineDirPath = MchBasePlugin::getPluginDirectoryPath()  . '/engine/';
		
		foreach(self::$arrAllAvailableModules as $moduleName => &$arrModule)
		{
			self::$arrRegisteredModules[$moduleName] = array();
			
			foreach ($arrModule['classes'] as $className => $filePath)
			{
				$filePath = $engineDirPath . ( $dirPath =  dirname($filePath) . DIRECTORY_SEPARATOR . basename($filePath) );
				
				if(@file_exists($filePath)){
					
					if(empty(self::$arrRegisteredModules[$moduleName])){
						if(@file_exists(  $adapterFilePath = dirname($filePath) .  DIRECTORY_SEPARATOR . 'ModuleAdapter.php' ) ){
							include $adapterFilePath;
						}
					}
					
					self::$arrRegisteredModules[$moduleName][$className] = $filePath;
					continue;
				}
				
				foreach($activatedPlugins as $activePlugin => $value)
				{
					
					if(false === strpos($activePlugin, self::getModuleStandAloneDirectoryName($moduleName)))
						continue;
					
					$filePath = dirname(WP_PLUGIN_DIR . DIRECTORY_SEPARATOR . MchUtils::stripLeftAndRightSlashes($activePlugin) ) . "/engine/$dirPath" ;
					
					break;
				}
				
				if(@file_exists($filePath))
				{
					if(empty(self::$arrRegisteredModules[$moduleName])){
						if(@file_exists(  $adapterFilePath = dirname($filePath) .  DIRECTORY_SEPARATOR . 'ModuleAdapter.php' ) ){
							include $adapterFilePath;
						}
					}
					
					self::$arrRegisteredModules[$moduleName][$className] = $filePath;
					continue;
				}
				
			}
			
			if(empty(self::$arrRegisteredModules[$moduleName]))
				unset(self::$arrRegisteredModules[$moduleName]);
			
			unset($arrModule['classes']);
		}
		
		
		\spl_autoload_register(function ($moduleClassName){
			
			foreach(MchModulesController::getRegisteredModules() as $arrModuleClasses)
			{
				if(!isset($arrModuleClasses[$moduleClassName]))
					continue;
				
				return require $arrModuleClasses[$moduleClassName];
			}
			
			return false;
			
			
			
		}, true, false);
		
		
	}
	
	public static function getModuleStandAloneDirectoryName($moduleName)
	{
		return strtolower(MchBasePlugin::getPluginSlug() . '-' . MchUtils::stripNonAlphaNumericCharacters($moduleName));
	}
	
	public static function getModuleStandAloneDirectoryPath($moduleName)
	{
		if(!self::isModuleRegistered($moduleName))
			return null;
		
		$moduleClassName = self::getModuleStandAloneClassName($moduleName);
		if(!class_exists($moduleClassName))
		{
			if(!defined('WP_PLUGIN_DIR'))
				return null;
			
			return 	WP_PLUGIN_DIR . DIRECTORY_SEPARATOR . self::getModuleStandAloneDirectoryName($moduleName);
		}
		
		$classReflector = new \ReflectionClass($moduleClassName);
		
		return dirname($classReflector->getFileName());
		
	}
	
	public static function getModuleStandAloneClassName($moduleName)
	{
		return MchUtils::stripNonAlphaNumericCharacters(MchBasePlugin::getPluginName() . $moduleName);
	}
	
	
	public static function getModuleIdByName($moduleName)
	{
		return isset(self::$arrAllAvailableModules[$moduleName]['info']['ModuleId']) ? self::$arrAllAvailableModules[$moduleName]['info']['ModuleId'] : null;
	}
	
	public static function isLicensedModule($moduleIdORmoduleName)
	{
		$moduleName = ((false === filter_var($moduleIdORmoduleName, FILTER_VALIDATE_INT)) ? $moduleIdORmoduleName : self::getModuleNameById($moduleIdORmoduleName));
		
		return !empty(self::$arrAllAvailableModules[$moduleName]['info']['IsLicensed']);
	}
	
	public static function getModuleDisplayName($moduleIdORmoduleName)
	{
		$moduleName = ((false === filter_var($moduleIdORmoduleName, FILTER_VALIDATE_INT)) ? $moduleIdORmoduleName : self::getModuleNameById($moduleIdORmoduleName));
		
		return !empty(self::$arrAllAvailableModules[$moduleName]['info']['DisplayName']) ?  self::$arrAllAvailableModules[$moduleName]['info']['DisplayName'] : null;
		
	}
	
	
	public static function unRegisterModule($moduleName)
	{
		unset(self::$arrRegisteredModules[(string)$moduleName]);
	}
	
	public static function getNotLicensedModuleNames()
	{
		$arrFreeModules = array();
		foreach(self::$arrAllAvailableModules as $moduleName => $arrAllModuleSettings){
			empty(self::$arrAllAvailableModules[$moduleName]['info']['IsLicensed']) ?  $arrFreeModules[] = $moduleName : null;
		}
		
		return $arrFreeModules;
	}
	
	public static function getLicensedModuleNames()
	{
		$arrModules = array();
		foreach(self::$arrAllAvailableModules as $moduleName => $arrAllModuleSettings){
			!empty(self::$arrAllAvailableModules[$moduleName]['info']['IsLicensed']) ?  $arrModules[] = $moduleName : null;
		}
		
		return $arrModules;
	}
	
	
	public static function getModuleNameById($moduleId)
	{
		foreach(self::$arrAllAvailableModules as $moduleKey => $moduleValue)
		{
			if (isset($moduleValue['info']['ModuleId']) && $moduleValue['info']['ModuleId'] == $moduleId)
				return $moduleKey;
		}
		
		return null;
	}
	
	public static function getModuleDisplayNameByInstance(MchBaseModule $moduleInstance)
	{
		if(null === self::$arrRegisteredModules)
			self::setRegisteredModules();
		
		$moduleClass = get_class($moduleInstance);
		
		foreach(self::$arrRegisteredModules as $moduleKey => $arrModuleClasses)
		{
			if(!isset($arrModuleClasses[$moduleClass]))
				continue;
			
			return self::getModuleDisplayName($moduleKey);
		}
		
		return null;
	}
	
	public static function getModuleOptionDisplayText($moduleId, $optionId)
	{
		if(null === ($moduleAdminInstance = self::getAdminModuleInstance(self::getModuleNameById($moduleId))))
			return null;
		
		return $moduleAdminInstance->getOptionDisplayTextByOptionId($optionId);
	}
	
	public static function getModuleOptionId($moduleName, $optionName)
	{
		if(null === ($moduleAdminInstance = self::getAdminModuleInstance($moduleName)))
			return null;
		
		return $moduleAdminInstance->getOptionIdByOptionName($optionName);
	}
	
	public static function getModuleDirectoryPath($moduleName)
	{
		if(null === self::$arrRegisteredModules)
			self::setRegisteredModules();
		
		if(!isset(self::$arrRegisteredModules[$moduleName]) || !is_array(self::$arrRegisteredModules[$moduleName]))
			return null;
		
		return @dirname(reset(self::$arrRegisteredModules[$moduleName]));
	}
	
	/**
	 * @param string $moduleName
	 * @param int $moduleType
	 * @return \MchBaseModule | null
	 */
	private static function getModuleInstance($moduleName, $moduleType, $forceNewInstance)
	{
		if(null === self::$arrRegisteredModules)
			self::setRegisteredModules();
		
		if(!isset(self::$arrRegisteredModules[$moduleName]))
			return null;
		
		$arrModuleClasses = \array_keys(self::$arrRegisteredModules[$moduleName]);
		
		if(!isset($arrModuleClasses[1]))
			return null;
		
		for($i = 0; $i < 2; ++$i)
		{
			$isPublicClass = (\strpos($arrModuleClasses[$i], 'Public') !== false);
			
			if($isPublicClass && 2 === $moduleType)
			{

//				if(!\class_exists($arrModuleClasses[$i], false)) {
//					include self::$arrRegisteredModules[$moduleName][$arrModuleClasses[$i]];
//				}
				
				return $arrModuleClasses[$i]::getInstance($forceNewInstance);
				
			}
			
			if( !$isPublicClass && 1 === $moduleType )
				return $arrModuleClasses[$i]::getInstance($forceNewInstance);
			
		}
		
		return null;
		
	}
	
	/**
	 * @param string $moduleName Module name
	 *
	 * @return \InvisibleReCaptcha\MchLib\Modules\MchBaseAdminModule|null
	 */
	public static function getAdminModuleInstance($moduleName, $forceNewInstance = false)
	{
		return self::getModuleInstance($moduleName, 1, $forceNewInstance);
	}
	
	/**
	 * @param string $moduleName Module name
	 *
	 * @return \MchBasePublicModule|null
	 */
	public static function getPublicModuleInstance($moduleName, $forceNewInstance = false)
	{
		return self::getModuleInstance($moduleName, 2, $forceNewInstance);
	}
	
	/**
	 * @param $moduleName string Module name
	 *
	 * @return bool
	 */
	public static function isModuleRegistered($moduleName)
	{
		if(null === self::$arrRegisteredModules)
			self::setRegisteredModules();
		
		return 	isset(self::$arrRegisteredModules[$moduleName]);
	}

//	public static function autoLoadModulesClasses($moduleClassName)
//	{
//
//		if(null === self::$arrRegisteredModules)
//			self::setRegisteredModules();
//
//		foreach(self::$arrRegisteredModules as $arrModuleClasses)
//		{
//			if(!isset($arrModuleClasses[$moduleClassName]))
//				continue;
//
//			return include $arrModuleClasses[$moduleClassName];
//		}
//
//	}

}

