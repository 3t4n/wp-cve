<?php
defined( 'ABSPATH' ) || exit;


add_action( 'widgets_init', 'yahman_addons_widgets_init',11 );

function yahman_addons_widgets_init() {
	$option = get_option('yahman_addons') ;
	if(empty($option['widget']) && empty($option['widget_area']) ) return;

	require_once YAHMAN_ADDONS_DIR . 'inc/widget.php';
	yahman_addons_add_widget($option);
}
