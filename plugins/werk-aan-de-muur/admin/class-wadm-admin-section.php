<?php

class Wadm_Admin_Section
{
	protected $_pluginName;

	protected $_sectionName;

	protected $_description;

	/**
	 * Wadm_Admin_Section constructor.
	 *
	 * @param $pluginName
	 * @param $sectionName
	 */
	public function __construct($pluginName, $sectionLabel, $sectionName)
	{
		$this->_pluginName = $pluginName;
		$this->_sectionLabel = $sectionLabel;
		$this->_sectionName = $sectionName;

		$this->_createSection();
	}

	/**
	 * Get plugin name
	 */
	public function getPluginName()
	{
		return $this->_pluginName;
	}

	/**
	 * Get section name
	 */
	public function getSectionLabel()
	{
		return $this->_sectionLabel;
	}

	/**
	 * Set section description
	 */
	public function setDescription($description)
	{
		$this->_description = $description;
	}

	/**
	 * Create admin section
	 */
	protected function _createSection()
	{
		add_settings_section(
			$this->_pluginName . '_' . $this->_sectionLabel,
			$this->_sectionName,
			array( $this, 'callBack'),
			$this->_pluginName
		);
	}

	/**
	 * Callback for section
	 */
	public function callBack()
	{
		if (!isset($this->_description))
			return false;

		echo '<p>' . $this->_description . '</p>';
	}

	/**
	 * Create and add a setting to this section
	 *
	 * @param $name
	 */
	public function createSetting($name, $key, $description = null, $validationOptions = null)
	{
		$setting = new Wadm_Admin_Setting($this);

		if ($description)
			$setting->setDescription($description);

		if ($validationOptions)
			$setting->setValidationOptions($validationOptions);

		$setting->addField($name, $key);
	}
}