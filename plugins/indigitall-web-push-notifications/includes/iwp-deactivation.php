<?php
	require_once IWP_PLUGIN_PATH . 'includes/iwpPluginOptions.php';
	require_once IWP_PLUGIN_PATH . 'admin/includes/iwpCustomEvents.php';

	function iwp_deactivate() {
		iwpCustomEvents::sendCustomEvent(iwpCustomEvents::MACRO_PLUGIN_DESACTIVAR);
		iwpPluginOptions::resetAllOptions();
	}
	register_deactivation_hook(IWP_PLUGIN_MAIN_FILE, 'iwp_deactivate');