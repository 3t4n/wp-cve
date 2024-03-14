<?php
/**
 * Copyright (c) 2016 Ultra Community (http://www.ultracommunity.com)
 */

namespace InvisibleReCaptcha;

use InvisibleReCaptcha\Controllers\ModulesController;
use InvisibleReCaptcha\MchLib\Modules\MchBaseModule;
use InvisibleReCaptcha\MchLib\Plugin\MchBasePublicPlugin;
use InvisibleReCaptcha\MchLib\Utils\MchWpUtils;
use InvisibleReCaptcha\Modules\BasePublicModule;
use InvisibleReCaptcha\Modules\Settings\SettingsAdminModule;
use InvisibleReCaptcha\Modules\Settings\SettingsPublicModule;
use InvisibleReCaptcha\Modules\WordPress\WordPressPublicModule;

class PublicEngine extends MchBasePublicPlugin
{
	private $arrRegisteredAssets = null;

	protected function __construct()
	{
		parent::__construct();

		foreach(array_keys((array)ModulesController::getRegisteredModules()) as $moduleName){
			if(null !== ($moduleInstance = ModulesController::getPublicModuleInstance($moduleName)))
				$moduleInstance->registerAttachedHooks();
		}

		add_action('google_invre_render_widget_action', array(WordPressPublicModule::getInstance(), 'renderReCaptchaHolderHtmlCode'));
		add_filter('google_invre_is_valid_request_filter', function ($isRequestValid){return RequestHandler::isInvisibleReCaptchaTokenValid();});
		add_filter('google_invre_widget_output_html_filter', function ($outputHtml){ return WordPressPublicModule::getInstance()->getReCaptchaHolderHtmlCode();});

	}


	public function enqueuePublicScriptsAndStyles()
	{

		$siteKey = esc_attr(SettingsPublicModule::getInstance()->getOption(SettingsAdminModule::OPTION_SITE_KEY));
		$holderClassName = 	'.' . BasePublicModule::RECAPTCHA_HOLDER_CLASS_NAME;
		$badgePosition   = SettingsPublicModule::getInstance()->getOption(SettingsAdminModule::OPTION_BADGE_POSITION);
		$languageCode    = SettingsPublicModule::getInstance()->getOption(SettingsAdminModule::OPTION_LANGUAGE);
		$customCss       = SettingsPublicModule::getInstance()->getOption(SettingsAdminModule::OPTION_BADGE_CUSTOM_CSS);

		//!has_filter('google_invre_custom_css')     ?: $customCss     = apply_filters('google_invre_custom_css', $customCss);
		!has_filter('google_invre_language_code_filter')  ?: $languageCode  = apply_filters('google_invre_language_code_filter', $languageCode);
		!has_filter('google_invre_badge_position_filter') ?: $badgePosition = apply_filters('google_invre_badge_position_filter', $badgePosition);


		if(empty($badgePosition) || !in_array($badgePosition, array('bottomright', 'bottomleft', 'inline'))){
			$badgePosition = 'bottomright';
		}

		if(!empty($languageCode)){
			$arrLanguages = SettingsAdminModule::getAvailableLanguages();
			isset($arrLanguages[$languageCode]) ?: $languageCode = null;
			unset($arrLanguages);
		}


		$inlineScript = <<<Mch

var renderInvisibleReCaptcha = function() {

    for (var i = 0; i < document.forms.length; ++i) {
        var form = document.forms[i];
        var holder = form.querySelector('{$holderClassName}');

        if (null === holder) continue;
		holder.innerHTML = '';

         (function(frm){
			var cf7SubmitElm = frm.querySelector('.wpcf7-submit');
            var holderId = grecaptcha.render(holder,{
                'sitekey': '{$siteKey}', 'size': 'invisible', 'badge' : '{$badgePosition}',
                'callback' : function (recaptchaToken) {
					if((null !== cf7SubmitElm) && (typeof jQuery != 'undefined')){jQuery(frm).submit();grecaptcha.reset(holderId);return;}
					 HTMLFormElement.prototype.submit.call(frm);
                },
                'expired-callback' : function(){grecaptcha.reset(holderId);}
            });

			if(null !== cf7SubmitElm && (typeof jQuery != 'undefined') ){
				jQuery(cf7SubmitElm).off('click').on('click', function(clickEvt){
					clickEvt.preventDefault();
					grecaptcha.execute(holderId);
				});
			}
			else
			{
				frm.onsubmit = function (evt){evt.preventDefault();grecaptcha.execute(holderId);};
			}


        })(form);
    }
};

Mch;


		if($this->compareWpVersions('4.5', '<')) {
			MchWpUtils::addActionHook('wp_head', function () use ($inlineScript) {
				echo '<script type="text/javascript">' . $inlineScript . '</script>';
				$googleApiUrl = 'https://www.google.com/recaptcha/api.js?onload=renderInvisibleReCaptcha&render=explicit';
				empty($language) ?: $googleApiUrl .= "&hl=$language";

				echo '<script src="' . $googleApiUrl . '" async defer></script>';
			});

			return;
		}

		if(!empty($customCss))
		{
			foreach(array('wp_head', 'login_head') as $headAction){
				MchWpUtils::addActionHook($headAction, function () use ($customCss) {
					echo '<style type="text/css">' . wp_specialchars_decode($customCss, \ENT_QUOTES) . '</style>';
				});
			}
		}

		$googleApiUrl = 'https://www.google.com/recaptcha/api.js?onload=renderInvisibleReCaptcha&render=explicit';
		empty($languageCode) ?: $googleApiUrl .= "&hl=$languageCode";

		wp_enqueue_script('google-invisible-recaptcha', $googleApiUrl , array(), null, true);

		wp_add_inline_script('google-invisible-recaptcha', $inlineScript, 'before');

		MchWpUtils::addFilterHook('script_loader_tag', function ($tag, $handle){
			if('google-invisible-recaptcha' !== $handle)
				return $tag;

			return str_replace( ' src', ' async defer src', $tag );
		}, 99, 2);

	}


	public function registerAfterSetupThemeHooks()
	{
		add_action('login_enqueue_scripts', array($this, 'enqueuePublicScriptsAndStyles'));

	}

	private function compareWpVersions( $since, $operator ) {
		return version_compare( str_replace( '-src', '', $GLOBALS['wp_version'] ), $since, $operator );
	}
}