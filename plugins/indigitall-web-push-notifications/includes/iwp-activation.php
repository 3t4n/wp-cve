<?php
	require_once IWP_PLUGIN_PATH . 'admin/includes/iwpCustomEvents.php';
	require_once IWP_PLUGIN_PATH . 'includes/iwpPluginOptions.php';

	function iwp_activate() {
		update_option(iwpPluginOptions::RETROACTIVE_INFO, "1");
		iwpCustomEvents::sendCustomEvent(iwpCustomEvents::MACRO_PLUGIN_ACTIVAR);
	}
	register_activation_hook(IWP_PLUGIN_MAIN_FILE, 'iwp_activate');