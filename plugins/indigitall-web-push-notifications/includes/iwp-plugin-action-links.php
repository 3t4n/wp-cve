<?php
	//Add Settings to Plugin page
	function iwp_add_action_links($actions) {
		$settingsText = __('Settings', 'iwp-text-domain');
		$linkToPlugin = admin_url( 'admin.php?page=indigitall-push' );
		$linkToSettings = "<a href='$linkToPlugin'>$settingsText</a>";
		array_unshift($actions, $linkToSettings);
		return $actions;
	}
	add_filter('plugin_action_links_' . IWP_PLUGIN_BASENAME, 'iwp_add_action_links');