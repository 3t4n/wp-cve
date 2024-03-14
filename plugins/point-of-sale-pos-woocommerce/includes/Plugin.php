<?php

namespace ZPOS;

class Plugin
{
	const ASSETS = 'assets';
	const ASSETS_CORE = self::ASSETS . DIRECTORY_SEPARATOR . 'core';
	const ASSETS_DEBUG_CORE = self::ASSETS . DIRECTORY_SEPARATOR . 'debug';

	const VERSION_OPTION = 'bizswoop_pos_version';
	const RESET_OPTION = '_pos_reset_mode_';

	public static function getManifest($file = null)
	{
		$manifest = implode(DIRECTORY_SEPARATOR, [PLUGIN_ROOT, self::ASSETS_CORE, 'manifest.json']);
		if (file_exists($manifest)) {
			$manifest = json_decode(file_get_contents($manifest), true);
		} else {
			$manifest = [];
		}

		if ($file !== null) {
			return isset($manifest[$file]) ? $manifest[$file] : null;
		} else {
			return $manifest;
		}
	}

	public static function getAssetUrl($file, $core = true, $raw = false)
	{
		$manifest_path = self::getManifest($file);

		$base_path = $file;

		if ($manifest_path != null) {
			$base_path = $manifest_path;
		}

		$debug = (bool) get_option('pos_debug_mode');

		$base = $core ? ($debug ? self::ASSETS_DEBUG_CORE : self::ASSETS_CORE) : self::ASSETS;

		$path = $base . DIRECTORY_SEPARATOR . $base_path;

		return self::getUrl($path, $raw);
	}

	public static function getUrl($path, $raw = false)
	{
		$plugin_data = get_plugin_data(PLUGIN_ROOT_FILE);
		$url = plugins_url($path, PLUGIN_ROOT_FILE);

		if ($raw) {
			return $url;
		}

		return add_query_arg('version', $plugin_data['Version'], $url);
	}

	public static function isMobileApp()
	{
		$is_mobile_app =
			isset($_SERVER['HTTP_IS_MOBILE_DEVICE']) &&
			filter_var($_SERVER['HTTP_IS_MOBILE_DEVICE'], FILTER_VALIDATE_BOOLEAN);

		return $is_mobile_app;
	}

	public static function isValidAddOnVersion($name, $version)
	{
		if ($versions = self::validAddOnVersions($name)) {
			return self::versionMatch($version, $versions);
		}
		return false;
	}

	public static function validAddOnVersions($name)
	{
		switch ($name) {
			case 'wc-pos-gateways':
				return ['1.2.*'];
			case 'MultipleUsersPOS':
				return ['1.2.*'];
			case 'pos-stripe-terminal':
				return ['1.1.*'];
			default:
				return false;
		}
	}

	private static function versionMatch($versionTest, $validVersions)
	{
		if (in_array($versionTest, $validVersions)) {
			return true;
		}

		$validVersions = array_map(function ($version) {
			$version = str_replace('*', '[0-9]+', $version);
			$version = str_replace('.', '\.', $version);
			return '/^' . $version . '$/';
		}, $validVersions);

		$result = array_reduce(
			$validVersions,
			function ($acc, $regex) use ($versionTest) {
				return $acc || preg_match($regex, $versionTest);
			},
			false
		);

		return $result;
	}

	public static function isActive($name)
	{
		switch ($name) {
			case 'pos-ui':
				return defined('ZPOS_UI\ACTIVE') && constant('ZPOS_UI\ACTIVE');
			case 'wc-pos-gateways':
				return defined('\ZPaymentPOS\ACTIVE') && constant('\ZPaymentPOS\ACTIVE');
			case 'MultipleUsersPOS':
				return defined('\ZMultipleUsersPOS\ACTIVE') && constant('\ZMultipleUsersPOS\ACTIVE');
		}
	}

	public static function getPOSCloudAppURL()
	{
		return 'https://pos.bizswoop.app';
	}

	public static function getVersion()
	{
		return get_option(self::VERSION_OPTION);
	}

	public static function setVersion($version)
	{
		return update_option(self::VERSION_OPTION, $version, true);
	}
}
