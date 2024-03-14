<?php

namespace InvisibleReCaptcha\Modules\WooCommerce;


use InvisibleReCaptcha\MchLib\Plugin\MchBaseAdminPlugin;
use InvisibleReCaptcha\MchLib\Utils\MchHtmlUtils;
use InvisibleReCaptcha\Modules\BaseAdminModule;

class WooCommerceAdminModule extends BaseAdminModule
{

	CONST OPTION_LOGIN_FORM_PROTECTION_ENABLED          = 'IsLoginEnabled';
	CONST OPTION_REGISTRATION_FORM_PROTECTION_ENABLED   = 'IsRegisterEnabled';
	CONST OPTION_CHECKOUT_FORM_PROTECTION_ENABLED       = 'IsCheckOutEnabled';
	CONST OPTION_LOST_PASSWORD_FORM_PROTECTION_ENABLED  = 'IsLostPwdEnabled';
	CONST OPTION_RESET_PASSWORD_FORM_PROTECTION_ENABLED = 'IsResetPwdEnabled';
	CONST OPTION_PRODUCT_REVIEW_FORM_PROTECTION_ENABLED = 'IsProdRevEnabled';

	public  function __construct()
	{
		parent::__construct();
	}


	public function getDefaultOptions()
	{
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

			self::OPTION_LOST_PASSWORD_FORM_PROTECTION_ENABLED  => array(
				'Value'      => null,
				'LabelText'  => __('Enable Lost Password Form Protection', 'invisible-recaptcha'),
				'InputType'  => MchHtmlUtils::FORM_ELEMENT_INPUT_CHECKBOX
			),

			self::OPTION_RESET_PASSWORD_FORM_PROTECTION_ENABLED  => array(
				'Value'      => null,
				'LabelText'  => __('Enable Reset Password Form Protection', 'invisible-recaptcha'),
				'InputType'  => MchHtmlUtils::FORM_ELEMENT_INPUT_CHECKBOX
			),

			self::OPTION_PRODUCT_REVIEW_FORM_PROTECTION_ENABLED  => array(
				'Value'      => null,
				'LabelText'  => __('Enable Product Review Form Protection', 'invisible-recaptcha'),
				'InputType'  => MchHtmlUtils::FORM_ELEMENT_INPUT_CHECKBOX
			),
			
//			self::OPTION_CHECKOUT_FORM_PROTECTION_ENABLED  => array(
//				'Value'      => null,
//				'LabelText'  => __('Enable Checkout Form Protection', 'invisible-recaptcha'),
//				'InputType'  => MchHtmlUtils::FORM_ELEMENT_INPUT_CHECKBOX,
//				'Description' => __('If you decide to protect the checkout form, make sure you test this carefully!', 'invisible-recaptcha')
//			),

		);

		return $arrDefaultSettingOptions;

	}

	public function validateModuleSettingsFields( $arrOptions )
	{
		$arrOptions = $this->sanitizeModuleSettings($arrOptions);
		$this->registerSuccessMessage(__('Your changes were successfully saved!', 'invisible-recaptcha'));

		return $arrOptions;
	}


	public function renderModuleSettingsSectionHeader( array $arrSectionInfo ) {
		$favIconUrl = MchBaseAdminPlugin::getPluginBaseUrl() . '/assets/admin/images/woocommerce-favicon.png';
		$favIconUrl = esc_url($favIconUrl);
		
		echo '<div class="mch-settings-section-header">
				<h3>
				
				<a style="text-decoration: none;" href="https://wordpress.org/plugins/woocommerce/" target="_blank">
					<img style="vertical-align:text-bottom;" src = "' . $favIconUrl . '" width="20" height="20" />
					<span>WooCommerce</span>
				</a>
				'.__(' Protection Settings', 'invisible-recaptcha').'</h3>
			</div>';
	}

}