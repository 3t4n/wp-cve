<?php
/**
 * Copyright (c) 2016 Ultra Community (http://www.ultracommunity.com)
 */

namespace InvisibleReCaptcha\Modules;


use InvisibleReCaptcha\MchLib\Modules\MchBaseAdminModule;
use InvisibleReCaptcha\MchLib\Utils\MchHtmlUtils;
use InvisibleReCaptcha\MchLib\Utils\MchWpUtils;

abstract class BaseAdminModule extends MchBaseAdminModule
{
	protected function __construct()
	{
		parent::__construct();
	}

	public  function renderModuleSettingsSectionHeader(array $arrSectionInfo)
	{
		//echo '<h3>' . __('WordPress General Settings', '') . '</h3><hr />';
	}

	protected function sanitizeModuleSettings($arrSettingOptions)
	{
//		if(!is_array($arrSettingOptions))
//			return $arrSettingOptions;
//
//		 return array_map(function($value){
//			return is_scalar($value) ? MchWpUtils::sanitizeText($value) : $value;
//		}, $arrSettingOptions);


		if(empty($arrSettingOptions))
			return $arrSettingOptions;

		$arrDefaultValues = $this->getDefaultOptions();

		foreach($arrSettingOptions as $key => &$value)
		{
			if( ! is_scalar($value) )
				continue;

			if(empty($arrDefaultValues[$key]['InputType'])){
				$value = MchWpUtils::sanitizeText($value);
				continue;
			}

			if($arrDefaultValues[$key]['InputType'] == MchHtmlUtils::FORM_ELEMENT_INPUT_TEXTAREA){
				$value = MchWpUtils::sanitizeTextArea($value);
				continue;
			}

			$value = MchWpUtils::sanitizeText($value);
		}

		return $arrSettingOptions;


	}


	public function saveDefaultOptions($justScalarValues = true)
	{
		foreach($this->getDefaultOptionsValues() as $optionName => $optionValue)
		{
			$canSave = $justScalarValues ? is_scalar($optionValue) : true;

			!($canSave) ?: $this->saveOption($optionName, $optionValue);

		}

	}

	protected function getFieldAttributesFilterName($settingsField)
	{
		!is_array($settingsField) ?: $settingsField = key($settingsField);
		return $this->getFormOptionFieldName( $settingsField ) . "-attributes";
	}


	public function renderModuleSettingsField(array $arrSettingsField)
	{
		$arrDefaultValues = $this->getDefaultOptionsValues();
		$optionName = key($arrSettingsField);
		if(null === $optionName || !array_key_exists($optionName, $arrDefaultValues))
			return;

		$optionValue = $this->getOption($optionName);

		is_scalar($optionValue) ?: $optionValue = null;

		$arrSettingsField = $arrSettingsField[$optionName];
		$arrFieldAttributes = array(
			'name'  => $this->getFormOptionFieldName($optionName),
			'type'  => !empty($arrSettingsField['InputType']) ? $arrSettingsField['InputType'] : 'text',
			'value' => $optionValue,
			'class' => array(),
			'id'    => $this->getSettingKey() . '-' . $optionName,
		);


		$arrFieldAttributes = apply_filters($this->getFieldAttributesFilterName($optionName), $arrFieldAttributes);


//		if($arrFieldAttributes['type'] === MchHtmlUtils::FORM_ELEMENT_INPUT_CHECKBOX)
//		{
//			!empty($arrFieldAttributes['value']) ? $arrFieldAttributes['checked'] = 'checked' : null;
//			$arrFieldAttributes['value'] = true;
//
//		}

		if($arrFieldAttributes['type'] === MchHtmlUtils::FORM_ELEMENT_SELECT)
		{
			!empty($arrFieldAttributes['class'])     ?: $arrFieldAttributes['class'] = array();
			!is_string($arrFieldAttributes['class']) ?: $arrFieldAttributes['class'] = explode(',', is_string($arrFieldAttributes['class']));

			if(isset($arrFieldAttributes['multiple'])){
				$arrFieldAttributes['name'] = $arrFieldAttributes['name'] . '[]';
			}
		}

		if(isset($arrFieldAttributes['class']) && is_array($arrFieldAttributes['class']))
		{
			$arrFieldAttributes['class'] = implode(' ', $arrFieldAttributes['class']);
		}
		
		
		
		$fieldOutputHtml =  MchHtmlUtils::createFormElement($arrFieldAttributes['type'], $arrFieldAttributes);
		
		$fieldOutputHtml =  apply_filters($this->getFieldAttributesFilterName($optionName) . '-output-html', $fieldOutputHtml, $arrFieldAttributes);
		
		if(!empty($arrSettingsField['HelpText'])){
			//$fieldOutputHtml .= '<i class="fa fa-info-circle uc-tooltip uc-help-tooltip" title="' . $arrSettingsField['HelpText'] . '"></i>';
		}
		
		echo $fieldOutputHtml;


		if(!empty($arrSettingsField['Description']))
		{
			echo '<p class = "description">' . $arrSettingsField['Description'] . '</p>';
		}

	}

//	protected function getFormattedFieldDescription($description)
//	{
//		return  '<p class = "description">' . esc_html( $description );  '</p>';
//	}


	public function getOptionDisplayTextByOptionId($settingOptionId)
	{
		$settingOptionId = (int)$settingOptionId;

		foreach($this->getDefaultOptions() as $arrOptionInfo)
		{
			if (isset($arrOptionInfo['Id']) &&  $arrOptionInfo['Id'] === $settingOptionId && isset($arrOptionInfo['DisplayText']))
				return esc_html($arrOptionInfo['DisplayText']);
		}

		return null;
	}


	public function getFormElementName($optionName)
	{
		if(!array_key_exists($optionName, (array)$this->getDefaultOptions()))
			return null;

		return esc_attr($this->getSettingKey() . '[' . $optionName . ']');
	}


	public function saveOption($optionName, $optionValue, $forceBlogOption = true)
	{
		return parent::saveOption($optionName, $optionValue, false);
	}

	public function deleteOption($optionName, $forceBlogOption = true)
	{
		return parent::deleteOption($optionName, false);
	}

	public function deleteAllSettingOptions($forceBlogOption = true)
	{
		return parent::deleteAllSettingOptions(false);
	}

	public function getAllSavedOptions($forceBlogOption = true)
	{
		return parent::getAllSavedOptions(false);
	}

	public function getOption($optionName, $forceBlogOption = true)
	{
		return parent::getOption($optionName, false);
	}

}