<?php
/**
 * Copyright (c) 2016 Ultra Community (http://www.ultracommunity.com)
 */

spl_autoload_register(function($className){

	static $arrClassMap = array(

			'InvisibleReCaptcha\MchLib\Modules\MchBaseModule'        => 'modules/MchBaseModule.php',
			'InvisibleReCaptcha\MchLib\Modules\MchBasePublicModule'  => 'modules/MchBasePublicModule.php',
			'InvisibleReCaptcha\MchLib\Modules\MchBaseAdminModule'   => 'modules/MchBaseAdminModule.php',
			'InvisibleReCaptcha\MchLib\Modules\MchGroupedModules'    => 'modules/MchGroupedModules.php',
			'InvisibleReCaptcha\MchLib\Modules\MchModulesController' => 'modules/MchModulesController.php',

			'InvisibleReCaptcha\MchLib\Plugin\MchBasePlugin'        => 'plugin/MchBasePlugin.php',
			'InvisibleReCaptcha\MchLib\Plugin\MchBaseAdminPlugin'   => 'plugin/MchBaseAdminPlugin.php',
			'InvisibleReCaptcha\MchLib\Plugin\MchBasePublicPlugin'  => 'plugin/MchBasePublicPlugin.php',
			'InvisibleReCaptcha\MchLib\Plugin\MchBaseAdminPage'     => 'plugin/MchBaseAdminPage.php',
			'InvisibleReCaptcha\MchLib\Plugin\MchGdbcPluginUpdater' => 'plugin/MchPluginUpdater.php',

			'InvisibleReCaptcha\MchLib\Utils\MchUtils'          => 'utils/MchUtils.php',
			'InvisibleReCaptcha\MchLib\Utils\MchWpUtils'        => 'utils/MchWpUtils.php',
			'InvisibleReCaptcha\MchLib\Utils\MchHtmlUtils'      => 'utils/MchHtmlUtils.php',
			'InvisibleReCaptcha\MchLib\Utils\MchIPUtils'        => 'utils/MchIPUtils.php',
			'InvisibleReCaptcha\MchLib\Utils\MchImageUtils'     => 'utils/MchImageUtils.php',
			'InvisibleReCaptcha\MchLib\Utils\MchFileUtils'      => 'utils/MchFileUtils.php',
			'InvisibleReCaptcha\MchLib\Utils\MchDirectoryUtils' => 'utils/MchDirectoryUtils.php',
			'InvisibleReCaptcha\MchLib\Utils\MchValidator'      => 'utils/MchValidator.php',
			'InvisibleReCaptcha\MchLib\Utils\MchMinifier'      => 'utils/MchMinifier.php',
			'InvisibleReCaptcha\MchLib\Utils\MchHttpRequest'    => 'utils/MchHttpRequest.php',
			'InvisibleReCaptcha\MchLib\Utils\FontAwesomeIconParser'    => 'utils/FontAwesomeIconParser.php',

			'InvisibleReCaptcha\MchLib\WordPress\Repository\PostRepository' => 'WordPress/Repository/PostRepository.php',
			'InvisibleReCaptcha\MchLib\WordPress\Repository\WpUserRepository' => 'WordPress/Repository/WpUserRepository.php',
			'InvisibleReCaptcha\MchLib\WordPress\Routing\Router'            => 'WordPress/Routing/Router.php',
			'InvisibleReCaptcha\MchLib\WordPress\CustomPostType'            => 'WordPress/CustomPostType.php',
			'InvisibleReCaptcha\MchLib\WordPress\Uploader'                  => 'WordPress/Uploader.php',

			'InvisibleReCaptcha\MchLib\Exceptions\MchLibException' => 'exceptions/MchLibException.php',
	);


	if (!isset($arrClassMap[$className]))
		return null;

	$filePath = __DIR__ . DIRECTORY_SEPARATOR . $arrClassMap[$className];

	unset($arrClassMap[$className]);

	return file_exists($filePath) ? include $filePath : null;

}, false, true );

