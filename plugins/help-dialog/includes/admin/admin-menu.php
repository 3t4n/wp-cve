<?php

/**
 * Setup WordPress menu for this plugin
 */

/**
 *  Register plugin menus
 */
function ephd_add_plugin_menus() {

	add_menu_page( __( 'Help Dialog', 'help-dialog' ), __( 'Help Dialog', 'help-dialog' ),
		EPHD_Admin_UI_Access::get_context_required_capability( ['admin_ephd_access_admin_pages_read'] ), 'ephd-help-dialog', array( new EPHD_Need_Help_Page(), 'display_need_help_page'), '', 7 );

	add_submenu_page( 'ephd-help-dialog', __( 'Help Dialog Widgets', 'help-dialog' ), __( 'Widgets', 'help-dialog' ),
		EPHD_Admin_UI_Access::get_context_required_capability( ['admin_ephd_access_admin_pages_read'] ), 'ephd-help-dialog-widgets', array( new EPHD_Widgets_Display(), 'display_page'), 10 );

	/* add_submenu_page( 'ephd-help-dialog', __( 'Help Dialog FAQs', 'help-dialog' ), __( 'FAQs', 'help-dialog' ),
						'manage_options', 'ephd-help-dialog-faqs', array( new EPHD_FAQs_Articles_Display(), 'display_page'), 15 ); TODO Future impact? */

	add_submenu_page( 'ephd-help-dialog', __( 'Help Dialog Contact Form', 'help-dialog' ), __( 'Form Submissions', 'help-dialog' ),
		'manage_options', 'ephd-help-dialog-contact-form', array( new EPHD_Contact_Form_Display(), 'display_page'), 15 );

	/* add_submenu_page( 'ephd-help-dialog', __( 'Help Dialog Contact Form', 'help-dialog' ), apply_filters( 'ephd_admin_sub_menu_contact_form' , __( 'Contact Form', 'help-dialog' ) ),
						'manage_options', 'ephd-help-dialog-contact-form', array( new EPHD_Contact_Form_Display(), 'display_page'), 15 ); TODO Future impact? */

	add_submenu_page( 'ephd-help-dialog', __( 'Help Dialog Analytics', 'help-dialog' ), __( 'Analytics', 'help-dialog' ),
						'manage_options', 'ephd-plugin-analytics', array( new EPHD_Analytics_Page(), 'display_page' ) );

	add_submenu_page( 'ephd-help-dialog', __( 'Help Dialog Advanced', 'help-dialog' ), __( 'Advanced', 'help-dialog' ),
						'manage_options', 'ephd-help-dialog-advanced-config', array( new EPHD_Config_Page(), 'display_page'), 20 );

}
add_action( 'admin_menu', 'ephd_add_plugin_menus', 10 );

/**
 * Change name for admin submenu pages
 */
function ephd_admin_menu_change_name() {
	global $submenu;

	if ( isset( $submenu['ephd-help-dialog'] ) ) {
		$submenu['ephd-help-dialog'][0][0] = __( 'Get Started', 'help-dialog' );
	}
}
add_action( 'admin_menu', 'ephd_admin_menu_change_name', 200 );
