<?php

$plugin = plugin_basename($PUBLISH_CHECKLIST_DIR . '/index.php');

// Attach plugin activation code
function pc_activate() {
	// create pc_on_publish_option in wp_options
	add_option('pc_on_publish', 'warn');
}
register_activation_hook($PUBLISH_CHECKLIST_DIR . '/index.php', 'pc_activate');

// Attach plugin uninstall code to clean up database and options
function pc_uninstall () {
	delete_option('pc_on_publish');
}
register_uninstall_hook($PUBLISH_CHECKLIST_DIR . '/index.php', 'pc_uninstall');

// Add settings link on plugin page
function your_plugin_settings_link($links) {
	$settings_link = '<a href="options-general.php?page=manage_publish_checklist">Settings</a>';
	array_unshift($links, $settings_link);

	return $links;
}
add_filter("plugin_action_links_$plugin", 'your_plugin_settings_link');