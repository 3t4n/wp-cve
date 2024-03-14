<?php

namespace InvisibleReCaptcha\Modules\Membership\UltraCommunity;

use InvisibleReCaptcha\MchLib\Utils\MchWpUtils;
use InvisibleReCaptcha\Modules\BasePublicModule;

class UltraCommunityPublicModule extends BasePublicModule
{

	public function __construct()
	{
		parent::__construct();

		if(!class_exists('\UltraCommunity\UltraCommHooks')) {
			return;
		}

		if(!defined('\UltraCommunity\UltraCommHooks::ACTION_LOGIN_FORM_BOTTOM')){
			return;
		}

		if($this->getOption(UltraCommunityAdminModule::OPTION_LOGIN_FORM_PROTECTION_ENABLED)){

			MchWpUtils::addActionHook(\UltraCommunity\UltraCommHooks::ACTION_LOGIN_FORM_BOTTOM, function(){
				UltraCommunityPublicModule::getInstance()->renderReCaptchaHolderHtmlCode();
			}, PHP_INT_MAX);


			MchWpUtils::addActionHook(\UltraCommunity\UltraCommHooks::ACTION_BEFORE_USER_LOG_IN, function($userName){

				if(BasePublicModule::isRecaptchaValid())
					return;

				throw new \UltraCommunity\UltraCommException(__('We\'ve encountered an error while trying to validate reCaptcha!', 'invisible-recaptcha'));

			}, PHP_INT_MAX, 1);

		}

		if($this->getOption(UltraCommunityAdminModule::OPTION_REGISTRATION_FORM_PROTECTION_ENABLED)){

			MchWpUtils::addActionHook(\UltraCommunity\UltraCommHooks::ACTION_REGISTRATION_FORM_BOTTOM, function(){
				UltraCommunityPublicModule::getInstance()->renderReCaptchaHolderHtmlCode();
			}, PHP_INT_MAX);


			MchWpUtils::addActionHook(\UltraCommunity\UltraCommHooks::ACTION_BEFORE_USER_REGISTRATION, function($userName){

				if(BasePublicModule::isRecaptchaValid())
					return;

				throw new \UltraCommunity\UltraCommException(__('We\'ve encountered an error while trying to validate reCaptcha!', 'invisible-recaptcha'));

			}, PHP_INT_MAX, 1);

		}

	}

}