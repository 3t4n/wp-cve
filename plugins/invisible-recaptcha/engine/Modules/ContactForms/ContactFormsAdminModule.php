<?php


namespace InvisibleReCaptcha\Modules\ContactForms;


use InvisibleReCaptcha\MchLib\Utils\MchHtmlUtils;
use InvisibleReCaptcha\MchLib\Utils\MchUtils;
use InvisibleReCaptcha\MchLib\Utils\MchValidator;
use InvisibleReCaptcha\MchLib\Utils\MchWpUtils;
use InvisibleReCaptcha\Modules\BaseAdminModule;

class ContactFormsAdminModule extends BaseAdminModule
{
	CONST OPTION_CF7_PROTECTION_ENABLED = 'CF7';
	CONST OPTION_GF_PROTECTION_ENABLED  = 'GF';
	CONST OPTION_GF_EXCLUDED_FORM_IDS   = 'GFE';
	
	public function getDefaultOptions()
	{
		static $arrDefaultSettingOptions = null;
		if(null !== $arrDefaultSettingOptions)
			return $arrDefaultSettingOptions;

		$arrDefaultSettingOptions = array(

			self::OPTION_CF7_PROTECTION_ENABLED  => array(
				'Value'      => null,
				'LabelText'  => __('Enable Protection for Contact Form 7', 'invisible-recaptcha'),
				'InputType'  => MchHtmlUtils::FORM_ELEMENT_INPUT_CHECKBOX,
			),
			
			self::OPTION_GF_PROTECTION_ENABLED  => array(
				'Value'      => null,
				'LabelText'  => __('Enable Protection for Gravity Forms', 'invisible-recaptcha'),
				'InputType'  => MchHtmlUtils::FORM_ELEMENT_INPUT_CHECKBOX,
			),
			
			self::OPTION_GF_EXCLUDED_FORM_IDS  => array(
				'Value'      => null,
				'LabelText'  => __('Excluded Gravity Forms IDs', 'invisible-recaptcha'),
				'InputType'  => MchHtmlUtils::FORM_ELEMENT_INPUT_TEXT,
				'Description' => __('A list of comma separated Gravity Forms IDs which should not be protected by Invisible reCaptcha', 'invisible-recaptcha'),
			),
		
		);

		return $arrDefaultSettingOptions;

	}

	public function validateModuleSettingsFields($arrOptions)
	{
		$arrOptions = $this->sanitizeModuleSettings($arrOptions);
		
		if(!empty($arrOptions[self::OPTION_GF_EXCLUDED_FORM_IDS]))
		{
			$arrOptions[self::OPTION_GF_EXCLUDED_FORM_IDS] = explode(',', $arrOptions[self::OPTION_GF_EXCLUDED_FORM_IDS]);
			$arrOptions[self::OPTION_GF_EXCLUDED_FORM_IDS] = array_map('trim', $arrOptions[self::OPTION_GF_EXCLUDED_FORM_IDS]);
			foreach ($arrOptions[self::OPTION_GF_EXCLUDED_FORM_IDS] as $index => $excludedFormId)
			{
				if(MchValidator::isInteger($excludedFormId))
					continue;
				unset($arrOptions[self::OPTION_GF_EXCLUDED_FORM_IDS][$index]);
			}
			
			$arrOptions[self::OPTION_GF_EXCLUDED_FORM_IDS] = array_values($arrOptions[self::OPTION_GF_EXCLUDED_FORM_IDS]);
		}
		
		return $arrOptions;
	}


	public function renderModuleSettingsSectionHeader( array $arrSectionInfo ) {
		echo '<div class="mch-settings-section-header">
				<h3>'.__('Contact Forms Protection Settings', 'invisible-recaptcha').'</h3>
			</div>';
	}
	
	
	public function renderModuleSettingsField(array $arrSettingsField)
	{
		
		$fieldKey   = key( $arrSettingsField );
		$fieldValue = $this->getOption( $fieldKey );
		
		
		
		add_filter($this->getFieldAttributesFilterName($fieldKey) . '-output-html', function( $outputHtml, $arrAttr ) use ($fieldKey, $fieldValue){
			
			
			$additionalOutput = '';
			
			if($fieldKey === ContactFormsAdminModule::OPTION_CF7_PROTECTION_ENABLED)
			{
				$additionalOutput = '<p class="description" style="float:left; margin:0 0 0 15px;">
					<span style = "vertical-align: top; display:inline-block; margin-top: 22px;">Offers protection for all forms built with <a href = "https://wordpress.org/plugins/contact-form-7/">Contact Form 7</a></span>
					<a target="_blank" href = "https://wordpress.org/plugins/contact-form-7/"><img width = "60" src = "http://ps.w.org/contact-form-7/assets/icon-256x256.png" /></a>
				</p>';
			}
			
			if($fieldKey === ContactFormsAdminModule::OPTION_GF_PROTECTION_ENABLED)
			{
				$additionalOutput = '<p class="description" style="float:left; margin:0 0 0 15px;">
					<span style = "vertical-align: top; display:inline-block; margin-top: 22px;">Offers protection for all forms built with <a href = "http://www.gravityforms.com/">Gravity Forms</a></span>
					<a target="_blank" href = "http://www.gravityforms.com/"><img width = "60" height="60" src = "http://gravityforms.s3.amazonaws.com/logos/gravityforms_logo_outline_sm.png" /></a>
				</p>';
				
			}
			
			return  $outputHtml .$additionalOutput;
			
			
		}, 10, 2);
		
		
		MchWpUtils::addFilterHook($this->getFieldAttributesFilterName($fieldKey), function( $arrFieldAttributes ) use ($fieldKey, $fieldValue){
			
			if(!empty($arrFieldAttributes['type']) && $arrFieldAttributes['type'] === MchHtmlUtils::FORM_ELEMENT_INPUT_CHECKBOX) {
				$arrFieldAttributes['style'] = 'float:left; margin-top: 26px;';
			}
			
			if($fieldKey === ContactFormsAdminModule::OPTION_GF_EXCLUDED_FORM_IDS)
			{
				$arrFieldAttributes['style'] = 'width:370px';
				$arrFieldAttributes['value'] = implode(', ', (array)$fieldValue);
			}
			
			
			return $arrFieldAttributes;
		});
		
		return parent::renderModuleSettingsField($arrSettingsField);
	}
}