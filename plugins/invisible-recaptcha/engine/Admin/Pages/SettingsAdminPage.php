<?php

namespace InvisibleReCaptcha\Admin\Pages;


use InvisibleReCaptcha\Controllers\ModulesController;
use InvisibleReCaptcha\MchLib\Modules\MchGroupedModules;
use InvisibleReCaptcha\MchLib\Utils\MchWpUtils;
use InvisibleReCaptcha\Modules\Settings\SettingsAdminModule;

class SettingsAdminPage extends BaseAdminPage
{
	public function __construct($pageMenuTitle)
	{
		parent::__construct($pageMenuTitle);

		$arrGroupedModules   = array();

		$arrGroupedModules[] = ModulesController::getAdminModuleInstance(ModulesController::MODULE_SETTINGS);
		$arrGroupedModules[] = ModulesController::getAdminModuleInstance(ModulesController::MODULE_WORDPRESS);
		$arrGroupedModules[] = ModulesController::getAdminModuleInstance(ModulesController::MODULE_WOOCOMMERCE);
		$arrGroupedModules[] = ModulesController::getAdminModuleInstance(ModulesController::MODULE_ULTRACOMMUNITY);
		$arrGroupedModules[] = ModulesController::getAdminModuleInstance(ModulesController::MODULE_BUDDYPRESS);


		$arrGroupedModules[] = ModulesController::getAdminModuleInstance(ModulesController::MODULE_CONTACT_FORMS);


		$this->registerGroupedModules(array(new MchGroupedModules(__('Invisible reCaptcha Settings', 'invisible-recaptcha'), $arrGroupedModules)));


		MchWpUtils::addActionHook(self::ACTION_AFTER_SETTINGS_FORM_SUBMIT_BUTTON, function($adminPage, $adminModuleInstance){
			if(!$adminModuleInstance instanceof SettingsAdminModule)
				return;

			echo '<div style="padding: 3px 8px; text-align: justify; border-left: 4px solid #428bca">
					<p style="font-size:14px; margin: 0 0 10px; padding: 0">Make sure your API keys are whitelisted by Google for the new Invisible reCaptcha!</p>
					<p style="font-size:14px; margin: 0; padding: 0">For a while, it is possible that Google will show the captcha challenge when the form is submitted. This will slowly go away!</p>
				</div>';
		}, 10, 2);


	}



}