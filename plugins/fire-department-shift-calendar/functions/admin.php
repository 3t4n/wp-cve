<?php

	// Add settings link to plugins page
	function fd_shift_calendar_add_settings_link( $links ) {
		$settings_link = '<a href="admin.php?page=' . FD_SHIFT_CAL_SLUG . '">' . __('Settings', 'fd_shift_calendar') . '</a>';
		array_push( $links, $settings_link );
		return $links;
	}
	add_filter('plugin_action_links_' . FD_SHIFT_CAL_BASENAME, 'fd_shift_calendar_add_settings_link' );

?>