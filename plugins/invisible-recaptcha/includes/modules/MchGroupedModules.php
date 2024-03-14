<?php

namespace InvisibleReCaptcha\MchLib\Modules;

class MchGroupedModules
{
	private $groupedModulesList = null;
	private $groupTitle         = null;
	private $groupDescription   = null;

	public function __construct($groupTitle = null, array $groupedModulesList = null)
	{
		$this->groupTitle         = $groupTitle;
		$this->groupedModulesList = array();

		foreach((array)$groupedModulesList as $adminModule){
			!($adminModule instanceof MchBaseAdminModule) ?: $this->groupedModulesList[] = $adminModule;
		}
	}

	public function addModule(MchBaseAdminModule $adminModule)
	{
		$this->groupedModulesList[] = $adminModule;
	}

	public function removeModule($moduleIndex)
	{
		unset($this->groupedModulesList[$moduleIndex]);
	}

	public function getGroupedModules()
	{
		return $this->groupedModulesList;
	}

	public function hasModules()
	{
		return isset($this->groupedModulesList[0]);
	}

	public function getGroupTitle()
	{
		return $this->groupTitle;
	}

	public function getGroupDescription()
	{
		return $this->groupDescription;
	}

	public function setGroupTitle( $groupTitle ) {
		$this->groupTitle = $groupTitle;
	}

	public function setGroupDescription( $groupDescription ) {
		$this->groupDescription = $groupDescription;
	}

}