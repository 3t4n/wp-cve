<?php

if (!defined('ABSPATH')) {
	exit;
}

/**
* kick start the plugin
*/
add_action('plugins_loaded', function() {
	require NGG_DLGALL_PLUGIN_ROOT . 'includes/class.NextGENDownloadGallery.php';
	NextGENDownloadGallery::getInstance()->pluginStart();
});
