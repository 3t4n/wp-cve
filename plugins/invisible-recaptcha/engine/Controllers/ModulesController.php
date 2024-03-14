<?php


namespace InvisibleReCaptcha\Controllers;


use InvisibleReCaptcha\MchLib\Modules\MchModulesController;
use InvisibleReCaptcha\MchLib\Plugin\MchBasePlugin;

class ModulesController extends MchModulesController
{
	CONST MODULE_SETTINGS       = 'Settings';
	CONST MODULE_WORDPRESS      = 'WordPress';
	CONST MODULE_CONTACT_FORMS  = 'ContactForms';
	CONST MODULE_WOOCOMMERCE    = 'WooCommerce';
	CONST MODULE_BUDDYPRESS     = 'BuddyPress';
	CONST MODULE_ULTRACOMMUNITY = 'UltraCommunity';
	
	protected static function getAllAvailableModules()
	{
		return array(

			self::MODULE_SETTINGS => array(
				'info'    => array(
					'DisplayName' => __('Settings', 'invisible-recaptcha'),
				),
				'classes' => array(
					'InvisibleReCaptcha\Modules\Settings\SettingsPublicModule' => 'Modules/Settings/SettingsPublicModule.php',
					'InvisibleReCaptcha\Modules\Settings\SettingsAdminModule'  => 'Modules/Settings/SettingsAdminModule.php',
				),
			),

			self::MODULE_WORDPRESS => array(
				'info'    => array(
					'DisplayName' => __('WordPress', 'invisible-recaptcha'),
				),
				'classes' => array(
					'InvisibleReCaptcha\Modules\WordPress\WordPressPublicModule' => 'Modules/WordPress/WordPressPublicModule.php',
					'InvisibleReCaptcha\Modules\WordPress\WordPressAdminModule'  => 'Modules/WordPress/WordPressAdminModule.php',
				),
			),

			self::MODULE_CONTACT_FORMS => array(
				'info'    => array(
					'DisplayName' => __('Contact Forms', 'invisible-recaptcha'),
				),
				'classes' => array(
					'InvisibleReCaptcha\Modules\ContactForms\ContactFormsAdminModule' => 'Modules/ContactForms/ContactFormsAdminModule.php',
					'InvisibleReCaptcha\Modules\ContactForms\ContactFormsPublicModule' => 'Modules/ContactForms/ContactFormsPublicModule.php',
				),
			),

			self::MODULE_WOOCOMMERCE => array(
				'info'    => array(
						'DisplayName' => __('WooCommerce', 'invisible-recaptcha'),
				),
				'classes' => array(
						'InvisibleReCaptcha\Modules\WooCommerce\WooCommerceAdminModule' => 'Modules/WooCommerce/WooCommerceAdminModule.php',
						'InvisibleReCaptcha\Modules\WooCommerce\WooCommercePublicModule' => 'Modules/WooCommerce/WooCommercePublicModule.php',
				),
			),
			
			self::MODULE_ULTRACOMMUNITY => array(
				'info'    => array(
					'DisplayName' => __('UltraCommunity', 'invisible-recaptcha'),
				),
				'classes' => array(
					'InvisibleReCaptcha\Modules\Membership\UltraCommunity\UltraCommunityAdminModule' => 'Modules/Membership/UltraCommunity/UltraCommunityAdminModule.php',
					'InvisibleReCaptcha\Modules\Membership\UltraCommunity\UltraCommunityPublicModule' => 'Modules/Membership/UltraCommunity/UltraCommunityPublicModule.php',
				),
			),

			self::MODULE_BUDDYPRESS => array(
				'info'    => array(
					'DisplayName' => __('BuddyPress', 'invisible-recaptcha'),
				),
				'classes' => array(
					'InvisibleReCaptcha\Modules\Membership\BuddyPress\BuddyPressAdminModule' => 'Modules/Membership/BuddyPress/BuddyPressAdminModule.php',
					'InvisibleReCaptcha\Modules\Membership\BuddyPress\BuddyPressPublicModule' => 'Modules/Membership/BuddyPress/BuddyPressPublicModule.php',
				),
			),
			
		);
	}

}

\spl_autoload_register(function($className){

	static $arrClassMap = array(

		'InvisibleReCaptcha\Modules\BaseAdminModule'  => 'Modules/BaseAdminModule.php',
		'InvisibleReCaptcha\Modules\BasePublicModule' => 'Modules/BasePublicModule.php',
	);

	if (!isset($arrClassMap[$className]))
		return null;

	$filePath = MchBasePlugin::getPluginDirectoryPath() . '/engine/' . $arrClassMap[$className];
	unset($arrClassMap[$className]);

	return file_exists($filePath) ? include $filePath : null;

}, false);
