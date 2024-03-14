<?php
	define('IWP_PLUGIN_PATH', plugin_dir_path(__FILE__));
	require_once IWP_PLUGIN_PATH . 'admin/includes/iwpCustomEvents.php';
	require_once IWP_PLUGIN_PATH . 'includes/iwpPluginOptions.php';

	iwpCustomEvents::sendCustomEvent(iwpCustomEvents::MACRO_PLUGIN_DESINSTALAR);
	iwpPluginOptions::deleteAllOptions();
