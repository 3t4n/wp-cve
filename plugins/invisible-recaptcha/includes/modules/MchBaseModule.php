<?php
/**
 * Copyright (c) 2016 Ultra Community (http://www.ultracommunity.com)
 */

namespace InvisibleReCaptcha\MchLib\Modules;

use InvisibleReCaptcha\MchLib\Plugin\MchBasePlugin;
use InvisibleReCaptcha\MchLib\Utils\MchUtils;
use InvisibleReCaptcha\MchLib\WordPress\CustomPostType;

abstract class MchBaseModule
{
	protected $arrRegisteredHooks = null;
	protected $moduleSettingsKey  = null;

	protected $customPostType = null;

	protected function __construct()
	{
		$this->arrRegisteredHooks = array(1 => array(), 2 => array()); // 1 - key for actions, 2 - key for filters

		$this->setModuleSettingsKey();

		//add_action('init', array($this, 'initializeModuleSettings'), PHP_INT_MAX);

	}

	public function getSettingKey()
	{
		if(null === $this->customPostType)
			return $this->moduleSettingsKey;

		if(!empty($this->customPostType->PostId) && false === \strpos($this->moduleSettingsKey, '-cpt-' . $this->customPostType->PostId . '-'))
			$this->setModuleSettingsKey();

		return $this->moduleSettingsKey;
	}

	private function setModuleSettingsKey()
	{
		$this->moduleSettingsKey  = MchBasePlugin::getPluginAbbr()  . '-';
		$this->moduleSettingsKey .= \str_replace(array('adminmodule', 'publicmodule'), '', \strtolower(MchUtils::getClassShortNameFromNameSpace(\get_class($this))));

		\strpos($this->moduleSettingsKey, 'settings') ?: $this->moduleSettingsKey .= '-settings';

		if(!empty($this->customPostType->PostId))
		{
			$this->moduleSettingsKey = str_replace('-settings', '-cpt-' . $this->customPostType->PostId . '-settings', $this->moduleSettingsKey);
		}
	}

	public function setCustomPostType(CustomPostType $customPostType)
	{
		$this->customPostType = $customPostType;
		$this->setModuleSettingsKey();
	}

	/**
	 * @return \InvisibleReCaptcha\MchLib\WordPress\CustomPostType
	 */
	public function getCustomPostType()
	{
		return $this->customPostType;
	}

	public function getCustomPostTypeId()
	{
		return empty($this->customPostType->PostId) ? null : $this->customPostType->PostId;
	}

	public function getAllSavedOptions($forceBlogOption = true)
	{
		return ((!$forceBlogOption) && MchBasePlugin::isNetworkActivated()) ? (array)get_site_option($this->moduleSettingsKey, array()) : (array)get_option($this->moduleSettingsKey, array());
	}

	public function getOption($optionName, $forceBlogOption = true)
	{
		$arrAllSavedOptions = $this->getAllSavedOptions($forceBlogOption);
		return isset($arrAllSavedOptions[$optionName]) ? $arrAllSavedOptions[$optionName] : null;
	}

	public function addActionHook($actionName, array $arrCallback, $priority = 10, $numberOfArgumentsToPass = 1)
	{
		return $this->addHook(1, $actionName, $arrCallback, $priority, $numberOfArgumentsToPass);
	}

	public function addFilterHook($filterName, array $arrCallback, $priority = 10, $numberOfArgumentsToPass = 1)
	{
		return $this->addHook(2, $filterName, $arrCallback, $priority, $numberOfArgumentsToPass);
	}

	private function addHook($hookType, $hookName, array $arrCallback, $priority, $numberOfArgumentsToPass)
	{
		if(1 !== $hookType && 2 !== $hookType)
			return -1;

		static $hookCounter = 0;
		++$hookCounter;
		$hookIndex  = (1 === $hookType) ? 'a_' : 'f_';
		$hookIndex .= "$hookCounter-$hookType-$hookName-$priority-$numberOfArgumentsToPass";

		$this->arrRegisteredHooks[$hookType][$hookIndex] = array($hookName, $arrCallback, $priority, $numberOfArgumentsToPass);

		return $hookIndex;
	}

	public function removeHookByIndex($hookIndex)
	{
		foreach($this->arrRegisteredHooks as $hookType => $arrIndexedHooks)
		{
			if(!isset($arrIndexedHooks[$hookIndex][3]))
				continue;

			(1 === $hookType) //hookName       , $arrCallBack   , $priority      , $numberOfArguments
				? remove_action($arrIndexedHooks[$hookIndex][0], $arrIndexedHooks[$hookIndex][1], $arrIndexedHooks[$hookIndex][2], $arrIndexedHooks[$hookIndex][3])
				: remove_filter($arrIndexedHooks[$hookIndex][0], $arrIndexedHooks[$hookIndex][1], $arrIndexedHooks[$hookIndex][2], $arrIndexedHooks[$hookIndex][3]);

			unset($this->arrRegisteredHooks[$hookType][$hookIndex]);
		}
	}

	public function isHookRegistered($hookIndex)
	{
		if(empty($hookIndex)){
			return false;
		}

		foreach($this->arrRegisteredHooks as $hookType => $arrIndexedHooks)
			if(isset($arrIndexedHooks[$hookIndex][3]))
				return true;

		return false;
	}

	public function registerAttachedHooks()
	{
		static $arrAlreadyRegisteredHooks = array();

		foreach($this->arrRegisteredHooks as $hookType => $arrIndexedHooks)
		{
			foreach($arrIndexedHooks as $hookIndex => $arrHookInfo)
			{
				if(!isset($arrHookInfo[3]) || isset($arrAlreadyRegisteredHooks[$hookIndex]))
					continue;

				(1 === $hookType) //hookName    , $callBack      , $priority      , $numberOfArguments
					? add_action($arrHookInfo[0], $arrHookInfo[1], $arrHookInfo[2], $arrHookInfo[3])
					: add_filter($arrHookInfo[0], $arrHookInfo[1], $arrHookInfo[2], $arrHookInfo[3]);

				$arrAlreadyRegisteredHooks[$hookIndex] = true;
			}

		}
	}

	/**
	 * @return MchBaseModule|MchBaseAdminModule
	 */
	public final static function getInstance($forceNewInstance = false)
	{
		static $classInstance = null;
		return (null !== $classInstance && (!$forceNewInstance)) ? $classInstance : $classInstance = new static();

//		static $arrInstances = array();
//		$calledClass = \get_called_class();
//
//		return isset($arrInstances[$calledClass]) ? $arrInstances[$calledClass] : $arrInstances[$calledClass] = new $calledClass();
	}

}