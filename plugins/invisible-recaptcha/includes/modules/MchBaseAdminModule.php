<?php
/**
 * Copyright (c) 2016 Ultra Community (http://www.ultracommunity.com)
 */

namespace InvisibleReCaptcha\MchLib\Modules;

use InvisibleReCaptcha\MchLib\Plugin\MchBasePlugin;

abstract class MchBaseAdminModule extends MchBaseModule
{
	private $arrDefaultOptionsValues = array();
	private $arrRegisteredMessages   = array();

	public abstract function getDefaultOptions();
	public abstract function validateModuleSettingsFields($arrOptions);

	protected function __construct()
	{
		parent::__construct();
	}

	public function getFormOptionFieldName($optionName)
	{
		return $this->getSettingKey() . '[' . $optionName . ']';
	}

	public function getDefaultOptionsValues()
	{
		if(empty($this->arrDefaultOptionsValues))
		{
			foreach((array)$this->getDefaultOptions() as $optionName => $arrOptionInfo)
			{
				$this->arrDefaultOptionsValues[$optionName] = isset($arrOptionInfo['Value']) ? $arrOptionInfo['Value'] : null;
			}
		}

		return $this->arrDefaultOptionsValues;
	}

	public function getDefaultOptionValue($optionName)
	{
		!empty($this->arrDefaultOptionsValues) ?: $this->getDefaultOptionsValues();

		return isset($this->arrDefaultOptionsValues[$optionName]) ? $this->arrDefaultOptionsValues[$optionName] : null;
	}

	public function saveNetworkSettingOptions(array $arrSettingOptions)
	{
		$this->isUsedNetworkWide = true;

		$arrSettingOptions = $this->validateModuleSettingsFields($arrSettingOptions);

		remove_filter('sanitize_option_' . $this->getSettingKey(), array($this, 'validateModuleSettingsFields'));

		update_site_option($this->getSettingKey(), $arrSettingOptions);

		wp_safe_redirect(add_query_arg('updated', '1'));
	}

	public function saveOption($optionName, $optionValue, $forceBlogOption = true)
	{
		$arrSavedOptions = $this->getAllSavedOptions($forceBlogOption);

		$arrSavedOptions[$optionName] = $optionValue;

		return ((!$forceBlogOption) && MchBasePlugin::isNetworkActivated()) ? update_site_option($this->getSettingKey(), $arrSavedOptions) : update_option($this->getSettingKey(), $arrSavedOptions);
	}

	public function deleteOption($optionName, $forceBlogOption = true)
	{
		$arrSavedOptions = $this->getAllSavedOptions();

		unset($arrSavedOptions[$optionName]);

		return ((!$forceBlogOption) && MchBasePlugin::isNetworkActivated()) ? update_site_option($this->getSettingKey(), $arrSavedOptions) : update_option($this->getSettingKey(), $arrSavedOptions);

	}

	public function deleteAllSettingOptions($forceBlogOption = true)
	{
		return ((!$forceBlogOption) && MchBasePlugin::isNetworkActivated()) ? delete_site_option($this->getSettingKey()) : delete_option($this->getSettingKey());
	}

	protected function registerErrorMessage($messageToDisplay)
	{
		$this->registerAdminMessage('ErrorMessage', $messageToDisplay);
		//add_settings_error($this->getSettingKey(), $this->getSettingKey(), $messageToDisplay, 'error');
	}

	protected function registerSuccessMessage($messageToDisplay)
	{
		$this->registerAdminMessage('SuccessMessage', $messageToDisplay);

		//add_settings_error($this->getSettingKey(), $this->getSettingKey(), $messageToDisplay, 'updated');
	}

	protected function registerWarningMessage($messageToDisplay)
	{
		$this->registerAdminMessage('WarningMessage', $messageToDisplay);
	}

	private function registerAdminMessage($messageType, $message)
	{
		$this->arrRegisteredMessages[$messageType] = $message;
	}

	public function getFormattedMessagesForDisplay()
	{
		$arrSavedOptions = $this->getAllSavedOptions();


		$htmlCode = '<div class = "mch-settings-message" style = "{holder-style}"><h3 style = "margin-bottom: 5px; padding: 6px 10px; line-height: 1.4">{message}</h3></div>';

		$arrMessageType = array(
			'ErrorMessage'   => array(
									'border-left:' => '4px solid #ce4844',
									'background:'  => '#f2dede'
								),

			'SuccessMessage' => array(
									'border-left:' => '4px solid #7ad03a',
									'background:'    => '#dff0d8'
								),

			'WarningMessage' => array(
									'border-left:' => '4px solid #ffba00',
									'background:'    => '#fcf8e3'
								),
		);

		foreach($arrMessageType as $messageType => $arrStyleInfo)
		{
			if(empty($arrSavedOptions[$messageType]))
				continue;

			$holderStyle = '';
			foreach($arrStyleInfo as $styleKey => $value)
				$holderStyle .= $styleKey . $value . ';';

			$htmlCode = str_replace(array('{holder-style}', '{message}'), array($holderStyle, wp_filter_kses($arrSavedOptions[$messageType])), $htmlCode);
			return $htmlCode;
		}

		return null;

	}

	public function saveRegisteredAdminMessages($forceBlogOption = true)
	{

		$arrSavedOptions = $this->getAllSavedOptions();
		$shouldUpdateOptions = !empty($this->arrRegisteredMessages);

		foreach(array('ErrorMessage', 'SuccessMessage', 'WarningMessage') as $messageType)
		{
			$shouldUpdateOptions = (true === $shouldUpdateOptions) ? true : isset($arrSavedOptions[$messageType]);
			unset($arrSavedOptions[$messageType]);
		}

		if(!$shouldUpdateOptions)
			return;

		foreach($this->arrRegisteredMessages as $messageType => $message)
		{
			$arrSavedOptions[$messageType] = $message;
		}

		remove_filter('sanitize_option_' . $this->getSettingKey(), array($this, 'validateModuleSettingsFields'));

		((!$forceBlogOption) && MchBasePlugin::isNetworkActivated()) ? update_site_option($this->getSettingKey(), $arrSavedOptions) : update_option($this->getSettingKey(), $arrSavedOptions);
	}

}