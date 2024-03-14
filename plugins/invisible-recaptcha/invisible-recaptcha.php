<?php
/**
 *
 * @package   Invisible reCaptcha
 * @author    Mihai Chelaru
 *
 * @wordpress-plugin
 * Plugin Name: Invisible reCaptcha
 * Description: Google Invisible reCaptcha for WordPress.
 * Version: 1.2.3
 * Author: Mihai Chelaru
 * Text Domain: invisible-recaptcha
 * Domain Path: /languages
 */


final class InvisibleReCaptcha
{
	CONST PLUGIN_VERSION    = '1.2.3';
	CONST PLUGIN_ABBR       = 'ic';
	CONST PLUGIN_SLUG       = 'invisible-recaptcha';
	CONST PLUGIN_NAME       = 'Invisible reCaptcha';

	CONST PLUGIN_MAIN_FILE  = __FILE__;

	private function __construct()
	{}

	public static function init()
	{
		\InvisibleReCaptcha\MchLib\Plugin\MchBasePlugin::setPluginInfo(array(
				'PLUGIN_MAIN_FILE'   => self::PLUGIN_MAIN_FILE,
				'PLUGIN_VERSION'     => self::PLUGIN_VERSION,
				'PLUGIN_SLUG'        => self::PLUGIN_SLUG,
				'PLUGIN_ABBR'        => self::PLUGIN_ABBR,
				'PLUGIN_NAME'        => self::PLUGIN_NAME,
		));

		InvisibleReCaptcha\RequestHandler::handleRequest();

	}

}

include __DIR__ . '/includes/MchLibAutoloader.php';
include __DIR__ . '/engine/RequestHandler.php';

(!defined('ABSPATH')) || InvisibleReCaptcha::init();

