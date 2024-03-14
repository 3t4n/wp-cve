<?php

namespace InvisibleReCaptcha\Modules\WordPress;


use InvisibleReCaptcha\MchLib\Utils\MchHtmlUtils;
use InvisibleReCaptcha\Modules\BaseAdminModule;

class WordPressAdminModule extends BaseAdminModule
{
	CONST OPTION_COMMENTS_FORM_PROTECTION_ENABLED = 'CF';
	CONST OPTION_LOGIN_FORM_PROTECTION_ENABLED = 'LF';
	CONST OPTION_REGISTRATION_FORM_PROTECTION_ENABLED = 'RF';
	CONST OPTION_FORGOT_PASSWD_FORM_PROTECTION_ENABLED = 'FPF';

	public function __construct()
	{
		parent::__construct();
	}

	public function getDefaultOptions() {
		static $arrDefaultSettingOptions = null;
		if(null !== $arrDefaultSettingOptions)
			return $arrDefaultSettingOptions;

		$arrDefaultSettingOptions = array(

			self::OPTION_LOGIN_FORM_PROTECTION_ENABLED  => array(
				'Value'      => null,
				'LabelText'  => __('Enable Login Form Protection', 'invisible-recaptcha'),
				'InputType'  => MchHtmlUtils::FORM_ELEMENT_INPUT_CHECKBOX
			),

			self::OPTION_REGISTRATION_FORM_PROTECTION_ENABLED  => array(
				'Value'      => null,
				'LabelText'  => __('Enable Registration Form Protection', 'invisible-recaptcha'),
				'InputType'  => MchHtmlUtils::FORM_ELEMENT_INPUT_CHECKBOX
			),

			self::OPTION_COMMENTS_FORM_PROTECTION_ENABLED  => array(
				'Value'      => null,
				'LabelText'  => __('Enable Comments Form Protection', 'invisible-recaptcha'),
				'InputType'  => MchHtmlUtils::FORM_ELEMENT_INPUT_CHECKBOX
			),

			self::OPTION_FORGOT_PASSWD_FORM_PROTECTION_ENABLED  => array(
				'Value'      => null,
				'LabelText'  => __('Enable Forgot Password Form Protection', 'invisible-recaptcha'),
				'InputType'  => MchHtmlUtils::FORM_ELEMENT_INPUT_CHECKBOX
			),

		);

		return $arrDefaultSettingOptions;

	}

	public function renderModuleSettingsSectionHeader( array $arrSectionInfo ) {
		echo '<div class="mch-settings-section-header">
				<h3>'.__('WordPress Protection Settings', 'invisible-recaptcha').'</h3>
			</div>';
	}


	public function validateModuleSettingsFields( $arrOptions ) {

		$arrOptions =$this->sanitizeModuleSettings($arrOptions);

		$this->registerSuccessMessage(__('Your changes were successfully saved!', 'invisible-recaptcha'));

		return $arrOptions;
	}
}