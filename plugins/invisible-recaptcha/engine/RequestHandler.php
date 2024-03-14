<?php

namespace InvisibleReCaptcha;

use InvisibleReCaptcha\Controllers\ModulesController;
use InvisibleReCaptcha\MchLib\Utils\MchHttpRequest;
use InvisibleReCaptcha\MchLib\Utils\MchWpUtils;
use InvisibleReCaptcha\Modules\Settings\SettingsAdminModule;
use InvisibleReCaptcha\Modules\Settings\SettingsPublicModule;

class RequestHandler
{
	public static function handleRequest()
	{
		

		MchWpUtils::addActionHook('plugins_loaded', function(){
			
			ModulesController::initializeAvailableModules();
			
			switch(true)
			{
				case MchWpUtils::isUserInDashboard() : AdminEngine::getInstance();   break;
				default                              : PublicEngine::getInstance();  break;
			}
			
		}, 0);

	}


	public static function isInvisibleReCaptchaTokenValid()
	{

		static $requestIsValid = -1;
		if(-1 !== $requestIsValid)
			return $requestIsValid;

		if(empty($_POST['g-recaptcha-response']))
			return false;

		$response = wp_remote_retrieve_body(wp_remote_get( add_query_arg( array(
			'secret'   => SettingsPublicModule::getInstance()->getOption(SettingsAdminModule::OPTION_SECRET_KEY),
			'response' => $_POST['g-recaptcha-response'],
			'remoteip' => MchHttpRequest::getClientIp()
		), 'https://www.google.com/recaptcha/api/siteverify' ) ));


		if(empty($response) || !( $json = json_decode( $response ) ) || empty($json->success)){

			return $requestIsValid =  false;
		}

		return $requestIsValid = true;
	}

}



spl_autoload_register(function($className){

	static $arrClassMap = array(

		'InvisibleReCaptcha\PublicEngine' => 'PublicEngine.php',
		'InvisibleReCaptcha\AdminEngine'  => 'AdminEngine.php',

		'InvisibleReCaptcha\Admin\Pages\BaseAdminPage'            => 'Admin/Pages/BaseAdminPage.php',
		'InvisibleReCaptcha\Admin\Pages\SettingsAdminPage'        => 'Admin/Pages/SettingsAdminPage.php',

		'InvisibleReCaptcha\Controllers\ModulesController'  => 'Controllers/ModulesController.php',
	);

	if (!isset($arrClassMap[$className]))
		return null;

	$filePath = __DIR__ . DIRECTORY_SEPARATOR . $arrClassMap[$className];
	unset($arrClassMap[$className]);

	return file_exists($filePath) ? include $filePath : null;



}, false, true);
