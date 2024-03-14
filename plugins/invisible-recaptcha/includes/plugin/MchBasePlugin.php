<?php
/**
 * Copyright (c) 2016 Ultra Community (http://www.ultracommunity.com)
 */

namespace InvisibleReCaptcha\MchLib\Plugin;

use InvisibleReCaptcha\MchLib\Utils\MchWpUtils;

abstract class MchBasePlugin
{
	protected static $PLUGIN_VERSION        = null;
	protected static $PLUGIN_SLUG           = null;
	protected static $PLUGIN_MAIN_FILE      = null;

	protected static $PLUGIN_DIRECTORY_PATH = null;
	protected static $PLUGIN_DIRECTORY_NAME = null;
	protected static $PLUGIN_BASE_NAME      = null;

	protected static $PLUGIN_URL  = null;
	protected static $PLUGIN_NAME = null;
	protected static $PLUGIN_ABBR = null;

	protected function __construct()
	{
		add_action('init', array($this, 'initializePlugin' ), 0);
	}

	public function initializePlugin()
	{
		$locale = \apply_filters('plugin_locale', get_locale(), self::$PLUGIN_SLUG);

		\load_textdomain(self::$PLUGIN_SLUG, trailingslashit( WP_LANG_DIR ) . self::$PLUGIN_SLUG . DIRECTORY_SEPARATOR . self::$PLUGIN_SLUG . '-' . $locale . '.mo' );

		\load_plugin_textdomain(self::$PLUGIN_SLUG, false, self::$PLUGIN_SLUG . DIRECTORY_SEPARATOR . 'languages' . DIRECTORY_SEPARATOR );

	}

	public static function isNetworkActivated()
	{
		static $isNetworkActivated = null;
		return (null !== $isNetworkActivated) ? $isNetworkActivated : $isNetworkActivated = MchWpUtils::isPluginNetworkActivated(self::$PLUGIN_MAIN_FILE);
	}

	public static function getPluginBaseName()
	{
		return self::$PLUGIN_BASE_NAME;
	}

	public static function getPluginDirectoryPath()
	{
		return self::$PLUGIN_DIRECTORY_PATH;
	}

	public static function getPluginSlug()
	{
		return self::$PLUGIN_SLUG;
	}

	public static function getPluginName()
	{
		return self::$PLUGIN_NAME;
	}

	public static function getPluginMainFile()
	{
		return self::$PLUGIN_MAIN_FILE;
	}

	public static function getPluginBaseUrl()
	{
		return self::$PLUGIN_URL;
	}

	public static function getPluginAbbr()
	{
		return self::$PLUGIN_ABBR;
	}

	public static function setPluginInfo(array $arrPluginInfo)
	{

//		\function_exists('wp_create_nonce') || require_once( ABSPATH . WPINC . '/pluggable.php' );

		self::$PLUGIN_ABBR           = isset($arrPluginInfo['PLUGIN_ABBR'])        ? $arrPluginInfo['PLUGIN_ABBR']       : null;
		self::$PLUGIN_SLUG           = isset($arrPluginInfo['PLUGIN_SLUG'])        ? $arrPluginInfo['PLUGIN_SLUG']       : null;
		self::$PLUGIN_NAME           = isset($arrPluginInfo['PLUGIN_NAME'])        ? $arrPluginInfo['PLUGIN_NAME']       : null;
		self::$PLUGIN_VERSION        = isset($arrPluginInfo['PLUGIN_VERSION'])     ? $arrPluginInfo['PLUGIN_VERSION']    : null;
		self::$PLUGIN_MAIN_FILE      = isset($arrPluginInfo['PLUGIN_MAIN_FILE'])   ? $arrPluginInfo['PLUGIN_MAIN_FILE']  : null;

		if(!isset(self::$PLUGIN_MAIN_FILE)) {
			throw new \Exception('Missing the Main File Path');
		}

		self::$PLUGIN_DIRECTORY_PATH = (null !== self::$PLUGIN_MAIN_FILE ? dirname(self::$PLUGIN_MAIN_FILE) : null);

		self::$PLUGIN_DIRECTORY_NAME = (null !== self::$PLUGIN_DIRECTORY_PATH ? plugin_basename(self::$PLUGIN_DIRECTORY_PATH) : null);

		self::$PLUGIN_URL            = (null !== self::$PLUGIN_MAIN_FILE ? untrailingslashit( plugins_url( '/', self::$PLUGIN_MAIN_FILE ) ) : null);

		self::$PLUGIN_BASE_NAME      = (null !== self::$PLUGIN_MAIN_FILE ? plugin_basename(self::$PLUGIN_MAIN_FILE) : null);


	}

	public static function getInstance()
	{
		static $instance = null;
		return (null !== $instance) ? $instance : $instance = new static();
	}

}