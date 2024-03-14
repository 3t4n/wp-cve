<?php

namespace InvisibleReCaptcha\Modules\Membership\BuddyPress;

use InvisibleReCaptcha\MchLib\Utils\MchWpUtils;
use InvisibleReCaptcha\Modules\BasePublicModule;
class BuddyPressPublicModule extends BasePublicModule
{
	CONST REGISTER_FLAG = 'google_invre_bp_register';

	public function __construct()
	{
		parent::__construct();

		if(!$this->getOption(BuddyPressAdminModule::OPTION_REGISTRATION_FORM_PROTECTION_ENABLED))
			return;


		empty($_POST[self::REGISTER_FLAG])     ?: $_POST['signup_submit'] = 'register';

		MchWpUtils::addActionHook('bp_account_details_fields', function(){

			BuddyPressPublicModule::getInstance()->renderReCaptchaHolderHtmlCode();

			echo '<input type = "hidden" name = "' . BuddyPressPublicModule::REGISTER_FLAG . '" value = "1" />';

		}, PHP_INT_MAX);

		MchWpUtils::addActionHook('bp_signup_validate', function(){
			global $bp; if( !isset($bp->signup) ) exit;

			if(BasePublicModule::isRecaptchaValid())
				return;

			$bp->signup->errors['google-recaptcha'] = __( 'Google reCaptcha was not validated!', 'invisible-recaptcha');
		});

	}

}