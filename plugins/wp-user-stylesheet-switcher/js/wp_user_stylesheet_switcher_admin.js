function wp_user_stylesheet_switcher_toggle_showlink (listType, inputId) {
	if (listType == "icon")
		jQuery ("#"+inputId).attr('disabled', false);
	else
		jQuery ("#"+inputId).attr('disabled', true);
}
