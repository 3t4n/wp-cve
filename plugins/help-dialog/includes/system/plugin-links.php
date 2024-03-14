<?php

/**
 * Setup links and information on Plugins WordPress page
 *
 * @copyright   Copyright (C) 2018, Echo Plugins
 */


/**
 * Adds various links for plugin on the Plugins page displayed on the left
 *
 * @param   array $links contains current links for this plugin
 * @return  array returns an array of links
 */
function ephd_add_plugin_action_links ( $links ) {
	$my_links = array(
			__( 'Configuration', 'help-dialog' )    => '<a href="' . esc_url( admin_url( 'admin.php?page=ephd-help-dialog-widgets' ) ) . '">' . esc_html__( 'Configuration', 'help-dialog' ) . '</a>',
	);

	// Go Pro link
	if ( ! EPHD_Utilities::is_plugin_enabled( 'pro' ) ) {
		$links['go_pro'] = sprintf( '<a href="%1$s" target="_blank" class="ephd-plugins-gopro">%2$s</a>', EPHD_Core_Utilities::get_plugin_sales_page( 'pro' ), esc_html__( 'Go Pro', 'help-dialog' ) );
	}

	return array_merge( $my_links, $links );
}
add_filter( 'plugin_action_links_' . plugin_basename(Echo_Help_Dialog::$plugin_file), 'ephd_add_plugin_action_links', 10, 2 );

/**
 * Add info about plugin on the Plugins page displayed on the right.
 *
 * @param $links
 * @param $file
 * @return array
 */
function ephd_add_plugin_row_meta( $links, $file ) {
	if ( $file != 'help-dialog/echo-help-dialog.php' ) {
		return $links;
	}

	$links[] = '<a href="https://www.helpdialog.com/contact-us/pre-sale-and-general-questions/" target="_blank">' . esc_html__( "Support", 'help-dialog' ) . '</a>';

	return $links;
}
add_filter( 'plugin_row_meta', 'ephd_add_plugin_row_meta', 10, 2 );
