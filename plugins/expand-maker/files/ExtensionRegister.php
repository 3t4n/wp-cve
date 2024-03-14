<?php
namespace yrm;
use \ReadMoreAdminHelper;

class ExtensionRegister
{
	public static function register($pluginName, $options = array())
	{
		$registeredData = array();
		$registeredPlugins = ReadMoreAdminHelper::getOption('YRM_REGISTERED_PLUGINS');
		
		if(!empty($registeredPlugins)) {
			$registeredData = ReadMoreAdminHelper::getOption('YRM_REGISTERED_PLUGINS');
		}
		
		if (!empty($registeredData)) {
			$registeredData = json_decode($registeredData, true);
		}
		
		if(empty($registeredData)) {
			$registeredData = array();
		}
		
		$registeredData[$pluginName] = $options;
		$registeredData = json_encode($registeredData);
		
		ReadMoreAdminHelper::updateOption('YRM_REGISTERED_PLUGINS', $registeredData);
		
		do_action('yrm_extension_activation_hook', $options);
	}
	
	public static function remove($pluginName)
	{
		$registeredPlugins = ReadMoreAdminHelper::getOption('YRM_REGISTERED_PLUGINS');
		
		if (!$registeredPlugins) {
			return false;
		}
		
		$registeredData = json_decode($registeredPlugins, true);
		
		if(empty($registeredData)) {
			return false;
		}
		
		if (empty($registeredData[$pluginName])) {
			return false;
		}
		unset($registeredData[$pluginName]);
		$registeredData = json_encode($registeredData);
		
		ReadMoreAdminHelper::updateOption('YRM_REGISTERED_PLUGINS', $registeredData);
		
		return true;
	}
	
	public static function hasInactiveExtensions()
	{
		$hasInactiveExtensions = false;
		$allRegisteredPBPlugins = ReadMoreAdminHelper::getOption('YRM_REGISTERED_PLUGINS');
		$allRegisteredPBPlugins = @json_decode($allRegisteredPBPlugins, true);
		if (empty($allRegisteredPBPlugins)) {
			return $allRegisteredPBPlugins;
		}
		
		foreach ($allRegisteredPBPlugins as $pluginPath => $registeredPlugin) {
			if (!isset($registeredPlugin['licence']['key'])) {
				continue;
			}
			if (!isset($registeredPlugin['licence']['file'])) {
				continue;
			}
			$extensionKey = $registeredPlugin['licence']['file'];
			if (strpos($extensionKey, 'wp-content/plugins/')) {
				$explodedPaths = explode('wp-content/plugins/', $extensionKey);
				$extensionKey = $explodedPaths[1];
			}
			$isPluginActive = is_plugin_active($extensionKey);
			$pluginKey = $registeredPlugin['licence']['key'];
			$isValidLicense = get_option('yrm-license-status-'.esc_attr($pluginKey));
			
			// if we even have at least one inactive extension, we don't need to check remaining extensions
			if ($isValidLicense != 'valid' && $isPluginActive) {
				$hasInactiveExtensions = true;
				break;
			}
		}
		
		return $hasInactiveExtensions;
	}
}
