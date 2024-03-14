<?php
/**
 * Copyright (c) 2016 Ultra Community (http://www.ultracommunity.com)
 */

namespace InvisibleReCaptcha\Modules;


use InvisibleReCaptcha\MchLib\Modules\MchBasePublicModule;

class BasePublicModule extends MchBasePublicModule
{
	CONST RECAPTCHA_HOLDER_CLASS_NAME = 'inv-recaptcha-holder';
	
	protected function __construct()
	{
		parent::__construct();
	}

	public function getAllSavedOptions($forceBlogOption = true)
	{
		return parent::getAllSavedOptions(false);
	}

	public function getOption($optionName, $forceBlogOption = true)
	{
		return parent::getOption($optionName, false);
	}


	public function getReCaptchaHolderHtmlCode()
	{
		return '<div class="' . self::RECAPTCHA_HOLDER_CLASS_NAME . '"></div>';
	}

	public function renderReCaptchaHolderHtmlCode()
	{
		echo $this->getReCaptchaHolderHtmlCode();
	}

	public static function isRecaptchaValid()
	{
		return (bool)apply_filters('google_invre_is_valid_request_filter', false);
	}
	
}