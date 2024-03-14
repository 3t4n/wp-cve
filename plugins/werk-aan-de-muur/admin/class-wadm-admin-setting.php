<?php

class Wadm_Admin_Setting
{
	protected $_section;

	protected $_pluginName;

	protected $_sectionLabel;

	protected $_settingName;

	protected $_description;

	protected $_validationOptions;


	public function __construct(Wadm_Admin_Section $section)
	{
		$this->_section = $section;
		$this->_pluginName = $section->getPluginName();
		$this->_sectionLabel = $section->getSectionLabel();
	}

	public function addField($title, $key)
	{
		// Always prefix setting name with plugin name to prevent conflicts with other plugins / settings
		$this->_settingName = $this->_pluginName . '_' . $key;

		add_settings_field(
			$this->_settingName,
			__( $title, Wadm::TEXT_DOMAIN ),
			array( $this, 'callBack' ),
			$this->_pluginName,
			$this->_pluginName . '_' . $this->_sectionLabel,
			array( 'label_for' => $this->_settingName )
		);

		$this->_register();
	}

	/**
	 * Set a description to add to the settings field
	 */
	public function setDescription($description)
	{
		$this->_description = $description;
	}

	/**
	 * Set validation options
	 */
	public function setValidationOptions($options)
	{
		$this->_validationOptions = $options;
	}

	/**
	 * Callback for this setting to print the actual html
	 */
	public function callBack()
	{
		$attributes = array(
			'type' => 'text',
			'class' => 'regular-text',
			'name' => $this->_settingName,
			'id' => $this->_settingName,
			'value' => get_option($this->_settingName),
		);

		if (isset($this->_validationOptions['type']))
			$attributes['type' ] = $this->_validationOptions['type'];

		if (isset($this->_validationOptions['required']) && $this->_validationOptions['required'])
			$attributes['required'] = 'required';

		if (isset($this->_validationOptions['minlength']))
			$attributes['minlength' ] = (int)$this->_validationOptions['minlength'];

		if (isset($this->_validationOptions['maxlength']))
			$attributes['maxlength' ] = (int)$this->_validationOptions['maxlength'];

		if (isset($this->_validationOptions['pattern']))
			$attributes['pattern' ] = $this->_validationOptions['pattern'];

		$attributesString = '';

		foreach ($attributes as $key => $value)
			$attributesString .= ' ' . $key . '="' . $value . '"';

		echo '<input' . $attributesString . '>';

		if (isset($this->_description))
			echo '<p class="description" id="' . $this->_settingName . '-description">' . $this->_description . '</p>';
	}

	/**
	 * Register this setting
	 */
	protected function _register()
	{
		register_setting($this->_pluginName, $this->_settingName);
	}
}